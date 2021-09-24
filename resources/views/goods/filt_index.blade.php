@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/js/modals.js"></script>

<div class="container-fluid">
    <div class="row text-center shadow-lg">
        <div class="col-3"><h2>Фильтр</h2></div>
        <div class="col-9"><h2>Товары</h2></div>
    </div>

    <div class="row">
        {{--Фильтр--}}
        <div class="col-3 text-center" style="max-height:90vh !important; overflow-y:scroll !important;">
            <form class="center m-md-3 p-md-3" method="GET" action="/goods" accept-charset="UTF-8">
                <div class="form-group border m-md-2 p-md-2 shadow-lg">
                    <label for="category">по категории</label>
                    <select class="form-control @error('filter.category_id') is-invalid @enderror" name="filter[category_id]" id="category">
                        <option selected="selected" value="">-</option>
                        @foreach ($categories as $cat)
                            @if (
                                isset($_REQUEST['filter']['category_id']) &&
                                ($_REQUEST['filter']['category_id'] !== '') &&
                                ($cat['id'] == $_REQUEST['filter']['category_id'])
                                )
                                <option selected="selected" value={{$cat['id']}}>{{$cat['name']}}</option>
                            @else
                                <option value={{$cat['id']}}>{{$cat['name']}}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('filter.category_id')
                    <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                    @enderror
                </div>
                <div class="form-group border m-md-2 p-md-2 shadow-lg">
                    <label for="name">по имени</label>
                    @if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== ''))
                        <input type="text" class="form-control @error('filter.name') is-invalid @enderror" id="name" name="filter[name]" value="{{$_REQUEST['filter']['name']}}">
                    @else
                        <input type="text" class="form-control @error('filter.name') is-invalid @enderror" id="name" name="filter[name]">
                    @endif
                    @error('filter.name')
                    <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                    @enderror
                </div>

                <div class="form-group border m-md-2 p-md-2 shadow-lg">
                    <div class="col" style="max-height: 50vh !important; overflow-y: scroll !important;">
                        <label for="additChars">имеет характеристики</label>
                        <table class="table table-striped table-sm table-warning table-bordered">
                            <tbody>
                            @foreach($additCharacteristics as $char)
                                <tr>
                                    <td>
                                        <div class="form-control @error('filter.additChars') is-invalid @enderror">
                                            <div class="row form-check">
                                                <label class="col-11 form-check-label" for="additChars">{{$char['name']}}</label>
                                                @if (
                                                    isset($_REQUEST['filter']['additChars']) &&
                                                    ($_REQUEST['filter']['additChars'] !== '') &&
                                                    in_array($char['id'], $_REQUEST['filter']['additChars'])
                                                    )
                                                    <input class="col-1 right form-check-input" type="checkbox" name="filter[additChars][]" value="{{$char['id']}}" id="additChars" checked>
                                                @else
                                                    <input class="col-1 right form-check-input" type="checkbox" name="filter[additChars][]" value="{{$char['id']}}" id="additChars">
                                                @endif
                                            </div>
                                            @error('filter.additChars')
                                            <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                                            @enderror
                                        </div>
                                    <td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="btn-block m-md-2 p-md-2 shadow-lg">
                    <a href="/goods" style="text-decoration: none">
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
            </form>
        </div>
        {{--Фильтр-end--}}
        {{--Товары--}}
        <div class="col-9 text-left" style="max-height: 90vh !important; overflow-y: scroll !important;">
            <table class="table table-success table-striped table-sm mx-auto">
                <thead>
                    <tr>
                        <th scope="col">Наименование</th>
                        <th scope="col">Описание</th>
                        <th scope="col">Цена</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goods as $item)
                        <tr>
                            <td>
                                <button
                                    type="button"
                                    class="text-left btn btn-block btn-outline-secondary btn-sm btn-modal_goods_show"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Нажать для подробностей/изменения"
                                    data-id="{{$item['id']}}"
                                    data-route="{{route('goods.update', $item['id'])}}"
                                    style="border: none"
                                >
                                    <b>{{Str::limit($item['name'], 40)}}</b>
                                </button>
                            </td>
                            <td>{{Str::limit($item['description'], 120)}}</td>
                            <td>{{$item['price']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{--Товары-end--}}
    </div>

    {{--Modal-show--}}
    <div class="modal fade" id="modalItem-show" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header shadow" style="background-color: #c0ffe2">
                    <h4 class="modal_goods_show_title"><b></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #d5fdef">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <div class="row">
                                <div class="col">
                                    <h6><b>Имя товара</b></h6>
                                    <p><span class="modal_goods_show_name"></span></p>
                                </div>
                                <div class="col">
                                    <h6><b>slug товара</b></h6>
                                    <p><span class="modal_goods_show_slug"></span></p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <h6><b>Описание</b></h6>
                            <p><span class="modal_goods_show_description"></span></p>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <div class="row">
                                <div class="col">
                                    <h6><b>Цена товара</b></h6>
                                    <p><span class="modal_goods_show_price"></span></p>
                                </div>
                                <div class="col">
                                    <h6><b>Категория товара</b></h6>
                                    <p><span class="modal_goods_show_category"></span></p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <div class="row">
                                <div class="col">
                                    <h6><b>Время создания товара</b></h6>
                                    <p><span class="modal_goods_show_created_at"></span></p>
                                </div>
                                <div class="col">
                                    <h6><b>Время последнего изменения товара</b></h6>
                                    <p><span class="modal_goods_show_updated_at"></span></p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <h6><b><u>Дополнительные характеристики товара</u></b></h6>
                            <span class="modal_goods_show_additional_chars"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #c0ffe2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <div class="modal_goods_edit_button"></div>
                    <button type="button" class="btn btn-danger">Удалить</button>
                </div>
            </div>
        </div>
    </div>
    {{--Modal-show-end--}}

    {{--Modal-edit--}}
    <div class="modal fade" id="modalItem-edit" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important; scrollbar-width: thin !important;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form id="modalItem-edit-form" method="POST" action="/goods/">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header shadow" style="background-color: #c0ffe2">
                        <h4 class="modal_goods_edit_title"><b></b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #d5fdef">
                        <span class="modal_goods_edit_save_results"></span>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="background-color: #e6fff4">
                                <div class="row">
                                    <div class="col">
                                        <h6><b><label for="modal_goods_edit_name">Имя товара</label></b></h6>
                                        <input class="form-control modal_goods_edit_name" id="modal_goods_edit_name" type="text" name="name">
                                    </div>
                                    <div class="col">
                                        <h6><b><label for="modal_goods_edit_slug">slug товара</label></b></h6>
                                        <input class="form-control modal_goods_edit_slug" id="modal_goods_edit_slug" type="text" name="slug">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item" style="background-color: #e6fff4">
                                <h6><b><label for="modal_goods_edit_description">Описание</label></b></h6>
                                <textarea class="form-control modal_goods_edit_description" id="modal_goods_edit_description" rows="2" name="description"></textarea>
                            </li>
                            <li class="list-group-item" style="background-color: #e6fff4">
                                <div class="row">
                                    <div class="col">
                                        <h6><b><label for="modal_goods_edit_price">Цена товара</label></b></h6>
                                        <input class="form-control modal_goods_edit_price" id="modal_goods_edit_price" type="text" name="price">
                                    </div>
                                    <div class="col">
                                        <h6><b><label for="modal_goods_edit_category">Категория товара</label></b></h6>
                                        <span class="modal_goods_edit_category" id="modal_goods_edit_category"></span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item" style="background-color: #e6fff4">
                                <div class="row">
                                    <div class="col">
                                        <h6><b>Время создания товара</b></h6>
                                        <p><span class="modal_goods_edit_created_at"></span></p>
                                    </div>
                                    <div class="col">
                                        <h6><b>Время последнего изменения товара</b></h6>
                                        <p><span class="modal_goods_edit_updated_at"></span></p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item" style="background-color: #e6fff4">
                                <h6><b><center>Дополнительные характеристики товара</center></b></h6>
                                <span class="modal_goods_edit_additional_chars"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer shadow" style="background-color: #c0ffe2">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <div class="btn-modal_goods_edit_save"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Modal-edit-end--}}
</div>
{{--@include('flash::message')--}}

@endsection
