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
        $this->docexcel->getProperties()->setCreator("PXP")
            ->setLastModifiedBy("PXP")
            ->setTitle($this->objParam->getParametro('titulo_archivo'))
            ->setSubject($this->objParam->getParametro('titulo_archivo'))
            ->setDescription('Estadistica')
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Estadistica");

        //$this->docexcel->setActiveSheetIndex(0);

        //$this->docexcel->getActiveSheet()->setTitle($this->objParam->getParametro('titulo_archivo'));
        $this->docexcel->getActiveSheet()->setTitle($this->objParam->getParametro('nombre_archivo'));
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


        //$datos = $this->objParam->getParametro('datos');
        $columnas = 0;
        $objWorksheet = $this->docexcel->getActiveSheet();
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

        $objWorksheet->fromArray(
                        array(
                            array('',	'uno',	'dos',	'tres'),
                            array('Q1',   12,   15,		21),
                            array('Q2',   56,   73,		86),
                            array('Q3',   52,   61,		69),
                            array('Q4',   30,   32,		10),
                        )
                    );
            //	Set the Labels for each data series we want to plot
            //		Datatype
            //		Cell reference for data
            //		Format Code
            //		Number of datapoints in series
            //		Data values
            //		Data Marker
            $dataSeriesLabels1 = array(
                new PHPExcel_Chart_DataSeriesValues('String', '!$C$1', NULL, 1),	//	2011
            );
            //	Set the X-Axis Labels
            //		Datatype
            //		Cell reference for data
            //		Format Code
            //		Number of datapoints in series
            //		Data values
            //		Data Marker
            $xAxisTickValues1 = array(
                new PHPExcel_Chart_DataSeriesValues('String', '!$A$2:$A$5', NULL, 4),	//	Q1 to Q4
            );
            //	Set the Data values for each data series we want to plot
            //		Datatype
            //		Cell reference for data
            //		Format Code
            //		Number of datapoints in series
            //		Data values
            //		Data Marker
            $dataSeriesValues1 = array(
                new PHPExcel_Chart_DataSeriesValues('Number', '!$C$2:$C$5', NULL, 4),
            );
            //var_dump($dataSeriesValues1); exit;
            //	Build the dataseries
            $series1 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
                null,			                                        // plotGrouping (Pie charts don't have any grouping)
                range(0, count($dataSeriesValues1)-1),					// plotOrder
                $dataSeriesLabels1,										// plotLabel
                $xAxisTickValues1,										// plotCategory
                $dataSeriesValues1										// plotValues
            );

        //	Set up a layout object for the Pie chart
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);
        //	Set the series in the plot area
        $plotArea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        //	Set the chart legend
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('Estadistica Pie Chart');
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
            'chart1',		// name
            $title1,		// title
            $legend1,		// legend
            $plotArea1,		// plotArea
            true,			// plotVisibleOnly
            0,				// displayBlanksAs
            NULL,			// xAxisLabel
            NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
        );
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('G2');
        $chart1->setBottomRightPosition('M15');
        //	Add the chart to the worksheet
        $objWorksheet->addChart($chart1);
        //	Set the Labels for each data series we want to plot
        //		Datatype
        //		Cell reference for data
        //		Format Code
        //		Number of datapoints in series
        //		Data values
        //		Data Marker
        /*$dataSeriesLabels2 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Estadistica!$C$1', NULL, 1),	//	2011
        );
        //	Set the X-Axis Labels
        //		Datatype
        //		Cell reference for data
        //		Format Code
        //		Number of datapoints in series
        //		Data values
        //		Data Marker
        $xAxisTickValues2 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Estadistica!$A$2:$A$5', NULL, 4),	//	Q1 to Q4
        );
        //	Set the Data values for each data series we want to plot
        //		Datatype
        //		Cell reference for data
        //		Format Code
        //		Number of datapoints in series
        //		Data values
        //		Data Marker
        $dataSeriesValues2 = array(
            new PHPExcel_Chart_DataSeriesValues('Number', 'Estadistica!$C$2:$C$5', NULL, 4),
        );
        //	Build the dataseries
        $series2 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_DONUTCHART,		// plotType
            NULL,			                                // plotGrouping (Donut charts don't have any grouping)
            range(0, count($dataSeriesValues2)-1),			// plotOrder
            $dataSeriesLabels2,								// plotLabel
            $xAxisTickValues2,								// plotCategory
            $dataSeriesValues2								// plotValues
        );
        //	Set up a layout object for the Pie chart
        $layout2 = new PHPExcel_Chart_Layout();
        $layout2->setShowVal(TRUE);
        $layout2->setShowCatName(TRUE);
        //	Set the series in the plot area
        $plotArea2 = new PHPExcel_Chart_PlotArea($layout2, array($series2));
        $title2 = new PHPExcel_Chart_Title('Test Donut Chart');
        //	Create the chart
        $chart2 = new PHPExcel_Chart(
            'chart2',		// name
            $title2,		// title
            NULL,			// legend
            $plotArea2,		// plotArea
            true,			// plotVisibleOnly
            0,				// displayBlanksAs
            NULL,			// xAxisLabel
            NULL			// yAxisLabel		- Like Pie charts, Donut charts don't have a Y-Axis
        );
        //	Set the position where the chart should appear in the worksheet
        $chart2->setTopLeftPosition('I7');
        $chart2->setBottomRightPosition('P20');
        //	Add the chart to the worksheet
        $objWorksheet->addChart($chart2);*/

        /*$title = 'Types';
        $chartTitle = 'Distribution of leave types';
        $label = 'Leave type';
        $value = 'Number of days';

        $this->docexcel->getActiveSheet()->setCellValue('A1', $label);
        $this->docexcel->getActiveSheet()->setCellValue('B1', $value);
        $this->docexcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
        $this->docexcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $rows = array(
            array('name'=>'uno','number'=>1),
            array('name'=>'dos','number'=>2),
            array('name'=>'tres','number'=>3),
            array('name'=>'cuatro','number'=>4)
            );
        $line = 2;
        foreach ($rows as $row) {
            $this->docexcel->getActiveSheet()->setCellValue('A' . $line, $row['name']);
            $this->docexcel->getActiveSheet()->setCellValue('B' . $line, $row['number']);
            $line++;
        }
        //Autofit
        foreach (range('A', 'B') as $colD) {
            $this->docexcel->getActiveSheet()->getColumnDimension($colD)->setAutoSize(TRUE);
        }
        $dataseriesLabels1 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Estadistica!$A$1', null, 1));
        $xAxisTickValues1 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Estadistica!$A$2:$A$' . $line, null, 4));
        $dataSeriesValues1 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Estadistica!$B$2:$B$' . $line, null, 4));
        //var_dump(count($dataSeriesValues1));exit;
        $series1 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_PIECHART,
            PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues1) - 1),
            $dataseriesLabels1,
            $xAxisTickValues1,
            $dataSeriesValues1
        );
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(TRUE);
        $layout1->setShowPercent(TRUE);
        $plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, null, false);
        $title1 = new PHPExcel_Chart_Title($chartTitle);
        $chart1 = new PHPExcel_Chart('chart1', $title1, $legend1, $plotarea1, true, 0, null, null);
        $chart1->setTopLeftPosition('E3');
        $chart1->setBottomRightPosition('K20');
        $this->docexcel->getActiveSheet()->addChart($chart1);


        $idind = array("y1","y2","y3","y4");
        $s1 = array("7","10","25","30");

        $grafico = new Graph(500,500,'auto');
        $grafico->img->SetImgFormat('jpeg');
        $grafico -> SetScale("textlin");
        $grafico -> title -> Set ("GRAFICA DE PRUEBA");
        $grafico -> yaxis -> title -> Set ("VALOR DE REFERENCIA");
        $grafico -> xaxis -> title -> Set ("INDICADORES");
        $grafico->xaxis->SetTickLabels($idind);

        $bp=new BarPlot($s1);
        $bp->SetFillColor("#00CC00");
        $bp->SetWidth(50);
        $bp->value->Show();

        $grafico->Add($bp);

        $grafico->Stroke(_IMG_HANDLER);

        $fileName = dirname(__FILE__) . "/../../reportes_generados/grafico.jpeg";
        $grafico->img->Stream($fileName);

        $gdImage = imagecreatefromjpeg(dirname(__FILE__) . "/../../reportes_generados/grafico.jpeg");
        imagescale($gdImage, 500,500);
        // Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
        $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
        $objDrawing->setName('Sample image');
        $objDrawing->setDescription('Sample image');
        $objDrawing->setImageResource($gdImage);
        $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
        $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        $objDrawing->setCoordinates('E5');
        $objDrawing->setHeight(150);
        $objDrawing->setWorksheet($this->docexcel->getActiveSheet());*/



        // start graphic

        /*$dsl5=array(

            new \PHPExcel_Chart_DataSeriesValues('Number', 'SapVsWB!$B$2:$E$2', NULL, 1),
            new \PHPExcel_Chart_DataSeriesValues('Number', 'SapVsWB!$B$3:$E$3', NULL, 1),
            new \PHPExcel_Chart_DataSeriesValues('Number', 'SapVsWB!$B$4:$E$4', NULL, 1),

        );

        $xal5=array(
            new \PHPExcel_Chart_DataSeriesValues('String', 'SapVsWB!$A$2:$A$4', NULL, 1),
        );

        $dsv5=array(
            new \PHPExcel_Chart_DataSeriesValues('Number', 'SapVsWB!$B$2:$E$2', NULL, 1),
            new \PHPExcel_Chart_DataSeriesValues('Number', 'SapVsWB!$B$3:$E$3', NULL, 1),
            new \PHPExcel_Chart_DataSeriesValues('Number', 'SapVsWB!$B$4:$E$4', NULL, 1),

        );

        $dsK=new \PHPExcel_Chart_DataSeries(
            \PHPExcel_Chart_DataSeries::TYPE_BARCHART,
            \PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
            range(0, count($dsv5)-1),
            $dsl5,
            $xal5,
            $dsv5
        );

// siguiente tipo de grafico
        $dsl=array(
            new \PHPExcel_Chart_DataSeriesValues('String', 'SapVsWB!$F$1', NULL, 1),
        );
        $xal=array(
            new \PHPExcel_Chart_DataSeriesValues('String', 'SapVsWB!$F$2:$F$4', NULL, 10),

        );
        $dsv=array(
            new \PHPExcel_Chart_DataSeriesValues('Number', 'SapVsWB!$F$2:$F$4', NULL, 10),

        );

        $ds=new \PHPExcel_Chart_DataSeries(
            \PHPExcel_Chart_DataSeries::TYPE_LINECHART,
            \PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            range(0, count($dsv)-1),
            $dsl,
            $xal,
            $dsv
        );

        $dsK->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
        $pa5=new \PHPExcel_Chart_PlotArea(NULL, array($dsK,$ds));
        $legend=new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title=new \PHPExcel_Chart_Title('');
        $chart2=new \PHPExcel_Chart(
            'chart1',
            $title,
            $legend,
            $pa5,
            true,
            0,
            NULL,
            NULL
        );
        $col= 10;
        $col2= $col + 25;
        $chart2->setTopLeftPosition('E'.$col.'');
        $chart2->setBottomRightPosition('S'.$col2.'');
        $this->docexcel->getActiveSheet()->addChart($chart2);*/


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


    }


}

?>