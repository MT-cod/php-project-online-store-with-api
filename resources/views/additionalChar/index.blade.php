@extends('layouts.app')

@section('content')

<!-- Scripts -->
<script src="/js/online_store_modals_additional_chars.js"></script>
<script src="/js/online_store_sorting_arrows_funcs.js"></script>

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
    @include('additionalChar.modal_additional_chars_edit')
    @include('additionalChar.modal_additional_chars_create')
    <!-- Modals-end -->

</div>
{{--<div class="container-fluid" style="height: 95vh !important; background: url(/back_blue.jpg) repeat">
    <div class="text-center">
        <h2><b>Дополнительные характеристики</b></h2>
    </div>
    --}}{{--Фильтр--}}{{--
    <form class="center m-md-1 p-md-1" style="background-color: rgba(187,223,241,0.5);" method="GET" action="/additionalChars" accept-charset="UTF-8">
        <div class="form-row justify-content-center align-items-center text-center shadow-lg">
            <div class="col-sm text-right">
                <label for="name"><b>Фильтровать по имени</b></label>
            </div>
            <div class="col-sm">
                @if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== ''))
                    <input type="text" class="form-control" id="name" name="filter[name]" value="{{$_REQUEST['filter']['name']}}">
                @else
                    <input type="text" class="form-control" id="name" name="filter[name]">
                @endif
            </div>
            <div class="col-sm text-left">
                <a href="/additionalChars" style="text-decoration: none">
                    <button class="btn btn-outline-secondary collapse multi_filt show" type="button" id="submit_filt1" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                        Сброс фильтра
                    </button>
                </a>
                <button id="submit_filt2" class="btn btn-outline-secondary collapse multi_filt" type="button" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Сброс фильтра
                </button>

                <input class="btn btn-outline-secondary collapse multi-collapse show" id="submit1" type="submit" value="Применить" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                <button id="submit2" class="btn btn-outline-secondary collapse multi-collapse" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Применить
                </button>
            </div>
            <div class="col-sm text-right">
                <div class="btn btn-primary shadow-lg" data-toggle="modal" data-target="#modal-additChar-create">Новая характеристика</div>
            </div>
        </div>
    </form>
    --}}{{--Фильтр-end--}}{{--

    --}}{{--Таблица характеристик--}}{{--
    @include('flash::message')
    <table class="table table-info table-striped table-hover table-sm mx-auto" style="opacity: 0.75">
        <thead>
            <tr class="text-center">
                <th scope="col">Наименование</th>
                <th scope="col">Значение</th>
                <th scope="col">4</th>
            </tr>
        </thead>
        <tbody class="text-left">
            @foreach ($additChars as $char)4
                <tr>
                    <td>
                        <b>{{Str::limit($char['name'], 40)}}</b>
                    </td>
                    <td>{{Str::limit($char['value'], 200)}}</td>
                    <td class="form-row justify-content-fluid">
                        <button type="button" class="btn btn-outline-secondary btn-sm btn-modal_additChar_edit" data-id="{{$char['id']}}">Изменить</button>
                        @if (count($char['goods']))
                            @php($goodsNames = implode(array_map(static fn ($goodsName) => $goodsName['name'] . '\n', $char['goods']->toArray())))
                            <form method="POST" action="{{route('additionalChars.destroy', $char['id'])}}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('В базе имеются товары с данной характеристикой!\n{{$goodsNames}}Вы действительно хотите удалить характеристику?\nУ товаров будет убрана данная характеристика!')">Удалить</button>
                            </form>
                        @else
                            <form method="POST" action="{{route('additionalChars.destroy', $char['id'])}}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Вы действительно хотите удалить характеристику?')">Удалить</button>
                            </form>
                        @endif
                    </td>4
                </tr>
            @endforeach
        </tbody>
    </table>
    --}}{{--Таблица характеристик-end--}}

    {{--Modal-edit--}}

    {{--Modal-edit-end--}}

    {{--Modal-create--}}

    {{--Modal-create-end--}}


@endsection
