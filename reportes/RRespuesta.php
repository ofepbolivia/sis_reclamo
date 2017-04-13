<?php
/*include_once(dirname(__FILE__).'/../../lib/PHPWord/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();
use \PhpOffice\PhpWord\PhpWord;*/
//include_once (dirname(__FILE__).'/../../../kerp_capacitacion/lib/phpdocx/Classes/Phpdocx/Create/CreateDocx.inc');
include_once (dirname(__FILE__).'/../../pxp/lib/phpdocx/Classes/Phpdocx/Create/CreateDocx.inc');
//winclude_once (dirname(__FILE__).'/../../../kerp_capacitacion/lib/phpdocx/Classes/Phpdocx/Transform/TransformDoc.inc');


Class RRespuesta {

    private $dataSource;

    public function datosHeader( $dataSource) {
        $this->dataSource = $dataSource;
    }

    function write($fileName){

        //echo dirname(__FILE__).'/Respuesta-REC-000071-2017.docx';exit;
        $text1 = '"En cumplimiento al Reglamento para la Atención de Reclamaciones Directas de Usuarios de los Servicios Aeronáuticos, su reclamación es considerada';
        $text2 = 'Artículo 59º del Decreto Supremo Nº 27172, si usted no está conforme con la respuesta obtenida, tiene derecho a presentar una Reclamación Administrativa ante la Autoridad de Regulación y Fiscalización de Telecomunicación y Transporte en el plazo de 15 días hábiles, a ser computables a partir de la recepción de la respuesta."';
        /*$docx = new Phpdocx\Transform\TransformDoc();


        $docx ->setStrFile(dirname(__FILE__).'/Respuesta-REC-000071-2017.docx');

        $docx ->generatePDF();*/
        //echo 'por los mas nece';exit;
        /*$docx = new Phpdocx\Create\CreateDocx();

        $docx->enableCompatibilityMode();
        $text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
            'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
            'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
            'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
            'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
            'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
            'officia deserunt mollit anim id est laborum.';
        $paramsText = array(
            'b' => 'single',
            'font' => 'Arial'
        );
        $docx->addText($text, $paramsText);
        $file = str_replace(".docx","",$fileName);
        //var_dump('que esta pasando'); exit;
        $docx->createDocx($file);*/

       // $docx->transformDocument($fileName, $file.'.pdf');

        //DESDE AQUI FUNCIONA
        $docx = new Phpdocx\Create\CreateDocxFromTemplate(dirname(__FILE__).'/plantilla_resp.docx');
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
        }

        $file = str_replace(".docx","",$fileName);


        $docx->createDocx($file);

    }


}

?>