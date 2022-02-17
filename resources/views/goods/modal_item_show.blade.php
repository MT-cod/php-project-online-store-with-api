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
                                <br>
                                <h6><b>slug товара</b></h6>
                                <p><span class="modal_goods_show_slug"></span></p>
                                <br>
                                <h6><b>Описание</b></h6>
                                <p><span class="modal_goods_show_description"></span></p>
                            </div>
                            <div class="col">
                                <h6><b><label for="modal_goods_show_image">Изображение товара</label></b></h6>
                                <br>
                                <span class="modal_goods_show_image" id="modal_goods_show_image"></span>
                            </div>
                        </div>
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
                    <li class="list-group-item text-center" style="background-color: #e6fff4">
                        <h6><b>Дополнительные характеристики товара</b></h6>
                        <span class="modal_goods_show_additional_chars"></span>
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
