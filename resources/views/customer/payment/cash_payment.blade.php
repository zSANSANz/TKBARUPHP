@extends('layouts.adminlte.master')

@section('title')
    @lang('customer.payment.cash.title')
@endsection

@section('page_title')
    <span class="fa fa-money fa-fw"></span>&nbsp;@lang('customer.payment.cash.page_title')
@endsection
@section('page_title_desc')
    @lang('customer.payment.cash.page_title_desc')
@endsection

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>@lang('labels.GENERAL_ERROR_TITLE')</strong> @lang('labels.GENERAL_ERROR_DESC')<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div ng-app="soModule" ng-controller="soController">
        {!! Form::model($currentSo, ['method' => 'POST', 'route' => ['db.customer.payment.cash', $currentSo->hId()], 'class' => 'form-horizontal', 'data-parsley-validate' => 'parsley']) !!}
            {{ csrf_field() }}

            @include('customer.payment.payment_summary_partial')

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">@lang('customer.payment.cash.box.payment')</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="inputPaymentType"
                                                   class="col-sm-2 control-label">@lang('customer.payment.cash.field.payment_type')</label>
                                            <div class="col-sm-4">
                                                <input id="inputPaymentType" type="text" class="form-control" readonly value="@lang('lookup.'.$paymentType)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="inputPaymentDate"
                                                   class="col-sm-2 control-label">@lang('customer.payment.cash.field.payment_date')</label>
                                            <div class="col-sm-4">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="inputPaymentDate"
                                                           name="payment_date" data-parsley-required="true">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="inputPaymentAmount"
                                                   class="col-sm-2 control-label">@lang('customer.payment.cash.field.payment_amount')</label>
                                            <div class="col-sm-4">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        Rp
                                                    </div>
                                                    <input type="text" class="form-control" id="inputPaymentAmount" ng-model="total_amount"
                                                           name="total_amount" data-parsley-required="true" fcsa-number>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 col-offset-md-5">
                            <div class="btn-toolbar">
                                <button id="submitButton" type="submit"
                                        class="btn btn-primary pull-right">@lang('buttons.submit_button')</button>
                                &nbsp;&nbsp;&nbsp;
                                <a id="cancelButton" href="{{ route('db.customer.payment.index') }}" class="btn btn-primary pull-right"
                                   role="button">@lang('buttons.cancel_button')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var app = angular.module("soModule", ['fcsa-number']);
        app.controller("soController", ['$scope', function ($scope) {
            var currentSo = JSON.parse('{!! htmlspecialchars_decode($currentSo->toJson()) !!}');

            $scope.so = {
                customer: currentSo.customer,
                items: [],
                warehouse: {
                    id: currentSo.warehouse.id,
                    name: currentSo.warehouse.name
                },
                vendorTrucking: {
                    id: (currentSo.vendor_trucking == null) ? '' : currentSo.vendor_trucking.id,
                    name: (currentSo.vendor_trucking == null) ? '' : currentSo.vendor_trucking.name
                }
            };

            for (var i = 0; i < currentSo.items.length; i++) {
                $scope.so.items.push({
                    id: currentSo.items[i].id,
                    product: currentSo.items[i].product,
                    base_unit: _.find(currentSo.items[i].product.product_units, isBase),
                    selected_unit: _.find(currentSo.items[i].product.product_units, getSelectedUnit(currentSo.items[i].selected_unit_id)),
                    quantity: parseFloat(currentSo.items[i].quantity).toFixed(0),
                    price: parseFloat(currentSo.items[i].price).toFixed(0)
                });
            }

            $scope.grandTotal = function () {
                var result = 0;
                angular.forEach($scope.so.items, function (item, key) {
                    result += (item.selected_unit.conversion_value * item.quantity * item.price);
                });
                return result;
            };

            function getSelectedUnit(selectedUnitId) {
                return function (element) {
                    return element.unit_id == selectedUnitId;
                }
            }

            function isBase(unit) {
                return unit.is_base == 1;
            }
        }]);

        $("#inputPaymentDate").daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            },
            singleDatePicker: true,
            showDropdowns: true
        });
    </script>
@endsection