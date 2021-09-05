@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row text-center shadow-lg">
        <div class="col-3"><h2>Категории</h2></div>
        <div class="col-9"><h2>Подробности</h2></div>
    </div>
    <div class="row">
        <div class="col-3" style="max-height: 85vh !important; overflow-y: scroll !important;">
            @foreach ($topCategories as $cat)
                @php
                    $childCount = $cat->childrens()->count();
                    $goodsCount = $cat->goods()->count();
                @endphp
                <div class="accordion">
                    <div class="card shadow-lg" style="background-color: #e8dbaa">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <button class="btn-secondary btn-block btn-sm text-left" type="button" data-toggle="collapse" data-target="#subcat{{$cat->id}}">
                                        <div class="row">
                                            <div class="col-8">
                                                <b>{{$cat->name}}</b>
                                            </div>
                                            <div class="col-4 text-right">
                                                @if ($childCount > 0)
                                                    <span class="badge badge-warning badge-pill">Подкатегорий {{$childCount}}</span>
                                                @endif
                                                @if ($goodsCount > 0)
                                                    <span class="badge badge-success badge-pill">Товаров {{$goodsCount}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <button class="btn-secondary btn-block btn-sm" type="button" data-toggle="collapse" data-target="#cat{{$cat->id}}"> >>> </button>
                        </div>
                        @if ($childCount > 0)
                            <div id="subcat{{$cat->id}}" class="collapse">
                                <div class="card-body">
                                    @foreach ($cat->childrens()->get() as $cat2lvl)
                                        @php
                                            $childLvl2Count = $cat2lvl->childrens()->count();
                                            $goodsLvl2Count = $cat2lvl->goods()->count();
                                        @endphp
                                        <div class="accordion">
                                            <div class="card shadow-lg" style="background-color: #e8dbaa">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col">
                                                            <button class="btn-secondary btn-block btn-sm text-left" type="button" data-toggle="collapse" data-target="#subcat{{$cat2lvl->id}}">
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <b>{{$cat2lvl->name}}</b>
                                                                    </div>
                                                                    <div class="col-4 text-right">
                                                                        @if ($childLvl2Count > 0)
                                                                            <span class="badge badge-warning badge-pill">Подкатегорий {{$childLvl2Count}}</span>
                                                                        @endif
                                                                        @if ($goodsLvl2Count > 0)
                                                                            <span class="badge badge-success badge-pill">Товаров {{$goodsLvl2Count}}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <button class="btn-secondary btn-block btn-sm" type="button" data-toggle="collapse" data-target="#cat{{$cat2lvl->id}}"> >>> </button>
                                                </div>
                                                @if ($childLvl2Count > 0)
                                                    <div id="subcat{{$cat2lvl->id}}" class="collapse">
                                                        <div class="card-body">
                                                            @foreach ($cat2lvl->childrens()->get() as $cat3lvl)
                                                                @php
                                                                    $goodsLvl3Count = $cat3lvl->goods()->count();
                                                                @endphp
                                                                <div class="accordion">
                                                                    <div class="card shadow-lg" style="background-color: #e8dbaa">
                                                                        <div class="card-header">
                                                                            <button class="btn-secondary btn-block btn-sm text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat3lvl->id}}">
                                                                                <div class="row">
                                                                                    <div class="col-8">
                                                                                        <b>{{$cat3lvl->name}}</b>
                                                                                    </div>
                                                                                    <div class="col-4 text-right">
                                                                                        @if ($goodsLvl3Count > 0)
                                                                                            <span class="badge badge-success badge-pill">Товаров {{$goodsLvl3Count}}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-9 text-left" style="max-height: 85vh !important; overflow-y: scroll !important;">
            @foreach (App\Models\Category::all() as $cat)
                <div id="cat{{$cat->id}}" class="card collapse">
                    <div class="card shadow-lg">
                        <ul class="list-group list-group-flush shadow">
                            <li class="list-group-item">
                                <h5 class="card-title font-weight-bold">Имя категории</h5>
                                <p class="card-text">{{$cat->name}}</p>
                            </li>
                            <li class="list-group-item">
                                <h5 class="card-title font-weight-bold">Время создания категории</h5>
                                <p class="card-text font-italic">{{$cat->created_at->format('d.m.Y')}}</p>
                            </li>
                            <li class="list-group-item">
                                <h5 class="card-title font-weight-bold">Описание категории</h5>
                                <p class="card-text font-italic">{{$cat->description}}</p>
                            </li>
                            <li class="list-group-item">
                                <h5 class="card-title font-weight-bold">Товары категории</h5>
                                @if ($cat->goods()->count() > 0)
                                    @foreach ($cat->goods()->get() as $goods)
                                        <div><a href="#">{{$goods->name}}</a></div>
                                    @endforeach
                                @else
                                    <p class="card-text">Данной категории не принадлежит товаров</p>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
