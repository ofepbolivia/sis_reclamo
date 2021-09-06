<?php

require_once dirname(__FILE__).'/../../pxp/lib/jpgraph/src/jpgraph.php';
require_once dirname(__FILE__).'/../../pxp/lib/jpgraph/src/jpgraph_bar.php';

class REstadisticasXls
{
    private $docexcel;
    private $objWriter;
    private $nombre_archivo;
    private $hoja;
    private $columnas=array();
    private $fila;
    private $equivalencias=array();

    private $indice, $m_fila, $titulo;
    private $swEncabezado=0; //variable que define si ya se imprimi� el encabezado
    private $objParam;
    public  $url_archivo;

    var $datos_titulo;
    var $datos_detalle;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;
    var $s1;
    var $t1;
    var $tg1;
    var $total;
    var $datos_entidad;
    var $datos_periodo;
    var $ult_codigo_partida;
    var $ult_concepto;



    function __construct(CTParametro $objParam){
        $this->objParam = $objParam;
        $this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
        //ini_set('memory_limit','512M');
        set_time_limit(400);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize'  => '10MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->docexcel = new PHPExcel();
            $this->docexcel->getProperties()->setCreator("BOA")
            ->setLastModifiedBy("BOA")
            ->setTitle($this->objParam->getParametro('titulo_archivo'))
            ->setSubject($this->objParam->getParametro('titulo_archivo'))
            ->setDescription('Estadistica')
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Report File");

        //$this->docexcel->setActiveSheetIndex(0);

        //$this->docexcel->getActiveSheet()->setTitle($this->objParam->getParametro('titulo_archivo'));

        //$this->docexcel->getActiveSheet()->setTitle($this->objParam->getParametro('titulo_archivo'));

        /*$this->equivalencias=array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
            9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
            18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
            26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
            34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
            42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
            50=>'AY',51=>'AZ',
            52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
            60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
            68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
            76=>'BY',77=>'BZ');*/

    }

    function imprimeDatos(){


        $datos = $this->objParam->getParametro('datos');
        //var_dump($datos);exit;
        $columnas = 0;
        //$objWorksheet = $this->docexcel->getActiveSheet();
        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 8,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'c5d9f1')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));

        $styleTitulos3 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => 'FFFFFF'
                )

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '3287c1'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $array_estado_reclamo = array();
        $array_genero_cliente = array();
        $array_oficina_incidente = array();
        $array_oficina_reclamo = array();
        $array_tipo_incidente = array();
        $array_general = array();
        $contador_estado_reclamo = 0;
        $contador_genero_cliente = 0;
        $contador_oficina_incidente = 0;
        $contador_oficina_reclamo = 0;
        $contador_tipo_incidente = 0;
        $tipo_tabla = '';

        $contador_bloque = 1;
        $contador_bloque_general = 0;
        $contador_general = 0;
        $index = 0;
        $color_pestana = array('ff0000','1100ff','55ff00','3ba3ff','ff4747','697dff','78edff','ba8cff',
        'ff80bb','ff792b','ffff5e','52ff97','bae3ff','ffaf9c','bfffc6','b370ff','ffa8b4','7583ff','9aff17','ff30c8');
        foreach($datos as $value){

            if($tipo_tabla != $value['tipo_tabla']){
                if($tipo_tabla != ''){


                    switch ($tipo_tabla){
                        case 'estado_reclamo':
                            $array_general = $array_estado_reclamo;
                            $contador_bloque_general += $contador_estado_reclamo;
                            $contador_general = $contador_estado_reclamo;
                            break;
                        case 'genero_cliente':
                            $array_general = $array_genero_cliente;
                            $contador_bloque_general += $contador_genero_cliente;//10
                            $contador_general = $contador_genero_cliente;//3
                            break;
                        case 'oficina_incidente':
                            $array_general = $array_oficina_incidente;
                            $contador_bloque_general += $contador_oficina_incidente;
                            $contador_general = $contador_oficina_incidente;
                            break;
                        case 'oficina_reclamo':
                            $array_general = $array_oficina_reclamo;
                            $contador_bloque_general += $contador_oficina_reclamo;
                            $contador_general = $contador_oficina_reclamo;
                            break;
                        case 'tipo_incidente':
                            $array_general = $array_tipo_incidente;
                            $contador_bloque_general += $contador_tipo_incidente;
                            $contador_general = $contador_tipo_incidente;
                            break;
                    }

                    $this->addHoja(ucwords(str_replace('_',' ',$tipo_tabla)),$index);
                    $this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                    $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->docexcel->getActiveSheet()->getTabColor()->setRGB($color_pestana[$index]);
                    $this->docexcel->getActiveSheet()->getStyle('A1:B'.$contador_general)->getAlignment()->setWrapText(true);
                    $this->docexcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleTitulos3);

                    //var_dump($array_general);
                    $this->docexcel->getActiveSheet()->fromArray(
                        $array_general
                    );

                    $dataSeriesLabels1 = array(
                        new PHPExcel_Chart_DataSeriesValues('String', '!$A1', NULL, 2),    //	2011
                    );

                    $xAxisTickValues1 = array(
                        new PHPExcel_Chart_DataSeriesValues('String', '!$A$2:$A$'.$contador_general, NULL, $contador_general-1),    //	Q1 to Q4
                    );

                    $dataSeriesValues1 = array(
                        new PHPExcel_Chart_DataSeriesValues('Number', '!$B$2:$B$'.$contador_general, NULL, $contador_general-1),
                    );

                    //	Build the dataseries
                    $series1 = new PHPExcel_Chart_DataSeries(
                        PHPExcel_Chart_DataSeries::TYPE_PIECHART,                // plotType
                        null,                                                    // plotGrouping (Pie charts don't have any grouping)
                        range(0, count($dataSeriesValues1) - 1),                    // plotOrder
                        $dataSeriesLabels1,                                        // plotLabel
                        $xAxisTickValues1,                                        // plotCategory
                        $dataSeriesValues1                                        // plotValues
                    );

                    //	Set up a layout object for the Pie chart
                    $layout1 = new PHPExcel_Chart_Layout();
                    $layout1->setShowVal(true);
                    $layout1->setShowPercent(true);
                    //	Set the series in the plot area
                    $plotArea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
                    //	Set the chart legend
                    $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
                    $title1 = new PHPExcel_Chart_Title('Estadisticas '.ucwords(str_replace('_',' ',$tipo_tabla)));
                    //	Create the chart
                    $chart1 = new PHPExcel_Chart(
                        'chart1',        // name
                        $title1,        // title
                        $legend1,        // legend
                        $plotArea1,        // plotArea
                        true,            // plotVisibleOnly
                        0,                // displayBlanksAs
                        NULL,            // xAxisLabel
                        NULL            // yAxisLabel		- Pie charts don't have a Y-Axis
                    );
                    //	Set the position where the chart should appear in the worksheet
                    $chart1->setTopLeftPosition('G2');
                    $chart1->setBottomRightPosition('M15');

                    //	Add the chart to the worksheet
                    $this->docexcel->getActiveSheet()->addChart($chart1);

                    $contador_bloque += $contador_bloque_general;
                    $index++;
                }

                switch ($value['tipo_tabla']){
                    case 'estado_reclamo':

                        array_push($array_estado_reclamo,array(ucwords(str_replace('_',' ',$value['tipo_tabla'])),'Nro Casos'));
                        array_push($array_estado_reclamo,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_estado_reclamo += 2;
                        break;
                    case 'genero_cliente':
                        array_push($array_genero_cliente,array(ucwords(str_replace('_',' ',$value['tipo_tabla'])),'Nro Casos'));
                        array_push($array_genero_cliente,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_genero_cliente += 2;
                        break;
                    case 'oficina_incidente':
                        array_push($array_oficina_incidente,array(ucwords(str_replace('_',' ',$value['tipo_tabla'])),'Nro Casos'));
                        array_push($array_oficina_incidente,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_oficina_incidente += 2;
                        break;
                    case 'oficina_reclamo':
                        array_push($array_oficina_reclamo,array(ucwords(str_replace('_',' ',$value['tipo_tabla'])),'Nro Casos'));
                        array_push($array_oficina_reclamo,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_oficina_reclamo += 2;
                        break;
                    case 'tipo_incidente':
                        array_push($array_tipo_incidente,array(ucwords(str_replace('_',' ',$value['tipo_tabla'])),'Nro Casos'));
                        array_push($array_tipo_incidente,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_tipo_incidente += 2;
                        break;
                }

                $tipo_tabla = $value['tipo_tabla'];
            }else{

                switch ($value['tipo_tabla']){
                    case 'estado_reclamo':
                        array_push($array_estado_reclamo,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_estado_reclamo += 1;
                        $array_general = $array_estado_reclamo;
                        $contador_general = $contador_estado_reclamo;
                        break;
                    case 'genero_cliente':
                        array_push($array_genero_cliente,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_genero_cliente += 1;
                        $array_general = $array_genero_cliente;
                        $contador_general = $contador_genero_cliente;
                        break;
                    case 'oficina_incidente':
                        array_push($array_oficina_incidente,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_oficina_incidente += 1;
                        $array_general = $array_oficina_incidente;
                        $contador_general = $contador_oficina_incidente;
                        break;
                    case 'oficina_reclamo':
                        array_push($array_oficina_reclamo,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_oficina_reclamo += 1;
                        $array_general = $array_oficina_reclamo;
                        $contador_general = $contador_oficina_reclamo;
                        break;
                    case 'tipo_incidente':
                        array_push($array_tipo_incidente,array($value['nombre_detalle'],$value['cantidad']));
                        $contador_tipo_incidente += 1;
                        $array_general = $array_tipo_incidente;
                        $contador_general = $contador_tipo_incidente;
                        break;

                }
                $tipo_tabla = $value['tipo_tabla'];
            }

        }

        $this->addHoja(ucwords(str_replace('_',' ',$tipo_tabla)),$index);
        $this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->docexcel->getActiveSheet()->getTabColor()->setRGB($color_pestana[$index]);
        $this->docexcel->getActiveSheet()->getStyle('A1:B'.$contador_general)->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleTitulos3);

        $this->docexcel->getActiveSheet()->fromArray(
            $array_general
        );

        $dataSeriesLabels1 = array(
            new PHPExcel_Chart_DataSeriesValues('String', '!$A1', NULL, 2),    //	2011
        );

        $xAxisTickValues1 = array(
            new PHPExcel_Chart_DataSeriesValues('String', '!$A$2:$A$'.$contador_general, NULL, $contador_general-1),    //	Q1 to Q4
        );

        $dataSeriesValues1 = array(
            new PHPExcel_Chart_DataSeriesValues('Number', '!$B$2:$B$'.$contador_general, NULL, $contador_general-1),
        );

        //	Build the dataseries
        $series1 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_PIECHART,                // plotType
            null,                                                    // plotGrouping (Pie charts don't have any grouping)
            range(0, count($dataSeriesValues1) - 1),                    // plotOrder
            $dataSeriesLabels1,                                        // plotLabel
            $xAxisTickValues1,                                        // plotCategory
            $dataSeriesValues1                                        // plotValues
        );

        //	Set up a layout object for the Pie chart
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);
        //	Set the series in the plot area
        $plotArea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        //	Set the chart legend
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('Estadisticas '.ucwords(str_replace('_',' ',$tipo_tabla)));
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
            'chart1',        // name
            $title1,        // title
            $legend1,        // legend
            $plotArea1,        // plotArea
            true,            // plotVisibleOnly
            0,                // displayBlanksAs
            NULL,            // xAxisLabel
            NULL            // yAxisLabel		- Pie charts don't have a Y-Axis
        );
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('G2');
        $chart1->setBottomRightPosition('M15');

        //	Add the chart to the worksheet
        $this->docexcel->getActiveSheet()->addChart($chart1);


    }

    public function addHoja($name,$index){
        //$index = $this->docexcel->getSheetCount();
        //echo($index);
        $this->docexcel->createSheet($index)->setTitle($name);
        $this->docexcel->setActiveSheetIndex($index);
        return $this->docexcel;
    }

    function generarReporte(){

        $this->imprimeDatos();

        //echo $this->nombre_archivo; exit;
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->docexcel->setActiveSheetIndex(0);

        /*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$this->url_archivo.'"');
        header('Cache-Control: max-age=0');*/

        //$this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel,  'Excel2007');
        $this->objWriter = new PHPExcel_Writer_Excel2007($this->docexcel);
        //$this->objWriter->setOffice2003Compatibility(true);
        /*$objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objReader->setIncludeCharts(TRUE);*/
        $this->objWriter->setIncludeCharts(true);

        $this->objWriter->save($this->url_archivo);

        /*$this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);*/


    }


}

?>