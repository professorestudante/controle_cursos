<?php


/**
 * Métodos de acesso ao controle do curso
 *
 *  @author Francisco Filho
 */
class ControleCurso {
    private $cod_curso;
    private $data;
    private $edicao;
    private $matricula;
    private $situacao;
    
    function __construct(Curso $cod_curso, Cursista $matricula) {
        $this->cod_curso=$cod_curso;
        $this->matricula = $matricula;
    }
    
    public function __set($name, $value) {
        $this->name=$value;
    }
    
    public function __get($name) {
        return $this->name;
    }
}
