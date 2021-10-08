@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/js/modals.js"></script>

<div class="container-fluid" style="height: 95vh !important; background: url(/back_blue.jpg) repeat">
    <div class="text-center">
        <h2><b>Дополнительные характеристики</b></h2>
    </div>
    {{--Фильтр--}}
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
    {{--Фильтр-end--}}

    {{--Таблица характеристик--}}
    @include('flash::message')
    <table class="table table-info table-striped table-sm mx-auto" style="opacity: 0.75">
        <thead>
            <tr class="text-center">
                <th scope="col">Наименование</th>
                <th scope="col">Значение</th>
                <th scope="col">Действия</th>
            </tr>
        </thead>
        <tbody class="text-left">
            @foreach ($additChars as $char)
                <tr>
                    <td>
                        <b>{{Str::limit($char['name'], 40)}}</b>
                    </td>
                    <td>{{Str::limit($char['value'], 200)}}</td>
                    <td class="form-row justify-content-fluid">
                        <button type="button" class="btn btn-outline-secondary btn-sm btn-modal_additChar_edit" data-id="{{$char['id']}}">Изменить</button>
                        @if (count($char['goods']))
                            @php($goodsNames = join(array_map(fn($goodsName) => $goodsName['name'] . '\n', $char['goods'])))
                            <form method="POST" action="{{route('additionalChars.destroy', $char['id'])}}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('В базе имеются товары с данной характеристикой!\n{{$goodsNames}} Вы действительно хотите удалить характеристику?')">Удалить</button>
                            </form>
                        @else
                            <form method="POST" action="{{route('additionalChars.destroy', $char['id'])}}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Вы действительно хотите удалить характеристику?')">Удалить</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{--Таблица характеристик-end--}}

    {{--Modal-edit--}}
    <div class="modal fade" id="modal-additChar-edit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form id="modal-additChar-edit-form" method="POST" action="/additionalChars">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header shadow" style="background-color: #dbfeff">
                        <h4 class="modal_additChar_edit_title"><b></b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #dbfeff">
                        <span class="modal_additChar_edit_save_results"></span>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="background-color: #e6f9ff">
                                <h6><b><label for="modal_additChar_edit_name">Имя характеристики</label></b></h6>
                                <input class="form-control" id="modal_additChar_edit_name" type="text" name="name">
                            </li>
                            <li class="list-group-item" style="background-color: #e6f9ff">
                                <h6><b><label for="modal_additChar_edit_value">Значение характеристики</label></b></h6>
                                <textarea class="form-control" id="modal_additChar_edit_value" rows="2" name="value"></textarea>
                            </li>
                            <li class="list-group-item" style="background-color: #e6fff4">
                                <div class="row">
                                    <div class="col">
                                        <h6><b>Время создания характеристики</b></h6>
                                        <p><span class="modal_additChar_edit_created_at"></span></p>
                                    </div>
                                    <div class="col">
                                        <h6><b>Время последнего изменения характеристики</b></h6>
                                        <p><span class="modal_additChar_edit_updated_at"></span></p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer shadow" style="background-color: #dbfeff">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <div class="btn-modal_additChar_edit_save"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Modal-edit-end--}}

    {{--Modal-create--}}
    <div class="modal fade" id="modal-additChar-create" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="modal-additChar-create-form" method="POST" action="{{route('additionalChars.store')}}">
                    @csrf
                    <div class="modal-header shadow" style="background-color: #dbfeff">
                        <h6><b>Создание новой дополнительной характеристики</b></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #dbfeff">
                        <span class="modal_additChar_create_results"></span>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="background-color: #e6f9ff">
                                <h6><b><label for="modal_additChar_create_name">Имя характеристики</label></b></h6>
                                <input class="form-control" id="modal_additChar_create_name" type="text" name="name">
                            </li>
                            <li class="list-group-item" style="background-color: #e6f9ff">
                                <h6><b><label for="modal_additChar_create_value">Значение характеристики</label></b></h6>
                                <textarea class="form-control" id="modal_additChar_create_value" rows="2" name="value"></textarea>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer shadow" style="background-color: #dbfeff">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Modal-create-end--}}

</div>

@endsection
