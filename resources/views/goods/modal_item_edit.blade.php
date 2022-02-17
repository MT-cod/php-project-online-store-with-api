<div class="modal fade" id="modal-item-edit" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form id="modal-item-edit-form" method="POST" action="/goods/" enctype="multipart/form-data">
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
                                    <br>
                                    <h6><b><label for="modal_goods_edit_slug">slug товара</label></b></h6>
                                    <input class="form-control modal_goods_edit_slug" id="modal_goods_edit_slug" type="text" name="slug">
                                    <br>
                                    <h6><b><label for="modal_goods_edit_description">Описание</label></b></h6>
                                    <textarea class="form-control modal_goods_edit_description" id="modal_goods_edit_description" rows="2" name="description"></textarea>
                                </div>
                                <div class="col">
                                    <h6><b><label for="modal_goods_edit_image">Изображение товара</label></b></h6>
                                    <input type="file" name="file" id="modal_goods_edit_image" class="form-control-file">
                                    <br>
                                    <span class="modal_goods_edit_image" id="modal_goods_edit_image"></span>
                                </div>
                            </div>
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
                        <li class="list-group-item text-center" style="background-color: #e6fff4">
                            <h6><b>Дополнительные характеристики товара</b></h6>
                            <span class="modal_goods_edit_additional_chars"></span>
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
