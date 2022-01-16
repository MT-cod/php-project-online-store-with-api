<div id="carouselExampleIndicators" class="col carousel slide text-center" data-ride="carousel" style="height: 91vh !important; vertical-align: middle !important;">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="d-block display-1">
                <div class="display-4 p-lg-5">Самый дорогой товар!</div>
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[0]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[0]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[0]['price'], 40)}}</div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="d-block display-1">
                <div class="display-4 p-lg-5">Самый дешевый товар!</div>
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[1]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[1]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[1]['price'], 40)}}</div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="d-block display-1">
                <div class="display-4 p-lg-5">Самый первый товар!</div>
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[2]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[2]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[2]['price'], 40)}}</div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="d-block display-1">
                <div class="display-4 p-lg-5">Самый свежий товар!</div>
                <div class="display-4 p-lg-5">{{Str::limit($carouselData[3]['name'], 40)}}</div>
                <div><h4><b>{{Str::limit($carouselData[3]['description'], 500)}}</b></h4></div>
                <div class="display-4 p-lg-5">Цена: {{Str::limit($carouselData[3]['price'], 40)}}</div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only" style="color: #0b2e13">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
