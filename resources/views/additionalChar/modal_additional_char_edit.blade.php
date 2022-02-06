<div class="modal fade" id="modal-additChar-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form id="modal-additChar-edit-form" method="POST" action="/additionalChars">
                @csrf
                @method('PATCH')
                <div class="modal-header shadow" style="background-color: #dbfeff">
                    <h4 class="modal_additChar_edit_title"><b></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #dbfeff">
                    <span class="modal_additChar_edit_save_results"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_additChar_edit_name">Имя характеристики</label></b></h6>
                            <input class="form-control" id="modal_additChar_edit_name" type="text" name="name">
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_additChar_edit_value">Значение характеристики</label></b></h6>
                            <textarea class="form-control" id="modal_additChar_edit_value" rows="2" name="value"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <div class="row">
                                <div class="col">
                                    <h6><b>Время создания характеристики</b></h6>
                                    <p><span class="modal_additChar_edit_created_at"></span></p>
                                </div>
                                <div class="col">
                                    <h6><b>Время последнего изменения характеристики</b></h6>
                                    <p><span class="modal_additChar_edit_updated_at"></span></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #dbfeff">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <div class="btn-modal_additChar_edit_save"></div>
                </div>
            </form>
        </div>
    </div>
</div>
