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
