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
                        <div class="row">
                            <div class="col">
                                <h6><b>Имя товара</b></h6>
                                <p><span class="modal_goods_show_name"></span></p>
                                <br>
                                <h6><b>Описание</b></h6>
                                <p><span class="modal_goods_show_description"></span></p>
                                <br>
                                <h6><b>Категория товара</b></h6>
                                <p><span class="modal_goods_show_category"></span></p>
                            </div>
                            <div class="col text-right">
                                <h6><b><label for="modal_goods_show_image">Изображение товара</label></b></h6>
                                <br>
                                <span class="modal_goods_show_image" id="modal_goods_show_image"></span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" style="background-color: rgba(255,255,255,0.5)">
                        <div class="row">
                            <div class="col">
                                <h6><b>Дополнительные характеристики товара</b></h6>
                                <span class="modal_goods_show_additional_chars"></span>
                            </div>
                            <div class="col text-right">
                                <h6><b>Цена товара</b></h6>
                                <p><span class="modal_goods_show_price"></span></p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item text-center" style="background-color: rgba(255,255,255,0.5)">
                        <div class="row">
                            <div class="col">
                                <h6><b>Наличие товара по складам</b></h6>
                                <span class="modal_goods_show_amounts"></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="modal-footer shadow" style="background: url(/back_gray.jpg) repeat">
                <div class="text-left col">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
                <div class="text-right col">
                    <form class="d-flex flex-row" action="{{route('basket.store')}}" method="POST">
                        @csrf
                        <h5><b><label class="p-2 m-2" for="modal_shop_quantity_goods">Кол-во: </label></b></h5>
                        <input type="hidden" id="goods_id" name="id" value="">
                        <input class="form-control modal_shop_quantity_goods w-25 p-2 m-2 text-right" id="modal_shop_quantity_goods" type="number" name="quantity" value="1" min="1" required>
                        <button type="submit" class="btn btn-sm btn-success p-2 m-2">Добавить в корзину</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
