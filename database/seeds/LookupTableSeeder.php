<?php

/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 1:16 PM
 */

use Illuminate\Database\Seeder;

use App\Model\Lookup;

class LookupTableSeeder extends Seeder
{
    public function run()
    {
        $lookup = [
            [
                'code' => 'STATUS.ACTIVE',
                'description' => 'Active',
                'category' => 'STATUS',
            ],
            [
                'code' => 'STATUS.INACTIVE',
                'description' => 'Inactive',
                'category' => 'STATUS',
            ],
            [
                'code' => 'YESNOSELECT.YES',
                'description' => 'Yes',
                'category' => 'YESNOSELECT',
            ],
            [
                'code' => 'YESNOSELECT.NO',
                'description' => 'No',
                'category' => 'YESNOSELECT',
            ],
            [
                'code' => 'CURRENCY.IDR',
                'description' => 'Indonesian Rupiah',
                'category' => 'CURRENCY',
            ],
            [
                'code' => 'CURRENCY.USD',
                'description' => 'US Dollar',
                'category' => 'CURRENCY',
            ],
            [
                'code' => 'LANGUAGE.IN',
                'description' => 'Indonesian',
                'category' => 'LANGUAGE',
            ],
            [
                'code' => 'LANGUANGE.EN',
                'description' => 'English',
                'category' => 'LANGUAGE',
            ],
            [
                'code' => 'TRUCKTYPE.OIL_8T',
                'description' => 'Oil 8T',
                'category' => 'TRUCKTYPE',
            ],
            [
                'code' => 'TRUCKTYPE.CARGO_8T',
                'description' => 'Cargo 8T',
                'category' => 'TRUCKTYPE',
            ],
            [
                'code' => 'TRUCKTYPE.CARGO_25T',
                'description' => 'Cargo 25T',
                'category' => 'TRUCKTYPE',
            ],
            [
                'code' => 'POSTATUS.D',
                'description' => 'Draft',
                'category' => 'POSTATUS',
            ],
            [
                'code' => 'POSTATUS.WA',
                'description' => 'Waiting Arrival',
                'category' => 'POSTATUS',
            ],
            [
                'code' => 'POSTATUS.WP',
                'description' => 'Waiting Payment',
                'category' => 'POSTATUS',
            ],
            [
                'code' => 'POSTATUS.C',
                'description' => 'Closed',
                'category' => 'POSTATUS',
            ],
            [
                'code' => 'POSTATUS.RJT',
                'description' => 'Rejected',
                'category' => 'POSTATUS',
            ],
            [
                'code' => 'POTYPE.S',
                'description' => 'Standard PO',
                'category' => 'POTYPE',
            ],
            [
                'code' => 'CUSTOMERTYPE.R',
                'description' => 'Registered Customer',
                'category' => 'CUSTOMERTYPE',
            ],[
                'code' => 'CUSTOMERTYPE.WI',
                'description' => 'Walk In Customer',
                'category' => 'CUSTOMERTYPE',
            ],
            [
                'code' => 'SUPPLIERTYPE.R',
                'description' => 'Registered Supplier',
                'category' => 'SUPPLIERTYPE',
            ],[
                'code' => 'SUPPLIERTYPE.WI',
                'description' => 'Walk In Supplier',
                'category' => 'SUPPLIERTYPE',
            ],
            [
                'code' => 'SOTYPE.S',
                'description' => 'Standard SO',
                'category' => 'SOTYPE',
            ],
            [
                'code' => 'SOTYPE.SVC',
                'description' => 'Service Sales',
                'category' => 'SOTYPE',
            ],
            [
                'code' => 'SOSTATUS.D',
                'description' => 'Draft',
                'category' => 'SOSTATUS',
            ],
            [
                'code' => 'SOSTATUS.WD',
                'description' => 'Awaiting For Delivery',
                'category' => 'SOSTATUS',
            ],
            [
                'code' => 'SOSTATUS.WCC',
                'description' => 'Awaiting Customer Confirmation',
                'category' => 'SOSTATUS',
            ],
            [
                'code' => 'SOSTATUS.WAPPV',
                'description' => 'Awaiting Approval',
                'category' => 'SOSTATUS',
            ],
            [
                'code' => 'SOSTATUS.WP',
                'description' => 'Awaiting For Payment',
                'category' => 'SOSTATUS',
            ],
            [
                'code' => 'SOSTATUS.C',
                'description' => 'Closed',
                'category' => 'SOSTATUS',
            ],
            [
                'code' => 'SOSTATUS.RJT',
                'description' => 'Rejected',
                'category' => 'SOSTATUS',
            ],
            [
                'code' => 'PAYMENTTYPE.C',
                'description' => 'Cash',
                'category' => 'PAYMENTTYPE',
            ],
            [
                'code' => 'PAYMENTTYPE.T',
                'description' => 'Transfer',
                'category' => 'PAYMENTTYPE',
            ],
            [
                'code' => 'PAYMENTTYPE.G',
                'description' => 'Giro',
                'category' => 'PAYMENTTYPE',
            ],
            [
                'code' => 'CASHPAYMENTSTATUS.C',
                'description' => 'Confirmed',
                'category' => 'CASHPAYMENTSTATUS',
            ],
            [
                'code' => 'TRFPAYMENTSTATUS.UNCONFIRMED',
                'description' => 'Unconfirmed',
                'category' => 'TRFPAYMENTSTATUS',
            ],
            [
                'code' => 'TRFPAYMENTSTATUS.CONFIRMED',
                'description' => 'Confirmed',
                'category' => 'TRFPAYMENTSTATUS',
            ],
            [
                'code' => 'GIROPAYMENTSTATUS.WE',
                'description' => 'Waiting Effective Date',
                'category' => 'GIROPAYMENTSTATUS',
            ],
            [
                'code' => 'PAYMENTTYPE.FR',
                'description' => 'Failed & Returned',
                'category' => 'GIROPAYMENTSTATUS',
            ],
            [
                'code' => 'TRUCKMTCTYPE.R',
                'description' => 'Regular Checkup',
                'category' => 'TRUCKMTCTYPE',
            ],
            [
                'code' => 'TRUCKMTCTYPE.TC',
                'description' => 'Tire Change',
                'category' => 'TRUCKMTCTYPE',
            ],
            [
                'code' => 'TRUCKMTCTYPE.SPC',
                'description' => 'Spare Part Change',
                'category' => 'TRUCKMTCTYPE',
            ],
            [
                'code' => 'TRUCKMTCTYPE.OC',
                'description' => 'Oil Change',
                'category' => 'TRUCKMTCTYPE',
            ],
            [
                'code' => 'USERTYPE.A',
                'description' => 'Admin',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'USERTYPE.O',
                'description' => 'Owner',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'USERTYPE.U',
                'description' => 'User',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'USERTYPE.DR',
                'description' => 'Driver',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'USERTYPE.FD',
                'description' => 'Front Desk',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'USERTYPE.FIN',
                'description' => 'Finance',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'USERTYPE.C',
                'description' => 'Customer',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'USERTYPE.S',
                'description' => 'Supplier',
                'category' => 'USERTYPE',
            ],
            [
                'code' => 'PRICELEVELTYPE.INC',
                'description' => 'Increment Value',
                'category' => 'PRICELEVELTYPE',
            ],
            [
                'code' => 'PRICELEVELTYPE.PCT',
                'description' => 'Percentage Value',
                'category' => 'PRICELEVELTYPE',
            ],
            [
                'code' => 'GIROSTATUS.N',
                'description' => 'New',
                'category' => 'GIROSTATUS',
            ],
            [
                'code' => 'GIROSTATUS.UP',
                'description' => 'Used for Payment',
                'category' => 'GIROSTATUS',
            ],
            [
                'code' => 'GIROSTATUS.R',
                'description' => 'Returned',
                'category' => 'GIROSTATUS',
            ],
            [
                'code' => 'SETTINGDATEFORMAT.DD-MM-YYYY',
                'description' => 'DD-MM-YYYY',
                'category' => 'SETTINGDATEFORMAT',
            ],
            [
                'code' => 'SETTINGDATEFORMAT.DD_MMM_YYYY',
                'description' => 'DD MMM YYYY',
                'category' => 'SETTINGDATEFORMAT',
            ],
            [
                'code' => 'SETTINGTIMEFORMAT.HH:MM',
                'description' => 'HH:MM',
                'category' => 'SETTINGTIMEFORMAT',
            ],
            [
                'code' => 'SETTINGTIMEFORMAT.HH:MM:SS',
                'description' => 'HH:MM:SS',
                'category' => 'SETTINGTIMEFORMAT',
            ],
            [
                'code' => 'SETTINGTIME1224FORMAT.12',
                'description' => '12 Hour',
                'category' => 'SETTINGTIME1224FORMAT',
            ],
            [
                'code' => 'SETTINGTIME1224FORMAT.24',
                'description' => '24 Hour',
                'category' => 'SETTINGTIME1224FORMAT',
            ],
            [
                'code' => 'SETTINGTHOUSANDSEPARATOR.COMMA',
                'description' => 'Comma Separated',
                'category' => 'SETTINGTHOUSANDSEPARATOR',
            ],
            [
                'code' => 'SETTINGTHOUSANDSEPARATOR.NONE',
                'description' => 'No Separator',
                'category' => 'SETTINGTHOUSANDSEPARATOR',
            ],
            [
                'code' => 'SETTINGDECIMALSEPARATOR.DOT',
                'description' => 'Dot Separated',
                'category' => 'SETTINGDECIMALSEPARATOR',
            ],
            [
                'code' => 'SETTINGDECIMALSEPARATOR.NONE',
                'description' => 'No Separator',
                'category' => 'SETTINGTHOUSANDSEPARATOR',
            ],
            [
                'code' => 'EXPENSETYPE.ADD',
                'description' => 'Adding value',
                'category' => 'EXPENSETYPE',
            ],
            [
                'code' => 'EXPENSETYPE.SUB',
                'description' => 'Subtracting value',
                'category' => 'EXPENSETYPE',
            ],
            [
                'code' => 'BANKUPLOAD.BCA',
                'description' => 'Bank BCA',
                'category' => 'BANKUPLOAD',
            ]
        ];
        foreach ($lookup as $key => $value) {
            Lookup::create($value);
        }
    }
}