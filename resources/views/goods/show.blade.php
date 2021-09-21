<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token" />

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body style="background-color: #fdebb9">
    <ul class="list-group list-group-flush">
        <li class="list-group-item" style="background-color: #e6fff4">
            <div class="row">
                <div class="col">
                    <h6><b>Имя товара</b></h6>
                    <p>{{$item->name}}</p>
                </div>
                <div class="col">
                    <h6><b>slug товара</b></h6>
                    <p>{{$item->slug}}</p>
                </div>
            </div>
        </li>
        <li class="list-group-item" style="background-color: #e6fff4">
            <h6><b>Описание</b></h6>
            <p>{{$item->description}}</p>
        </li>
        <li class="list-group-item" style="background-color: #e6fff4">
            <h6><b>Цена товара</b></h6>
            <p>{{$item->price}}</p>
        </li>
        <li class="list-group-item" style="background-color: #e6fff4">
            <div class="row">
                <div class="col">
                    <h6><b>Время создания товара</b></h6>
                    <p>{{$item->created_at->format('d.m.Y H:i:s')}}</p>
                </div>
                <div class="col">
                    <h6><b>Время последнего изменения товара</b></h6>
                    <p>{{$item->updated_at->format('d.m.Y H:i:s')}}</p>
                </div>
            </div>
        </li>
        <li class="list-group-item" style="background-color: #e6fff4">
            <div class="row">
                <div class="col">
                    <h6><b>Дополнительные характеристики товара</b></h6>
                    @foreach($item->additionalChars()->get() as $char)
                        <p><u>{{$char->name}}</u> ({{$char->value}})</p>
                    @endforeach
                </div>
                <div class="col">
                    <h6><b>Категория товара</b></h6>
                    <p>{{$item->category()->first()->name}}</p>
                </div>
            </div>
        </li>
    </ul>
</body>
{{--<div class="container-fluid">--}}
