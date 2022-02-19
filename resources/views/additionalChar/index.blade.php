@extends('layouts.app')

@section('content')

<!-- Scripts -->
<script src="/js/online_store_modals_additional_chars.js"></script>
<script src="/js/online_store_common_funcs.js"></script>

<!-- Styles -->
<link href="{{ asset('css/online_store_blue.css') }}" rel="stylesheet">

<div class="container-fluid" style="background: url(/back_blue.jpg) repeat">

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
            <div style="position: absolute"><h3><b>Дополнительные характеристики</b></h3></div>
            @include('flash::message')
        </div>

        <div class="col-2 pr-3 pt-1 text-right">
            <div class="btn btn-primary shadow-lg btn-sm btn-block" data-toggle="modal" data-target="#modal-additChar-create" data-toggle="tooltip" data-placement="bottom" title="Создать новую характеристику">Новая характеристика</div>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Filter -->
        @include('additionalChar.filter')

        <!-- Additional chars -->
        @include('additionalChar.additional_chars_table')
    </div>

    <!-- Modals -->
    @include('additionalChar.modal_additional_char_edit')
    @include('additionalChar.modal_additional_char_create')
    <!-- Modals-end -->

</div>

@endsection
