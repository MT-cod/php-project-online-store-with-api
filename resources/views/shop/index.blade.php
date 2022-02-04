@extends('layouts.app')

@section('content')

<!-- Scripts -->
<script src="/js/online_store_modals_shop.js"></script>
<script src="/js/online_store_sorting_arrows_funcs.js"></script>

<!-- Styles -->
<link href="{{ asset('css/online_store_gray.css') }}" rel="stylesheet">

<div class="container-fluid" style="background: url(/back_gray.jpg) repeat">

    <div class="row justify-content-center" style="height: 4vh !important">
        @if (isset($_REQUEST['filter_expand']))
            <div class="col-2 gy-3 pt-1 text-left collapse filt">
        @else
            <div class="col-2 gy-3 pt-1 text-left collapse filt show">
        @endif
            <button type="button" class="btn btn-secondary btn-block btn-sm" id="filt_btn_expand" data-toggle="collapse" data-target=".filt" aria-controls="filter filt_btn_expand filt_btn_collapse"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Фильтр >></b></button>
            </div>
        @if (isset($_REQUEST['filter_expand']))
            <div class="col-2 gy-3 pt-1 text-left collapse filt show">
        @else
            <div class="col-2 gy-3 pt-1 text-left collapse filt">
        @endif
            <button type="button" class="btn btn-secondary btn-block btn-sm" id="filt_btn_collapse" data-toggle="collapse" data-target=".filt" aria-controls="filter filt_btn_expand filt_btn_collapse"><b><< Фильтр&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></button>
            </div>

        <div class="col-8 text-center btn-sm pl-5 pr-5 pt-0">
            @include('flash::message')
        </div>

        <div class="col-2 pr-3 pt-1 text-right">
            <button class="btn btn-secondary btn-block btn-modal_basket_show btn-sm" data-toggle="tooltip" data-placement="bottom" title="Показать корзину товаров">
                Корзина <span class="badge badge-light baskCount">{{$baskCount}}</span>
            </button>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Filter -->
        @include('shop.filter')

        <!-- Goods -->
        @if ($goods)
            @include('shop.goods_table')
        @else
            @include('shop.goods_carousel')
        @endif
        <!-- Goods-end -->
    </div>

    <!-- Modals -->
    @include('shop.modal_item_show')
    @include('shop.modal_basket_show')
    @include('shop.modal_preorder_show')
    <!-- Modals-end -->

</div>

@endsection
