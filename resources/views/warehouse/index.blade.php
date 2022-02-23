@extends('layouts.app')

@section('content')

<!-- Scripts -->
<script src="/js/online_store_modals_warehouses.js"></script>
<script src="/js/online_store_common_funcs.js"></script>

<!-- Styles -->
<link href="{{ asset('css/online_store_blue.css') }}" rel="stylesheet">

<div class="container-fluid" style="background: url(/back_darky_green.jpg) repeat">

    <div class="row justify-content-center" style="height: 4vh !important">
        <div class="col-10 text-center btn-sm pl-5 pr-5 pt-0">
            <div style="position: absolute"><h3><b>Склады</b></h3></div>
            @include('flash::message')
        </div>

        <div class="col-2 pr-3 pt-1 text-right">
            @guest
                <div type="button" class="btn btn-success btn-sm btn-block" onclick="return alert('Для создания склада необходимо авторизоваться!')">Новый склад</div>
            @else
                <div class="btn btn-success shadow-lg btn-sm btn-block" data-toggle="modal" data-target="#modal-warehouse-create" data-toggle="tooltip" data-placement="bottom" title="Создать новый склад">Новый склад</div>
            @endguest
        </div>
    </div>

    <div class="row justify-content-center">
        @include('warehouse.warehouses_table')
    </div>

    <!-- Modals -->
    @include('warehouse.modal_warehouse_edit')
    @include('warehouse.modal_warehouse_create')
    <!-- Modals-end -->

</div>

@endsection
