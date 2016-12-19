<?php
include_once(dirname(__FILE__).'/../../lib/PHPWord/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();
Class RRespuesta {

    private $dataSource;

    public function datosHeader( $dataSource) {
        $this->dataSource = $dataSource;


    }

    function write($fileName)
    {

        $text1 = '"En cumplimiento al Reglamento para la Atención de Directas de Usuarios de los Servicios Aeronáuticos, su reclamación es considerada';
        $text2 = 'Artículo 59º del Decreto Supremo Nº 27172, si usted no está conforme con la respuesta obtenida, tiene derecho a presentar una Reclamación Administrativa ante la Autoridad de Regulación y Fiscalización de Telecomunicación y Transporte en el plazo de 15 días hábiles, a ser computables a partir de la recepción de la respuesta."';
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $document = $phpWord->loadTemplate(dirname(__FILE__) . '/plantilla_respuesta.docx');
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");

        $document->setValue('FECHA', $this->dataSource[0]['fecha_respuesta']); // On section/content
        $document->setValue('NUM_CITE', $this->dataSource[0]['num_cite']); // On section/content
        $document->setValue('GENERO', $this->dataSource[0]['genero']);

        $document->setValue('NOMBRE_CLIENTE', $this->dataSource[0]['nombre_completo1']); // On section/content
        $document->setValue('REFERENCIA', $this->dataSource[0]['asunto']); // On section/content

        $document->setValue('RESPUESTA', $this->dataSource[0]['respuesta']); // On section/content

        $document->setValue('PROCE',$this->dataSource[0]['prodedente']); // On section/content
        if ($this->dataSource[0]['prodedente'] == 'procedente' or $this->dataSource[0]['prodedente'] == 'improcedente') {

            $document->setValue('ART', $text2); // On section/content
            $document->setValue('ES', $text1); // On section/content
        }else{
            $document->setValue('ART', ' '); // On section/content
            $document->setValue('ES', ' '); // On section/content
        }


        $document->saveAs($fileName);


    }


}

?>