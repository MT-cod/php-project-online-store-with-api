<div class="modal fade" id="modal-order-edit" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y: auto;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header shadow" style="background: url(/back_blue.jpg) repeat">
                <h4><b><span id="modal_order_id"></span></b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background: url(/back_blue.jpg) repeat">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="background-color: rgba(255,255,255,0.3)">
                        <div class="row">
                            <div class="col">
                                <div style="background-color: rgba(0,0,0,0.12)"><h6><b>Имя заказчика:</b></h6></div>
                                <b><span class="text-break" id="modal_order_name" style="font-size: 1.2rem;"></span></b>
                            </div>
                            <div class="col">
                                <div style="background-color: rgba(0,0,0,0.12)"><h6><b>E-mail:</b></h6></div>
                                <b><span class="text-break" id="modal_order_email" style="font-size: 1.2rem;"></span></b>
                            </div>
                            <div class="col">
                                <div style="background-color: rgba(0,0,0,0.12)"><h6><b>Телефон:</b></h6></div>
                                <b><span class="text-break" id="modal_order_phone" style="font-size: 1.2rem;"></span></b>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" style="background-color: rgba(255,255,255,0.3)">
                        <div class="row">
                            <div class="col">
                                <div style="background-color: rgba(0,0,0,0.12)"><h6><b>Адрес доставки:</b></h6></div>
                                <b><span class="text-break" id="modal_order_address" style="font-size: 1.2rem;"></span></b>
                            </div>
                            <div class="col">
                                <div style="background-color: rgba(0,0,0,0.12)"><h6><b>Комментарий:</b></h6></div>
                                <b><span class="text-break" id="modal_order_comment" style="font-size: 1.2rem;"></span></b>
                            </div>
                        </div>
                    </li>
                </ul>
                <br>
                <p><span id="modal_order_basket"></span></p>
            </div>
            <div class="modal-footer shadow" style="background: url(/back_blue.jpg) repeat">
                <div class="col">
                    <button type="button" class="btn btn-sm btn-secondary btn-block" data-dismiss="modal">Закрыть</button>
                </div>
                <div class="col">
                    <form class="modal-order-form" action="/orders" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="status" name="completed" value="">
                        <button type="submit" class="btn btn-sm btn-primary btn-block" id="btn-order-complete"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
