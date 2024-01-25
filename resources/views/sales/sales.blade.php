@extends('layouts.master')

@section('title')
    Sales Transactions
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Sales Transactions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-footer">
                @if ($setting->tipe_nota == 1)
                <button class="btn btn-warning btn-flat" onclick="Reciept('{{ route('transaction.smPDF') }}', 'Genearte Reciept')">Print Invoice</button>
                @else
                <button class="btn btn-warning btn-flat" onclick="PDF('{{ route('transaction.lgPDF') }}', 'Generate PDF')">Print Invoice</button>
                @endif
                <a href="{{ route('transaction.create') }}" class="btn btn-primary btn-flat">New Transaction</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // add to delete cookie innerHeight first
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    function Reciept(url, title) {
        popupCenter(url, title, 625, 500);
    }

    function PDF(url, title) {
        popupCenter(url, title, 900, 675);
    }

    function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

        const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left       = (width - w) / 2 / systemZoom + dualScreenLeft
        const top        = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow  = window.open(url, title,
        `
            scrollbars=yes,
            width  = ${w / systemZoom},
            height = ${h / systemZoom},
            top    = ${top},
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }
</script>
@endpush
