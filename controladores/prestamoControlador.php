<?php

if ($peticionAjax) {
    require_once "../modelos/prestamoModelo.php";
} else {
    require_once "./modelos/prestamoModelo.php";
}

class prestamoControlador extends prestamoModelo
{

    /*--------- Controlador Buscar Cliente Prestamo ---------*/
    public function buscar_cliente_prestamo_controlador()
    {
        /* Recuperar el Texto */
        $cliente = mainModel::limpiar_cadena($_POST['buscar_cliente']);

        /* Comprobar el Texto */
        if ($cliente == "") {
            return '<div class="alert alert-warning" role="alert">
			<p class="text-center mb-0">
			<i class="fas fa-exclamation-triangle fa-2x"></i><br>
			Debes de Introducir el DNI, Nombre, Apellido, Telefono
			</p>
			</div>';
            exit();
        }

        /* Seleccionando Clientes en la BD */
        $datos_cliente = mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_dni LIKE '%$cliente%' OR cliente_nombre LIKE '%$cliente%' OR cliente_apellido LIKE '%$cliente%' OR cliente_telefono LIKE '%$cliente%' ORDER BY cliente_nombre ASC");

        if ($datos_cliente->rowCount() >= 1) {
            $datos_cliente = $datos_cliente->fetchAll();

            $tabla = '<div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
            foreach ($datos_cliente as $rows) {
                $tabla .= '<tr class="text-center">
				<td>' . $rows['cliente_nombre'] . ' ' . $rows['cliente_apellido'] . ' - ' . $rows['cliente_dni'] . '</td>
				<td>
				<button type="button" class="btn btn-primary" onclick="agregar_cliente(' . $rows['cliente_id'] . ')"><i class="fas fa-user-plus"></i></button>
				</td>
				</tr>';
            }
            $tabla .= '</tbody></table></div>';
            return $tabla;
        } else {
            return '<div class="alert alert-warning" role="alert">
			<p class="text-center mb-0">
			<i class="fas fa-exclamation-triangle fa-2x"></i><br>
			No hemos encontrado ningún cliente en el sistema que coincida con <strong>“' . $cliente . '”</strong>
			</p>
			</div>';
            exit();
        }
    } /* Fin Controlador */


    /*--------- Controlador Agregar Cliente Prestamo ---------*/
    public function agregar_cliente_prestamo_controlador()
    {
        /* Recuperar el Id */
        $id = mainModel::limpiar_cadena($_POST['id_agregar_cliente']);

        /* Comprobando el Cliente en la BD */
        $check_cliente = mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");
        if ($check_cliente->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Encontrar el Cliente en la Base de Datos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_cliente->fetch();
        }

        /* Iniciando la Sesión */
        session_start(['name' => 'SPM']);

        if (empty($_SESSION['datos_cliente'])) {
            $_SESSION['datos_cliente'] = [
                "ID" => $campos['cliente_id'],
                "DNI" => $campos['cliente_dni'],
                "Nombre" => $campos['cliente_nombre'],
                "Apellido" => $campos['cliente_apellido']
            ];

            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente Agregado",
                "Texto" => "El Cliente se Agregó para Realizar un Remito",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Agregar el Cliente al Remito",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
        }
    } /* Fin Controlador */


    /*--------- Controlador Eliminar Cliente Prestamo ---------*/
    public function eliminar_cliente_prestamo_controlador()
    {

        /* Iniciando la Sesión */
        session_start(['name' => 'SPM']);

        unset($_SESSION['datos_cliente']);

        if (empty($_SESSION['datos_cliente'])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Se Removió el Cliente",
                "Texto" => "Los Datos del Cliente se Removieron con Éxito",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Remover los Datos del Cliente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    } /* Fin Controlador */

    /*--------- Controlador Buscar Item Prestamo ---------*/
    public function buscar_item_prestamo_controlador()
    {
        /* Recuperar el Texto */
        $item = mainModel::limpiar_cadena($_POST['buscar_item']);

        /* Comprobar el Texto */
        if ($item == "") {
            return '<div class="alert alert-warning" role="alert">
			<p class="text-center mb-0">
			<i class="fas fa-exclamation-triangle fa-2x"></i><br>
			Debes de Introducir el Código, Nombre del ÍTEM
			</p>
			</div>';
            exit();
        }

        /* Seleccionando Items en la BD */
        $datos_item = mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE (item_codigo LIKE '%$item%' OR item_nombre LIKE '%$item%' OR item_patrimonio LIKE '%$item%') AND (item_estado='Habilitado') ORDER BY item_nombre ASC");

        if ($datos_item->rowCount() >= 1) {
            $datos_item = $datos_item->fetchAll();

            $tabla = '<div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
            foreach ($datos_item as $rows) {
                $tabla .= '<tr class="text-center">
				<td>' . $rows['item_codigo'] . '-' . $rows['item_nombre'] . '</td>
				<td>
				<button type="button" class="btn btn-primary" onclick="modal_agregar_item(' . $rows['item_id'] . ')"><i class="fas fa-box-open"></i></button>
				</td>
				</tr>';
            }
            $tabla .= '</tbody></table></div>';
            return $tabla;
        } else {
            return '<div class="alert alert-warning" role="alert">
			<p class="text-center mb-0">
			<i class="fas fa-exclamation-triangle fa-2x"></i><br>
			No hemos encontrado ningún item en el sistema que coincida con <strong>“' . $item . '”</strong>
			</p>
			</div>';
            exit();
        }
    }/* Fin Controlador */


    /*--------- Controlador Agregar Item Prestamo ---------*/
    public function agregar_item_prestamo_controlador()
    {

        /* Recuperando id del Item */
        $id = mainModel::limpiar_cadena($_POST['id_agregar_item']);

        /* Comprobando Item en la DB */
        $check_item = mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id='$id' AND item_estado='Habilitado' ");
        if ($check_item->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Seleccionar el Item Por Favor Intente Nuevamente",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_item->fetch();
        }

        /*== Recuperando Detalle del Prestamo ==*/
        $formato = mainModel::limpiar_cadena($_POST['detalle_formato']);
        $cantidad = mainModel::limpiar_cadena($_POST['detalle_cantidad']);
        $tiempo = mainModel::limpiar_cadena($_POST['detalle_tiempo']);
        $costo = mainModel::limpiar_cadena($_POST['detalle_costo_tiempo']);

        /*== Comprobar Campos Vacios ==*/
        if ($cantidad == "" || $tiempo == "" || $costo == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No has LLenado Todos los Campos que son Obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Verificando Integridad de los Datos ==*/
        if (mainModel::verificar_datos("[0-9]{1,7}", $cantidad)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "La Cantidad no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9]{1,7}", $tiempo)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Tiempo no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9.]{1,15}", $costo)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Costo no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($formato != "Horas" && $formato != "Dias" && $formato != "Evento" && $formato != "Mes") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Formato no es Valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        session_start(['name' => 'SPM']);

        if (empty($_SESSION['datos_item'][$id])) {
            $costo = number_format($costo, 2, '.', '');

            $_SESSION['datos_item'][$id] = [
                "ID" => $campos['item_id'],
                "Codigo" => $campos['item_codigo'],
                "Nombre" => $campos['item_nombre'],
                "Detalle" => $campos['item_detalle'],
                "Formato" => $formato,
                "Cantidad" => $cantidad,
                "Tiempo" => $tiempo,
                "Costo" => $costo
            ];

            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item Agregado",
                "Texto" => "El Item Ha Sido Agregado para Realizar un Remito",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Item que Intenta Agregar Ya se Encuentra Seleccionado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

    } /* Fin Controlador */

    /*--------- Controlador Eliminar Item Prestamo ---------*/
    public function eliminar_item_prestamo_controlador()
    {

        /* Recuperando el Id Item */
        $id = mainModel::limpiar_cadena($_POST['id_eliminar_item']);

        /* Iniciando la Sesión */
        session_start(['name' => 'SPM']);

        unset($_SESSION['datos_item'][$id]);

        if (empty($_SESSION['datos_item'][$id])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Se Removió el Item",
                "Texto" => "Los Datos del Item se Removieron con Éxito",
                "Tipo" => "success"
            ];/*	echo json_encode($alerta);exit();	*/
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Remover los Datos del Item",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    } /* Fin Controlador */


    /*--------- Controlador Datos Prestamo ---------*/
    public function datos_prestamo_controlador($tipo, $id)
    {
        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return prestamoModelo::datos_prestamo_modelo($tipo, $id);
    } /* Fin Controlador */


    /*--------- Controlador Agregar Prestamo ---------*/
    public function agregar_prestamo_controlador()
    {
        try {

            /* Iniciando la Sesión */
            session_start(['name' => 'SPM']);

            /* Comprobando Items */
            if ($_SESSION['prestamo_item'] == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "No has Seleccionado Ningún Item para realizar el Remito",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /* Comprobando Cliente */
            if (empty($_SESSION['datos_cliente'])) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "No has Seleccionado Ningún Cliente para realizar el Remito",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /* Recibiendo datos del Formulario */
            $fecha_inicio = mainModel::limpiar_cadena($_POST['prestamo_fecha_inicio_reg']);
            $hora_inicio = mainModel::limpiar_cadena($_POST['prestamo_hora_inicio_reg']);
            $fecha_final = mainModel::limpiar_cadena($_POST['prestamo_fecha_final_reg']);
            $hora_final = mainModel::limpiar_cadena($_POST['prestamo_hora_final_reg']);
            $estado = mainModel::limpiar_cadena($_POST['prestamo_estado_reg']);
            $total_pagado = mainModel::limpiar_cadena($_POST['prestamo_pagado_reg']);
            $observacion = mainModel::limpiar_cadena($_POST['prestamo_observacion_reg']);
            $destino = mainModel::limpiar_cadena($_POST['prestamo_destino_reg']);

            /* Comprobando Integridad de los Datos */
            if (mainModel::verificar_fecha($fecha_inicio)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "La Fecha de Inicio no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])", $hora_inicio)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "La Hora de Inicio no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_fecha($fecha_final)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "La Fecha de Final no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])", $hora_final)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "La Hora de Entrega no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[0-9.]{1,10}", $total_pagado)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "El Total Depositado no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ($observacion != "") {
                if (mainModel::verificar_datos(".*", $observacion)) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un Error Inesperado",
                        "Texto" => "La Observacion no Coincide con el Formato Solicitado",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if ($destino != "") {
                if (mainModel::verificar_datos(".*", $destino)) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un Error Inesperado",
                        "Texto" => "El destino no Coincide con el Formato Solicitado",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if ($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "El Estado no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /* Comprobando Las Fechas */
            if (strtotime($fecha_final) < strtotime($fecha_inicio)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "La Fecha de Entrega NO Puede ser MENOR que la Fecha de Inicio del Remito",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /* Formatendo Totales, Fechas y Horas */
            $total_prestamo = number_format($_SESSION['prestamo_total'], 2, '.', '');
            $total_pagado = number_format($total_pagado, 2, '.', '');

            $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio));
            $fecha_final = date("Y-m-d", strtotime($fecha_final));

            $hora_inicio = date("h:i A", strtotime($hora_inicio));
            $hora_final = date("h:i A", strtotime($hora_final));

            /* Generando Código de Préstamo */
            $correlativo = mainModel::ejecutar_consulta_simple("SELECT prestamo_id FROM prestamo");
            $correlativo = ($correlativo->rowCount()) + 1;
            $codigo = mainModel::generar_codigo_aleatorio("CP", 7, $correlativo);

            $datos_prestamo_reg = [
                "Codigo" => $codigo,
                "FechaInicio" => $fecha_inicio,
                "HoraInicio" => $fecha_inicio,
                "FechaFinal" => $fecha_final,
                "HoraFinal" => $hora_final,
                "Cantidad" => $_SESSION['prestamo_item'],
                "Total" => $total_prestamo,
                "Pagado" => $total_pagado,
                "Estado" => $estado,
                "Observacion" => $observacion,
                "Destino" => $destino,
                "Usuario" => $_SESSION['id_spm'],
                "Cliente" => $_SESSION['datos_cliente']['ID']
            ];

            /* Agregar Datos en Préstamo */
            $agregar_prestamo = prestamoModelo::agregar_prestamo_modelo($datos_prestamo_reg);

            if ($agregar_prestamo->rowCount() != 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado (Error: 001)",
                    "Texto" => "No Hemos Podido REGISTRAR el Remito,por Favor Intente Nuevamente",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /* Agregar Pago en Préstamo */
            if ($total_pagado > 0) {

                $datos_pago_reg = [
                    "Total" => $total_pagado,
                    "Fecha" => $fecha_inicio,
                    "Codigo" => $codigo
                ];

                $agregar_pago = prestamoModelo::agregar_pago_modelo($datos_pago_reg);

                if ($agregar_pago->rowCount() != 1) {
                    prestamoModelo::eliminar_prestamo_modelo($codigo, "Prestamo");
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un Error Inesperado Error: 002)",
                        "Texto" => "No Hemos Podido REGISTRAR el Remito, por Favor Intente Nuevamente",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }


            /* Agregar Detalle en Préstamo */
            $errores_detalle = 0;

            foreach ($_SESSION['datos_item'] as $items) {

                $costo = number_format($items['Costo'], 2, '.', '');
                $descripcion = $items['Codigo'] . " " . $items['Nombre'];

                $datos_detalle_reg = [
                    'Cantidad' => $items['Cantidad'],
                    'Formato' => $items['Formato'],
                    'Tiempo' => $items['Tiempo'],
                    'Costo' => $costo,
                    'Descripcion' => $descripcion,
                    'Destino' => $destino,
                    'Prestamo' => $codigo,
                    'Item' => $items['ID']
                ];

                // AGREGAR LOS DETALLES DE LOS ITEMS AL PRESTAMOS
                $agregar_detalle = prestamoModelo::agregar_detalle_modelo($datos_detalle_reg);

                // AHORA ACTUALIZAR EL STOCK DEL ITEM A CERO E INHABILITADO
                prestamoModelo::actualizar_stock_item($items['ID'], "SALIDA");

                if ($agregar_detalle->rowCount() != 1) {
                    $errores_detalle = 1;
                    break;
                }
            }

            if ($errores_detalle == 0) {
                unset($_SESSION['datos_cliente']);
                unset($_SESSION['datos_item']);
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Remito Registrado",
                    "Texto" => "Los Datos del Remito han Sido Registrados en el Sistema",
                    "Tipo" => "success"
                ];
            } else {
                prestamoModelo::eliminar_prestamo_modelo($codigo, "Detalle");
                prestamoModelo::eliminar_prestamo_modelo($codigo, "Pago");
                prestamoModelo::eliminar_prestamo_modelo($codigo, "Prestamo");
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado Error: 003)",
                    "Texto" => "No Hemos Podido REGISTRAR el Remito, por Favor Intente Nuevamente",
                    "Tipo" => "error"
                ];
            }
            echo json_encode($alerta);

        } catch (Exception $ex) {
            echo "NO SE PUDO REGISTRAR";
        }
    } /* Fin Controlador */


    /*--------- Controlador para Paginar Préstamos ---------*/
    public function paginador_prestamos_controlador($pagina, $registros, $privilegio, $url, $tipo, $fecha_inicio, $fecha_final)
    {

        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);

        $url = mainModel::limpiar_cadena($url);
        $url = SERVERURL . $url . "/";

        $tipo = mainModel::limpiar_cadena($tipo);
        $fecha_inicio = mainModel::limpiar_cadena($fecha_inicio);
        $fecha_final = mainModel::limpiar_cadena($fecha_final);
        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        if ($tipo == "Busqueda") {
            if (mainModel::verificar_fecha($fecha_inicio) || mainModel::verificar_fecha($fecha_final)) {
                return '
				<div class="alert alert-danger text-center" role="alert">
					<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
					<h4 class="alert-heading">¡Ocurrió un error Inesperado!</h4>
					<p class="mb-0">Lo Sentimos NO Podemos Realizar la Búsqueda ya que ha Ingresado una FECHA Incorrecta.</p>
				</div>
				';
                exit();
            }
        }

        $campos = "prestamo.prestamo_id,prestamo.prestamo_codigo,prestamo.prestamo_fecha_inicio,prestamo.prestamo_fecha_final,prestamo.prestamo_total,prestamo.prestamo_pagado,prestamo.prestamo_estado,prestamo.usuario_id,prestamo.cliente_id,cliente.cliente_nombre,cliente.cliente_apellido";

        if ($tipo == "Busqueda" && $fecha_inicio != "" && $fecha_final != "") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE (prestamo.prestamo_fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_final') ORDER BY prestamo.prestamo_id DESC LIMIT $inicio,$registros";
        } else {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE prestamo.prestamo_estado='$tipo' ORDER BY prestamo.prestamo_id DESC LIMIT $inicio,$registros";
        }

        $conexion = mainModel::conectar();

        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int)$total->fetchColumn();

        $Npaginas = ceil($total / $registros);


        $tabla .= '<div class="table-responsive">
		<table class="table table-dark table-sm">
		<thead>
		<tr class="text-center roboto-medium">
		<th>ID</th>
		<th>CLIENTE</th>
		<th>FECHA DE REMITO</th>
		<th>FECHA DE ENTREGA</th>
		<th>TIPO</th>
		<th>ESTADO</th>
		<th>REMITO</th>';
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
				<td>' . $rows['prestamo_id'] . '</td>
				<td>' . $rows['cliente_nombre'] . ' ' . $rows['cliente_apellido'] . '</td>
				<td>' . date("d-m-Y", strtotime($rows['prestamo_fecha_inicio'])) . '</td>
				<td>' . date("d-m-Y", strtotime($rows['prestamo_fecha_final'])) . '</td>
				<td>Remito</td>';

                if ($rows['prestamo_pagado'] < $rows['prestamo_total']) {
                    $tabla .= '<td><span class="badge badge-danger">Pendiente</span></td>';
                } else {
                    $tabla .= '<td><span class="badge badge-light">Devuelto</span></td>';
                }

                $tabla .= '
				<td>
				<a href="' . SERVERURL . 'facturas/invoice.php?id=' . mainModel::encryption($rows['prestamo_id']) . '" class="btn btn-info" target="_blank">
				<i class="fas fa-file-pdf"></i>	
				</a>
				</td>
				';

                if ($privilegio == 1 || $privilegio == 2) {

                    if ($rows['prestamo_estado'] == "Finalizado" && $rows['prestamo_pagado'] == $rows['prestamo_total']) {
                        $tabla .= '<td>
						<button class="btn btn-success" disabled>
						<i class="fas fa-sync-alt"></i>
						</button>
						</td>';
                    } else {
                        $tabla .= '<td>
						<a href="' . SERVERURL . 'reservation-update/' . mainModel::encryption($rows['prestamo_id']) . '/" class="btn btn-success">
						<i class="fas fa-sync-alt"></i>
						</a>
						</td>';
                    }
                }

                if ($privilegio == 1) {
                    $tabla .= '<td>
					<form class="FormularioAjax" action="' . SERVERURL . 'ajax/prestamoAjax.php" method="POST" data-form="delete" autocomplete="off">
					<input type="hidden" name="prestamo_codigo_del" value="' . mainModel::encryption($rows['prestamo_codigo']) . '">
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
                $tabla .= '<tr class="text-center" ><td colspan="9">
				<a href="' . $url . '" class="btn btn-raised btn-primary btn-sm"> Haga Click Acá para Recargar el Listado</a>
				</td></tr>';
            } else {
                $tabla .= '<tr class="text-center" ><td colspan="9">No hay Registros en el Sistema</td></tr>';
            }
        }

        $tabla .= '</tbody></table></div>';

        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<p class="text-right">Mostrando Remitos de ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';

            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }

        return $tabla;
    }/* Fin Controlador */


    /*--------- Controlador para Eliminar Préstamos ---------*/
    public function eliminar_prestamo_controlador()
    {

        /*-- Recibiendo Código de Prestamo --*/
        $codigo = mainModel::decryption($_POST['prestamo_codigo_del']);
        $codigo = mainModel::limpiar_cadena($codigo);

        /*-- Comprobando Prestamo en la DB --*/
        $check_prestamo = mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM prestamo WHERE prestamo_codigo='$codigo'");
        if ($check_prestamo->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Remito que Intenta Eliminar NO Existe en el Sistema",
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


        /*-- Comprobando y Eliminar Pagos --*/
        $check_pagos = mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM pago WHERE prestamo_codigo='$codigo'");
        $check_pagos = $check_pagos->rowCount();
        if ($check_pagos > 0) {

            $eliminar_pagos = prestamoModelo::eliminar_prestamo_modelo($codigo, "Pago");
            if ($eliminar_pagos->rowCount() != $check_pagos) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "No Hemos Podido Eliminar el Remito, por Favor Intente Nuevamente",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }


        /*-- Comprobando y Eliminar Detalles --*/
        $check_detalles = mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM detalle WHERE prestamo_codigo='$codigo'");
        $check_detalles = $check_detalles->rowCount();
        if ($check_detalles > 0) {

            $eliminar_detalles = prestamoModelo::eliminar_prestamo_modelo($codigo, "Detalle");
            if ($eliminar_detalles->rowCount() != $check_detalles) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "No Hemos Podido Eliminar el Remito, por Favor Intente Nuevamente",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        $eliminar_prestamo = prestamoModelo::eliminar_prestamo_modelo($codigo, "Prestamo");

        if ($eliminar_prestamo->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Remito Eliminado",
                "Texto" => "El Remito ha Sido Eliminado del Sistema",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Eliminar el Remito, por Favor Intente Nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin Controlador */


    /*--------- Controlador para Agregar Pagos ---------*/
    public function agregar_pago_controlador()
    {

        /*-- Recibiendo Datos --*/
        $codigo = mainModel::decryption($_POST['pago_codigo_reg']);
        $codigo = mainModel::limpiar_cadena($codigo);

        $monto = mainModel::limpiar_cadena($_POST['pago_monto_reg']);
        $monto = number_format($monto, 2, '.', '');

        /*-- Comprobando que el pago sea mayor a 0 --*/
        if ($monto <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El digito debe ser mayor a 0",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- Comprobando Prestamo en la DB --*/
        $datos_prestamo = mainModel::ejecutar_consulta_simple("SELECT * FROM prestamo WHERE prestamo_codigo='$codigo'");

        if ($datos_prestamo->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Remito al cuál Intenta cambiar el estado NO Existe en el Sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $datos_prestamo = $datos_prestamo->fetch();
            $prestamoId = $datos_prestamo["prestamo_id"];
        }


        /*-- Comprobando que el Monto no sea mayor a lo que falta PAGAR --*/
        $pendiente = number_format(($datos_prestamo['prestamo_total'] - $datos_prestamo['prestamo_pagado']), 2, '.', '');
        if ($monto > $pendiente) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El digito que Acaba de Ingresar Supera el solicitado que Tiene este Remito",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando los Privilegios
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

        // Calculando el Total a Pagar y Fecha
        $total_pagado = number_format(($monto + $datos_prestamo['prestamo_pagado']), 2, '.', '');
        $fecha = date("Y-m-d");

        $datos_pago_reg = [
            "Total" => $monto,
            "Fecha" => $fecha,
            "Codigo" => $codigo,
            "Id" => $prestamoId,
        ];

        $agregar_pago = prestamoModelo::agregar_pago_modelo($datos_pago_reg);

        if ($agregar_pago->rowCount() == 1) {

            $datos_prestamo_up = [
                "Tipo" => "Pago",
                "Monto" => $total_pagado,
                "Codigo" => $codigo,
                "Id" => $prestamoId,
            ];

            if (prestamoModelo::actualizar_prestamo_modelo($datos_prestamo_up)) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Cambio de estado Realizado",
                    "Texto" => "El cambio de estado Se ha Realizado con éxito",
                    "Tipo" => "success"
                ];
            } else {
                prestamoModelo::eliminar_prestamo_modelo($codigo, "Pago");
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "No Hemos Podido Registrar el cambio de estado",
                    "Tipo" => "error"
                ];
            }
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Registrar el cambio de estado",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin Controlador */


    /*--------- Controlador para Actualizar Préstamo ---------*/
    public function actualizar_prestamo_controlador()
    {

        /*-- Recibiendo Código --*/
        $codigo = mainModel::decryption($_POST['prestamo_codigo_up']);
        $codigo = mainModel::limpiar_cadena($codigo);


        /*-- Comprobando Prestamo en la DB --*/
        $check_prestamo = mainModel::ejecutar_consulta_simple("SELECT prestamo_codigo FROM prestamo WHERE prestamo_codigo='$codigo'");
        if ($check_prestamo->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Remito que Intenta Actualizar NO Existe en el Sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- Recibir Datos --*/
        $estado = mainModel::limpiar_cadena($_POST['prestamo_estado_up']);
        $observacion = mainModel::limpiar_cadena($_POST['prestamo_observacion_up']);
        $destino = mainModel::limpiar_cadena($_POST['prestamo_destino_up']);

        /*-- Verificar la Integridad de los Datos --*/
        if ($observacion != "") {
            if (mainModel::verificar_datos(".*", $observacion)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "La Observacion no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        if ($destino != "") {
            if (mainModel::verificar_datos(".*", $destino)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un Error Inesperado",
                    "Texto" => "El destino no Coincide con el Formato Solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        if ($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "El Estado no Coincide con el Formato Solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando los Privilegios
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

        $datos_prestamo_up = [
            "Tipo" => "Prestamo",
            "Estado" => $estado,
            "Observacion" => $observacion,
            "Destino" => $destino,
            "Codigo" => $codigo
        ];

        if (prestamoModelo::actualizar_prestamo_modelo($datos_prestamo_up)) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Remito Actualizado",
                "Texto" => "Los Datos del Remito se han Actualizado con Éxito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Actualizar el Remito",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);

    }/* Fin Controlador */
	
	/*--------- Función para devolver un item del remito ---------*/
    public function Devolver_Item_De_Remito()
    {
		
        /*-- Recibiendo el item_id y el prestamo_codigo de Prestamo --*/
        $item_id 			= mainModel::decryption($_POST['item_id_devo']);
		$prestamo_codigo 	= mainModel::decryption($_POST['prestamo_codigo_devo']);
		
        $item_id 			= mainModel::limpiar_cadena($item_id);
		$prestamo_codigo 	= mainModel::limpiar_cadena($prestamo_codigo);

        

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

       
        $devolver_item = prestamoModelo::Devolver_Item_De_Prestamo_Remitos($prestamo_codigo, $item_id); 
        if ($devolver_item->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item Devuelto",
                "Texto" => "El item seleccionado se ha devuelto al inventario.",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un Error Inesperado",
                "Texto" => "No Hemos Podido Devolver el Item seleccionado, por Favor Intente Nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin Controlador */
}