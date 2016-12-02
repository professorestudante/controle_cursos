<?php

/**
 * Métodos de acesso a classe Curso
 * @author Francisco Filho
 */
class Curso {
    private $titulo;
    private $cod_curso;
    private $horas;
    
    public function __set($name, $value) {
        $this->name=$value;
    }
    
    public function __get($name) {
        return $this->name;
    }
    
    
}
