<div class="modal fade" id="modal-additChar-create" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="modal-additChar-create-form" method="POST" action="{{route('additionalChars.store')}}">
                @csrf
                <div class="modal-header shadow" style="background-color: #dbfeff">
                    <h6><b>Создание новой дополнительной характеристики</b></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #dbfeff">
                    <span class="modal_additChar_create_results"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_additChar_create_name">Имя характеристики</label></b></h6>
                            <input class="form-control" id="modal_additChar_create_name" type="text" name="name">
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_additChar_create_value">Значение характеристики</label></b></h6>
                            <textarea class="form-control" id="modal_additChar_create_value" rows="2" name="value"></textarea>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #dbfeff">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
