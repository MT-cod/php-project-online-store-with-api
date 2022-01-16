@if (isset($_REQUEST['chars_expand']))
    <input type="hidden" name="chars_expand" value="{{ $_REQUEST['chars_expand'] }}">
@else
    <input type="hidden" name="chars_expand" value="0">
@endif

@if (isset($_REQUEST['chars_expand']) && ($_REQUEST['chars_expand'] == 1))
    <div class="p-sm-1 collapse chars">
@else
    <div class="p-sm-1 collapse chars show">
@endif
        <button type="button" class="btn btn-light btn-block btn-sm" id="chars_btn_expand" data-toggle="collapse" data-target=".chars" onclick="$('input[name=chars_expand]').val(1)"><b>Дополнительные характеристики ▼</b></button>
    </div>
@if (isset($_REQUEST['chars_expand']) && ($_REQUEST['chars_expand'] == 1))
    <div class="p-sm-1 collapse chars show">
@else
    <div class="p-sm-1 collapse chars">
@endif
        <button type="button" class="btn btn-light btn-block btn-sm" id="chars_btn_collapse" data-toggle="collapse" data-target=".chars" onclick="$('input[name=chars_expand]').val(0)"><b>Дополнительные характеристики ▲</b></button>
    </div>

    @if (isset($_REQUEST['chars_expand']) && ($_REQUEST['chars_expand'] == 1))
        <div class="form-group shadow-lg collapse chars show" style="background-color: rgba(0,0,0,0.15);">
    @else
        <div class="form-group shadow-lg collapse chars" style="background-color: rgba(0,0,0,0.15);">
    @endif
            <div class="col-sm p-sm-1" style="max-height: 60vh !important; overflow-y: auto;">
                @foreach($additCharacteristics as $char)
                    <div class="custom-control custom-checkbox" style="border: 1px groove white; background-color: rgba(255,255,255,0.1);">
                        @if (
                            isset($_REQUEST['filter']['additChars']) &&
                            ($_REQUEST['filter']['additChars'] !== '') &&
                            in_array($char['id'], $_REQUEST['filter']['additChars'])
                            )
                            <input type="checkbox" class="col-1 custom-control-input" name="filter[additChars][]" id="additChars-{{$char['id']}}" value="{{$char['id']}}" checked>
                        @else
                            <input type="checkbox" class="col-1 custom-control-input" name="filter[additChars][]" id="additChars-{{$char['id']}}" value="{{$char['id']}}">
                        @endif
                        <label class="col-11 custom-control-label p-1 m-1 text-left text-break"
                               for="additChars-{{$char['id']}}"
                               data-toggle="tooltip"
                               data-placement="bottom"
                               title="Значение характеристики {{$char['name']}}:&#13; {{$char['value']}}"
                               style="font-size: .7rem;">
                            <strong>{{$char['name']}}</strong>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
