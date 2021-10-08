@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/js/modals.js"></script>

<div class="container-fluid" style="height: 95vh !important; background: url(/back_sunny.jpg) repeat">
    <div class="row text-center shadow-lg">
        <div class="col-3"><h2><b>Категории</b></h2></div>
        <div class="col-9"><h2><b>Подробности</b></h2></div>
    </div>
    <div class="row accordion">
        <div class="col-3" style="max-height:90vh !important; overflow-y:scroll !important;">
            <ul class="list-group list-group-flush">
                <div class="row" style="background-color: #ffdb5d; border: groove;" >
                    @guest
                        <div type="button" class="col-12 font-weight-bold btn-warning" onclick="return alert('Для создания новой категории необходимо авторизоваться!')">○ Новая категория</div>
                    @else
                        <div type="button" class="col-12 font-weight-bold btn-warning btn-modal_category_create" data-toggle="tooltip" data-placement="bottom" title="Создать новую категорию">○ Новая категория</div>
                    @endguest
                </div>
                @foreach ($categTree as $cat)
                    @php
                        $childCount = (isset($cat['childrens'])) ? count($cat['childrens']) : 0;
                    @endphp
                    <div class="row" style="background-color: #ffdb5d; border: groove;">
                        @if ($childCount > 0)
                        <div class="col-10 font-weight-bold">
                            <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat['id']}}" data-toggle="tooltip" data-placement="bottom" title="Подробнее о категории">• {{$cat['name']}}</div>
                        </div>
                        <div class="col-2 text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#subcat{{$cat['id']}}">
                            <span class="categ-collapse-pill badge-hover badge badge-warning badge-pill" id="categ-collapse-pill-{{$cat['id']}}" data-childcount="{{$childCount}}" data-id="{{$cat['id']}}" data-toggle="tooltip" data-placement="bottom" title="Развернуть/свернуть категорию">{{$childCount}}&#9660;</span>
                        </div>
                        @else
                            <div class="col-12 font-weight-bold">
                                <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat['id']}}" data-toggle="tooltip" data-placement="bottom" title="Подробнее о категории">• {{$cat['name']}}</div>
                            </div>
                        @endif
                    </div>
                    {{--2-й уровень вложенности категорий--}}
                    @if ($childCount > 0)
                        <div id="subcat{{$cat['id']}}" class="collapse">
                            @foreach ($cat['childrens'] as $cat2lvl)
                                @php
                                    $childLvl2Count = (isset($cat2lvl['childrens'])) ? count($cat2lvl['childrens']) : 0;
                                @endphp
                                <div class="row" style="background-color: #fce088; border: groove;">
                                    @if ($childLvl2Count > 0)
                                        <div class="col-10 font-weight-bold">
                                            <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat2lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Подробнее о категории">•• {{$cat2lvl['name']}}</div>
                                        </div>
                                        <div class="col-2 text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#subcat{{$cat2lvl['id']}}">
                                            <span class="categ-collapse-pill badge-hover badge badge-warning badge-pill" id="categ-collapse-pill-{{$cat2lvl['id']}}" data-childcount="{{$childLvl2Count}}" data-id="{{$cat2lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Развернуть/свернуть категорию">{{$childLvl2Count}}&#9660;</span>
                                        </div>
                                    @else
                                        <div class="col-12 font-weight-bold">
                                            <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat2lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Подробнее о категории">•• {{$cat2lvl['name']}}</div>
                                        </div>
                                    @endif
                                </div>
                                {{--3-й уровень вложенности категорий--}}
                                @if ($childLvl2Count > 0)
                                    <div id="subcat{{$cat2lvl['id']}}" class="collapse">
                                        @foreach ($cat2lvl['childrens'] as $cat3lvl)
                                            <div class="row" style="background-color: #ffe597; border: groove;">
                                                <div class="col-12 font-weight-bold">
                                                    <div class="shadow-lg text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat3lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Подробнее о категории">••• {{$cat3lvl['name']}}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </ul>
        </div>
        {{--Подробности--}}
        <div class="col-9 text-left" style="max-height: 90vh !important; overflow-y: scroll !important;">
            @include('flash::message')
            @foreach ($categories as $cat)
                <div id="cat{{$cat['id']}}" class="collapse">
                    <ul class="list-group list-group-flush" style="border: 1px #fc0 dashed; opacity: 0.75">
                        <li class="list-group-item">
                            <button type="button" class="close" aria-label="Close" data-toggle="collapse" data-target="#cat{{$cat['id']}}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="font-weight-bold">Имя категории</h5>
                            <p class="">{{$cat['name']}}</p>
                        </li>
                        <li class="list-group-item">
                            <h5 class="font-weight-bold">Время создания категории</h5>
                            <p class="font-italic">{{$cat['created_at']}}</p>
                        </li>
                        <li class="list-group-item">
                            <h5 class="font-weight-bold">Описание категории</h5>
                            <p class="font-italic">{{$cat['description']}}</p>
                        </li>
                        <li class="modal-footer" style="background-color: #ffffff">
                            @guest
                                <button type="button" class="btn btn-warning btn-sm" onclick="return alert('Для изменения категории необходимо авторизоваться!')">Изменить</button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="return alert('Для удаления категории необходимо авторизоваться!')">Удалить</button>
                            @else
                                <button type="button" class="btn btn-warning btn-sm btn-modal_category_edit" data-id="{{$cat['id']}}">Изменить</button>
                                <form action="/categories/{{$cat['id']}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Вы действительно хотите удалить категорию?')">Удалить</button>
                                </form>
                            @endguest
                        </li>
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    {{--Modal-create--}}
    <div class="modal fade" id="modal-categ-create" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="modal-categ-create-form" method="POST" action="{{route('categories.store')}}">
                    @csrf
                    <div class="modal-header shadow" style="background-color: #fff89f">
                        <h4 class="modal_categ_create_title"><b></b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #fff8ae">
                        <span class="modal_categ_create_results"></span>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="background-color: #fff9b9">
                                <h6><b><label for="modal_categ_create_name">Имя категории</label></b></h6>
                                <input class="form-control modal_categ_create_name" id="modal_categ_create_name" type="text" name="name">
                            </li>
                            <li class="list-group-item" style="background-color: #fff9b9">
                                <h6><b><label for="modal_categ_create_description">Описание категории</label></b></h6>
                                <textarea class="form-control modal_categ_create_description" id="modal_categ_create_description" rows="2" name="description"></textarea>
                            </li>
                            <li class="list-group-item" style="background-color: #fff9b9">
                                <h6><b><label for="modal_create_categ_parent_category">Категория будет является дочерней для:</label></b></h6>
                                <span class="modal_create_categ_parent_category" id="modal_create_categ_parent_category"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer shadow" style="background-color: #fff89f">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-outline-primary btn-modal_categ_try_store">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Modal-create-end--}}

    {{--Modal-edit--}}
    <div class="modal fade" id="modal-categ-edit" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form id="modal-categ-edit-form" method="POST" action="/categories/">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header shadow" style="background-color: #fff89f">
                        <h4 class="modal_categ_edit_title"><b></b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #fff8ae">
                        <span class="modal_categ_edit_save_results"></span>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="background-color: #fff9b9">
                                <h6><b><label for="modal_categ_edit_name">Имя категории</label></b></h6>
                                <input class="form-control modal_categ_edit_name" id="modal_categ_edit_name" type="text" name="name">
                            </li>
                            <li class="list-group-item" style="background-color: #fff9b9">
                                <h6><b><label for="modal_categ_edit_description">Описание категории</label></b></h6>
                                <textarea class="form-control modal_categ_edit_description" id="modal_categ_edit_description" rows="2" name="description"></textarea>
                            </li>
                            <li class="list-group-item" style="background-color: #fff9b9">
                                <div class="row">
                                    <div class="col">
                                        <h6><b>Время создания категории</b></h6>
                                        <p><span class="modal_categ_edit_created_at"></span></p>
                                    </div>
                                    <div class="col">
                                        <h6><b>Время последнего изменения категории</b></h6>
                                        <p><span class="modal_categ_edit_updated_at"></span></p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item" style="background-color: #fff9b9">
                                <h6><b><label for="modal_categ_edit_parent_category">Категория является дочерней для:</label></b></h6>
                                <span class="modal_categ_edit_parent_category" id="modal_categ_edit_parent_category"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer shadow" style="background-color: #fff89f">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <div class="btn-modal_categ_edit_save"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Modal-edit-end--}}

</div>

@endsection
