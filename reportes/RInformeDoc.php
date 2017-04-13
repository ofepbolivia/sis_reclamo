<?php
include_once(dirname(__FILE__).'/../../lib/PHPWord/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();
Class RInformeDoc {

    private $dataSource;

    public function datosHeader( $dataSource) {
        $this->dataSource = $dataSource;
        //var_dump ($this->dataSource);exit;

    }

    function write($fileName) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $document = $phpWord->loadTemplate(dirname(__FILE__).'/plantilla_informe_tecnico.docx');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");

         //$document->setValue('FECHA', strftime("%d de %B de %Y", strtotime($this->dataSource[0]['fecha_informe']))); // On section/content
         $document->setValue('FECHA', $this->dataSource[0]['fecha_informe']); // On section/content
         $document->setValue('N_FRD', $this->dataSource[0]['nro_frd']); // On section/content
         $document->setValue('N_PRE_IMPRE', $this->dataSource[0]['correlativo_preimpreso_frd']); // On section/content

         $document->setValue('NOMBRE_CLIENTE', $this->dataSource[0]['nombre_completo1']); // On section/content
         $document->setValue('NUMERO_CEL', $this->dataSource[0]['celular']); // On section/content
         $document->setValue('CORREO', $this->dataSource[0]['email']); // On section/content

        $document->setValue('N_VUELO', $this->dataSource[0]['nro_vuelo']); // On section/content
        $document->setValue('FECHA_IN', $this->obtenerFechaEnLetra($this->dataSource[0]['fecha_hora_incidente'])); // On section/content
        $document->setValue('LUGAR_IN', $this->dataSource[0]['nombre']); // On section/content
        $document->setValue('ORIGEN', $this->dataSource[0]['origen']); // On section/content
        $document->setValue('DESTINO', $this->dataSource[0]['destino']); // On section/content

        $document->setValue('COMPENSACION', $this->dataSource[0]['desc_nombre_compensacion']); // On section/content
        $document->setValue('DETALLE_DEL_FRD', $this->dataSource[0]['detalle_incidente']); // On section/content

        $document->setValue('ANTECEDENTES', $this->dataSource[0]['antecedentes_informe']); // On section/content
        $document->setValue('ANALISIS_TEC', $this->dataSource[0]['analisis_tecnico']); // On section/content
        $document->setValue('CONCLU_RECOME', $this->dataSource[0]['conclusion_recomendacion']); // On section/content
        $document->setValue('FUNCIONARIO', $this->dataSource[0]['funcionario_reg']);


        $document->saveAs($fileName);

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


}

?>