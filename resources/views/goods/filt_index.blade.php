@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row text-center shadow-lg">
        <div class="col-3"><h2>Категории</h2></div>
        <div class="col-9"><h2>Товары</h2></div>
    </div>
    <div class="row">
        <div class="col-3 text-center" style="max-height:90vh !important; overflow-y:scroll !important;">
            <form class="center m-md-3 p-md-3" method="GET" action="/goods" accept-charset="UTF-8">
                <div class="form-group border m-md-2 p-md-2 shadow-lg">
                    <label for="category">по категории</label>
                    <select class="form-control @error('filter.category_id') is-invalid @enderror" name="filter[category_id]" id="category">
                        <option selected="selected" value="">-</option>
                        @foreach ($categories as $cat)
                            @if (
                                isset($_REQUEST['filter']['category_id']) &&
                                ($_REQUEST['filter']['category_id'] !== '') &&
                                ($cat['id'] == $_REQUEST['filter']['category_id'])
                                )
                                <option selected="selected" value={{$cat['id']}}>{{$cat['name']}}</option>
                            @else
                                <option value={{$cat['id']}}>{{$cat['name']}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group border m-md-2 p-md-2 shadow-lg">
                    <label for="name">по имени</label>
                    @if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== ''))
                        <input type="text" class="form-control" id="name" name="filter[name]" value="{{$_REQUEST['filter']['name']}}">
                    @else
                        <input type="text" class="form-control" id="name" name="filter[name]">
                    @endif
                </div>

                <div class="form-group border m-md-2 p-md-2 shadow-lg">
                    <div class="col"  style="max-height: 50vh !important; overflow-y: scroll !important;">
                    <label for="additChars">по дополнительным характеристикам</label>
                    <table class="table table-striped table-sm table-warning table-bordered">
                        <tbody>
                        @foreach($additCharacteristics as $char)
                            <tr>
                                <td>
                                    <div class="form-control @error('filter.additChars') is-invalid @enderror">
                                    <div class="row form-check">
                                        <label class="col-11 form-check-label" for="additChars">{{$char['name']}}</label>
                                        @if (
                                            isset($_REQUEST['filter']['additChars']) &&
                                            ($_REQUEST['filter']['additChars'] !== '') &&
                                            in_array($char['id'], $_REQUEST['filter']['additChars'])
                                            )
                                            <input class="col-1 right form-check-input" type="checkbox" name="filter[additChars][]" value="{{$char['id']}}" id="additChars" checked>
                                        @else
                                            <input class="col-1 right form-check-input" type="checkbox" name="filter[additChars][]" value="{{$char['id']}}" id="additChars">
                                        @endif
                                    </div>
                                    </div>
                                <td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>

                <div class="btn-block m-md-2 p-md-2 shadow-lg">
                    <a class="btn btn-outline-primary" href="/goods" role="button">Сброс фильтра</a>
                    <input class="btn btn-outline-primary" type="submit" value="Применить">
                </div>
            </form>
        </div>
        {{--Товары--}}
        <div class="col-9 text-left" style="max-height: 90vh !important; overflow-y: scroll !important;">
            <table class="table table-success table-striped table-sm mx-auto">
                <thead>
                    <tr>
                        <th scope="col">Наименование</th>
                        <th scope="col">Описание</th>
                        <th scope="col">Цена</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goods as $item)
                        <tr>
                            <td><a href="#">{{Str::limit($item['name'], 40)}}</a></td>
                            <td>{{Str::limit($item['description'], 120)}}</td>
                            <td>{{$item['price']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
