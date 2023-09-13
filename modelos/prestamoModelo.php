<?php

require_once "mainModel.php";

class prestamoModelo extends mainModel
{

    /*--------- Modelo Agregar Prestamo ---------*/
    protected static function agregar_prestamo_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO prestamo(prestamo_codigo,prestamo_fecha_inicio,prestamo_hora_inicio,prestamo_fecha_final,prestamo_hora_final,prestamo_cantidad,prestamo_total,prestamo_pagado,prestamo_estado,prestamo_observacion,prestamo_destino,usuario_id,cliente_id) VALUES(:Codigo,:FechaInicio,:HoraInicio,:FechaFinal,:HoraFinal,:Cantidad,:Total,:Pagado,:Estado,:Observacion,:Destino,:Usuario,:Cliente)");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":FechaInicio", $datos['FechaInicio']);
        $sql->bindParam(":HoraInicio", $datos['HoraInicio']);
        $sql->bindParam(":FechaFinal", $datos['FechaFinal']);
        $sql->bindParam(":HoraFinal", $datos['HoraFinal']);
        $sql->bindParam(":Cantidad", $datos['Cantidad']);
        $sql->bindParam(":Total", $datos['Total']);
        $sql->bindParam(":Pagado", $datos['Pagado']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Observacion", $datos['Observacion']);
        $sql->bindParam(":Destino", $datos['Destino']);
        $sql->bindParam(":Usuario", $datos['Usuario']);
        $sql->bindParam(":Cliente", $datos['Cliente']);
        $sql->execute();

        return $sql;
    }


    /*--------- Modelo Agregar Detalle ---------*/
    protected static function agregar_detalle_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO detalle(detalle_cantidad,detalle_formato,detalle_tiempo,detalle_costo_tiempo,detalle_descripcion,prestamo_codigo,item_id) VALUES(:Cantidad,:Formato,:Tiempo,:Costo,:Descripcion,:Prestamo,:Item)");

        $sql->bindParam(":Cantidad", $datos['Cantidad']);
        $sql->bindParam(":Formato", $datos['Formato']);
        $sql->bindParam(":Tiempo", $datos['Tiempo']);
        $sql->bindParam(":Costo", $datos['Costo']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->bindParam(":Prestamo", $datos['Prestamo']);
        $sql->bindParam(":Item", $datos['Item']);
        $sql->execute();

        return $sql;
    }

    // ACTUALIZAR EL STOCK A HABILITADO O INHABILITADO SEGUN REALICE REMITO O DEVOLUCIONES
    protected static function actualizar_stock_item($item, $modo)
    {

        $stock = 0;
        $estado = "Deshabilitado";
        if ($modo == 'ENTRADA') {
            $stock = 1;
            $estado = "Habilitado";
        }

        $sql = mainModel::conectar()->prepare("UPDATE item SET item_stock=:stock, item_estado=:estado  WHERE item_id=:item");
        $sql->bindParam(":stock", $stock);
        $sql->bindParam(":estado", $estado);
        $sql->bindParam(":item", $item);
        $sql->execute();
    }


    /*--------- Modelo Agregar Pago ---------*/
    protected static function agregar_pago_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO pago(pago_total,pago_fecha,prestamo_codigo) VALUES(:Total,:Fecha,:Codigo)");

        $sql->bindParam(":Total", $datos['Total']);
        $sql->bindParam(":Fecha", $datos['Fecha']);
        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->execute();

        return $sql;
    }


    /*--------- Modelo Eliminar Préstamo ---------*/
    protected static function eliminar_prestamo_modelo($codigo, $tipo)
    {
        try {

            if ($tipo == "Prestamo") {
                $sql = mainModel::conectar()->prepare("DELETE FROM prestamo WHERE prestamo_codigo=:Codigo");
            } elseif ($tipo == "Detalle") {
                // ANTES DE ELIMINAR LOS DETALLES ACTUALIZAR LOS DETALLES DEL PRESTAMOS
                self::get_prestamo_detalles("ENTRADA", $codigo);
                // CONTINUAR A LO SIGUIENTE
                $sql = mainModel::conectar()->prepare("DELETE FROM detalle WHERE prestamo_codigo=:Codigo");
            } elseif ($tipo == "Pago") {
                $sql = mainModel::conectar()->prepare("DELETE FROM pago WHERE prestamo_codigo=:Codigo");
            }

            $sql->bindParam(":Codigo", $codigo);
            $sql->execute();

            return $sql;
        } catch (\PDOException $Ex) {
            echo "NO SE PUDO ELIMINAR";
        }
    }


    /*--------- Modelo Seleccionar Datos del Préstamo ---------*/
    protected static function datos_prestamo_modelo($tipo, $id)
    {
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM prestamo WHERE prestamo_id=:ID");
            $sql->bindParam(":ID", $id);
        } elseif ($tipo == "Conteo_Reservacion") {
            $sql = mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Reservacion'");
        } elseif ($tipo == "Conteo_Prestamos") {
            $sql = mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Prestamo'");
        } elseif ($tipo == "Conteo_Finalizado") {
            $sql = mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Finalizado'");
        } elseif ($tipo == "Conteo") {
            $sql = mainModel::conectar()->prepare("SELECT prestamo_id FROM prestamo");
        } elseif ($tipo == "Detalle") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM detalle WHERE prestamo_codigo=:Codigo");
            $sql->bindParam(":Codigo", $id);
        } elseif ($tipo == "Pago") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM pago WHERE prestamo_codigo=:Codigo");
            $sql->bindParam(":Codigo", $id);
        }

        $sql->execute();

        return $sql;
    }


    /*--------- Modelo Actualizar Datos del Préstamo ---------*/
    protected static function actualizar_prestamo_modelo($datos)
    {
        try {

            if ($datos['Tipo'] == "Pago") {
                $sql = mainModel::conectar()->prepare("UPDATE prestamo SET prestamo_pagado=:Monto WHERE prestamo_codigo=:Codigo");
                $sql->bindParam(":Monto", $datos['Monto']);
				//actuaizar cada item del prestama cuando se cambia de estado el prestamo
				$sql_2 = mainModel::conectar()->prepare("UPDATE detalle SET estado_item= 1 WHERE prestamo_codigo=:prestamo_codigo");
                $sql_2->bindParam(":prestamo_codigo", $datos['Codigo']);
				$sql_2->execute();
				
            } elseif ($datos['Tipo'] == "Prestamo") {
                $sql = mainModel::conectar()->prepare("UPDATE prestamo SET prestamo_estado=:Estado,prestamo_observacion=:Observacion WHERE prestamo_codigo=:Codigo");
                $sql->bindParam(":Estado", $datos['Estado']);
                $sql->bindParam(":Observacion", $datos['Observacion']);
                $sql->bindParam(":Destino", $datos['Destino']);
            }

            $sql->bindParam(":Codigo", $datos['Codigo']);

            $sql->execute();
			

            // GET ITEMS EN EL PRESTADO
            self::get_prestamo_detalles("ENTRADA", $datos['Codigo']);


            return $sql;
        } catch (\PDOException $ex) {
            echo "NO SE PUDO ACTUALIZAR";
        }
    }

    // TRAER LOS DATOS DEL PRESTAMO Y DEVOLVER LOS STOCK
    protected static function get_prestamo_detalles($modo, $codigo)
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM detalle WHERE prestamo_codigo=:Codigo");
        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();
        $items = $sql->fetchAll();

        // RECORRER
        foreach ($items as $ite) {
            // MANDAR ACTUALIZAR STOCK
            self::actualizar_stock_item($ite["item_id"], $modo);
        }

        return true;
    }
	 /*--------- Funcion para devolver un item al inventario del Préstamo ---------*/
    protected static function Devolver_Item_De_Prestamo_Remitos($prestamo_codigo, $item_id) 
    {
		 
        try {
 
                $sql = mainModel::conectar()->prepare("UPDATE detalle set estado_item = 1   WHERE prestamo_codigo=:prestamo_codigo AND item_id=:item_id");
              
 
				$sql->bindParam(":prestamo_codigo", $prestamo_codigo);
				$sql->bindParam(":item_id", $item_id);
				$sql->execute();
				
				//actulizar el inventario del item devuelto
				$sql_item = mainModel::conectar()->prepare("UPDATE item set item_stock = 1, item_estado = 'Habilitado'   WHERE item_id=:item_id");
              
				$sql_item->bindParam(":item_id", $item_id);
				$sql_item->execute(); 
				
				//sql para comprobar si todos los items han sido devueltos
				$sql_item_devueltos = mainModel::conectar()->prepare("select count(*) as CUENTA from detalle d  where d.prestamo_codigo=:prestamo_codigo and estado_item = 0");
				
				$sql_item_devueltos->bindParam(":prestamo_codigo", $prestamo_codigo);
				$sql_item_devueltos->execute(); 
				$resultado = $sql_item_devueltos->fetch(PDO::FETCH_ASSOC);
				//si el valor CUENTA es 0 significa que ya todos los items fueron devueltos
				if($resultado['CUENTA'] == 0){
					//todos los items han sido devueltos, que actualice el estado del prestamo
					$sql_prestamo = mainModel::conectar()->prepare("UPDATE prestamo set prestamo_pagado = prestamo_total WHERE prestamo_codigo=:prestamo_codigo");
				  
					$sql_prestamo->bindParam(":prestamo_codigo", $prestamo_codigo);
					$sql_prestamo->execute();
				}


            return $sql;
        } catch (\PDOException $Ex) {
            echo "NO SE PUDO DEVOLVER EL ITEM";
        }
    }



}