<?php
include_once(dirname(__FILE__).'/../../lib/PHPWord/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();
Class RRespuesta {

    private $dataSource;

    public function datosHeader( $dataSource) {
        $this->dataSource = $dataSource;
    }


    function write($fileName) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $document = $phpWord->loadTemplate(dirname(__FILE__).'/plantilla_respuesta.docx');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");

        $document->setValue('FECHA', strftime("%d de %B de %Y", strtotime($this->dataSource[0]['fecha_respuesta']))); // On section/content
        $document->setValue('NRO_TRAMITE', $this->dataSource[0]['nro_cite']); // On section/content
        $document->setValue('GESTION', $this->dataSource[0]['gestion']);
        $document->setValue('GENERO', $this->dataSource[0]['genero']);

        $document->setValue('NOMBRE_CLIENTE', $this->dataSource[0]['nombre_completo1']); // On section/content
        $document->setValue('REFERENCIA', $this->dataSource[0]['asunto']); // On section/content

        $document->setValue('RESPUESTA', $this->dataSource[0]['respuesta']); // On section/content

        $document->setValue('PROCE', $this->dataSource[0]['prodedente']); // On section/content


        $document->saveAs($fileName);

    }


}

?>