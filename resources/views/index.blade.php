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
            <button class="btn btn-secondary btn-block btn-modal_basket_show btn-sm" data-toggle="tooltip" data-placement="bottom" title="Показать Вашу корзину товаров">
                Корзина <span class="badge badge-light">{{$baskCount}}</span>
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
            <form class="text-center" method="GET" action="/" accept-charset="UTF-8">
                <input type="hidden" name="filter_expand" value="1">
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
                                    <label class="col-11 pl-0 ml-0 form-check-label text-left text-break"
                                           style="font-size: .7rem;"
                                           for="additChars"
                                           data-toggle="tooltip"
                                           data-placement="bottom"
                                           title="Значение характеристики {{$char['name']}}:&#13; {{$char['value']}}">
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
                                        class="text-left btn btn-block btn-outline-secondary btn-sm btn-modal_shop_goods_show"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                        title="Нажать для подробностей/покупки"
                                        data-id="{{$item['id']}}"
                                        data-name="{{$item['name']}}"
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
                        <form class="modal_shop_quantity_add_form d-flex flex-row" action="{{route('basket.store')}}" method="POST">
                            @csrf
                            <h5><b><label class="p-2 m-2" for="modal_shop_quantity_goods">Кол-во: </label></b></h5>
                            <input type="hidden" id="goods_id" name="id" value="">
                            <input type="hidden" id="goods_name" name="name" value="">
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header shadow" style="background: url(/back_gray.jpg) repeat">
                    <h4><b>Корзина товаров</b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0" style="background: url(/back_gray.jpg) repeat">
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item" style="background-color: rgba(255,255,255,0.5)">
                            {{--<form id="test-form" action="/basket/1" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger">&times;</button>
                            </form>--}}
                            <p><span class="modal_basket_show_goods"></span></p>
                        </li>
                    </ul>
                    <div class="text-center col align-middle pt-2">
                        <button type="submit" class="btn btn-sm btn-success" data-update="true">Применить изменения</button>
                    </div>
                </div>
                <div class="modal-footer shadow" style="background: url(/back_gray.jpg) repeat">
                    <div class="col">
                        <button type="button" class="btn btn-sm btn-secondary btn-block" data-dismiss="modal">Закрыть</button>
                    </div>
                    <div class="col">
                        <form action="{{route('basket.destroy', 0)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-block" onclick="return confirm('Вы действительно очистить корзину?')">Очистить</button>
                        </form>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-primary btn-block" id="btn-basket-order">Оформить заказ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--Modal-basket-show-end--}}

</div>

@endsection
