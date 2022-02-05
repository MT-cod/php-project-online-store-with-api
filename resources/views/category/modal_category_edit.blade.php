<div class="modal fade" id="modal-categ-edit" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form id="modal-categ-edit-form" method="POST" action="/categories/">
                @csrf
                @method('PATCH')
                <div class="modal-header shadow" style="background-color: #fff89f">
                    <h4 class="modal_categ_edit_title"><b></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #fff8ae">
                    <span class="modal_categ_edit_save_results"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #fff9b9">
                            <h6><b><label for="modal_categ_edit_name">Имя категории</label></b></h6>
                            <input class="form-control modal_categ_edit_name" id="modal_categ_edit_name" type="text" name="name">
                        </li>
                        <li class="list-group-item" style="background-color: #fff9b9">
                            <h6><b><label for="modal_categ_edit_description">Описание категории</label></b></h6>
                            <textarea class="form-control modal_categ_edit_description" id="modal_categ_edit_description" rows="2" name="description"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #fff9b9">
                            <div class="row">
                                <div class="col">
                                    <h6><b>Время создания категории</b></h6>
                                    <p><span class="modal_categ_edit_created_at"></span></p>
                                </div>
                                <div class="col">
                                    <h6><b>Время последнего изменения категории</b></h6>
                                    <p><span class="modal_categ_edit_updated_at"></span></p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item" style="background-color: #fff9b9">
                            <h6><b><label for="modal_categ_edit_parent_category">Категория является дочерней для:</label></b></h6>
                            <span class="modal_categ_edit_parent_category" id="modal_categ_edit_parent_category"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #fff89f">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <div class="btn-modal_categ_edit_save"></div>
                </div>
            </form>
        </div>
    </div>
</div>
