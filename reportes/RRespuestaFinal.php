<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDFFormulario.php';

class RRespuestaFinal extends ReportePDFFormulario
{
    var $datos;
    var $datos2;

    function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = K_PATH_IMAGES.'MEMBRETE BOA.jpg';
        $this->Image($img_file, 0, 0, 210, 260, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
        $this->generarRespuesta();

    }


    function generarRespuesta()
    {
        $this->SetMargins(52, 35, 25);
        $this->SetHeaderMargin(10);

        $height = 25;
        $width3 = 185;
        $width4 = 75;
        $this->SetFont('helvetica','B',11);
        $this->Cell($width3+5, $height, 'Cochabamba, '.$this->datos[0]['fecha_respuesta'], '', 0, 'R');
        $this->Ln(10);
        $this->SetFont('helvetica','B',11);
        $this->Cell($width3-37, $height, $this->datos[0]['num_cite'], '', 0, 'R');
        $this->Ln(18);
        $this->SetFont('helvetica','',11);
        $this->Cell($width3+50, $height, $this->datos[0]['genero'], 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln(4.5);
        $this->SetFont('helvetica','B',11);
        $this->Cell($width3+50, $height, $this->datos[0]['nombre_completo1'], 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln(4.5);
        $this->SetFont('helvetica','U',11);
        $this->Cell($width3+50, $height, 'Presente', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln(19);
        $this->SetFont('helvetica','B',11);
        $this->MultiCell($width3-170, $height, 'Asunto: ', 0, 'L', 0, 0, '', '', true);
        $this->SetFont('helvetica','BU',11);
        $this->MultiCell(55, 0.5,$this->datos[0]['asunto'] , 0, 'L', 0, 1, '', '', true);
        $this->ln();
        $this->SetFont('helvetica','',11);
        $this->MultiCell(55, 0.5,'De nuestra consideración:' , 0, 'L', 0, 1, '', '', true);
        $this->ln(4);
        $this->SetFont('helvetica','',11);
        $this->MultiCell($width4*2, $height, $this->datos[0]["respuesta"]."\n",'J', 0, '' ,'');
        $this->ln();

        $nro_Cite = $this->datos2[0]['num_cite'];
        $nro_frd = $this->datos2[0]['nro_frd'];
        $oficina = $this->datos2[0]['oficina'];
        $ini_rep = $this->datos2[0]['iniciales_fun_reg'];
        $ini_vis = $this->datos2[0]['iniciales_fun_vis'];

        $html = 'Numero Cite: '.$nro_Cite."\n".'Numero FRD: '.$nro_frd."\n".'Lugar de Reclamo: '.$oficina."\n".'Elaborado por: '.$ini_rep."\n".'Revisado por: '.$ini_vis;



        // set style for barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $this->write2DBarcode($html, 'QRCODE,L',110, 224, 25, 25, $style, 'N');
        $this->Text(100, 250, 'Atencion al Cliente BoA');

    }
    public function Footer() {
        $this->SetMargins(55, 35, 25);
        $this->SetHeaderMargin(10);
        $height = 25;
        $width4 = 75;
        $this->SetY(-24);
        $tex = '"En cumplimiento al Reglamento para la Atención de Directas de Usuarios de los Servicios Aeronáuticos, su reclamación es considerada';
        $tex2 ='Artículo 59º del Decreto Supremo Nº 27172, si usted no está conforme con la respuesta obtenida, tiene derecho a presentar una Reclamación Administrativa ante la Autoridad de Regulación y Fiscalización de Telecomunicación y Transporte en el plazo de 15 días hábiles, a ser computables a partir de la recepción de la respuesta."';
        $this->SetFont('helvetica', 'I', 7);
        $this->MultiCell($width4*2, $height, $tex."\n".$this->datos[0]["prodedente"].'.'."\n".$tex2."\n",'J', 0, '' ,'');
    }
    function setDatos($datos,$datos2) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
    }

    function generarReporte() {
        $this->setFontSubsetting(false);


    }
}
?>