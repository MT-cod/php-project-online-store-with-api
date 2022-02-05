<div class="modal fade" id="modal-categ-create" tabindex="-1" role="dialog" aria-hidden="true" style="max-height:100vh !important; overflow-y:scroll !important;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="modal-categ-create-form" method="POST" action="{{route('categories.store')}}">
                @csrf
                <div class="modal-header shadow" style="background-color: #fff89f">
                    <h4 class="modal_categ_create_title"><b></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #fff8ae">
                    <span class="modal_categ_create_results"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #fff9b9">
                            <h6><b><label for="modal_categ_create_name">Имя категории</label></b></h6>
                            <input class="form-control modal_categ_create_name" id="modal_categ_create_name" type="text" name="name">
                        </li>
                        <li class="list-group-item" style="background-color: #fff9b9">
                            <h6><b><label for="modal_categ_create_description">Описание категории</label></b></h6>
                            <textarea class="form-control modal_categ_create_description" id="modal_categ_create_description" rows="2" name="description"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #fff9b9">
                            <h6><b><label for="modal_create_categ_parent_category">Категория будет является дочерней для:</label></b></h6>
                            <span class="modal_create_categ_parent_category" id="modal_create_categ_parent_category"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #fff89f">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-outline-primary btn-modal_categ_try_store">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
