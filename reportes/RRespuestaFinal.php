<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDF.php';

class RRespuestaFinal extends ReportePDF
{
    var $datos;
    var $datos2;

    function setDatos($datos,$datos2) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
    }

    function Header() {
        // get the current page break margin
        //$bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        //$auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        //$this->SetAutoPageBreak(false, 0);
        // set bacground image

        $this->SetHeaderMargin(10);
        //var_dump(K_PATH_IMAGES);exit;
        //$img_file = K_PATH_IMAGES.'MEMBRETE BOA.jpg';

        $img_file = dirname(__FILE__).'/../media/direcciones.jpg';
        $img_agua = dirname(__FILE__).'/../media/marcaAgua.jpg';

        //$this->Image($img_file, 0, 0, 250, 260, '', '', '', false, 300, '', false, false, 0);
        $this->Image($img_file, 7, 2, 52, 260, '', '', '', false, 300, '', false, false, 0);

        $this->Image($img_agua, 130, 150, 80, 80, '', '', '', false, 300, '', false, false, 0);
        $this->SetFont('helvetica','B',11);
        $this->Cell(190, 25, 'Cochabamba, '.$this->datos[0]['fecha_respuesta'], '', 0, 'R');
        $this->Ln(7);
        $this->SetFont('helvetica','B',11);
        $this->Cell(190, 25, $this->datos[0]['num_cite'], '', 0, 'R');
        $this->Ln(1);
        // restore auto-page-break status
        //Set auto page breaks

        // set the starting point for the page content
        //$this->setPageMark();
        //$this->generarRespuesta();

    }


    function generarReporte(){
        $this->AddPage();
        $this->SetMargins(52, 35, 10);
        $this->SetHeaderMargin(10);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



        $height = 25;
        $width3 = 185;
        $width4 = 75;
        $this->SetFont('helvetica','B',11);
        /*$this->Cell($width3+5, $height, 'Cochabamba, '.$this->datos[0]['fecha_respuesta'], '', 0, 'R');
        $this->Ln(10);
        $this->SetFont('helvetica','B',11);
        $this->Cell($width3-37, $height, $this->datos[0]['num_cite'], '', 0, 'R');*/
        $this->Ln(1);
        $this->SetFont('helvetica','',9);
        $this->Cell($width3+50, $height, $this->datos[0]['genero'], 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln(4.5);
        $this->SetFont('helvetica','B',9);
        $this->Cell($width3+50, $height, strtoupper ($this->datos[0]['nombre_completo1']), 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln(4.5);
        $this->SetFont('helvetica','U',9);
        $this->Cell($width3+50, $height, 'Presente .-', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln(19);
        $this->SetFont('helvetica','B',9);
        $this->Cell($width3-170, $height, 'Asunto: ', 0, 0, false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','BU',9);
        $this->Cell(55, 0.5,$this->datos[0]['asunto'] , 0, 2, false, '', 0, false, 'T', 'C');
        $this->ln(5);
        $this->SetFont('helvetica','',9);
        $this->MultiCell(55, 0.5,'De nuestra consideración:' , 0, 'L', 0, 1, '', '', true);
        $this->ln(4);
        $this->SetFont('helvetica','',9);


        $this->writeHTML($this->datos[0]['respuesta'], true, 0, true, true);
        $this->ln();

        /*$nro_Cite = $this->datos2[0]['num_cite'];
        $nro_frd = $this->datos2[0]['nro_frd'];
        $oficina = $this->datos2[0]['oficina'];
        $ini_rep = $this->datos2[0]['iniciales_fun_reg'];
        $ini_vis = $this->datos2[0]['iniciales_fun_vis'];

        $html = 'Numero Cite: '.$nro_Cite."\n".'Numero FRD: '.$nro_frd."\n".'Lugar de Reclamo: '.$oficina."\n".'Elaborado por: '.$ini_rep."\n".'Revisado por: '.$ini_vis;*/



        // set style for barcode
        /*$style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );*/

        //$this->write2DBarcode($html, 'QRCODE,L',115, $this->GetY(), 20, 20, $style, 'N');

        $this->Text(100, $this->GetY()+10, 'Atención al Cliente BoA');


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

    public function Footer() {
        $this->SetMargins(50, 35, 25);
        //$this->SetHeaderMargin(10);
        $height = 5;
        $width4 = 75;
        $this->SetY(-24);

        $nro_Cite = $this->datos2[0]['num_cite'];
        $nro_frd = $this->datos2[0]['nro_frd'];
        $oficina = $this->datos2[0]['oficina'];
        $ini_rep = $this->datos2[0]['iniciales_fun_reg'];
        $ini_vis = $this->datos2[0]['iniciales_fun_vis'];
        $fecha_envio = $this->obtenerFechaEnLetra(date("j-n-Y"));

        $html = 'Numero Cite: '.$nro_Cite."\n".'Numero FRD: '.$nro_frd."\n".'Lugar de Reclamo: '.$oficina."\n".'Elaborado por: '.$ini_rep."\n".'VoBo por: '.$ini_vis. "\nFecha de Envio de Respuesta: ".$fecha_envio;
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $this->write2DBarcode($html, 'QRCODE,L', 11, 230, 25, 25, $style, 'N');
        
        if($this->datos[0]["prodedente"] != 'ninguno' && $this->datos[0]["tipo_respuesta"] == 'respuesta_final') {
            //var_dump('a');exit;
            $tex = '"En cumplimiento al Reglamento para la Atención de Reclamaciones Directas de Usuarios de los Servicios Aeronáuticos, su reclamación es considerada';
            $tex2 = 'Artículo 59º del Decreto Supremo Nº 27172, si usted no está conforme con la respuesta obtenida, tiene derecho a presentar una Reclamación Administrativa ante la Autoridad de Regulación y Fiscalización de Telecomunicación y Transporte en el plazo de 15 días hábiles, a ser computables a partir de la recepción de la respuesta."';
            $procedente = $this->datos[0]["prodedente"];
        }else if($this->datos[0]["prodedente"] == 'ninguno'){
            //var_dump('b');exit;
            $tex = '';
            $tex2 = '';
            $procedente = '';
        }else if($this->datos[0]["tipo_respuesta"] == 'respuesta_parcial'){
            //var_dump('c');exit;
            $tex = '';
            $tex2 = 'Artículo 59º del Decreto Supremo Nº 27172, si usted no está conforme con la respuesta obtenida, tiene derecho a presentar una Reclamación Administrativa ante la Autoridad de Regulación y Fiscalización de Telecomunicación y Transporte en el plazo de 15 días hábiles, a ser computables a partir de la recepción de la respuesta."';
            $procedente = '';
        }
            $this->SetFont('helvetica', 'I', 7);
        $this->MultiCell($width4*2, $height, $tex."\t".$procedente.'.'."\n".$tex2."\n",'J', 0, '' ,'');
    }


}
?>