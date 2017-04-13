<?php
include_once(dirname(__FILE__).'/../../lib/PHPWord/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();
Class RReclamoDoc {

    private $dataSource;

    public function datosHeader( $dataSource) {
        $this->dataSource = $dataSource;

    }

    function write($fileName) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $document = $phpWord->loadTemplate(dirname(__FILE__).'/plantilla_reclamo.docx');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");


        $document->setValue('FECHA', $this->dataSource[0]['fecha_hora_recepcion']); // On section/content
        $document->setValue('FRD', $this->dataSource[0]['nro_frd']); // On section/content
        $document->setValue('PREFRD', $this->dataSource[0]['correlativo_preimpreso_frd']); // On section/content

        $document->setValue('NOMBRE_CLIENTE', $this->dataSource[0]['desc_nom_cliente']); // On section/content
        $document->setValue('N_VUELO', $this->dataSource[0]['nro_vuelo']); // On section/content
        $document->setValue('FECHA_IN', $this->dataSource[0]['fecha_hora_incidente']); // On section/content
        $document->setValue('FECHA_VUE', $this->dataSource[0]['fecha_hora_vuelo']); // On section/content
        $document->setValue('ORIGEN', $this->dataSource[0]['origen']); // On section/content
        $document->setValue('DESTINO', $this->dataSource[0]['destino']); // On section/content

        $document->setValue('TIPO_INC', $this->dataSource[0]['desc_incidente']); // On section/content
        $document->setValue('SUB_INC', $this->dataSource[0]['desc_sudnom_incidente']); // On section/content
        $document->setValue('FECHA_INC', $this->dataSource[0]['fecha_hora_incidente']); // On section/content
        $document->setValue('OFICNA_INC', $this->dataSource[0]['desc_oficina']); // On section/content

        $document->setValue('INCIDENRE', $this->dataSource[0]['detalle_incidente']); // On section/content
        $document->setValue('OBSERVACIONES', $this->dataSource[0]['observaciones_incidente']); // On section/content

        $document->setValue('OFI_REC', $this->dataSource[0]['desc_oficina_registro_incidente']); // On section/content
        $document->setValue('FECHA_RECP', $this->dataSource[0]['fecha_hora_recepcion']); // On section/content
        $document->setValue('FUNCIONARIO', $this->dataSource[0]['desc_nombre_funcionario']); // On section/content
        $document->setValue('MEDIO', $this->dataSource[0]['desc_nombre_medio']); // On section/content
        
        $document->saveAs($fileName);


    }


}

?>