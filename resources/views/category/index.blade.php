@extends('layouts.app')

@section('content')

<div class="container-11">
    <div class="row text-center shadow-lg">
        <div class="col-4"><h2>Категории</h2></div>
        <div class="col-8"><h2>Свойства</h2></div>
    </div>
    <div class="row">
        <div class="col-3">
            <nav class="nav flex-sm-column">
            @foreach ($topCategories as $cat)
                @php
                    $childCount = $cat->childrens()->count();
                    $goodsCount = $cat->goods()->count();
                @endphp
                <div class="accordion">
                    <div class="card shadow-lg" style="background-color: #e8dbaa">
                        <div class="card-header">
                            <button class="nav-link btn btn-light btn-block btn-sm text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat->id}}">
                                <b>{{$cat->name}}</b>
                                @if ($childCount > 0)
                                    <span class="badge badge-warning badge-pill">Подкатегорий {{$childCount}}</span>
                                @endif
                                @if ($goodsCount > 0)
                                    <span class="badge badge-success badge-pill">Товаров {{$goodsCount}}</span>
                                @endif
                            </button>
                        </div>
                        @if ($childCount > 0)
                            <div id="cat{{$cat->id}}" class="collapse">
                                <div class="card-body">
                                    @foreach ($cat->childrens()->get() as $cat2lvl)
                                        @php
                                            $childLvl2Count = $cat2lvl->childrens()->count();
                                            $goodsLvl2Count = $cat2lvl->goods()->count();
                                        @endphp
                                        <div class="accordion">
                                            <div class="card shadow-lg" style="background-color: #e8dbaa">
                                                <div class="card-header">
                                                    <button class=" btn btn-light btn-block btn-sm text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat2lvl->id}}">
                                                        <b>{{$cat2lvl->name}}</b>
                                                        @if ($childLvl2Count > 0)
                                                            <span class="badge badge-warning badge-pill">Подкатегорий {{$childLvl2Count}}</span>
                                                        @endif
                                                        @if ($goodsLvl2Count > 0)
                                                            <span class="badge badge-success badge-pill">Товаров {{$goodsLvl2Count}}</span>
                                                        @endif
                                                    </button>
                                                </div>
                                                @if ($childLvl2Count > 0)
                                                    <div id="cat{{$cat2lvl->id}}" class="collapse">
                                                        <div class="card-body">
                                                            @foreach ($cat2lvl->childrens()->get() as $cat3lvl)
                                                                @php
                                                                    $goodsLvl3Count = $cat3lvl->goods()->count();
                                                                @endphp
                                                                <div class="accordion">
                                                                    <div class="card shadow-lg" style="background-color: #e8dbaa">
                                                                        <div class="card-header">
                                                                            <button class=" btn btn-light btn-block btn-sm text-left" type="button" data-toggle="collapse" data-target="#cat{{$cat3lvl->id}}">
                                                                                <b>{{$cat3lvl->name}}</b>
                                                                                @if ($goodsLvl3Count > 0)
                                                                                    <span class="badge badge-success badge-pill">Товаров {{$goodsLvl3Count}}</span>
                                                                                @endif
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
            </nav>
        </div>
        <div class="col-8 text-left">
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
