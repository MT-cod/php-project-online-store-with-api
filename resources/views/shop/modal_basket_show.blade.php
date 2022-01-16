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
