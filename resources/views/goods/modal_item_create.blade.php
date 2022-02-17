<div class="modal fade" id="modal-item-create" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important; scrollbar-width: thin !important;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form id="modal-item-create-form" method="POST" action="{{route('goods.store')}}">
                @csrf
                <div class="modal-header shadow" style="background-color: #c0ffe2">
                    <h4 class="modal_goods_create_title"><b></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #d5fdef">
                    <span class="modal_goods_create_results"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <div class="row">
                                <div class="col-6">
                                    <h6><b><label for="modal_goods_create_name">Имя товара</label></b></h6>
                                    <input class="form-control modal_goods_create_name" id="modal_goods_create_name" type="text" name="name">
                                </div>
                                <div class="col-3">
                                    <h6><b><label for="modal_goods_create_slug">slug товара</label></b></h6>
                                    <input class="form-control modal_goods_create_slug" id="modal_goods_create_slug" type="text" name="slug">
                                </div>
                                <div class="col-3">
                                    <h6><b><label for="modal_goods_create_image">Изображение товара</label></b></h6>
                                    <input type="file" name="file" id="modal_goods_create_image" class="form-control-file">
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <h6><b><label for="modal_goods_create_description">Описание</label></b></h6>
                            <textarea class="form-control modal_goods_create_description" id="modal_goods_create_description" rows="2" name="description"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <div class="row">
                                <div class="col">
                                    <h6><b><label for="modal_goods_create_price">Цена товара</label></b></h6>
                                    <input class="form-control modal_goods_create_price" id="modal_goods_create_price" type="text" name="price">
                                </div>
                                <div class="col">
                                    <h6><b><label for="modal_goods_create_category">Категория товара</label></b></h6>
                                    <span class="modal_goods_create_category" id="modal_goods_create_category"></span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <h6><b><center>Дополнительные характеристики товара</center></b></h6>
                            <span class="modal_goods_create_additional_chars"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #c0ffe2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary btn-modal_goods_try_store">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
