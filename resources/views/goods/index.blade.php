@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
    <div class="col-4" style="max-height: 90vh !important; overflow-y: scroll !important;">
    <nav class="nav flex-sm-column">
        @foreach ($goods as $val)
            <a class="nav-link" href="#">{{$val['name']}}</a>
        @endforeach
    </nav>
    </div>
    <div class="col-8">
        ergfegegegegregegeg
    </div>
    </div>
</div>

@endsection
