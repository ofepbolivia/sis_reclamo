<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDF.php';

class RConstanciaEnvioPDF extends ReportePDF
{
    var $datos;
    var $datos2;

    function setDatos($datos,$datos2) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
		//var_dump($this->datos);exit;
    }

    function Header() {
        $height = 30;
        //cabecera del reporte
        //$this->Image(dirname(__FILE__).'/../../lib'.$_SESSION['_DIR_LOGO'], 5, 8, 60, 15);
        $this->Cell(40, $height, '', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(16);
        $this->SetFont('','B');
        $this->Cell(105, $height, 'CONSTANCIA DE ENVÍO NOTIFICACIÓN DE RESPUESTA', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Ln();
    }


    function generarReporte(){
		$this->AddPage();
        //$this->SetHeaderMargin(10);
        $this->SetMargins(15, 25, 15,true);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	    $height = 5;
        $width2 = 2;
        $width3 = 1;

        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->ln(18);
        $this->SetFont('', 'B',9);
        $this->Cell($width3+20, $height, 'De: ', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2, $height, 'Sistema ERP BOA', $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();
        $this->Cell($width3+20, $height, 'Enviado el: ', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2, $height, $this->datos[0]['fecha_respuesta'], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFont('', 'B',9);
        $this->Cell($width3+20, $height, 'Para: ', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2, $height, $this->datos[0]['email'], $white, 0, 'L', false, '', 0, false, 'T', 'C');        
        $this->ln();        
        $this->SetFont('', 'B');
        $this->Cell($width3+20, $height, 'CC: ', 0, 0, 'L', false, '', 0, false, 'T', 'C');		
        $this->SetFont('', '');
        $this->Cell($width3+$width2, $height, 'sac@boa.bo', $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();
        $this->Cell($width3+20, $height, 'Asunto', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2, $height, $this->datos[0]["titulo_correo"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln(12);
		$this->SetFont('helvetica','',16);		
		$this->Cell(55, 0.5,$this->datos[0]['titulo_correo'] , 0, 2, false, '', 0, false, 'T', 'C');
	    $this->ln(4);
        $this->SetFont('helvetica','',9);
        $this->writeHTML($this->datos[0]['descripcion'], true, 0, true, true);
		$this->ln();		
        //$this->Text(100, $this->GetY()+10, 'Atención al Cliente BoA');


    }

    function obtenerFechaEnLetra($fecha){
        $dia= $this->conocerDiaSemanaFecha($fecha);
        $num = date("j", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $mes = $mes[(date('m', strtotime($fecha))*1)-1];
        return $dia.', '.$num.' de '.$mes.' del '.$anno;
    }

    function conocerDiaSemanaFecha($fecha) {
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
        $dia = $dias[date('w', strtotime($fecha))];
        return $dia;
    }



}
?>