<?php

class RLibroRespuestaPDF extends ReportePDF  {
    var $datos ;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;

    function Header(){

        $height = 5;
        $height2 = 40;
        $this->Image(dirname(__FILE__) . '/../../lib' . $_SESSION['_DIR_LOGO'], 5, 8, 60, 15);
        $this->SetFont('times', 'B', 20);
        $this->Write(0, 'LIBRO DE RESPUESTAS', '', 0, 'C', true, 0, false, false, 0);
        $this->Write(0, 'OFICINAS - SAC', '', 0, 'C', true, 0, false, false, 0);
        //$this->ln(0,5);
        $this->SetFont('','B',10);
        //$this->Cell(0,$height,$this->objParam->getParametro('oficina'),0,1,'C');
        //$this->Cell(130,5,'Del : ' . $this->objParam->getParametro('fecha_ini'),0, 0, 'C', false, '', 0, false, 'T', 'C');
        //$this->Cell(110,5,'Al : ' . $this->objParam->getParametro('fecha_fin'),0, 0, 'C', false, '', 0, false, 'T', 'C');
        //$this->Ln();

    }

    function setDatos($datos) {
        $this->datos = $datos;
    }
    function generarReporte() {


        $this->SetMargins(55,25,20);
        $this->setFontSubsetting(false);
        $this->AddPage();
        //$this->Ln(10);
        $this->SetFont('','B',9);

        $conf_det_tablewidths=array(20,20,30,30,30,40);
        $conf_det_tablealigns=array('C','C','C','C','C','C');

        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;


        $RowArray = array(

            'Fecha',
            'OB-CC-N',
            'Asunto',
            'Ciudad',
            'Oficina',
            'Destinatario'
        );
        $this-> MultiRow($RowArray,false,1);
        $this->SetFont('','',8);
        $conf_det_tablewidths=array(20,20,30,30,30,40);
        $conf_det_tablealigns=array('C','C','L','C','C','C');
        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;
        //echo $this->datos;exit;
        foreach ($this->datos as $Row) {

            $RowArray = array(
                'Fecha' => $Row['fecha'],
                'OB-CC-N'=> $Row['correlativo'],
                'Asunto' => $Row['tipo'].'/'.$Row['subtipo'],
                'Ciudad' => $Row['oficina'],
                'Oficina' => $Row['oficina'],
                'Destinatario' => $Row['cliente']


            );

            $this-> MultiRow($RowArray);

        }
        //$this->Ln(10);
    }
}
?>