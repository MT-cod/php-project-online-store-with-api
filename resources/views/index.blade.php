@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/js/modals.js"></script>

<style>
    .carousel-control-prev-icon {
        filter: invert(100%);
    }
    .carousel-control-next-icon {
        filter: invert(100%);
    }
    .carousel-indicators {
        filter: invert(100%);
    }

    .pagination > li{
        color: black;
        background-color: #dadada;
        border: 1px solid black;
    }
    .pagination > li > a:hover,
    .pagination > li > span:hover
    {
        color: black;
        background-color: #bdbdbd;
    }
    .page-item.active .page-link {
        z-index: 3;
        color: white;
        background-color: #414141;
        border-color: #1c1c1c;
    }
    .page-link {
        color: black;
        background-color: #dadada;
    }

    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #414141 !important;
    }
    .custom-checkbox .custom-control-input:checked:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.1rem rgba(37, 37, 37, 0.25)
    }
    .custom-checkbox .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.1rem rgba(0, 0, 0, 0.25)
    }

    th.clickableRow {
        cursor: pointer;
    }
</style>

<div class="container-fluid" style="background: url(/back_gray.jpg) repeat">

    <div class="row justify-content-center" style="height: 4vh !important">
        @if (isset($_REQUEST['filter_expand']))
            <div class="col-2 pl-3 pt-1 text-left collapse filt">
        @else
            <div class="col-2 pl-3 pt-1 text-left collapse filt show">
        @endif
            <button type="button" class="btn btn-secondary btn-block btn-sm" id="filt_btn_expand" data-toggle="collapse" data-target=".filt" aria-controls="filter filt_btn_expand filt_btn_collapse"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Фильтр >></b></button>
        </div>
        @if (isset($_REQUEST['filter_expand']))
            <div class="col-2 pl-3 pt-1 text-left collapse filt show">
        @else
            <div class="col-2 pl-3 pt-1 text-left collapse filt">
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
        {{--Фильтр--}}
        @if (isset($_REQUEST['filter_expand']))
            <div class="col-2 collapse filt show" id="filter" style="height: 91vh !important; overflow-y: auto;">
        @else
            <div class="col-2 collapse filt" id="filter" style="height: 91vh !important; overflow-y: auto;">
        @endif
            <form class="text-center" id="fsp" method="GET" action="/" accept-charset="UTF-8">
                <input type="hidden" name="filter_expand" value="1">
                <input id="perpage" type="hidden" name="perpage" value="{{ $_REQUEST['perpage'] ?? 15}}">
                <input id="sortByName" type="hidden" name="sort[name]" value="{{ $_REQUEST['sort']['name'] ?? ''}}">
                <input id="sortByPrice" type="hidden" name="sort[price]" value="{{ $_REQUEST['sort']['price'] ?? ''}}">

                <div class="form-group border m-md-2 p-md-2 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
                    <label for="category"><b>по категории</b></label>
                    <select class="form-control" name="filter[category_id]" id="category" style="background-color: rgba(255,255,255,0.3);">
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
                </div>
                <div class="form-group border m-md-2 p-md-2 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
                    <label for="name"><b>по имени</b></label>
                    @if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== ''))
                        <input type="text" class="form-control" id="name" name="filter[name]" value="{{$_REQUEST['filter']['name']}}" style="background-color: rgba(255,255,255,0.3);">
                    @else
                        <input type="text" class="form-control" id="name" name="filter[name]" style="background-color: rgba(255,255,255,0.3);">
                    @endif
                </div>

                <div class="form-group shadow-lg" style="background-color: rgba(0,0,0,0.15);">
                    <div class="col-sm" style="max-height: 60vh !important; overflow-y: auto;">
                        <label><b>товар имеет характеристики</b></label>
                        @foreach($additCharacteristics as $char)
                            <div class="custom-control custom-checkbox" style="border: 1px groove white; background-color: rgba(255,255,255,0.1);">
                                @if (
                                    isset($_REQUEST['filter']['additChars']) &&
                                    ($_REQUEST['filter']['additChars'] !== '') &&
                                    in_array($char['id'], $_REQUEST['filter']['additChars'])
                                    )
                                    <input type="checkbox" class="col-1 custom-control-input" name="filter[additChars][]" id="additChars-{{$char['id']}}" value="{{$char['id']}}" checked>
                                @else
                                    <input type="checkbox" class="col-1 custom-control-input" name="filter[additChars][]" id="additChars-{{$char['id']}}" value="{{$char['id']}}">
                                @endif
                                <label class="col-11 custom-control-label p-1 m-1 text-left text-break"
                                       for="additChars-{{$char['id']}}"
                                       data-toggle="tooltip"
                                       data-placement="bottom"
                                       title="Значение характеристики {{$char['name']}}:&#13; {{$char['value']}}"
                                       style="font-size: .7rem;">
                                    <strong>{{$char['name']}}</strong>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="btn-block m-md-2 p-md-2 shadow-lg">
                    <a href="/?filter_expand=1" style="text-decoration: none">
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
                <div class="row p-0 m-0">
                    <div class="col-10 d-flex justify-content-center pagination pagination-sm">
                        {{ $goods->links('pagination::bootstrap-4') }}
                    </div>
                    <div class="col-2 p-sm-1 m-0 d-flex justify-content-center">
                        <b>Показать</b>
                        <input href="#" onclick="$('#perpage').val(15)" type="submit" form="fsp" value="15">
                        <input href="#" onclick="$('#perpage').val(50)" type="submit" form="fsp" value="50">
                        <input href="#" onclick="$('#perpage').val(500)" type="submit" form="fsp" value="500">
                    </div>
                </div>
                <table class="table table-bordered table-hover table-sm mx-auto">
                    <thead style="background-color: rgba(0,0,0,0.1);">
                        <tr>
                            @if (isset($_REQUEST['sort']['name']) && ($_REQUEST['sort']['name'] === 'asc'))
                                <th
                                    scope="col"
                                    class="text-center clickableRow sortingGoodsTable"
                                    data-sort_col_name="name"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Нажать для сортировки">
                                    Наименование товара ▲
                                </th>
                            @elseif (isset($_REQUEST['sort']['name']) && ($_REQUEST['sort']['name'] === 'desc'))
                                <th scope="col"
                                    class="text-center clickableRow sortingGoodsTable"
                                    data-sort_col_name="name"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Нажать для сортировки">
                                    Наименование товара ▼
                                </th>
                            @else
                                <th scope="col"
                                    class="text-center clickableRow sortingGoodsTable"
                                    data-sort_col_name="name"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Нажать для сортировки">
                                    Наименование товара
                                </th>
                            @endif
                            <th scope="col" class="text-center">Описание товара</th>
                            @if (isset($_REQUEST['sort']['price']) && ($_REQUEST['sort']['price'] === 'asc'))
                                <th
                                    scope="col"
                                    class="clickableRow sortingGoodsTable"
                                    data-sort_col_name="price"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Нажать для сортировки">
                                    Цена ▲
                                </th>
                            @elseif (isset($_REQUEST['sort']['price']) && ($_REQUEST['sort']['price'] === 'desc'))
                                <th scope="col"
                                    class="clickableRow sortingGoodsTable"
                                    data-sort_col_name="price"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Нажать для сортировки">
                                    Цена ▼
                                </th>
                            @else
                                <th scope="col"
                                    class="clickableRow sortingGoodsTable"
                                    data-sort_col_name="price"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Нажать для сортировки">
                                    Цена
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody style="background-color: rgba(0,0,0,0.05);">
                        @foreach ($goods as $item)
                            <tr>
                                <td>
                                    <button
                                        type="button"
                                        class="text-left btn btn-block btn-outline-secondary btn-sm btn-modal_shop_goods_show"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                        title="Нажать для подробностей/покупки"
                                        data-id="{{$item['id']}}"
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
        @else
            {{--Карусель-товаров--}}
            <div id="carouselExampleIndicators" class="col carousel slide text-center" data-ride="carousel" style="height: 91vh !important; vertical-align: middle !important;">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="d-block display-1">
                            <div class="display-4 p-lg-5">Самый дорогой товар!</div>
                            <div class="display-4 p-lg-5">{{Str::limit($carouselData[0]['name'], 40)}}</div>
                            <div><h4><b>{{Str::limit($carouselData[0]['description'], 500)}}</b></h4></div>
                            <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[0]['price'], 40)}}</div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="d-block display-1">
                            <div class="display-4 p-lg-5">Самый дешевый товар!</div>
                            <div class="display-4 p-lg-5">{{Str::limit($carouselData[1]['name'], 40)}}</div>
                            <div><h4><b>{{Str::limit($carouselData[1]['description'], 500)}}</b></h4></div>
                            <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[1]['price'], 40)}}</div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="d-block display-1">
                            <div class="display-4 p-lg-5">Самый первый товар!</div>
                            <div class="display-4 p-lg-5">{{Str::limit($carouselData[2]['name'], 40)}}</div>
                            <div><h4><b>{{Str::limit($carouselData[2]['description'], 500)}}</b></h4></div>
                            <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[2]['price'], 40)}}</div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="d-block display-1">
                            <div class="display-4 p-lg-5">Самый свежий товар!</div>
                            <div class="display-4 p-lg-5">{{Str::limit($carouselData[3]['name'], 40)}}</div>
                            <div><h4><b>{{Str::limit($carouselData[3]['description'], 500)}}</b></h4></div>
                            <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[3]['price'], 40)}}</div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only" style="color: #0b2e13">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            {{--Карусель-товаров-end--}}
        @endif
        {{--Товары-end--}}
    </div>
    {{--Modal-item-show--}}
    <div class="modal fade" id="modal-item-show" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y: auto;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header shadow" style="background: url(/back_gray.jpg) repeat">
                    <h4 class="modal_goods_show_title"><b></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: url(/back_gray.jpg) repeat">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: rgba(255,255,255,0.5)">
                            <h6><b>Имя товара</b></h6>
                            <p><span class="modal_goods_show_name"></span></p>
                        </li>
                        <li class="list-group-item" style="background-color: rgba(255,255,255,0.5)">
                            <h6><b>Описание</b></h6>
                            <p><span class="modal_goods_show_description"></span></p>
                        </li>
                        <li class="list-group-item" style="background-color: rgba(255,255,255,0.5)">
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
                        <li class="list-group-item" style="background-color: rgba(255,255,255,0.5)">
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
                        <li class="list-group-item" style="background-color: rgba(255,255,255,0.5)">
                            <h6><b><center>Дополнительные характеристики товара</center></b></h6>
                            <span class="modal_goods_show_additional_chars"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background: url(/back_gray.jpg) repeat">
                    <div class="text-left col align-middle">
                        <form class="d-flex flex-row" action="{{route('basket.store')}}" method="POST">
                            @csrf
                            <h5><b><label class="p-2 m-2" for="modal_shop_quantity_goods">Кол-во: </label></b></h5>
                            <input type="hidden" id="goods_id" name="id" value="">
                            <input class="form-control modal_shop_quantity_goods w-25 p-2 m-2 text-right" id="modal_shop_quantity_goods" type="number" name="quantity" value="1" min="1" required>
                            <button type="submit" class="btn btn-sm btn-success p-2 m-2">Добавить в корзину</button>
                        </form>
                    </div>
                    <div class="text-right col">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--Modal-item-show-end--}}

    {{--Modal-basket-show--}}
    <div class="modal fade" id="modal-basket-show" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y: auto;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header shadow" style="background: url(/back_gray.jpg) repeat">
                    <h4><b>Корзина товаров</b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: url(/back_gray.jpg) repeat">
                    <form class="modal-basket-form" action="/basket/0" method="POST">
                        @csrf
                        @method('PATCH')
                        <p><span class="modal_basket_edit_results" style="font-size: .9rem;"></span></p>
                        <p><span class="modal_basket_show_goods"></span></p>
                    </form>
                </div>
                <div class="modal-footer shadow" style="background: url(/back_gray.jpg) repeat">
                    <div class="col">
                        <button type="button" class="btn btn-sm btn-secondary btn-block" data-dismiss="modal">Закрыть</button>
                    </div>
                    @if ($baskCount > 0)
                        <div class="col">
                            <form action="{{route('basket.destroy', 0)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-block" onclick="return confirm('Вы действительно очистить корзину?')">Очистить</button>
                            </form>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-basket-preorder">Оформить заказ</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{--Modal-basket-show-end--}}

    {{--Modal-preorder-show--}}
    <div class="modal fade" id="modal-preorder-show" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y: auto;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form class="modal-preorder-form" action="/orders" method="POST">
                    @csrf
                    <div class="modal-header shadow" style="background: url(/back_gray.jpg) repeat">
                        <h4><b>Оформление заказа</b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background: url(/back_gray.jpg) repeat">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="background-color: rgba(0,0,0,0.05)">
                                <div class="row">
                                    <div class="col">
                                        <h6><b><label for="modal_preorder_name">*Ваше имя</label></b></h6>
                                        <input class="form-control" id="modal_preorder_name" type="text" name="name" required>
                                    </div>
                                    <div class="col">
                                        <h6><b><label for="modal_preorder_email">*Ваш email</label></b></h6>
                                        <input class="form-control" id="modal_preorder_email" type="email" name="email" required>
                                    </div>
                                    <div class="col">
                                        <h6><b><label for="modal_preorder_phone">*Ваш телефон</label></b></h6>
                                        <input class="form-control" id="modal_preorder_phone" type="text" name="phone" required>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item pb-0" style="background-color: rgba(0,0,0,0.05)">
                                <div class="row">
                                    <div class="col">
                                        <h6><b><label for="modal_preorder_address">Адрес доставки</label></b></h6>
                                        <textarea class="form-control" id="modal_preorder_address" rows="2" name="address"></textarea>
                                    </div>
                                    <div class="col">
                                        <h6><b><label for="modal_preorder_comment">Комментарий</label></b></h6>
                                        <textarea class="form-control" id="modal_preorder_comment" rows="2" name="comment"></textarea>
                                    </div>
                                </div>
                                <br>
                                <h6><i>* - поля обязательные для заполнения</i></h6>
                            </li>
                        </ul>
                        <br>
                        <p><span class="modal_preorder_basket"></span></p>
                    </div>
                    <div class="modal-footer shadow" style="background: url(/back_gray.jpg) repeat">
                        <div class="col">
                            <button type="button" class="btn btn-sm btn-secondary btn-block" data-dismiss="modal">Закрыть</button>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-sm btn-primary btn-block">Подтвердить заказ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Modal-preorder-show-end--}}

</div>

@endsection
