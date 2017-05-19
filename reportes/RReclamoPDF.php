<?php
class RReclamoPDF extends  ReportePDF{
    var $datos ;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;
    function Header() {
        $height = 30;

        //cabecera del reporte
        $this->Image(dirname(__FILE__).'/../../lib'.$_SESSION['_DIR_LOGO'], 5, 8, 60, 15);
        $this->Cell(40, $height, '', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(16);
        $this->SetFont('','B');
        $this->Cell(105, $height, 'DATOS DEL RECLAMO', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->ln();

    }



    function  reporteReclamo()
    {  // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->AddPage('tamano','LETTER');
        //$this->SetHeaderMargin(10);
        $this->SetMargins(15, 25, 15,true);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        $height = 5;
        $width2 = 5;
        $width3 = 46;
        $width4 = 93;

        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->setTextColor(0,0,0);
        $this->Cell($width3+20, $height, 'FECHA DEL RECLAMO', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Cell($width3, $height, 'N° DE PREIMPRESO FRD', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell($width2+30, $height, 'N° FRD', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Cell(35, $height, 'N° DE TRAMITE', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Ln();
        $this->SetFontSize(9);
        $this->SetFont('', '');
        $this->Cell($width3+20, $height, $this->datos[0]["fecha_reg"], 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Cell($width3-10, $height, $this->datos[0]["correlativo_preimpreso_frd"], 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Cell($width2+50, $height, $this->datos[0]["nro_frd"], 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Cell($width2+11, $height, $this->datos[0]["nro_tramite"], 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Ln();
        $white = array('LTRB' =>array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 255, 255)));

        $this->SetFontSize(11);
        $this->SetFont('', 'B');
        $this->Cell($width3, 10 , 'DATOS DEL CLIENTE', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->ln();
        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'NOMBRES:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["nombre_cliente"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();
        $this->Cell($width3+25, $height, 'APELLIDOS:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["apellidos"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'DOCUMENTO DE IDENTIDAD:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2, $height, $this->datos[0]["ci"]." ".$this->datos[0]["lugar_expedicion"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();
        $this->Cell($width3+25, $height, 'TELEFONO/CELULAR:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["celular"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'CORREO ELECTRÓNICO:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["email"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();
        $this->Cell($width3+25, $height, 'DIRECCIÓN:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["direccion"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'PAÍS:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["pais"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'CIUDAD:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["ciudad"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();

        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'ZONA:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["barrio_zona"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();

        $this->SetFontSize(11);
        $this->SetFont('', 'B');
        $this->cell($width3, 10 , 'DATOS DEL SERVICIO QUE ORIGINA EL RECLAMO', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->ln();

        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'ORIGEN:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["origen"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'DESTINO:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["destino"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'NÚMERO DE VUELO:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["nro_vuelo"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'FECHA Y HORA DE VUELO:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["fecha_hora_vuelo"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();


        $this->SetFontSize(11);
        $this->SetFont('', 'B');
        $this->Cell($width3, 10 , 'DATOS DEL INCIDENTE', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->ln();

        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'TIPO DE INCIDENTE:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["desc_incidente"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();
        $this->Cell($width3+25, $height, 'SUBTIPO DE INCIDENTE:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["desc_sudnom_incidente"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'OFICINA INCIDENTE:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["desc_oficina"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'FECHA Y HORA DE INCIDENTE:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0][" fecha_hora_incidente"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();

        $this->SetFont('', 'B',11);
        $this->Cell($width3, 10 , 'DETALLE INCIDENTE', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        //$this->writeHTML('<p>&nbsp;DETALLE INCIDENTE</p>',true);
        $this->Ln();
        $this->SetFont('', '',9);
        $this->MultiCell($width4*2, $height, $this->datos[0]["detalle_incidente"]."\n",'l', 0, '' ,'');
        //$this->writeHTML('<p style="text-align: justify; ">&nbsp;'.$this->datos[0]['detalle_incidente'].'</p>',true);
        $this->Ln();
        $this->SetFont('', 'B',11);
        $this->Cell($width3, 10 , 'OBSERVACIONES INCIDENTE', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        //$this->writeHTML('<p>&nbsp;OBSERVACIONES INCIDENTE</p>',true);
        $this->Ln();
        $this->SetFont('', '',9);
        $this->MultiCell($width4*2, $height, $this->datos[0]["observaciones_incidente"]."\n",'l', 0, '' ,'');
        //$this->writeHTML('<p style="text-align: justify; ">'.$this->datos[0]['observaciones_incidente'].'</p>',true);
        $this->Ln();
        $this->SetFont('', 'B',11);
        $this->Cell($width3, 10 , 'DATOS DE RECEPCIÓN', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        //$this->writeHTML('<p>&nbsp;DATOS DE RECEPCIÓN</p>',true);
        $this->Ln();
        $this->SetFont('', 'B',9);
        $this->Cell($width3+25, $height, 'OFICINA RECLAMO:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->SetFont('', '');
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["desc_oficina_registro_incidente"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();

        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'FECHA Y HORA DE RECEPCIÓN:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["fecha_hora_recepcion"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();

        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'FUNCIONARIO QUE RECIBE RECLAMO:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["desc_nombre_funcionario"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();

        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->Cell($width3+25, $height, 'MEDIO RECLAMO:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->Cell($width3+$width2-10, $height, $this->datos[0]["desc_nombre_medio"], $white, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', 'B');
        $this->ln();



    }

    function setDatos($datos) {
        $this->datos = $datos;

    }
    function generarReporte() {
        $this->setFontSubsetting(false);
        $this->reporteReclamo();


    }

}






























?>