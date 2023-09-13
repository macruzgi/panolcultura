<?php

require_once "mainModel.php";

class itemModelo extends mainModel
{

    /*--------- Modelo Agregar Item ---------*/
    protected static function agregar_item_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO item(item_codigo,item_nombre,item_stock,item_estado,item_detalle,item_patrimonio) VALUES(:Codigo,:Nombre,:Stock,:Estado,:Detalle,:Patrimonio)");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Stock", $datos['Stock']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Detalle", $datos['Detalle']);
        $sql->bindParam(":Patrimonio", $datos['Patrimonio']);
        $sql->execute();

        return $sql;
    }


    /*--------- Modelo Eliminar Item ---------*/
    protected static function eliminar_item_modelo($id)
    {
        $sql = mainModel::conectar()->prepare("DELETE FROM item WHERE item_id=:ID");

        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }


    /*--------- Modelo Datos del Item ---------*/
    protected static function datos_item_modelo($tipo, $id)
    {
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM item WHERE item_id=:ID");
            $sql->bindParam(":ID", $id);
        } elseif ($tipo == "Conteo") {
            $sql = mainModel::conectar()->prepare("SELECT item_id FROM item");
        }
        $sql->execute();

        return $sql;
    }


    /*--------- Modelo Actualizar Item ---------*/
    protected static function actualizar_item_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE item SET item_codigo=:Codigo,item_nombre=:Nombre,item_stock=:Stock,item_estado=:Estado,item_detalle=:Detalle,item_patrimonio=:Patrimonio WHERE item_id=:ID");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Stock", $datos['Stock']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Detalle", $datos['Detalle']);
        $sql->bindParam(":Patrimonio", $datos['Patrimonio']);
        $sql->bindParam(":ID", $datos['ID']);
        $sql->execute();

        return $sql;
    }

    protected static function get_remitos_item($id)
    {

        $query = "SELECT SQL_CALC_FOUND_ROWS pr.prestamo_id,
                            pr.prestamo_codigo,
                            pr.prestamo_fecha_inicio,
                            pr.prestamo_fecha_final,
                            pr.prestamo_pagado,
                            pr.prestamo_total,
                            pr.prestamo_estado,
                            cl.cliente_nombre,
                            cl.cliente_apellido
                            FROM prestamo AS pr
                                INNER JOIN cliente AS cl ON pr.cliente_id = cl.cliente_id
                                INNER JOIN detalle AS de ON de.prestamo_codigo = pr.prestamo_codigo
                            WHERE de.item_id = :ID ORDER BY pr.prestamo_id DESC";

        $sql = mainModel::conectar()->prepare($query);
        $sql->bindParam(":ID", $id);
        $sql->execute();
        return $sql->fetchAll();
    }


}