<?php

	if($peticionAjax){
		require_once "../modelos/usuarioModelo.php";
	}else{
		require_once "./modelos/usuarioModelo.php";
	}

	class usuarioControlador extends usuarioModelo{

		/*--------- Controlador Agregar Usuario ---------*/
		public function agregar_usuario_controlador(){
			$dni=mainModel::limpiar_cadena($_POST['usuario_dni_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
			$apellido=mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
			$direccion=mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);

			$usuario=mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
			$email=mainModel::limpiar_cadena($_POST['usuario_email_reg']);
			$clave1=mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
			$clave2=mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);


			$privilegio=mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);


			/*== comprobar campos vacios ==*/
			if($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $clave1=="" || $clave2==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No has LLenado Todos los Campos que son Obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[0-9-]{10,20}",$dni)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El DNI no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El NOMBRE no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El APELLIDO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($telefono!=""){
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
			}

			if($direccion!=""){
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un Error Inesperado",
						"Texto"=>"La DIRECCION no Coincide con el Formato Solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El NOMBRE DE USUARIO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave2)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"Las CLAVES no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Comprobando DNI ==*/
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");
			if ($check_dni->rowCount()>0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El DNI Ingresado ya se Encuentra Registrado en el Sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Comprobando Usuario ==*/
			$check_user=mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
			if ($check_user->rowCount()>0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El NOMBRE DE USUARIO Ingresado ya se Encuentra Registrado en el Sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Comprobando Email ==*/
			if ($email!="") {
				if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
					$check_email=mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
					if ($check_email->rowCount()>0) {
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un Error Inesperado",
							"Texto"=>"El EMAIL Ingresado ya se Encuentra Registrado en el Sistema",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}
				} else {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"Ha Ingresado un CORREO no Valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
				}
			}


			/*== Comprobando Claves ==*/
			if ($clave1!=$clave2) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"Las CLAVES que Acaba de Ingresar no Coinciden",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			} else {
				$clave=mainModel::encryption($clave1);
			}

			/*== Comprobando Privilegio ==*/
			if ($privilegio<1 || $privilegio>3) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El PRIVILEGIO Seleccionado no es Valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$datos_usuario_reg=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
				"Email"=>$email,
				"Usuario"=>$usuario,
				"Clave"=>$clave,
				"Estado"=>"Activa",
				"Privilegio"=>$privilegio
			];

			$agregar_usuario=usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

			if ($agregar_usuario->rowCount()==1) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Usuario Registrado",
					"Texto"=>"Los Datos del Usuario han Sido Registrados con Exito",
					"Tipo"=>"success"
				];
			} else {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Podido Registrar el Usuario",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} /* Fin Controlador */


		/*--------- Controlador para Paginar Usuario ---------*/
		public function paginador_usuario_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$id=mainModel::limpiar_cadena($id);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1 ;
			$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;

			if (isset($busqueda) && $busqueda!="") {
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id!='$id' AND usuario_id!='1') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_telefono LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";
			} else {
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id!='$id' AND usuario_id!='1' ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";
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
							<th>TELÉFONO</th>
							<th>USUARIO</th>
							<th>EMAIL</th>
							<th>ACTUALIZAR</th>
							<th>ELIMINAR</th>
						</tr>
					</thead>
				<tbody>';
			if ($total>=1 && $pagina<=$Npaginas) {
				$contador=$inicio+1;
				$reg_inicio=$inicio+1;
				foreach ($datos as $rows) {
					$tabla.='
					<tr class="text-center" >
						<td>'.$contador.'</td>
						<td>'.$rows['usuario_dni'].'</td>
						<td>'.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'</td>
						<td>'.$rows['usuario_telefono'].'</td>
						<td>'.$rows['usuario_usuario'].'</td>
						<td>'.$rows['usuario_email'].'</td>
						<td>
							<a href="'.SERVERURL.'user-update/'.mainModel::encryption($rows['usuario_id']).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>
							</a>
						</td>
						<td>
							<form class="FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
							<input type="hidden" name="usuario_id_del" value="'.mainModel::encryption($rows['usuario_id']).'">
								<button type="submit" class="btn btn-warning">
										<i class="far fa-trash-alt"></i>
								</button>
							</form>
						</td>
					</tr>';
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
				$tabla.='<p class="text-right">Mostrando Usuario de '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';

				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}

			return $tabla;
		}/* Fin Controlador */


		/*--------- Controlador para Eliminar Usuario ---------*/
		public function eliminar_usuario_controlador(){

			/* Recibiendo ID del Usuario */
			$id=mainModel::decryption($_POST['usuario_id_del']);
			$id=mainModel::limpiar_cadena($id);

			/* Comprobando el Usuario Principal */
			if ($id==1) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Podemos Eliminar el Usuario Principal del Sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			/* Comprobando el Usuario en la BD */
			$check_usuario=mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_id='$id'");
			if ($check_usuario->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El Usuario que Intenta Eliminar no Existe en el Sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/* Comprobando los Prestamos */
			$check_prestamos=mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM prestamos WHERE usuario_id='$id' LIMIT 1");
			if ($check_prestamos->rowCount()>0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Podemos Elimninar este Usuario Debido a que tiene Prestamos Asociados, Recomendamos Desabilitar el Usuario si ya no Será Utilizado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/* Comprobando Privilegios */
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


			$eliminar_usuario=usuarioModelo::eliminar_usuario_modelo($id);
			if ($eliminar_usuario->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Usuario Eliminado",
					"Texto"=>"El Usuario ha Sido Eliminado del Sistema Exitosamente",
					"Tipo"=>"success"
				];
			} else {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Podido Eliminar el Usuario, por Davor Intente Nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		}/* Fin Controlador */


		/*--------- Controlador Datos del Usuario ---------*/
		public function datos_usuario_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);

			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return usuarioModelo::datos_usuario_modelo($tipo,$id);
		}/* Fin Controlador */


		/*--------- Controlador Actualizar Usuario ---------*/
		public function actualizar_usuario_controlador(){

			// Recibiendo el ID
			$id=mainModel::decryption($_POST['usuario_id_up']);
			$id=mainModel::limpiar_cadena($id);

			// Comprobar el Usuario en la BD
			$check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM usuario WHERE usuario_id='$id'");
			if ($check_user->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Encontrado el Usuario en el Sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			} else {
				$campos=$check_user->fetch();
			}

			$dni=mainModel::limpiar_cadena($_POST['usuario_dni_up']);
			$nombre=mainModel::limpiar_cadena($_POST['usuario_nombre_up']);
			$apellido=mainModel::limpiar_cadena($_POST['usuario_apellido_up']);
			$telefono=mainModel::limpiar_cadena($_POST['usuario_telefono_up']);
			$direccion=mainModel::limpiar_cadena($_POST['usuario_direccion_up']);

			$usuario=mainModel::limpiar_cadena($_POST['usuario_usuario_up']);
			$email=mainModel::limpiar_cadena($_POST['usuario_email_up']);

			if (isset($_POST['usuario_estado_up'])) {
				$estado=mainModel::limpiar_cadena($_POST['usuario_estado_up']);
			} else {
				$estado=$campos['usuario_estado'];
			}

			if (isset($_POST['usuario_privilegio_up'])) {
				$privilegio=mainModel::limpiar_cadena($_POST['usuario_privilegio_up']);
			} else {
				$privilegio=$campos['usuario_privilegio'];
			}

			$admin_usuario=mainModel::limpiar_cadena($_POST['usuario_admin']);

			$admin_clave=mainModel::limpiar_cadena($_POST['clave_admin']);

			$tipo_cuenta=mainModel::limpiar_cadena($_POST['tipo_cuenta']);

			/*== comprobar campos vacios ==*/
			if($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $admin_usuario=="" || $admin_clave==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No has LLenado Todos los Campos que son Obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[0-9-]{10,20}",$dni)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El DNI no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El NOMBRE no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El APELLIDO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($telefono!=""){
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
			}

			if($direccion!=""){
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un Error Inesperado",
						"Texto"=>"La DIRECCION no Coincide con el Formato Solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El NOMBRE DE USUARIO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$admin_usuario)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"Tu NOMBRE DE USUARIO no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"Tu CLAVE no Coincide con el Formato Solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$admin_clave=mainModel::encryption($admin_clave);

			if ($privilegio<1 || $privilegio>3) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El PRIVILEGIO no Corresponde a un Valor Valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if ($estado!="Activa" && $estado!="Deshabilitada") {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"El ESTADO de la Cuenta no Coincice con el Formato",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Comprobando DNI ==*/
			if ($dni!=$campos['usuario_dni']) {
				$check_dni=mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");
				if ($check_dni->rowCount()>0) {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un Error Inesperado",
						"Texto"=>"El DNI Ingresado ya se Encuentra Registrado en el Sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			/*== Comprobando Usuario ==*/
			if ($usuario!=$campos['usuario_usuario']) {
				$check_user=mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
				if ($check_user->rowCount()>0) {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un Error Inesperado",
						"Texto"=>"El NOMBRE DE USUARIO Ingresado ya se Encuentra Registrado en el Sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}


			/*== Comprobando Email ==*/
			if ($email!=$campos['usuario_email'] && $email!="") {
 				if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
 					$check_email=mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
 					if ($check_email->rowCount()>0) {
 						$alerta=[
 							"Alerta"=>"simple",
 							"Titulo"=>"Ocurrió un Error Inesperado",
 							"Texto"=>"El Nuevo EMAIL Ingresado ya se Encuentra Registrado en el Sistema",
 							"Tipo"=>"error"
 						];
 						echo json_encode($alerta);
 						exit();
 					}
 				} else {
 					$alerta=[
 						"Alerta"=>"simple",
 						"Titulo"=>"Ocurrió un Error Inesperado",
 						"Texto"=>"Ha Ingresado un CORREO no Valido",
 						"Tipo"=>"error"
 					];
 					echo json_encode($alerta);
 					exit();
 				}
			}

			/*== Comprobando Claves ==*/
			if ($_POST['usuario_clave_nueva_1']!="" || $_POST['usuario_clave_nueva_2']!="") {
				if ($_POST['usuario_clave_nueva_1']!=$_POST['usuario_clave_nueva_2']) {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un Error Inesperado",
						"Texto"=>"Las Nuevas CLAVES Ingresadas no Coinciden",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				} else {
					if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_1']) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_2'])) {
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un Error Inesperado",
							"Texto"=>"Las Nuevas CLAVES no Coinciden con el Formato Solicitado",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}
					$clave=mainModel::encryption($_POST['usuario_clave_nueva_1']);
				}
			} else {
				$clave=$campos['usuario_clave'];
			}


			/*== Comprobando Credenciales para Actualizar Datos ==*/
			if ($tipo_cuenta=="Propia") {
				$check_cuenta=mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_clave='$admin_clave' AND usuario_id='$id'");
			} else {
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
				$check_cuenta=mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_clave='$admin_clave'");
			}

			if ($check_cuenta->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"Nombre y Clave de Administrador No Validos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Preparando Datos para Enviarlos al Modelo ==*/
			$datos_usuario_up=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
				"Email"=>$email,
				"Usuario"=>$usuario,
				"Clave"=>$clave,
				"Estado"=>$estado,
				"Privilegio"=>$privilegio,
				"ID"=>$id
			];

			if (usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Datos Actualizados",
					"Texto"=>"Los Datos han Sido Actualizados con Exito",
					"Tipo"=>"success"
				];
			} else {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un Error Inesperado",
					"Texto"=>"No Hemos Podido Actualizar los Datos, por Favor Intente Nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}/* Fin Controlador */
	}