@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/js/modals.js"></script>

<style>
    tr.clickableRow {cursor: pointer;}
</style>

<div class="container-fluid" style="height: 95vh !important; background: url(/back_blue.jpg) repeat">
    <div class="text-center">
        <h2><b>Заказы</b></h2>
    </div>
    {{--Фильтр--}}
    <form class="center m-md-1 p-md-1" style="background-color: rgba(187,223,241,0.5);" method="GET" action="/orders" accept-charset="UTF-8">
        <div class="form-row justify-content-center align-items-center text-center shadow-lg">
            <div class="col-sm text-right">
                <label for="name"><b>Фильтровать по имени заказчика</b></label>
            </div>
            <div class="col-sm">
                @if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== ''))
                    <input type="text" class="form-control" id="name" name="filter[name]" value="{{$_REQUEST['filter']['name']}}">
                @else
                    <input type="text" class="form-control" id="name" name="filter[name]">
                @endif
            </div>
            <div class="col-sm text-left">
                <a href="/orders" style="text-decoration: none">
                    <button class="btn btn-outline-secondary collapse multi_filt show" type="button" id="submit_filt1" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                        Сброс фильтра
                    </button>
                </a>
                <button id="submit_filt2" class="btn btn-outline-secondary collapse multi_filt" type="button" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Сброс фильтра
                </button>

                <input class="btn btn-outline-secondary collapse multi-collapse show" id="submit1" type="submit" value="Применить" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                <button id="submit2" class="btn btn-outline-secondary collapse multi-collapse" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Применить
                </button>
            </div>
        </div>
    </form>
    {{--Фильтр-end--}}

    {{--Таблица заказов--}}
    @include('flash::message')
    <table class="table table-info table-striped table-bordered table-hover table-sm mx-auto" style="opacity: 0.75">
        <thead>
            <tr class="text-center">
                <th scope="col">№</th>
                <th scope="col">Дата/Время</th>
                <th scope="col">Имя заказчика</th>
                <th scope="col">E-mail</th>
                <th scope="col">Телефон</th>
                <th scope="col">Статус</th>
            </tr>
        </thead>
        <tbody class="text-left">
            @foreach ($orders as $order)
                <tr class="text-center clickableRow btn-modal_order_edit" data-id="{{$order['id']}}">
                    <td class="text-break"><b>{{Str::limit($order['id'], 40)}}</b></td>
                    <td class="text-break">{{Str::limit($order['created_at'], 60)}}</td>
                    <td class="text-break">{{Str::limit($order['name'], 60)}}</td>
                    <td class="text-break">{{Str::limit($order['email'], 60)}}</td>
                    <td class="text-break">{{Str::limit($order['phone'], 60)}}</td>
                    <td class="text-break">
                        @if ($order['completed'])
                            <b>Завершён</b>
                        @else
                            <b>В работе</b>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    {{--Таблица заказов-end--}}

    {{--Modal-oder-edit--}}
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
    {{--Modal-oder-edit-end--}}

</div>

@endsection
