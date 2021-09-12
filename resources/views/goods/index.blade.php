@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row text-center shadow-lg">
        <div class="col-3"><h2>Категории</h2></div>
        <div class="col-9"><h2>Товары</h2></div>
    </div>
    <div class="row accordion">
        <div class="col-3" style="max-height:90vh !important; overflow-y:scroll !important;">
            <form class="center m-md-3 p-md-3" method="GET" action="/goods" accept-charset="UTF-8" class="form-inline">
                <select class="form-control" name="filter[category_id]">
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
                <div><input class="btn btn-outline-primary mr-2" type="submit" value="Поиск"></div>
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
