<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDF.php';
//require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');

class RFrdFaltante extends  ReportePDF{
    var $datos ;
    function Header() {
        $this->Ln(10);

        $url_imagen = dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png';
        //var_dump($url_imagen);exit;
        $oficina = $this->datos[0]['nombre'];
        $html = <<<EOF
		<style>
		table, th, td {
   			border: 1px solid black;
   			border-collapse: collapse; 
   			font-family: "Calibri";
   			font-size: 11pt;
		}
		</style>
		<body>
		<table border="1">
        	<tr>
            	<td style="width: 20%" align="center"><img src="$url_imagen" ></td> 
            	<td style="width: 60%" align="center"><br><h3>FRDS - $oficina</h3></td> 
            	<td style="width: 20%" align="center"><img src="$url_imagen"></td> 
        	</tr>
        </table>
EOF;
        $this->writeHTML ($html);
    }

    function setDatos($datos) {
        $this->datos = $datos;
        //var_dump( $this->datos);exit;
    }

    function  generarReporte()
    {
        $this->AddPage();
        $this->SetMargins(15, 40, 15);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        $frds = explode(',',$this->datos[0]['frds']);
        asort($frds);
        //var_dump($frds);exit;
        $frd_faltantes = $this->datos[0]['frd_faltantes'];
        $frds = implode(',',$frds);

        $tbl = '<table>
                <tr>
                <td style="width: 10%"></td>
                <td style="width: 80%">
                <table cellspacing="0" cellpadding="1" border="2">
                    <thead>
                        <tr>
                            
                            <td style="font-family: Calibri; font-size: 18px;" align="center"><b> Lista de frds Registrados</b></td>
                            <td style="font-family: Calibri; font-size: 18px;" align="center"><b> Lista de frds Faltantes</b></td>
                        </tr>
                    </thead>
                    <tr>
                        
                        <td style="font-family: Calibri; font-size: 13px;"><br>'.$frds.'</td>
                        <td style="font-family: Calibri; font-size: 13px;"><br>'.$frd_faltantes.' </td>
                        
                     </tr>

                </table>
                </td>
                <td style="width:10%;"></td>
                </tr>
                </table>     
            ';
        $this->Ln(10);
        $this->writeHTML($tbl, true, false, false, false, '');

    }
}
?>