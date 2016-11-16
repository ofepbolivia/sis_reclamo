<?php
class RLibroReclamoPDF extends ReportePDF  {
    var $datos ;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;

    function Header(){

        $height = 5;
        //cabecera del reporte
        $this->Image(dirname(__FILE__) . '/../../lib' . $_SESSION['_DIR_LOGO'], 5, 8, 60, 15);
        $this->SetFont('times', 'BI', 20);
        $this->Write(0, 'LIBRO DE RECLAMOS', '', 0, 'C', true, 0, false, false, 0);
        $this->Write(0, 'ODECO - OPERADOR', '', 0, 'C', true, 0, false, false, 0);
        $this->ln(0,5);
        $this->SetFont('','B',10);
        $this->Cell(0,5,$this->objParam->getParametro('oficina'),0,1,'C');

        $this->Cell(130,$height,'Del : ' . $this->objParam->getParametro('fecha_ini'),0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Cell(110,$height,'Al : ' . $this->objParam->getParametro('fecha_fin'),0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->Ln(10);
        /////Haber

        $this->SetFont('','B',9);
        $conf_det_tablewidths=array(18,20,45,30,30,20,20,25,40);
        $conf_det_tablealigns=array('C','C','C','C','C','C','C','C','C');

        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;


        $RowArray = array(

            'FRD',
            'Preimpreso',
            'Nombre y Apellido',
            'Datos de Contacto',
            'Motivo de Reclamo',
            'Fecha de Incidente',
            'Fecha de Recepcion',
            'Fecha Envio Oficina Central',
            'Detalle de Reclamo'
        );


        $this-> MultiRow($RowArray,false,1);
        $this->reporterLibro();
    }

    function reporterLibro(){

        $conf_det_tablewidths=array(18,20,45,30,30,20,20,25,40);
        $conf_det_tablealigns=array('C','C','L','C','C','C','C','C','L');
        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;
        $this->SetFont('','',8);
        foreach ($this->datos as $Row) {

            $RowArray = array(
                'FRD' => $Row['nro_frd'],
                'Preimpreso'=> $Row['correlativo_preimpreso_frd'],
                'Nombre y Apellido' => $Row['nombre'],
                'Datos de Contacto' => $Row['celular']."\n".$Row['telefono'],
                'Motivo de Reclamo' => $Row['nombre_incidente']."\n".$Row['sub_incidente'],
                'Fecha de Incedenteo' => date("d-M-Y", strtotime($Row['fecha_hora_incidente'])) ,
                'Fecha de Recepcion' =>  date("d-M-Y", strtotime($Row['fecha_hora_recepcion'])),
                'Fecha Envio Oficina Central' =>  $Row['fecha_hora_recepcion_sac'],
                'Observaciones' => $Row['detalle_incidente'],


            );

            $this-> MultiRow($RowArray);

        }

    }

    function setDatos($datos) {
        $this->datos = $datos;
    }
    function generarReporte() {

        $this->setFontSubsetting(false);
        $this->AddPage();

    }
}
?>