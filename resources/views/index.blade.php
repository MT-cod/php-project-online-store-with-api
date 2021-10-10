@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/js/modals.js"></script>

<div class="container-fluid" style="background: url(/back_gray.jpg) repeat">

    <div class="row justify-content-center" style="height: 4vh !important">
        <div class="col-2 pl-3 pt-1 text-left collapse filt show">
            <button type="button" class="btn btn-secondary btn-block btn-sm" id="filt_btn_expand" data-toggle="collapse" data-target=".filt" aria-controls="filter filt_btn_expand filt_btn_collapse"><b>Фильтр >></b></button>
        </div>
        <div class="col-2 pl-3 pt-1 text-left collapse filt">
            <button type="button" class="btn btn-secondary btn-block btn-sm" id="filt_btn_collapse" data-toggle="collapse" data-target=".filt" aria-controls="filter filt_btn_expand filt_btn_collapse"><b><< Фильтр</b></button>
        </div>
        <div class="col-8"></div>
        <div class="col-2 pr-3 pt-1 text-right">
            @guest
                <button type="button" class="btn btn-secondary btn-block btn-sm" onclick="return alert('Для создания товара необходимо авторизоваться!')">Корзина</button>
            @else
                <button class="btn btn-secondary btn-block btn-modal_goods_create btn-sm" data-toggle="tooltip" data-placement="bottom" title="Создать новый товар">Корзина</button>
            @endguest
        </div>
    </div>
    <div class="row justify-content-center">
        {{--Фильтр--}}
        <div class="col-2 collapse filt" id="filter" style="height: 91vh !important; overflow-y: auto;">
            <form class="text-center" method="GET" action="/" accept-charset="UTF-8">
                <div class="form-group border m-md-2 p-md-2 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
                    <label for="category"><b>по категории</b></label>
                    <select class="form-control @error('filter.category_id') is-invalid @enderror" name="filter[category_id]" id="category" style="background-color: rgba(255,255,255,0.3);">
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
                <div class="form-group border m-md-2 p-md-2 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
                    <label for="name"><b>по имени</b></label>
                    @if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== ''))
                        <input type="text" class="form-control @error('filter.name') is-invalid @enderror" id="name" name="filter[name]" value="{{$_REQUEST['filter']['name']}}" style="background-color: rgba(255,255,255,0.3);">
                    @else
                        <input type="text" class="form-control @error('filter.name') is-invalid @enderror" id="name" name="filter[name]" style="background-color: rgba(255,255,255,0.3);">
                    @endif
                    @error('filter.name')
                    <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                    @enderror
                </div>

                <div class="form-group shadow-lg" style="background-color: rgba(0,0,0,0.15);">
                    <div class="col-sm" style="max-height: 60vh !important; overflow-y: auto;">
                        <label for="additChars"><b>имеет характеристики</b></label>
                        @foreach($additCharacteristics as $char)
                            <div class="form-control p-0 m-0 @error('filter.additChars') is-invalid @enderror" style="background-color: rgba(255,255,255,0);">
                                <div class="form-check p-0 m-0" style="background-color: rgba(255,255,255,0.1);">
                                    <label class="col-11 pl-0 ml-0 form-check-label text-left text-break" style="font-size: .7rem;" for="additChars">
                                        <strong>{{$char['name']}}</strong>
                                    </label>
                                    @if (
                                        isset($_REQUEST['filter']['additChars']) &&
                                        ($_REQUEST['filter']['additChars'] !== '') &&
                                        in_array($char['id'], $_REQUEST['filter']['additChars'])
                                        )
                                        <input class="col-1 align-content-end form-check-input pl-0" type="checkbox" name="filter[additChars][]" value="{{$char['id']}}" id="additChars" checked>
                                    @else
                                        <input class="col-1 align-content-end form-check-input pl-0" type="checkbox" name="filter[additChars][]" value="{{$char['id']}}" id="additChars">
                                    @endif
                                </div>
                                @error('filter.additChars')
                                <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="btn-block m-md-2 p-md-2 shadow-lg">
                    <a href="/" style="text-decoration: none">
                        <button class="btn btn-secondary collapse multi_filt show" type="button" id="submit_filt1" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                            Сброс фильтра
                        </button>
                    </a>
                    <button id="submit_filt2" class="btn btn-secondary collapse multi_filt" type="button" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Сброс фильтра
                    </button>

                    <input class="btn btn-secondary collapse multi-collapse show" id="submit1" type="submit" value="Применить" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                    <button id="submit2" class="btn btn-secondary collapse multi-collapse" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Применить
                    </button>
                </div>
            </form>
        </div>
        {{--Фильтр-end--}}
        {{--Товары--}}
        @if ($goods)
            <div class="col text-left" style="height: 91vh !important; overflow-y: auto;">
                @include('flash::message')
                <table class="table table-bordered table-hover table-sm mx-auto">
                    <thead style="background-color: rgba(0,0,0,0.1);">
                        <tr>
                            <th scope="col" class="text-center">Наименование товара</th>
                            <th scope="col" class="text-center">Описание товара</th>
                            <th scope="col">Цена</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: rgba(0,0,0,0.05);">
                        @foreach ($goods as $item)
                            <tr>
                                <td>
                                    <button
                                        type="button"
                                        class="text-left btn btn-block btn-outline-secondary btn-sm btn-modal_goods_show"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                        title="Нажать для подробностей/покупки"
                                        data-id="{{$item['id']}}"
                                        data-edit_route="{{route('goods.update', $item['id'])}}"
                                        data-delete_route="{{route('goods.destroy', $item['id'])}}"
                                        style="border: none">
                                        <h6><b>{{Str::limit($item['name'], 40)}}</b></h6>
                                    </button>
                                </td>
                                <td>{{Str::limit($item['description'], 120)}}</td>
                                <td><b>{{$item['price']}}</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        {{--Товары-end--}}
    </div>
    {{--Modal-show--}}
    <div class="modal fade" id="modal-item-show" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important;">
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
                            <h6><b><center>Дополнительные характеристики товара</center></b></h6>
                            <span class="modal_goods_show_additional_chars"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #c0ffe2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    @guest
                        <button type="button" class="btn btn-warning" onclick="return alert('Для изменения товара необходимо авторизоваться!')">Изменить</button>
                        <button type="button" class="btn btn-danger" onclick="return alert('Для удаления товара необходимо авторизоваться!')">Удалить</button>
                    @else
                        <div class="modal_goods_edit_button"></div>
                        <form class="modal_goods_delete_form" action="/goods/" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить товар?')">Удалить</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    {{--Modal-show-end--}}

</div>

@endsection
