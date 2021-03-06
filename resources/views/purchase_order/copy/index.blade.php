@extends('layouts.adminlte.master')

@section('title')
    @lang('purchase_order.copy.index.title')
@endsection

@section('page_title')
    <span class="fa fa-copy fa-rotate-180 fa-fw"></span>&nbsp;@lang('purchase_order.copy.index.page_title')
@endsection

@section('page_title_desc')
    @lang('purchase_order.copy.index.page_title_desc')
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('purchase_order_copy') !!}
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger">
            <strong>@lang('labels.GENERAL_ERROR_TITLE')</strong> @lang('labels.GENERAL_ERROR_DESC')<br><br>
            <ul>
                @if (Session::get('error') == 'po_not_found')
                    <li>@lang("purchase_order.copy.search.po_not_found") {{ Session::get('code') }}</li>
                @else
                    <li>{{ Session::get('error') }} {{ Session::get('code') }}</li>
                @endif
            </ul>
        </div>
    @endif

    <div ng-app="poCopyModule" ng-controller="poCopyController">
        <form class="form-horizontal" id="searchForm">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('purchase_order.copy.index.header.search')</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputSearchPOCode" ng-model="poCode"
                                   placeholder="Purchase Order Code">
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-block btn-md btn-primary"
                               href="{{ route('db.po.copy.index') }}/@{{ poCode }}">Search</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('purchase_order.copy.index.header.title')</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">@lang('purchase_order.copy.index.table.header.code')</th>
                        <th class="text-center">@lang('purchase_order.copy.index.table.header.po_date')</th>
                        <th class="text-center">@lang('purchase_order.copy.index.table.header.supplier')</th>
                        <th class="text-center">@lang('purchase_order.copy.index.table.header.shipping_date')</th>
                        <th class="text-center">@lang('labels.ACTION')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($poCopies as $key => $copy)
                        <tr>
                            <td class="text-center">{{ $copy->code }}</td>
                            <td class="text-center">{{ $copy->po_created }}</td>
                            <td class="text-center">
                                @if($copy->supplier_type == 'SUPPLIERTYPE.R')
                                    {{ $copy->supplier->name }}
                                @else
                                    {{ $copy->walk_in_supplier }}
                                @endif
                            </td>
                            <td class="text-center">{{ $copy->shipping_date }}</td>
                            <td class="text-center" width="10%">
                                <a class="btn btn-xs btn-primary"
                                   href="{{ route('db.po.copy.edit', ['poCode' => $copy->main_po_code, 'id' => $copy->hId()]) }}"
                                   title="Edit"><span class="fa fa-pencil fa-fw"></span></a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['db.po.copy.delete', $copy->main_po_code, $copy->hId()], 'style'=>'display:inline'])  !!}
                                <button type="submit" class="btn btn-xs btn-danger" title="Delete" id="delete_button">
                                    <span class="fa fa-close fa-fw"></span></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
                <a class="btn btn-success" href="{{ route('db.po.copy.create', ['code' => $poCode]) }}"><span class="fa fa-plus fa-fw"></span>&nbsp;@lang('buttons.create_new_button')</a>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        $('#delete_button').on('click', function (e) {
            e.preventDefault();
            var form = $(this).parents('form');
            swal({
                title: "@lang('messages.alert.delete.purchase_order.copy.title')",
                text: "@lang('messages.alert.delete.purchase_order.copy.text')",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "@lang('buttons.reject_button')",
                cancelButtonText: "@lang('buttons.cancel_button')",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) form.submit();
            });
        });

        var app = angular.module('poCopyModule', []);
        app.controller('poCopyController', ['$scope', function ($scope) {
            $scope.poCode = '{{ $poCode }}';
        }]);
    </script>
@endsection
