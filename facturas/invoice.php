<?php
	$peticionAjax=true;

	require_once "../config/APP.php";

	$id=(isset($_GET['id'])) ? $_GET['id'] : 0;

	/*--------- Instancia al Controlador ---------*/
	require_once "../controladores/prestamoControlador.php";
	$ins_prestamo = new prestamoControlador();

	$datos_prestamo=$ins_prestamo->datos_prestamo_controlador("Unico",$id);

	if ($datos_prestamo->rowCount()==1) {
		$datos_prestamo=$datos_prestamo->fetch();

		/*--------- Instancia al Controlador Empresa ---------*/
		require_once "../controladores/empresaControlador.php";
		$ins_empresa = new empresaControlador();

		$datos_empresa=$ins_empresa->datos_empresa_controlador();
		$datos_empresa=$datos_empresa->fetch();


		/*--------- Instancia al Controlador Usuario ---------*/
		require_once "../controladores/usuarioControlador.php";
		$ins_usuario = new usuarioControlador();

		$datos_usuario=$ins_usuario->datos_usuario_controlador("Unico",$ins_usuario->encryption($datos_prestamo['usuario_id']));
		$datos_usuario=$datos_usuario->fetch();

		/*--------- Instancia al Controlador Cliente ---------*/
		require_once "../controladores/clienteControlador.php";
		$ins_cliente = new clienteControlador();

		$datos_cliente=$ins_cliente->datos_cliente_controlador("Unico",$ins_cliente->encryption($datos_prestamo['cliente_id']));
		$datos_cliente=$datos_cliente->fetch();

	require "./fpdf.php";

	$pdf = new FPDF('P','mm','Letter');
	$pdf->SetMargins(17,17,17);
	$pdf->AddPage();
	$pdf->Image('../vistas/assets/img/logo.png',10,10,30,30,'PNG');

	$pdf->SetFont('Arial','B',18);
	$pdf->SetTextColor(0,107,181);
	$pdf->Cell(0,10,utf8_decode(strtoupper($datos_empresa['empresa_nombre'])),0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(-35,10,utf8_decode('N. de remito'),'',0,'C');

	$pdf->Ln(10);

	$pdf->SetFont('Arial','',15);
	$pdf->SetTextColor(0,107,181);
	$pdf->Cell(0,10,utf8_decode(""),0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(-35,10,utf8_decode($datos_prestamo['prestamo_id']),'',0,'C');

	$pdf->Ln(25);

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(36,8,utf8_decode('Fecha de emisión:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(27,8,utf8_decode(date("d/m/Y", strtotime($datos_prestamo['prestamo_fecha_inicio']))),0,0);
	$pdf->Ln(8);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(27,8,utf8_decode('Atendido por:'),"",0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(13,8,utf8_decode($datos_usuario['usuario_nombre']." ".$datos_usuario['usuario_apellido']),0,0);

	$pdf->Ln(15);

	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,8,utf8_decode('Cliente:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(65,8,utf8_decode($datos_cliente['cliente_nombre']." ".$datos_cliente['cliente_apellido']),0,0);
	$pdf->SetTextColor(33,33,33);


	$pdf->Ln(8);

	$pdf->Cell(16,8,utf8_decode('Destino:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(109,8,utf8_decode($datos_prestamo['prestamo_destino']),0,0);

	$pdf->Ln(15);

	$pdf->SetFillColor(38,198,208);
	$pdf->SetDrawColor(38,198,208);
	$pdf->SetTextColor(33,33,33);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(15,10,utf8_decode('ID'),1,0,'C',true);
	$pdf->Cell(90,10,utf8_decode('Patrimonio - Descripción'),1,0,'C',true);

	$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

	$pdf->Ln(10);

	$pdf->SetTextColor(97,97,97);

	// DETALLES DEL PRÉSTAMO
	$datos_detalle=$ins_prestamo->datos_prestamo_controlador("Detalle",$ins_prestamo->encryption($datos_prestamo['prestamo_codigo']));
	$datos_detalle=$datos_detalle->fetchAll();

	$total=0;
	$entregado = "";
	$total_devueltos = 0;
	foreach ($datos_detalle as $items) {

		$subtotal=$items['detalle_cantidad']*($items['detalle_costo_tiempo']*$items['detalle_tiempo']);
        $subtotal=number_format($subtotal,2,'.','');

		$pdf->Cell(15,10,utf8_decode($items['item_id']),'L',0,'C');
		$pdf->Cell(90,10,utf8_decode($items['detalle_descripcion']),'L',0,'C');
		if($items['estado_item'] == 1){
				$entregado = "Devuelto";
				$total_devueltos = $total_devueltos + 1;
		}
		else {
			$entregado = utf8_decode($items['detalle_cantidad']);
		}
		$pdf->Cell(25,10, $entregado,'LR',0,'C');
		
		$pdf->Ln(10);

		$total+=($items['detalle_cantidad']);
	}

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,10,utf8_decode('Devueltos'),'LTB',0,'C');
	$pdf->Cell(65,10,$total_devueltos,'LTB',0,'C');
	$pdf->Cell(25,10,utf8_decode('TOTAL'),'LTB',0,'C');
	$pdf->Cell(25,10,utf8_decode(MONEDA.number_format($total)),'LRTB',0,'C');

	$pdf->Ln(15);

	$pdf->MultiCell(0,9,utf8_decode("OBSERVACIÓN: ".$datos_prestamo['prestamo_observacion']),0,'J',false);

	$pdf->SetFont('Arial','',12);



	$pdf->Ln(25);

	/*----------  INFO. EMPRESA  ----------*/
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(0,6,utf8_decode($datos_empresa['empresa_nombre']),0,0,'C');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(0,6,utf8_decode("Dirección: ".$datos_empresa['empresa_direccion']),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Teléfono: ".$datos_empresa['empresa_telefono']),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Correo: ".$datos_empresa['empresa_email']),0,0,'C');


	$pdf->Output("I","Factura_".$datos_prestamo['prestamo_id'].".pdf",true);

	} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo COMPANY; ?></title>
	<?php include "../vistas/inc/Link.php"; ?>
</head>
<body>
	<div class="full-box container-404">
		<div>
			<p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
			<h1 class="text-center">Ocurrió un Error</h1>
			<p class="lead text-center">No Hemos Encontrado el Remito Seleccionado</p>
		</div>
	</div>
	<?php include "../vistas/inc/Script.php"; ?>
</body>
</html>
<?php } ?>