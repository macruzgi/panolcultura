<?php

	if($peticionAjax){
		require_once "../modelos/clienteModelo.php";
	}else{
		require_once "./modelos/clienteModelo.php";
	}

	class clienteControlador extends clienteModelo{

		/*--------- Controlador Agregar Cliente ---------*/
		public function agregar_cliente_controlador(){

			$dni=mainModel::limpiar_cadena($_POST['cliente_dni_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['cliente_nombre_reg']);
			$apellido=mainModel::limpiar_cadena($_POST['cliente_apellido_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['cliente_telefono_reg']);
			$direccion=mainModel::limpiar_cadena($_POST['cliente_direccion_reg']);

			/*== Comprobar Campos Vacios ==*/
			if($dni=="" || $nombre=="" || $apellido=="" || $telefono=="" || $direccion==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No has LLenado Todos los Campos que son Obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Verificando Integridad de los Datos ==*/
			if(mainModel::verificar_datos("[0-9-]{1,27}",$dni)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El DNI no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El NOMBRE no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El APELLIDO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El TELEFONO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"La DIRECCION no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Comprobar DNI ==*/
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");
			if ($check_dni->rowCount()>0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El DNI Ingresado ya se Encuentra Registrado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$datos_cliente_reg=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion
			];

			$agregar_cliente=clienteModelo::agregar_cliente_modelo($datos_cliente_reg);

			if ($agregar_cliente->rowCount()==1) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Cliente Registrado",
					"Texto"=>"Los Datos del CLIENTE se Registraron con Exito",
					"Tipo"=>"success"
				];
			} else {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Podido Registrar el CLIENTE, por Favor Intente Nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} /* Fin Controlador */


		/*--------- Controlador para Paginar Cliente ---------*/
		public function paginador_cliente_controlador($pagina,$registros,$privilegio,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1 ;
			$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;

			if (isset($busqueda) && $busqueda!="") {
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE cliente_dni LIKE '%$busqueda%' OR cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%' OR cliente_telefono LIKE '%$busqueda%' ORDER BY cliente_nombre ASC LIMIT $inicio,$registros";
			} else {
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente ORDER BY cliente_nombre ASC LIMIT $inicio,$registros";
			}

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas=ceil($total/$registros);


			$tabla.='<div class="table-responsive">
				<table class="table table-dark table-sm">
					<thead>
						<tr class="text-center roboto-medium">
							<th>#</th>
							<th>DNI</th>
							<th>NOMBRE</th>
							<th>APELLIDO</th>
							<th>TELÉFONO</th>
							<th>DIRECCION</th>';
							if ($privilegio==1 || $privilegio==2) {
								$tabla.='<th>ACTUALIZAR</th>';
							}
							if ($privilegio==1) {
							$tabla.='<th>ELIMINAR</th>';
							}
						$tabla.='</tr>
					</thead>
				<tbody>';

			if ($total>=1 && $pagina<=$Npaginas) {
				$contador=$inicio+1;
				$reg_inicio=$inicio+1;
				foreach ($datos as $rows) {
					$tabla.='
					<tr class="text-center" >
						<td>'.$contador.'</td>
						<td>'.$rows['cliente_dni'].'</td>
						<td>'.$rows['cliente_nombre'].'</td>
						<td>'.$rows['cliente_apellido'].'</td>
						<td>'.$rows['cliente_telefono'].'</td>
						<td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].' " data-content="'.$rows['cliente_direccion'].'">
							<i class="fas fa-info-circle"></i>
						</button></td>';
						if ($privilegio==1 || $privilegio==2) {
						$tabla.='<td>
							<a href="'.SERVERURL.'client-update/'.mainModel::encryption($rows['cliente_id']).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>
							</a>
						</td>';
						}

						if ($privilegio==1) {
							$tabla.='<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/clienteAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="cliente_id_del" value="'.mainModel::encryption($rows['cliente_id']).'">
									<button type="submit" class="btn btn-warning">
											<i class="far fa-trash-alt"></i>
									</button>
								</form>
							</td>';
						}
						$tabla.='</tr>';
					$contador++;
				}
				$reg_final=$contador-1;
			} else {
				if ($total>=1) {
					$tabla.='<tr class="text-center" ><td colspan="9">
					<a href="'.$url.'" class="btn btn-raised btn-primary btn-sm"> Haga Click Acá para Recargar el Listado</a>
					</td></tr>';
				} else {
					$tabla.='<tr class="text-center" ><td colspan="9">No hay Registros en el Sistema</td></tr>';
				}
			}

			$tabla.='</tbody></table></div>';

			if ($total>=1 && $pagina<=$Npaginas) {
				$tabla.='<p class="text-right">Mostrando Cliente de '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';

				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}

			return $tabla;
		}/* Fin Controlador */


		/*--------- Controlador Eliminar Cliente ---------*/
		public function eliminar_cliente_controlador(){

			// Recuperar el ID del Cliente
			$id=mainModel::decryption($_POST['cliente_id_del']);
			$id=mainModel::limpiar_cadena($id);

			// Comprobar el Cliente en la BD
			$check_cliente=mainModel::ejecutar_consulta_simple("SELECT cliente_id FROM cliente WHERE cliente_id='$id'");
			if ($check_cliente->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Encontrado el CLIENTE en el Sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			// Comprobar Prestamos
			$check_prestamos=mainModel::ejecutar_consulta_simple("SELECT cliente_id FROM prestamo WHERE cliente_id='$id' LIMIT 1");
			if ($check_prestamos->rowCount()>0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Podemos Eliminar el CLIENTE del Sistema porque Tenemos Remitos Ascociados",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			// Comprobar los Privilegios
			session_start(['name'=>'SPM']);
			if ($_SESSION['privilegio_spm']!=1) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Tienes los Permisos Necesarios para Realizar esta Operacion",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$eliminar_cliente=clienteModelo::eliminar_cliente_modelo($id);

			if ($eliminar_cliente->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Cliente Eliminado",
					"Texto"=>"El CLIENTE ha Sido Eliminado del Sistema con Exito",
					"Tipo"=>"success"
				];
			} else {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Podido Elimar el CLIENTE por Favor Intente Nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}/* Fin Controlador */


		/*--------- Controlador Datos del Cliente ---------*/
		public function datos_cliente_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);

			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return clienteModelo::datos_cliente_modelo($tipo,$id);
		}/* Fin Controlador */


		/*--------- Controlador Actualizar Cliente ---------*/
		public function actualizar_cliente_controlador(){
			// Recuperar el Id
			$id=mainModel::decryption($_POST['cliente_id_up']);
			$id=mainModel::limpiar_cadena($id);

			// Comprobar el Cliente en la DB
			$check_cliente=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");
			if ($check_cliente->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Encontrado el CLIENTE en el Sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			} else {
				$campos=$check_cliente->fetch();
			}

			$dni=mainModel::limpiar_cadena($_POST['cliente_dni_up']);
			$nombre=mainModel::limpiar_cadena($_POST['cliente_nombre_up']);
			$apellido=mainModel::limpiar_cadena($_POST['cliente_apellido_up']);
			$telefono=mainModel::limpiar_cadena($_POST['cliente_telefono_up']);
			$direccion=mainModel::limpiar_cadena($_POST['cliente_direccion_up']);

			/*== Comprobar Campos Vacios ==*/
			if($dni=="" || $nombre=="" || $apellido=="" || $telefono=="" || $direccion==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No has LLenado Todos los Campos que son Obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Verificando Integridad de los Datos ==*/
			if(mainModel::verificar_datos("[0-9-]{1,27}",$dni)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El DNI no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El NOMBRE no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El APELLIDO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El TELEFONO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"La DIRECCION no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Comprobar DNI ==*/
			if ($dni!=$campos['cliente_dni']) {
				$check_dni=mainModel::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");
				if ($check_dni->rowCount()>0) {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un Error Inesperado",
						"Texto"=>"El DNI Ingresado ya se Encuentra Registrado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}


			// Comprobando los Privilegios del Administrador
			session_start(['name'=>'SPM']);
			if ($_SESSION['privilegio_spm']<1 || $_SESSION['privilegio_spm']>2) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Tienes los Permisos Necesarios para Realizar esta Operacion",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$datos_cliente_up=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
				"ID"=>$id
			];

			if (clienteModelo::actualizar_cliente_modelo($datos_cliente_up)) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Cliente Actualizado",
					"Texto"=>"Los Datos del Cliente han Sido Actualizados con Exito",
					"Tipo"=>"success"
				];
			} else {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Podido Actualizar los Datos del Cliente por Favor Intente Nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}/* Fin Controlador */
	}