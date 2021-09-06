<?php

//include_once (dirname(__FILE__).'/../../../kerp_capacitacion/lib/phpdocx/Classes/Phpdocx/Create/CreateDocx.inc');
include_once (dirname(__FILE__).'/../../pxp/lib/phpdocx/Classes/Phpdocx/Create/CreateDocx.inc');
//winclude_once (dirname(__FILE__).'/../../../kerp_capacitacion/lib/phpdocx/Classes/Phpdocx/Transform/TransformDoc.inc');


Class RReporteGrafico{

    private $dataSource;

    public function datosHeader( $dataSource ) {
        $this->dataSource = $dataSource;
        //var_dump($this->dataSource);exit;
    }

    function write($fileName){
         //var_dump(gettype(intval($this->dataSource['v_hombres'])));exit;
        //DESDE AQUI FUNCIONA
        $docx = new Phpdocx\Create\CreateDocx();
        $docx->setLanguage('es-ES');

        $data = array(
            'Hombres' => array(intval($this->dataSource['v_hombres'])),
            'Mujeres' => array(intval($this->dataSource['v_mujeres'])),
            'No Especifica' => array(intval($this->dataSource['v_noEspecifica']))
        );

        $paramsChart = array(
            'data' => $data,
            'type' => 'pie3DChart',
            'title'=>'Reporte Reclamos segun Genero',
            'rotX' => 20,
            'rotY' => 20,
            'perspective' => 30,
            'color' => 2,
            'sizeX' => 10,
            'sizeY' => 5,
            'chartAlign' => 'center',
            'showPercent' => 1,
            'border' => 1
        );
        /*$legends = array(
            '0' => array( 'sequence 1', 'sequence 2', 'sequence 3'),
            'legend1' => array( intval($this->dataSource['v_hombres'])),
            'legend2' => array( intval($this->dataSource['v_mujeres']))
        );
        $paramsChart = array(
            'data' => $legends,
            'type' => 'pie3DChart',
            'title' => 'Tabla Genero',
            'cornerX' => 20, 'cornerY' => 20, 'cornerP' => 30,
            'color' => 2,
            'textWrap' => 0,
            'sizeX' => 10, 'sizeY' => 10,
            'jc' => 'left',
            'showPercent' => 1,
            'font' => 'Times New Roman'
        );*/

        $docx->addChart($paramsChart);

        $data = array(
            'legend' => array('No Especifica', 'Mujeres', 'Hombres'),
            'Cantidad' => array(intval($this->dataSource['v_noEspecifica']), intval($this->dataSource['v_mujeres']), intval($this->dataSource['v_hombres'])),
            'Porcentaje' => array(100, 100, 100),

        );
        $paramsChart = array(
            'data' => $data,
            'type' => 'col3DChart',
            'color' => '2',
            'perspective' => '40',
            'rotX' => '30',
            'rotY' => '30',
            'chartAlign' => 'center',
            'showtable' => 1,
            'sizeX' => '10',
            'sizeY' => '10',
            'legendPos' => 't',
            'legendOverlay' => '0',
            'border' => '1',
            'hgrid' => '0',
            'vgrid' => '0',
            'groupBar' => 'percentStacked'
        );

        $docx->addChart($paramsChart);

        $legends = array(
            'legend1' => array( 10, 11, 12),
             'legend2' => array( 0, 1, 2),
             'legend3' => array( 40, 41, 42)
        );
        $args = array(
             'data' => $legends,
             'type' => 'pie3DChart',
             'title' => 'Genero',
             'cornerX' => 20, 'cornerY' => 20, 'cornerP' => 30,
             'color' => 2,
             'textWrap' => 0,
             'sizeX' => 10, 'sizeY' => 10,
             'jc' => 'left',
             'showPercent' => 1,
             'font' => 'Times New Roman'
        );
        $docx-> addChart($args);

        /*$docx = new Phpdocx\Create\CreateDocxFromTemplate(dirname(__FILE__).'/plantilla_resp.docx');
        $docx->setLanguage('es-ES');


        $docx->replaceVariableByText(array('FECHA' => 'Cochabamba, '.$this->dataSource[0]['fecha_respuesta']), array('firstMatch'=>true, 'parseLineBreaks'=>false, 'target'=>'header','raw'=>false));
        $docx->replaceVariableByText(array('NUM_CITE' => $this->dataSource[0]['num_cite']), array('firstMatch'=>true, 'parseLineBreaks'=>false, 'target'=>'header','raw'=>false));

        $docx->replaceVariableByText(array('GENERO' => $this->dataSource[0]['genero']));

        $docx->replaceVariableByText(array('NOMBRE_CLIENTE' => strtoupper ($this->dataSource[0]['nombre_completo1'])));
        $docx->replaceVariableByText(array('REFERENCIA' => $this->dataSource[0]['asunto']));

        $docx->replaceVariableByHTML('RESPUESTA', 'block', '<body>'.$this->dataSource[0]['respuesta'].'</body>', array('isFile' => false, 'parseDivsAsPs' => true, 'downloadImages' => false));

        $docx->replaceVariableByText(array('PROCE' => $this->dataSource[0]['prodedente']), array('firstMatch'=>true, 'parseLineBreaks'=>false, 'target'=>'footer','raw'=>false));

        if ($this->dataSource[0]['prodedente'] == 'procedente' || $this->dataSource[0]['prodedente'] == 'improcedente') {
            $docx->replaceVariableByText(array('ART'=> $text2), array('firstMatch'=>true, 'parseLineBreaks'=>false, 'target'=>'footer','raw'=>false));
            $docx->replaceVariableByText(array('ES'=> $text1), array('firstMatch'=>true, 'parseLineBreaks'=>false, 'target'=>'footer','raw'=>false));
        }else{
            $docx->replaceVariableByText(array('ART'=> ''), array('firstMatch'=>true, 'parseLineBreaks'=>false, 'target'=>'footer','raw'=>false));
            $docx->replaceVariableByText(array('ES'=> ''), array('firstMatch'=>true, 'parseLineBreaks'=>false, 'target'=>'footer','raw'=>false));
        }*/

        $file = str_replace(".docx","",$fileName);

        $docx->createDocx($file);

            /*header("Content-type: application/docx");
            header('Content-Description: File Transfer');
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: inline; filename="'.$fileName.'"');
            header('Accept-Ranges: bytes');
            readfile(dirname(__FILE__)."/../../../reportes_generados/".$fileName);
            unlink(dirname(__FILE__)."/../../../reportes_generados/".$fileName);*/



            /*header ('Content-Disposition: attachment; filename="'.$fileName.'"');
            header ("Content-Type: application/octet-stream");
            header ("Content-Length: ".filesize($fileName));
            //var_dump(dirname(__FILE__));exit;
            readfile(dirname(__FILE__)."/../../../reportes_generados/".$fileName);
            var_dump('archivo entra');*/
            /*header('Content-Disposition: attachment; filename="'.$fileName.'"');

            readfile(dirname(__FILE__)."/../../../reportes_generados/".$fileName);
            echo file_get_contents($fileName);*/
    }


}

?>