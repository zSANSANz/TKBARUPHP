<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/9/2016
 * Time: 11:50 PM
 */

namespace App\Model;

use Auth;
use Carbon\Carbon;
use App\Traits\StoreFilter;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\SalesOrder
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $customer_id
 * @property integer $warehouse_id
 * @property integer $vendor_trucking_id
 * @property string $code
 * @property \Carbon\Carbon $so_created
 * @property string $so_type
 * @property \Carbon\Carbon $shipping_date
 * @property string $customer_type
 * @property string $walk_in_cust
 * @property string $walk_in_cust_detail
 * @property string $article_code
 * @property string $status
 * @property string $remarks
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Item[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Deliver[] $delivers
 * @property-read \App\Model\Customer $customer
 * @property-read \App\Model\Warehouse $warehouse
 * @property-read \App\Model\VendorTrucking $vendorTrucking
 * @property-read \App\Model\Store $store
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Payment[] $payments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Expense[] $expenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\SalesOrderCopy[] $copies
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereStoreId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereWarehouseId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereVendorTruckingId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereSoCreated($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereSoType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereShippingDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereCustomerType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereWalkInCust($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereWalkInCustDetail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereArticleCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereRemarks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereDeletedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\SalesOrder whereDeletedAt($value)
 * @mixin \Eloquent
 */
class SalesOrder extends Model
{
    use SoftDeletes;

    use StoreFilter;

    protected $dates = ['deleted_at', 'so_created', 'shipping_date'];

    protected $table = 'sales_orders';

    protected $fillable = [
        'store_id',
        'customer_id',
        'warehouse_id',
        'vendor_trucking_id',
        'code',
        'so_type',
        'so_created',
        'shipping_date',
        'customer_type',
        'walk_in_cust',
        'walk_in_cust_detail',
        'so_type',
        'status',
        'remarks'
    ];

    protected $hidden = [
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    public function hId()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function items()
    {
        return $this->morphMany('App\Model\Item', 'itemable');
    }

    public function delivers()
    {
        return $this->hasManyThrough('App\Model\Deliver', 'App\Model\Item', 'itemable_id', 'item_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Model\Customer', 'customer_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Model\Warehouse', 'warehouse_id');
    }

    public function vendorTrucking()
    {
        return $this->belongsTo('App\Model\VendorTrucking', 'vendor_trucking_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Model\Store', 'store_id');
    }

    public function payments()
    {
        return $this->morphMany('App\Model\Payment', 'payable');
    }

    public function cashPayments()
    {
        return $this->payments->filter(function ($payment){
            return $payment->type === 'PAYMENTTYPE.C';
        });
    }

    public function transferPayments()
    {
        return $this->payments->filter(function ($payment){
            return $payment->type === 'PAYMENTTYPE.T';
        });
    }

    public function giroPayments()
    {
        return $this->payments->filter(function ($payment){
            return $payment->type === 'PAYMENTTYPE.G';
        });
    }

    public function totalAmount()
    {
        $itemAmounts = $this->items->map(function($item){
            return $item->price * $item->to_base_quantity;
        });

        $itemTotalAmount = count($itemAmounts) > 0 ? $itemAmounts->sum() : 0;

        $expenseAmounts = $this->expenses->map(function ($expense){
            return $expense->type === 'EXPENSETYPE.ADD' ? $expense->amount : ($expense->amount * -1);
        });

        $expenseTotalAmount = count($expenseAmounts) > 0 ? $expenseAmounts->sum() : 0;

        return $itemTotalAmount + $expenseTotalAmount;
    }

    public function totalAmountPaid()
    {
        return $this->payments->filter(function ($payment, $key){
            return $payment->status !== 'TRFPAYMENTSTATUS.UNCONFIRMED'
            && $payment->status !== 'GIROPAYMENTSTATUS.WE'
            && $payment->status !== 'PAYMENTTYPE.FR';
        })->sum('total_amount');
    }

    public function updatePaymentStatus()
    {
        if($this->totalAmount() === $this->totalAmountPaid())
            $this->status = "SOSTATUS.C";

        $this->save();
    }

    public function expenses(){
        return $this->morphMany('App\Model\Expense', 'expensable');
    }

    public function copies()
    {
        return $this->hasMany('App\Model\SalesOrderCopy', 'main_so_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->updated_by = $user->id;
            }
        });

        static::deleting(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->deleted_by = $user->id;
                $model->save();
            }
        });
    }
}