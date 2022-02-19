<div id="carouselExampleIndicators" class="col carousel slide text-center" data-ride="carousel" style="height: 91vh !important; vertical-align: middle !important;">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="d-block display-1 btn-modal_shop_goods_show"
                 style="cursor: pointer;background-color: rgba(0,0,0,0.05)"
                 data-id="{{$carouselData[0]['id']}}"
                 data-toggle="tooltip"
                 data-placement="bottom"
                 title="Нажать для подробностей/покупки">
                <div class="display-4 p-lg-5">Самый дорогой товар!</div>
                @if (isset($carouselData[0]['media'][0]['id']))
                    <img src="{{implode('/', [
                            str_contains($_SERVER['SERVER_PROTOCOL'], 'https') ? 'https://' : 'http://',
                            $_SERVER['HTTP_HOST'],
                            'storage',
                            $carouselData[0]['media'][0]['id'],
                            'conversions',
                            $carouselData[0]['media'][0]['name'] . '-normal.jpg'
                            ])}}" alt="[изображение]">
                @endif
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[0]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[0]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[0]['price'], 40)}}</div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="d-block display-1 btn-modal_shop_goods_show"
                 style="cursor: pointer;background-color: rgba(0,0,0,0.05)"
                 data-id="{{$carouselData[1]['id']}}"
                 data-toggle="tooltip"
                 data-placement="bottom"
                 title="Нажать для подробностей/покупки">
                <div class="display-4 p-lg-5">Самый дешевый товар!</div>
                @if (isset($carouselData[1]['media'][0]['id']))
                    <img src="{{implode('/', [
                            str_contains($_SERVER['SERVER_PROTOCOL'], 'https') ? 'https://' : 'http://',
                            $_SERVER['HTTP_HOST'],
                            'storage',
                            $carouselData[1]['media'][0]['id'],
                            'conversions',
                            $carouselData[1]['media'][0]['name'] . '-normal.jpg'
                            ])}}" alt="[изображение]">
                @endif
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[1]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[1]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[1]['price'], 40)}}</div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="d-block display-1 btn-modal_shop_goods_show"
                 style="cursor: pointer;background-color: rgba(0,0,0,0.05)"
                 data-id="{{$carouselData[2]['id']}}"
                 data-toggle="tooltip"
                 data-placement="bottom"
                 title="Нажать для подробностей/покупки">
                <div class="display-4 p-lg-5">Самый первый товар!</div>
                @if (isset($carouselData[2]['media'][0]['id']))
                    <img src="{{implode('/', [
                            str_contains($_SERVER['SERVER_PROTOCOL'], 'https') ? 'https://' : 'http://',
                            $_SERVER['HTTP_HOST'],
                            'storage',
                            $carouselData[2]['media'][0]['id'],
                            'conversions',
                            $carouselData[2]['media'][0]['name'] . '-normal.jpg'
                            ])}}" alt="[изображение]">
                @endif
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[2]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[2]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[2]['price'], 40)}}</div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="d-block display-1 btn-modal_shop_goods_show"
                 style="cursor: pointer;background-color: rgba(0,0,0,0.05)"
                 data-id="{{$carouselData[3]['id']}}"
                 data-toggle="tooltip"
                 data-placement="bottom"
                 title="Нажать для подробностей/покупки">
                <div class="display-4 p-lg-5">Самый свежий товар!</div>
                @if (isset($carouselData[3]['media'][0]['id']))
                    <img src="{{implode('/', [
                            str_contains($_SERVER['SERVER_PROTOCOL'], 'https') ? 'https://' : 'http://',
                            $_SERVER['HTTP_HOST'],
                            'storage',
                            $carouselData[3]['media'][0]['id'],
                            'conversions',
                            $carouselData[3]['media'][0]['name'] . '-normal.jpg'
                            ])}}" alt="[изображение]">
                @endif
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[3]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[3]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[3]['price'], 40)}}</div>
            </div>
        </div>
    </div>
    <br>
    <a class="btn btn-dark" href="/?perpage=20&sort%5Bname%5D=&filter%5Bname%5D=">ВСЕ ТОВАРЫ</a>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only" style="color: #0b2e13">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
