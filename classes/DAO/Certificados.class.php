<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cerificados
 *
 * @author cefop 9
 */
class Certificados {
    
    private $cod_certificado;
    private $cod_aprovacao;
    private $matricula;
    private $horas;
    private $data;
    
    public function __set($name, $value) {
        $this->$name=$value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
}
