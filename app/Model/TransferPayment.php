<?php

namespace App\Model;

/**
 * App\Model\TransferPayment
 *
 * @property integer $id
 * @property string $effective_date
 * @property integer $bank_account_from_id
 * @property integer $bank_account_to_id
 * @property-read \App\Model\Payment $payment
 * @property-read \App\Model\BankAccount $bankAccountFrom
 * @property-read \App\Model\BankAccount $bankAccountTo
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TransferPayment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TransferPayment whereEffectiveDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TransferPayment whereBankAccountFromId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TransferPayment whereBankAccountToId($value)
 * @mixin \Eloquent
 */
class TransferPayment extends Payment
{
    public $timestamps = false;

    public function payment(){
        return $this->morphOne('App\Model\Payment', 'payment_detail');
    }

    public function bankAccountFrom()
    {
        return $this->belongsTo('App\Model\BankAccount');
    }

    public function bankAccountTo()
    {
        return $this->belongsTo('App\Model\BankAccount');
    }

    public static function boot()
    {
        static::creating(function ($model) {
        });

        static::updating(function ($model) {
        });

        static::deleting(function ($model) {
        });
    }
}
