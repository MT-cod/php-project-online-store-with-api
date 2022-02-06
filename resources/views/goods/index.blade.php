@extends('layouts.app')

@section('content')

<!-- Scripts -->
<script src="/js/online_store_modals_goods.js"></script>

<!-- Styles -->
<link href="{{ asset('css/online_store_green.css') }}" rel="stylesheet">

<div class="container-fluid" style="background: url(/back_green.jpg) repeat">

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
                <div style="position: absolute"><h3><b>Товары</b></h3></div>
                @include('flash::message')
            </div>

            <div class="col-2 pr-3 pt-1 text-right">
                @guest
                    <button type="button" class="btn btn-success btn-sm btn-block" onclick="return alert('Для создания товара необходимо авторизоваться!')">Новый товар</button>
                @else
                    <button class="btn btn-success btn-sm btn-block btn-modal_goods_create" data-toggle="tooltip" data-placement="bottom" title="Создать новый товар">Новый товар</button>
                @endguest
            </div>
    </div>

    <div class="row justify-content-center">
        <!-- Filter -->
        @include('goods.filter')

        <!-- Goods -->
        @include('goods.goods_table')
    </div>

    <!-- Modals -->
    @include('goods.modal_item_show')
    @include('goods.modal_item_edit')
    @include('goods.modal_item_create')
    <!-- Modals-end -->

</div>

@endsection
