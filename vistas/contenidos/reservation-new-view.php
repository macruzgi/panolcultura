<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO REMITO
    </h3>
    <!--p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium quod harum vitae, fugit quo soluta. Molestias officiis voluptatum delectus doloribus at tempore, iste optio quam recusandae numquam non inventore dolor.
    </p-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO REMITO</a>
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
	<div class="container-fluid form-neon">
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR RESPONSABLE O ITEMS</p>
            <p class="text-center">
                <?php if (empty($_SESSION['datos_cliente'])) { ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCliente"><i class="fas fa-user-plus"></i> &nbsp; Agregar responsable</button>
                <?php } ?>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalItem"><i class="fas fa-box-open"></i> &nbsp; Agregar item</button>
            </p>
            <div>
                <span class="roboto-medium">RESPONSABLE:</span> 
                <?php if (empty($_SESSION['datos_cliente'])) { ?>
                <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un responsable</span>
                <?php } else { ?>
      			<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamoAjax.php" method="POST" data-form="loans" style="display: inline-block !important;">
                    <input type="hidden" name="id_eliminar_cliente" value="<?php echo $_SESSION['datos_cliente']['ID']; ?> ">
                	<?php echo $_SESSION['datos_cliente']['Nombre']." ".$_SESSION['datos_cliente']['Apellido']." (".$_SESSION['datos_cliente']['DNI'].")";?>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                </form>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>ID</th>
                            <th>CODIGO</th>
                            <th>ITEM</th>
                            <th>CANTIDAD</th>
                            <th>DETALLE</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            if (isset($_SESSION['datos_item']) && count($_SESSION['datos_item'])>=1) {

                                $_SESSION['prestamo_total']=0;
                                $_SESSION['prestamo_item']=0;

                                foreach ($_SESSION['datos_item'] as $items) {
                                    $subtotal=$items['Cantidad']*($items['Costo']*$items['Tiempo']);
                                    $subtotal=number_format($subtotal,2,'.','');
                        ?>
                        <tr class="text-center" >
                            <td><?php echo $items['ID']; ?></td>
                            <td><?php echo $items['Codigo']; ?></td>
                            <td><?php echo $items['Nombre']; ?></td>
                            <td><?php echo $items['Cantidad']; ?></td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="<?php echo $items['Nombre']; ?>" data-content="<?php echo $items['Detalle']; ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamoAjax.php" method="POST" data-form="loans" autocomplete="off">
                                <input type="hidden" name="id_eliminar_item" value="<?php echo $items['ID']; ?>">  
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php 
                                $_SESSION['prestamo_total']+=$subtotal;
                                $_SESSION['prestamo_item']+=$items['Cantidad'];
                               } 
                        ?>
                        <tr class="text-center bg-light">
                            <td><strong>TOTAL</strong></td>
                            <td><strong><?php echo $_SESSION['prestamo_item'];?> Items</strong></td>
                            <td colspan="2"></td>
                            <td><strong><?php echo ""; ?></strong></td>
                            <td colspan="2"></td>
                        </tr>
                        <?php  
                            } else {
                                $_SESSION['prestamo_total']=0;
                                $_SESSION['prestamo_item']=0;
                        ?>
                        <tr class="text-center" >
                            <td colspan="7">No has Seleccionado Items</td>
                        </tr>
                        <?php 
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
		<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamoAjax.php" method="POST" data-form="save" autocomplete="off">
            <fieldset>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                        <legend><i class="far fa-clock"></i> &nbsp; Fecha del remito</legend>
                            <div class="form-group">
                                <label for="prestamo_fecha_inicio">Fecha de remito</label>
                                <input type="date" class="form-control" name="prestamo_fecha_inicio_reg" value="<?php echo date("Y-m-d"); ?>" id="prestamo_fecha_inicio">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                        <legend><i class="fas fa-history"></i> &nbsp; Fecha de entrega</legend>
                            <div class="form-group">
                                <label for="prestamo_fecha_final">Fecha de entrega</label>
                                <input type="date" class="form-control" name="prestamo_fecha_final_reg" value="<?php echo date("Y-m-d", strtotime("+1 day")); ?>" id="prestamo_fecha_final">
                            </div>
                        </div>
                        

                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="prestamo_hora_inicio">Hora de remito</label>
                                <input type="time" class="form-control" name="prestamo_hora_inicio_reg" value="<?php echo date("h:i"); ?>" id="prestamo_hora_inicio">
                            </div>
                        </div>
                        <div class="col-12 col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="prestamo_hora_final">Hora de entrega</label>
                                <input type="time" class="form-control" name="prestamo_hora_final_reg" value="<?php echo date("h:i"); ?>" id="prestamo_hora_final">
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
                                <label for="prestamo_estado" class="bmd-label-floating">Estado</label>
                                <select class="form-control" name="prestamo_estado_reg" id="prestamo_estado">
                                    <!--option value="" selected="">Seleccione una opción</option-->
                                    <!--option value="Reservacion">Reservación</option-->
                                    <option value="Prestamo">Remito</option>
                                    <!--option value="Finalizado">Finalizado</option-->
                                </select>
                            </div>
                        </div>
						<div class="col-12 col-md-4" style="display: none;">
							<div class="form-group">
								<label for="prestamo_total" class="bmd-label-floating">Dias en total <?php echo MONEDA; ?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" readonly="" value="<?php echo number_format($_SESSION['prestamo_total'],2,'.',''); ?>" id="prestamo_total" maxlength="10">
							</div>
						</div>
                        <div class="col-12 col-md-4" style="display: none;">
                            <div class="form-group">
                                <label for="prestamo_pagado" class="bmd-label-floating">Dias concluidos  <?php echo MONEDA; ?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" name="prestamo_pagado_reg" id="prestamo_pagado" maxlength="10" value="0" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="prestamo_observacion" class="bmd-label-floating">Observación</label>
                                <input type="text" pattern=".*" class="form-control" name="prestamo_observacion_reg" id="prestamo_observacion" maxlength="400">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="prestamo_destino" class="bmd-label-floating">Destino</label>
                                <input type="text" pattern=".*" class="form-control" name="prestamo_destino_reg" id="prestamo_destino" maxlength="400">
                            </div>
                        </div>
					</div>
				</div>
			</fieldset>
			<br><br><br>
			<p class="text-center" style="margin-top: 40px;">
				<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
				&nbsp; &nbsp;
				<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
			</p>
		</form>
	</div>
</div>


<!-- MODAL CLIENTE -->
<div class="modal fade" id="ModalCliente" tabindex="-1" role="dialog" aria-labelledby="ModalCliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCliente">Agregar Responsable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_cliente" class="bmd-label-floating">DNI, Nombre, Apellido, Telefono</label>
                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_cliente" id="input_cliente" maxlength="30" onkeypress="buscarConEnter(event)">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_clientes"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="buscar_cliente()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function buscarConEnter(event) {
        if (event.key === 'Enter') {
            buscar_cliente();
        }
    }
</script>



<!-- MODAL ITEM -->
<div class="modal fade" id="ModalItem" tabindex="-1" role="dialog" aria-labelledby="ModalItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalItem">Agregar item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_item" class="bmd-label-floating">Código, Nombre o Patrimonio</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_item" id="input_item" maxlength="30" onkeypress="buscarEnEnter(event)">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_items"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="buscar_item()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function buscarEnEnter(event) {
        if (event.key === 'Enter') {
            buscar_item();
        }
    }
</script>



<!-- MODAL AGREGAR ITEM -->
<div class="modal fade" id="ModalAgregarItem" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamoAjax.php" method="POST" data-form="default" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarItem">AGREGAR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_agregar_item" id="id_agregar_item">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="detalle_formato" class="bmd-label-floating">Formato de Remito</label>
                                <select class="form-control" name="detalle_formato" id="detalle_formato">
                                    <option value="Dias">Días</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display: none;">
                            <div class="form-group">
                                <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de items</label>
                                <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_cantidad" id="detalle_cantidad" maxlength="7" required="" value="1" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="detalle_tiempo" class="bmd-label-floating">Cantidad de dias</label>
                                <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_tiempo" id="detalle_tiempo" maxlength="7" required="" value="1" >
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display: none;">
                            <div class="form-group">
                                <label for="detalle_costo_tiempo" class="bmd-label-floating">Unidad de tiempo</label>
                                <input type="text" pattern="[0-9.]{1,15}" class="form-control" name="detalle_costo_tiempo" id="detalle_costo_tiempo" maxlength="15" required="" value="1" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" onclick="modal_buscar_item()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 

"./vistas/inc/reservation.php"; ?>