<?php
    if ($_SESSION['privilegio_spm']<1 || $_SESSION['privilegio_spm']>2) {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR REMITO
    </h3>
    <!--p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium quod harum vitae, fugit quo soluta. Molestias officiis voluptatum delectus doloribus at tempore, iste optio quam recusandae numquam non inventore dolor.
    </p-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO REMITO</a>
        </li>
        <!--li>
            <a href="<?php echo SERVERURL; ?>reservation-reservation/"><i class="far fa-calendar-alt"></i> &nbsp; RESERVACIONES</a>
        </li-->
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-pending/"><i class="fa fa-sticky-note"></i> &nbsp; REMITOS</a>
        </li>
        <!--li>
            <a href="<?php echo SERVERURL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; FINALIZADOS</a>
        </li-->
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/prestamoControlador.php";

        $ins_prestamo= new prestamoControlador();

        $datos_prestamo=$ins_prestamo->datos_prestamo_controlador("Unico",$pagina[1]);

        if ($datos_prestamo->rowCount()==1) {

            $campos=$datos_prestamo->fetch();

            if ($campos['prestamo_estado']=="Finalizado" && $campos['prestamo_pagado']==$campos['prestamo_total']) {
    ?>
            <div class="alert alert-danger text-center" role="alert">
                <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
                <p class="mb-0">Lo sentimos, no podemos Actualizar el Remito debido a que ya se Encuentra Devuelto.</p>
            </div>
    <?php } else { ?>
	<div class="container-fluid form-neon">

        <?php if ($campos['prestamo_pagado']!=$campos['prestamo_total']) {?>
            <div class="container-fluid">
                <p class="text-center roboto-medium">CAMBIAR ESTADO PARA ESTE REMITO</p>
                <p class="text-center">Este remito se encuentra pendiente, para marcar como devuelto hacer click en el campo CAMBIAR ESTADO</p>
                <p class="text-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalPago"><i class=""></i> &nbsp; Cambiar Estado</button>
                </p>
            </div>
        <?php } ?>

        <div class="container-fluid">
            <?php 
                require_once "./controladores/clienteControlador.php";

                $ins_cliente= new clienteControlador();

                $datos_cliente=$ins_cliente->datos_cliente_controlador("Unico",$lc->encryption($campos['cliente_id']));
                $datos_cliente=$datos_cliente->fetch();
            ?>
            <div>
                <span class="roboto-medium">RESPONSABLE:</span> 
                &nbsp; <?php echo $datos_cliente['cliente_nombre']." ".$datos_cliente['cliente_apellido']; ?>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>ITEM</th>
                            <th>CANTIDAD</th>
							<th>DEVOLVER</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $datos_detalle=$ins_prestamo->datos_prestamo_controlador("Detalle",$lc->encryption($campos['prestamo_codigo']));

                            $datos_detalle=$datos_detalle->fetchAll();

                            foreach ($datos_detalle as $items) {
                                $subtotal=$items['detalle_cantidad']*($items['detalle_costo_tiempo']*$items['detalle_tiempo']);
                                $subtotal=number_format($subtotal,2,'.','');
                        ?>
                        <tr class="text-center" >
                            <td><?php echo $items['detalle_descripcion']; ?></td>
                            <td><?php echo $items['detalle_cantidad']; ?></td>
							<td>  
								<?php  
									if($items['estado_item'] == 0){ 
								?>
										<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamoAjax.php" method="POST" data-form="loans" autocomplete="off"> 
										<input type="hidden" name="item_id_devo" value="<?php echo $lc->encryption($items['item_id']); ?>">
										<input type="hidden" name="prestamo_codigo_devo" value="<?php echo $lc->encryption($campos['prestamo_codigo']); ?>"> 
											<button type="submit" class="btn btn-warning">
												<i class="far fa-trash-alt"></i>
											</button>
										</form>
								<?php
									}
									else{
										?>
										<span class="badge badge-light">Devuelto</span>
									<?php 
									}
								?>
							</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
		<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamoAjax.php" method="POST" data-form="update" autocomplete="off">
            <input type="hidden" name="prestamo_codigo_up" value="<?php echo $lc->encryption($campos['prestamo_codigo']); ?>">
            <fieldset>
                <legend><i class="far fa-clock"></i> &nbsp; Fecha del remito</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="prestamo_fecha_inicio">Fecha de remito</label>
                                <input type="date" value="<?php echo $campos['prestamo_fecha_inicio']; ?>" class="form-control" readonly="" id="prestamo_fecha_inicio">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="prestamo_fecha_final">Fecha de entrega</label>
                                <input type="date" value="<?php echo $campos['prestamo_fecha_final']; ?>" class="form-control" readonly="" id="prestamo_fecha_final">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><i class="fas fa-cubes"></i> &nbsp; Otros datos</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="prestamo_estado" class="bmd-label-floating"> Estado </label>
                                <select class="form-control" name="prestamo_estado_up" id="prestamo_estado">
                                    <!--option value="Reservacion" <?php if ($campos['prestamo_estado']=="Reservacion") {
                                        echo 'selected=""';
                                    } ?> >Reservación <?php if ($campos['prestamo_estado']=="Reservacion") {
                                        echo '(Actual)'; } ?></option-->

                                    <option value="Prestamo" <?php if ($campos['prestamo_estado']=="Prestamo") {
                                        echo 'selected=""';
                                    } ?> >Remito <?php if ($campos['prestamo_estado']=="Prestamo") {
                                        echo '(Actual)'; } ?></option>

                                    <!--option value="Finalizado" <?php if ($campos['prestamo_estado']=="Finalizado") {
                                        echo 'selected=""';
                                    } ?>>Finalizado <?php if ($campos['prestamo_estado']=="Finalizado") {
                                        echo '(Actual)'; } ?></option-->
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display: none;">
                            <div class="form-group">
                                <label for="prestamo_total" class="bmd-label-floating">Dias en total <?php echo MONEDA; ?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" readonly="" value="<?php echo $campos['prestamo_total'] ?>" id="prestamo_total" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display: none;">
                            <div class="form-group">
                                <label for="prestamo_pagado" class="bmd-label-floating">Dias concluidos <?php echo MONEDA; ?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" readonly="" value="<?php echo $campos['prestamo_pagado'] ?>" id="prestamo_pagado" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label for="prestamo_observacion" class="bmd-label-floating"> Observación </label>
                                <input type="text" pattern=".*" class="form-control" name="prestamo_observacion_up" value="<?php echo $campos['prestamo_observacion'] ?>" id="prestamo_observacion" maxlength="400">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <br><br><br>
            <p class="text-center" style="margin-top: 40px;">
                <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
            </p>
        </form>
	</div>

    <!-- MODAL PAGOS -->
    <div class="modal fade" id="ModalPago" tabindex="-1" role="dialog" aria-labelledby="ModalPago" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamoAjax.php" method="POST" data-form="save" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalPago">Cambiar Estado a Devuelto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="display: none;" >
                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr class="text-center bg-dark">
                                    <th>FECHA</th>
                                    <th>DIGITO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $datos_pagos=$ins_prestamo->datos_prestamo_controlador("Pago",$lc->encryption($campos['prestamo_codigo']));

                                    if ($datos_pagos->rowCount()>0) {
                                        $datos_pagos=$datos_pagos->fetchAll();
                                        foreach($datos_pagos as $pagos) {
                                            echo '
                                            <tr class="text-center">
                                                <td>'.date("d-m-Y",strtotime($pagos['pago_fecha'])).'</td>
                                                <td>'.MONEDA.$pagos['pago_total'].'</td>
                                            </tr>';
                                        }
                                    } else {
                                 ?>
                                <tr class="text-center">
                                    <td colspan="2">No hay cambios Registrados</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <input type="hidden" name="pago_codigo_reg" value="<?php echo $lc->encryption($campos['prestamo_codigo']); ?>">
                        <div class="form-group" style="display: none;">
                            <label for="pago_monto_reg" class="bmd-label-floating">Para cambiar a devuelto digitar <strong><?php echo MONEDA.number_format(($campos['prestamo_total']-$campos['prestamo_pagado']),2,'.',','); ?></strong> <?php echo MONEDA; ?></label>
                            <input type="text" pattern="[0-9.]{1,10}" class="form-control" name="pago_monto_reg" id="pago_monto_reg" maxlength="10" required="" value="<?php echo MONEDA.number_format(($campos['prestamo_total']-$campos['prestamo_pagado']),2,'.',','); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-info btn-sm" >Cambiar estado</button> &nbsp;&nbsp; 
                    <button type="button" class="btn btn-raised btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <?php  
            }
        } else { 
    ?>
    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>
    <?php } ?>
</div>
