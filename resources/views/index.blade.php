@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3 class="display-10">Online store</h3>
    </div>
</div>

@php
    $categTreeWithGoods = \App\Models\Category::categTreeWithGoods();
    $goods = [];
    $categoriesList = \App\Models\Category::categoriesList();
@endphp
<div class="container-fluid">
    <div class="row accordion">
        <div class="col-3" style="max-height:90vh !important; overflow-y:scroll !important;">
            <ul class="list-group list-group-flush">
                @foreach ($categTreeWithGoods as $cat)
                    @php
                        $childCount = (isset($cat['childrens'])) ? count($cat['childrens']) : 0;
                    @endphp
                    <div class="row" style="background-color: #ffdb5d; border: groove;">
                        @if ($childCount > 0)
                            <div class="col-12 font-weight-bold">
                                <div class="text-left" type="button" data-toggle="collapse" data-target="#subcat{{$cat['id']}}" data-toggle="tooltip" data-placement="bottom" title="Развернуть категорию">{{$cat['name']}}</div>
                            </div>
                        @else
                            <div class="col-12 font-weight-bold">{{$cat['name']}}</div>
                        @endif
                    </div>
                    {{--2-й уровень вложенности категорий--}}
                    @if ($childCount > 0)
                        <div id="subcat{{$cat['id']}}" class="collapse">
                            @foreach ($cat['childrens'] as $cat2lvl)
                                @php
                                    $childLvl2Count = (isset($cat2lvl['childrens'])) ? count($cat2lvl['childrens']) : 0;
                                    $goodsCount2lvl = (isset($cat2lvl['goods'])) ? count($cat2lvl['goods']) : 0;
                                @endphp
                                <div class="row" style="background-color: #fce088; border: groove;">
                                    @if ($childLvl2Count > 0)
                                        @if ($goodsCount2lvl > 0)
                                            @php
                                                $goods[$cat2lvl['id']] = $cat2lvl['goods'];
                                            @endphp
                                            <div class="col-10 font-weight-bold">
                                                <div class="text-left" type="button" data-toggle="collapse" data-target="#subcat{{$cat2lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Развернуть категорию">&ensp;&ensp;{{$cat2lvl['name']}}</div>
                                            </div>
                                            <div class="col-2 text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#item{{$cat2lvl['id']}}">
                                                <span class="badge-hover badge badge-success badge-pill" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">Тов {{$goodsCount2lvl}}</span>
                                            </div>
                                        @else
                                            <div class="col-12 font-weight-bold">
                                                <div class="text-left" type="button" data-toggle="collapse" data-target="#subcat{{$cat2lvl['id']}}" data-toggle="tooltip" data-placement="bottom" title="Развернуть категорию">&ensp;&ensp;{{$cat2lvl['name']}}</div>
                                            </div>
                                        @endif
                                    @else
                                        @if ($goodsCount2lvl > 0)
                                            @php
                                                $goods[$cat2lvl['id']] = $cat2lvl['goods'];
                                            @endphp
                                            <div class="col-10 font-weight-bold">
                                                <div class="text-left">&ensp;&ensp;{{$cat2lvl['name']}}</div>
                                            </div>
                                            <div class="col-2 text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#item{{$cat2lvl['id']}}">
                                                <span class="badge-hover badge badge-success badge-pill" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">Тов {{$goodsCount2lvl}}</span>
                                            </div>
                                        @else
                                            <div class="col-12 font-weight-bold">
                                                <div class="text-left">&ensp;&ensp;{{$cat2lvl['name']}}</div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                {{--3-й уровень вложенности категорий--}}
                                @if ($childLvl2Count > 0)
                                    <div id="subcat{{$cat2lvl['id']}}" class="collapse">
                                        @foreach ($cat2lvl['childrens'] as $cat3lvl)
                                            @php
                                                $goodsCount3lvl = (isset($cat3lvl['goods'])) ? count($cat3lvl['goods']) : 0;
                                            @endphp
                                            <div class="row" style="background-color: #ffe597; border: groove;">
                                                @if ($goodsCount2lvl > 0)
                                                    @php
                                                    $goods[$cat3lvl['id']] = $cat3lvl['goods'];
                                                    @endphp
                                                    <div class="col-10 font-weight-bold">
                                                        <div class="text-left">&ensp;&ensp;&ensp;&ensp;{{$cat3lvl['name']}}</div>
                                                    </div>
                                                    <div class="col-2 text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#item{{$cat3lvl['id']}}">
                                                        <span class="badge-hover badge badge-success badge-pill" data-toggle="tooltip" data-placement="bottom" title="Показать товары категории">Тов {{$goodsCount3lvl}}</span>
                                                    </div>
                                                @else
                                                    <div class="col-12 font-weight-bold">
                                                        <div class="text-left">&ensp;&ensp;&ensp;&ensp;{{$cat3lvl['name']}}</div>
                                                    </div>
                                                @endif
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
            @foreach ($goods as $catId => $catGoods)
                <div id="item{{$catId}}" class="collapse">
                    @foreach($catGoods as $item)
                        <div class="row" style="background-color: #d8fd95; border: 1px solid silver;">
                            <div class="text-left">&ensp;&ensp;&ensp;&ensp;{{$item['name']}}</div>
                        {{--<ul class="list-group list-group-flush shadow">
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
                        </ul>--}}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
