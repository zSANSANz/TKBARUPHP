@extends('layouts.adminlte.master')

@section('title')
    @lang('sales_order.payment.index.title')
@endsection

@section('page_title')
    <span class="fa fa-calculator fa-fw"></span>&nbsp;@lang('sales_order.payment.index.page_title')
@endsection
@section('page_title_desc')
    @lang('sales_order.payment.index.page_title_desc')
@endsection
@section('breadcrumbs')
    {!! Breadcrumbs::render('sales_order_payment') !!}
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('sales_order.payment.index.header.title')</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center">@lang('sales_order.payment.index.table.header.code')</th>
                    <th class="text-center">@lang('sales_order.payment.index.table.header.customer')</th>
                    <th class="text-center">@lang('sales_order.payment.index.table.header.so_date')</th>
                    <th class="text-center">@lang('sales_order.payment.index.table.header.total')</th>
                    <th class="text-center">@lang('sales_order.payment.index.table.header.paid')</th>
                    <th class="text-center">@lang('sales_order.payment.index.table.header.rest')</th>
                    <th class="text-center">@lang('labels.ACTION')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($salesOrders as $key => $so)
                    <tr>
                        <td class="text-center">{{ $so->code }}</td>
                        <td class="text-center">
                            @if($so->customer_type == 'CUSTOMERTYPE.R')
                                {{ $so->customer->name }}
                            @else
                                {{ $so->walk_in_customer }}
                            @endif
                        </td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($so->so_created)) }}</td>
                        <td class="text-center">{{ number_format($so->totalAmount(), 0) }}</td>
                        <td class="text-center">{{ number_format($so->totalAmountPaid(), 0) }}</td>
                        <td class="text-center">{{ number_format($so->totalAmount() - $so->totalAmountPaid(), 0) }}</td>
                        <td class="text-center" width="10%">
                            <a class="btn btn-xs btn-primary" href="{{ route('db.so.payment.cash', $so->hId()) }}" title="Cash"><span class="fa fa-money fa-fw"></span></a>
                            <a class="btn btn-xs btn-primary" href="{{ route('db.so.payment.transfer', $so->hId()) }}" title="Transfer"><span class="fa fa-send fa-fw"></span></a>
                            <a class="btn btn-xs btn-primary" href="{{ route('db.so.payment.giro', $so->hId()) }}" title="Giro"><span class="fa fa-book fa-fw"></span></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('custom_js')
    <script type="application/javascript">
    </script>
@endsection
