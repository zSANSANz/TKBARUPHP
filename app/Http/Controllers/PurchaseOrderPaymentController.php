<?php

namespace App\Http\Controllers;

use App\Model\CashPayment;
use App\Model\Giro;
use App\Model\GiroPayment;
use App\Model\Lookup;
use App\Model\Payment;
use App\Model\PurchaseOrder;
use App\Model\Store;
use App\Model\TransferPayment;
use App\Services\PaymentService;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PurchaseOrderPaymentController extends Controller
{

    private $purchaseOrderService;
    private $paymentService;

    public function __construct(PurchaseOrderService $purchaseOrderService, PaymentService $paymentService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
        $this->paymentService = $paymentService;
        $this->middleware('auth');
    }

    public function paymentIndex()
    {
        Log::info('[PurchaseOrderController@paymentIndex]');

        $purchaseOrders = PurchaseOrder::with('supplier')->where('status', '=', 'POSTATUS.WP')->paginate(10);
        $poStatusDDL = Lookup::where('category', '=', 'POSTATUS')->get()->pluck('description', 'code');

        return view('purchase_order.payment.payment_index', compact('purchaseOrders', 'poStatusDDL'));
    }

    public function paymentHistory($id){
        $currentPo = $currentPo = $this->purchaseOrderService->getPOForPayment($id);
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('purchase_order.payment.payment_history', compact('currentPo', 'paymentTypeDDL', 'paymentStatusDDL',
            'expenseTypes'));
    }

    public function createCashPayment($id)
    {
        Log::info('[PurchaseOrderController@createCashPayment]');

        $currentPo = $this->purchaseOrderService->getPOForPayment($id);
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $paymentType = 'PAYMENTTYPE.C';
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('purchase_order.payment.cash_payment',
            compact('currentPo', 'paymentTypeDDL', 'paymentStatusDDL', 'paymentType', 'expenseTypes'));
    }

    public function saveCashPayment(Request $request, $id)
    {
        Log::info('[PurchaseOrderController@saveCashPayment]');

        $payment = $this->paymentService->createCashPayment($request);

        $currentPo = PurchaseOrder::find($id);

        $currentPo->payments()->save($payment);

        $currentPo->updatePaymentStatus();

        return redirect(route('db.po.payment.index'));
    }

    public function createTransferPayment($id)
    {
        Log::info('[PurchaseOrderController@createTransferPayment]');

        $currentPo = $this->purchaseOrderService->getPOForPayment($id);
        $currentStore = Store::with('bankAccounts.bank')->find(Auth::user()->store_id);
        $storeBankAccounts = $currentStore->bankAccounts;
        $supplierBankAccounts = is_null($currentPo->supplier) ? collect([]) : $currentPo->supplier->bankAccounts;
        $paymentType = 'PAYMENTTYPE.T';
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('purchase_order.payment.transfer_payment', compact('currentPo', 'paymentTypeDDL', 'paymentStatusDDL',
            'paymentType', 'storeBankAccounts', 'supplierBankAccounts', 'expenseTypes'));
    }

    public function saveTransferPayment(Request $request, $id)
    {
        Log::info('[PurchaseOrderController@saveTransferPayment]');

        $payment = $this->paymentService->createTransferPayment($request);

        $currentPo = PurchaseOrder::find($id);

        $currentPo->payments()->save($payment);

        return redirect(route('db.po.payment.index'));
    }

    public function createGiroPayment($id)
    {
        Log::info('[PurchaseOrderController@createGiroPayment]');

        $currentPo = $currentPo = $this->purchaseOrderService->getPOForPayment($id);
        $availableGiros = Giro::with('bank')->where('status', '=', 'GIROSTATUS.N')->get();
        $paymentTypeDDL = Lookup::where('category', '=', 'PAYMENTTYPE')->get()->pluck('description', 'code');
        $paymentStatusDDL = Lookup::whereIn('category', ['CASHPAYMENTSTATUS', 'TRFPAYMENTSTATUS', 'GIROPAYMENTSTATUS'])
            ->get()->pluck('description', 'code');
        $paymentType = 'PAYMENTTYPE.G';
        $expenseTypes = Lookup::where('category', '=', 'EXPENSETYPE')->get(['description', 'code']);

        return view('purchase_order.payment.giro_payment', compact('currentPo', 'paymentTypeDDL', 'paymentStatusDDL',
            'paymentType', 'availableGiros', 'bankDDL', 'expenseTypes'));
    }

    public function saveGiroPayment(Request $request, $id)
    {
        Log::info('[PurchaseOrderController@saveGiroPayment]');

        $giroId = $request->input("giro_id");

        $giro = Giro::find($giroId);
        $giro->status = 'GIROSTATUS.UP';
        $giro->save();

        $payment = $this->paymentService->createGiroPayment($request, $giro);

        $currentPo = PurchaseOrder::find($id);

        $currentPo->payments()->save($payment);

        return redirect(route('db.po.payment.index'));
    }
}
