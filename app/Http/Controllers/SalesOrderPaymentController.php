<?php

namespace App\Http\Controllers;

use App\Model\Giro;
use App\Model\Bank;
use App\Model\CashPayment;
use App\Model\GiroPayment;
use App\Model\Lookup;
use App\Model\Payment;
use App\Model\SalesOrder;
use App\Model\Store;
use App\Model\TransferPayment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SalesOrderPaymentController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->middleware('auth');
    }

    public function paymentIndex()
    {
        Log::info('SalesOrderController@paymentIndex');

        $salesOrders = SalesOrder::with('customer')->where('status', '=', 'SOSTATUS.WP')->get();
        $soStatusDDL = Lookup::where('category', '=', 'SOSTATUS')->get()->pluck('description', 'code');

        return view('sales_order.payment.payment_index', compact('salesOrders', 'soStatusDDL'));
    }

    public function paymentHistory($id){
        $currentSo = SalesOrder::with('payments', 'items.product.productUnits.unit', 'customer.profiles.phoneNumbers.provider',
            'customer.bankAccounts.bank', 'vendorTrucking', 'warehouse', 'expenses')->find($id);
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('sales_order.payment.payment_history', compact('currentSo', 'paymentTypeDDL', 'paymentStatusDDL', 'expenseTypes'));
    }

    public function createCashPayment($id)
    {
        Log::info('[SalesOrderController@createCashPayment]');

        $currentSo = SalesOrder::with('payments', 'items.product.productUnits.unit', 'customer.profiles.phoneNumbers.provider',
            'customer.bankAccounts.bank', 'vendorTrucking', 'warehouse', 'expenses')->find($id);
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $paymentType = 'PAYMENTTYPE.C';
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('sales_order.payment.cash_payment', compact('currentSo', 'paymentTypeDDL', 'paymentStatusDDL', 'paymentType',
            'expenseTypes'));
    }

    public function saveCashPayment(Request $request, $id)
    {
        Log::info('[SalesOrderController@saveCashPayment]');

        $payment = $this->paymentService->createCashPayment($request);

        $currentSo = SalesOrder::find($id);

        $currentSo->payments()->save($payment);

        $currentSo->updatePaymentStatus();

        return redirect(route('db.so.payment.index'));
    }

    public function createTransferPayment($id)
    {
        Log::info('[SalesOrderController@createTransferPayment]');

        $currentStore = Store::with('bankAccounts.bank')->find(Auth::user()->store_id);
        $currentSo = SalesOrder::with('payments', 'items.product.productUnits.unit', 'customer.profiles.phoneNumbers.provider',
            'customer.bankAccounts.bank', 'vendorTrucking', 'warehouse', 'expenses')->find($id);
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $storeBankAccounts = $currentStore->bankAccounts;
        $customerBankAccounts = empty($currentSo->customer) ? collect([]) : $currentSo->customer->bankAccounts;
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $paymentType = 'PAYMENTTYPE.T';
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('sales_order.payment.transfer_payment', compact('currentSo', 'paymentTypeDDL', 'paymentStatusDDL', 'paymentType',
            'storeBankAccounts', 'customerBankAccounts', 'expenseTypes'));
    }

    public function saveTransferPayment(Request $request, $id)
    {
        Log::info('[SalesOrderController@saveTransferPayment]');

        $payment = $this->paymentService->createTransferPayment($request);

        $currentSo = SalesOrder::find($id);

        $currentSo->payments()->save($payment);

        return redirect(route('db.so.payment.index'));
    }

    public function createGiroPayment($id)
    {
        Log::info('[SalesOrderController@createGiroPayment]');

        $currentSo = SalesOrder::with('payments', 'items.product.productUnits.unit',
            'customer.profiles.phoneNumbers.provider', 'customer.bankAccounts.bank',
            'vendorTrucking', 'warehouse', 'expenses')->find($id);
        $bankDDL = Bank::whereStatus('STATUS.ACTIVE')->get(['id', 'name']);
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $paymentType = 'PAYMENTTYPE.G';
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('sales_order.payment.giro_payment', compact('currentSo', 'paymentTypeDDL', 'paymentStatusDDL', 'paymentType',
            'bankDDL', 'expenseTypes'));
    }

    public function saveGiroPayment(Request $request, $id)
    {
        Log::info('[SalesOrderController@saveGiroPayment]');

        $giroParam = [
            'store_id' => Auth::user()->store_id,
            'bank_id' => $request->input('bank_id'),
            'serial_number' => $request->input('serial_number'),
            'effective_date' => date('Y-m-d', strtotime($request->input('effective_date'))),
            'amount' => floatval(str_replace(',', '', $request->input('amount'))),
            'printed_name' => $request->input('printed_name'),
            'status' => 'GIROSTATUS.N',
            'remarks' => $request->input('remarks')
        ];

        $giro = Giro::create($giroParam);

        $payment = $this->paymentService->createGiroPayment($request, $giro);

        $currentSo = SalesOrder::find($id);

        $currentSo->payments()->save($payment);

        return redirect(route('db.so.payment.index'));
    }
}
