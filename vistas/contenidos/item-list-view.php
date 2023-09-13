<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS
    </h3>
    <!--p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum delectus eos enim numquam fugit optio accusantium, aperiam eius facere architecto facilis quibusdam asperiores veniam omnis saepe est et, quod obcaecati.
    </p-->
</div>
<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i>
                &nbsp; LISTA DE ITEMS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
    require_once "./controladores/itemControlador.php";
    $ins_item = new itemControlador();

    echo $ins_item->paginador_item_controlador($pagina[1], 15, $_SESSION['privilegio_spm'], $pagina[0], "");
    ?>
</div>

<!-- MODAL ITEM -->
<div class="modal fade" id="ModalRemitos" tabindex="-1" role="dialog" aria-labelledby="ModalRemitos" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remitos del Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-dark table-lg">
                        <thead>
                        <tr class="text-center roboto-medium">
                            <th>ID</th>
                            <th>CLIENTE</th>
                            <th>FECHA REMITO</th>
                            <th>FECHA ENTREGA</th>
                            <th>ESTADO</th>
                            <th>REMITO</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr><td colspan="7" class="text-center text-muted">No se encontraron registros.</td></tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    $("#tableItems tbody").on("click", ".btn_remitos", function () {

        let id = Number($(this).attr("data-id"));
        let nombre = $.trim($(this).attr("data-nombre"));


        $('#ModalRemitos').modal('show');
        $('#ModalRemitos .modal-title').html("<b>Remitos de " + nombre + "</b>");

        $("#ModalRemitos table tbody").html('<tr><td colspan="7" class="text-center text-muted">No se encontraron registros.</td></tr>');

        let params = new FormData();
        params.append("id", id);
        params.append("get_remitos_item", true);

        fetch("<?php echo SERVERURL; ?>ajax/itemAjax.php", {
            method: 'POST',
            body: params
        })
            .then(respuesta => respuesta.text())
            .then(respuesta => {
                if(respuesta.length) {
                    $("#ModalRemitos table tbody").html(respuesta);
                }

            });
    });

</script>