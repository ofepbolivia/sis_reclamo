<?php
class RReclamoPDF extends  ReportePDF{
    var $datos;
    var $ancho_hoja;
    function Header() {
        $height = 20;
        //cabecera del reporte
        $this->Image(dirname(__FILE__).'/../../lib'.$_SESSION['_DIR_LOGO'], 245, 8, 30, 12);
        $this->SetFont('','B',12);
        $this->Cell(105, $height, 'INFORME DE RECLAMO', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        //Titulos de columnas superiores
        $this->Cell(40,3.5,'R','LTR',0,'C');
        $this->ln();

    }

}






























?>