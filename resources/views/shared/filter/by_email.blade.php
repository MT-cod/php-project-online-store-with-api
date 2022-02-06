<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="email"><b>по e-mail</b></label>
    @if (isset($_REQUEST['filter']['email']) && ($_REQUEST['filter']['email'] !== ''))
        <input type="text" class="form-control" id="email" name="filter[email]" value="{{$_REQUEST['filter']['email']}}" style="background-color: rgba(255,255,255,0.3);">
    @else
        <input type="text" class="form-control" id="email" name="filter[email]" style="background-color: rgba(255,255,255,0.3);">
    @endif
</div>
