@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row text-center shadow-lg">
            <div class="col-3"><h2>Категории</h2></div>
            <div class="col-9"><h2>Товары</h2></div>
        </div>
        <div class="row accordion">
            <div class="col-3" style="max-height:90vh !important; overflow-y:scroll !important;">
                <ul class="list-group list-group-flush">
                    @foreach ($categTreeWithGoods as $cat)
                        @php
                            $childCount = (isset($cat['childrens'])) ? count($cat['childrens']) : 0;
                        @endphp
                        <div class="row" style="background-color: #ffdb5d; border: groove;">
                            @if ($childCount > 0)
                                <div class="col-10 font-weight-bold">
                                    <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat['id']}}" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">{{$cat['name']}}</div>
                                </div>
                                <div class="col-2 text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#subcat{{$cat['id']}}">
                                    <span class="badge-hover badge badge-warning badge-pill" data-toggle="tooltip" data-placement="bottom" title="Развернуть категорию">{{$childCount}}&#8595</span>
                                </div>
                            @else
                                <div class="col-12 font-weight-bold">
                                    <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat['id']}}" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">{{$cat['name']}}</div>
                                </div>
                            @endif
                        </div>
                        {{--2-й уровень вложенности категорий--}}
                        @if ($childCount > 0)
                            <div id="subcat{{$cat['id']}}" class="collapse">
                                @foreach ($cat['childrens'] as $cat2lvl)
                                    @php
                                        $childLvl2Count = (isset($cat2lvl['childrens'])) ? count($cat2lvl['childrens']) : 0;
                                        /*$goodsCount = array_walk();*/
                                    @endphp
                                    <div class="row" style="background-color: #fce088; border: groove;">
                                        @if ($childLvl2Count > 0)
                                            <div class="col-10 font-weight-bold">
                                                <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat2lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">&ensp;&ensp;{{$cat2lvl['name']}}</div>
                                            </div>
                                            <div class="col-2 text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#subcat{{$cat2lvl['id']}}">
                                                <span class="badge-hover badge badge-warning badge-pill" data-toggle="tooltip" data-placement="bottom" title="Развернуть категорию">{{$childLvl2Count}}&#8595</span>
                                            </div>
                                        @else
                                            <div class="col-12 font-weight-bold">
                                                <div class="text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat2lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">&ensp;&ensp;{{$cat2lvl['name']}}</div>
                                            </div>
                                        @endif
                                    </div>
                                    {{--3-й уровень вложенности категорий--}}
                                    @if ($childLvl2Count > 0)
                                        <div id="subcat{{$cat2lvl['id']}}" class="collapse">
                                            @foreach ($cat2lvl['childrens'] as $cat3lvl)
                                                <div class="row" style="background-color: #ffe597; border: groove;">
                                                    <div class="col-12 font-weight-bold">
                                                        <div class="shadow-lg text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat3lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">&ensp;&ensp;&ensp;&ensp;{{$cat3lvl['name']}}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </ul>
            </div>
            {{--Товары--}}
            <div class="col-9 text-left" style="max-height: 90vh !important; overflow-y: scroll !important;">
                @foreach ($goods as $item)
                    <div id="cat{{$item['id']}}" class="card collapse">
                        <div class="card shadow-lg">
                            <ul class="list-group list-group-flush shadow">
                                <li class="list-group-item">
                                    <h5 class="card-title font-weight-bold">Имя категории</h5>
                                    <p class="card-text">{{$item['id']}} {{$item['name']}}</p>
                                </li>
                                <li class="list-group-item">
                                    <h5 class="card-title font-weight-bold">Время создания категории</h5>
                                    <p class="card-text font-italic">{{$item['created_at']}}</p>
                                </li>
                                <li class="list-group-item">
                                    <h5 class="card-title font-weight-bold">Описание категории</h5>
                                    <p class="card-text font-italic">{{$item['description']}}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
