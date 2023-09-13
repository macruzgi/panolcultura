<?php

if ($peticionAjax) {
    require_once "../modelos/itemModelo.php";
} else {
    require_once "./modelos/itemModelo.php";
}

class itemControlador extends itemModelo
{

    /*--------- Controlador Agregar Item ---------*/
    public function agregar_item_controlador()
    {
        $codigo = mainModel::limpiar_cadena($_POST['item_codigo_reg']);
        $nombre = mainModel::limpiar_cadena($_POST['item_nombre_reg']);
        $stock = mainModel::limpiar_cadena($_POST['item_stock_reg']);
        $estado = mainModel::limpiar_cadena($_POST['item_estado_reg']);
        $detalle = mainModel::limpiar_cadena($_POST['item_detalle_reg']);
        $patrimonio = mainModel::limpiar_cadena($_POST['item_patrimonio_reg']);

        /*== comprobar campos vacios ==*/
        if ($codigo == "" || $nombre == "" || $stock == "" || $estado == "" || $patrimonio == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No has LLenado Todos los Campos que son Obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Verificando integridad de los datos ==*/
        if (mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}", $codigo)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Codigo no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos(".*", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Nombre no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9]{1,9}", $stock)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Stock no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($detalle != "") {
            if (mainModel::verificar_datos(".*", $detalle)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "El Detalle no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}", $patrimonio)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Patrimonio no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($estado != "Habilitado" && $estado != "Deshabilitado") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Estado del Item no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Comprobar El Codigo Item ==*/
        $check_codigo = mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo='$codigo'");
        if ($check_codigo->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Código que ha Ingresado ya Existe en el Sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Comprobar El Patrimonio Item ==*/
        $check_patrimonio = mainModel::ejecutar_consulta_simple("SELECT item_patrimonio FROM item WHERE item_patrimonio='$patrimonio'");
        if ($check_patrimonio->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Patrimonio que ha Ingresado ya Existe en el Sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $datos_item_reg = [
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Stock" => $stock,
            "Estado" => $estado,
            "Detalle" => $detalle,
            "Patrimonio" => $patrimonio,
        ];

        $agregar_item = itemModelo::agregar_item_modelo($datos_item_reg);

        if ($agregar_item->rowCount() == 1) {
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Item Registrado",
                "Texto" => "Los Datos del Item han Sido Registrados con Éxito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Registrar el Item, por Favor Intenta Nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin Controlador */


    /*--------- Controlador para Paginar Items ---------*/
    public function paginador_item_controlador($pagina, $registros, $privilegio, $url, $busqueda)
    {

        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);

        $url = mainModel::limpiar_cadena($url);
        $url = SERVERURL . $url . "/";

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        if (isset($busqueda) && $busqueda != "") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE item_codigo LIKE '%$busqueda%' OR item_nombre LIKE '%$busqueda%' OR item_patrimonio LIKE '%$busqueda%'ORDER BY item_id DESC LIMIT $inicio,$registros";
        } else {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item ORDER BY item_id DESC LIMIT $inicio,$registros";
        }

        $conexion = mainModel::conectar();

        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int)$total->fetchColumn();

        $Npaginas = ceil($total / $registros);

        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<p class="text-right">' . $total . ' items encontrados</p>';

            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        $tabla .= '<div class="table-responsive">
				<table class="table table-dark table-sm" id="tableItems">
					<thead>
						<tr class="text-center roboto-medium">
							<th>ID</th>
							<th>PATRIMONIO</th>
							<th>CODIGO</th>
							<th>NOMBRE</th>
							<th>STOCK</th>
							<th>ESTADO</th>
							<th>DETALLE</th>';
        if ($privilegio == 1 || $privilegio == 2) {
            $tabla .= '<th>ACTUALIZAR</th>';
        }
        if ($privilegio == 1) {
            $tabla .= '<th>ELIMINAR</th>';
        }
        $tabla .= '</tr>
					</thead>
				<tbody>';

        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            $reg_inicio = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
					<tr class="text-center" >
						<td>' . $rows['item_id'] . '</td>
						<td>' . $rows['item_patrimonio'] . '</td>
						<td>' . $rows['item_codigo'] . '</td>
						<td>' . $rows['item_nombre'] . '</td>
						<td>' . $rows['item_stock'] . '</td>
						<td>' . $rows['item_estado'] . '</td>
						<td>
						    <button type="button" class="btn btn-info btn_remitos" data-id="' . $rows['item_id'] . '" data-nombre="' . $rows['item_nombre'] . '">
							<i class="fas fa-eye"></i> Remitos
						</button></td>';
                if ($privilegio == 1 || $privilegio == 2) {
                    $tabla .= '<td>
							<a href="' . SERVERURL . 'item-update/' . mainModel::encryption($rows['item_id']) . '/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>
							</a>
						</td>';
                }

                if ($privilegio == 1) {
                    $tabla .= '<td>
								<form class="FormularioAjax" action="' . SERVERURL . 'ajax/itemAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="item_id_del" value="' . mainModel::encryption($rows['item_id']) . '">
									<button type="submit" class="btn btn-warning">
											<i class="far fa-trash-alt"></i>
									</button>
								</form>
							</td>';
                }
                $tabla .= '</tr>';
                $contador++;
            }
            $reg_final = $contador - 1;
        } else {
            if ($total >= 1) {
                $tabla .= '<tr class="text-center" ><td colspan="8">
					<a href="' . $url . '" class="btn btn-raised btn-primary btn-sm"> Haga Click Acá para Recargar el Listado</a>
					</td></tr>';
            } else {
                $tabla .= '<tr class="text-center" ><td colspan="8">No hay Registros en el Sistema</td></tr>';
            }
        }

        $tabla .= '</tbody></table></div>';

        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<p class="text-right">Mostrando Item de ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';

            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }

        return $tabla;
    }/* Fin Controlador */


    /*--------- Controlador Eliminar Item ---------*/
    public function eliminar_item_controlador()
    {

        // Recibiendo el ID
        $id = mainModel::decryption($_POST['item_id_del']);
        $id = mainModel::limpiar_cadena($id);

        // Comprobar Item en la BD
        $check_item = mainModel::ejecutar_consulta_simple("SELECT item_id FROM item WHERE item_id='$id'");
        if ($check_item->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Item que Intenta Eliminar no Existe en el Sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando Detalles del Prestamo
        $check_prestamos = mainModel::ejecutar_consulta_simple("SELECT item_id FROM detalle WHERE item_id='$id' LIMIT 1");
        if ($check_prestamos->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Podemos Eliminar el Item Debido a Que Tiene Remitos Asociados, Recomendamos Desabilitar el Item si ya no Será Usado en el Sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobar los Privilegios
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] != 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Tienes los Permisos Necesarios para Realizar esta Operación",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $eliminar_item = itemModelo::eliminar_item_modelo($id);

        if ($eliminar_item->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item Eliminado",
                "Texto" => "El Item Ha Sido Eliminado del Sistemas con Éxito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Eliminar el Item del Sistema. Por Favor Intente Nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin Controlador */


    /*--------- Controlador Datos del Item ---------*/
    public function datos_item_controlador($tipo, $id)
    {
        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return itemModelo::datos_item_modelo($tipo, $id);
    }/* Fin Controlador */


    /*--------- Controlador Actualizar Item ---------*/
    public function actualizar_item_controlador()
    {

        // Recuperar el Id
        $id = mainModel::decryption($_POST['item_id_up']);
        $id = mainModel::limpiar_cadena($id);

        // Comprobar el Item en la DB
        $check_item = mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id='$id'");
        if ($check_item->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Encontrado el ITEM en el Sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_item->fetch();
        }

        $codigo = mainModel::limpiar_cadena($_POST['item_codigo_up']);
        $nombre = mainModel::limpiar_cadena($_POST['item_nombre_up']);
        $stock = mainModel::limpiar_cadena($_POST['item_stock_up']);
        $estado = mainModel::limpiar_cadena($_POST['item_estado_up']);
        $detalle = mainModel::limpiar_cadena($_POST['item_detalle_up']);
        $patrimonio = mainModel::limpiar_cadena($_POST['item_patrimonio_up']);

        // Actualizar el estado en la base de datos
        //$actualizarEstado = mainModel::ejecutar_consulta_simple("UPDATE item SET item_estado='$nuevoEstado' WHERE item_id='$id'");
        /*== comprobar campos vacios ==*/
        if ($nombre == "" || $stock == "" || $estado == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No has LLenado Todos los Campos que son Obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Verificando integridad de los datos ==*/
        if (mainModel::verificar_datos(".*", $codigo)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Codigo no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos(".*", $patrimonio)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Patrimonio no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos(".*", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Nombre no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9]{1,9}", $stock)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Stock no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($detalle != "") {
            if (mainModel::verificar_datos(".*", $detalle)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "El Detalle no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        if ($estado != "Habilitado" && $estado != "Deshabilitado") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Estado del Item no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }


        /*== Comprobar Codigo ==*/
        if ($codigo != $campos['item_codigo'] && $codigo !== '') {
            $check_codigo = mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo='$codigo'");
            if ($check_codigo->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "El ITEM Ingresado ya se Encuentra Registrado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        /*== Comprobar Patrimonio ==*/
        if ($patrimonio != $campos['item_patrimonio'] && $patrimonio !== '') {
            $check_patrimonio = mainModel::ejecutar_consulta_simple("SELECT item_patrimonio FROM item WHERE item_patrimonio='$patrimonio'");
            if ($check_patrimonio->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "El ITEM Ingresado ya se Encuentra Registrado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }


        // Comprobando los Privilegios del Administrador
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] < 1 || $_SESSION['privilegio_spm'] > 2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Tienes los Permisos Necesarios para Realizar esta Operacion",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $datos_item_up = [
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Stock" => $stock,
            "Estado" => $estado,
            "Detalle" => $detalle,
            "Patrimonio" => $patrimonio,
            "ID" => $id
        ];

        if (itemModelo::actualizar_item_modelo($datos_item_up)) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item Actualizado",
                "Texto" => "Los Datos del Item Han Sido Actualizados con éxito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Actualizar los Datos del Item, por Favor Intente Nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    } /* Fin Controlador */

    public function get_remitos_item_controller()
    {
        $id = $_POST["id"];

        $tbody = '';

        $remitos = itemModelo::get_remitos_item($id);

        foreach ($remitos as $rows) {
            $tbody .= '
				<tr class="text-center" >
				<td>' . $rows['prestamo_id'] . '</td>
				<td>' . $rows['cliente_nombre'] . ' ' . $rows['cliente_apellido'] . '</td>
				<td>' . date("d/m/Y", strtotime($rows['prestamo_fecha_inicio'])) . '</td>
				<td>' . date("d/m/Y", strtotime($rows['prestamo_fecha_final'])) . '</td>
                ';

            if ($rows['prestamo_pagado'] < $rows['prestamo_total']) {
                $tbody .= '<td><span class="badge badge-danger">Pendiente</span></td>';
            } else {
                $tbody .= '<td><span class="badge badge-light">Devuelto</span></td>';
            }

            $tbody .= '
				<td>
                    <a href="' . SERVERURL . 'facturas/invoice.php?id=' . mainModel::encryption($rows['prestamo_id']) . '" class="btn btn-info" target="_blank">
                        <i class="fas fa-file-pdf"></i>	
                    </a>
				</td>
				';

            $tbody .= '</tr>';
        }

        return $tbody;

    }

}