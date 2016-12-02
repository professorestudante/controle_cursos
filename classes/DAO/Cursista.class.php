<?php

/**
 * Métodos de acesso ao cursista
 *
 * @author Francisco Filho
 */
class Cursista {
    private $nome;
    private $matricula;
    private $email;
    
    public function __set($name, $value) {
        $this->name=$value;
    }
    
    public function __get($name) {
        return $this->name;
    }
}
