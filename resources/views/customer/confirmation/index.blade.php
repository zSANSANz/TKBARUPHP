@extends('layouts.adminlte.master')

@section('title')
    @lang('customer.confirmation.index.title')
@endsection

@section('page_title')
    <span class="fa fa-check fa-fw"></span>&nbsp;@lang('customer.confirmation.index.page_title')
@endsection
@section('page_title_desc')
    @lang('customer.confirmation.index.page_title_desc')
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @elseif ($message = Session::get('info'))
        <div class="alert alert-info">
            <p>{{ $message }}</p>
        </div>
        {{ Session::forget('info') }}
    @endif

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('customer.confirmation.index.header.title')</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">@lang('customer.confirmation.index.table.header.so_code')</th>
                        <th class="text-center">@lang('customer.confirmation.index.table.header.deliver_date')</th>
                        <th class="text-center">@lang('customer.confirmation.index.table.header.deliverer')</th>
                        <th class="text-center">@lang('customer.confirmation.index.table.header.items')</th>
                        <th class="text-center">@lang('labels.ACTION')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($solist))
                        @foreach ($solist as $key => $so)
                            <tr>
                                <td class="text-center">{{ $so->code }}</td>
                                <td class="text-center">
                                    @foreach ($so->items as $i)
                                        {{ $i->delivers()->first()->deliver_date->format('d-m-Y') }}
                                    @endforeach
                                </td>
                                <td class="text-center"></td>
                                <td class="text-center">
                                    @foreach ($so->items as $i)
                                        {{ $i->product()->first()->name }}<br/>
                                    @endforeach
                                </td>
                                <td class="text-center" width="20%">
                                    <a class="btn btn-xs btn-primary" href="{{ route('db.customer.confirmation.confirm', $so->hId()) }}"><span class="fa fa-pencil fa-fw"></span></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            @if (!empty($solist))
                {{ $solist->render() }}
            @endif
        </div>
    </div>
@endsection
