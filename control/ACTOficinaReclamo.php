<?php
/**
 *@package pXP
 *@file gen-ACTOficina.php
 *@author  (admin)
 *@date 15-01-2014 16:05:34
 *@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class ACTOficinaReclamo extends ACTbase{

    function listarOficina(){
        $this->objParam->defecto('ordenacion','id_oficina');

        $this->objParam->defecto('dir_ordenacion','asc');

        /*if ($this->objParam->getParametro('activo') != '') {
            $this->objParam->addFiltro(" OR ofi.estado_reg  = ''". $this->objParam->getParametro('activo')."''");
        }*/

        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODOficinaReclamo','listarOficina');
        } else{
            $this->objFunc=$this->create('MODOficinaReclamo');

            $this->res=$this->objFunc->listarOficina($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarOficina(){
        $this->objFunc=$this->create('MODOficinaReclamo');
        if($this->objParam->insertar('id_oficina')){
            $this->res=$this->objFunc->insertarOficina($this->objParam);
        } else{
            $this->res=$this->objFunc->modificarOficina($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarOficina(){
        $this->objFunc=$this->create('MODOficinaReclamo');
        $this->res=$this->objFunc->eliminarOficina($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

}

?>