<?php

class Persona {
    // Atributos o Propiedades
    // -Visibilidad: public / private / -protected-
    private $_nombres;
    private $_apellidoPaterno;
    private $_apellidoMaterno;
    private $_genero;
    
    
    // Constructor
    public function __construct($nombres, $genero){
        $this->_nombres = $nombres;
        $this->_genero  = $genero;
    }

    // Métodos, -Getters y Setters-
    public function getApellidoPaterno(){
        return $this->_apellidoPaterno;
    }

    public function getApellidoMaterno(){
        return $this->_apellidoMaterno;
    }

    public function getNombres(){
        return $this->_nombres;
    }

    public function getNombreCompleto(){
        return $this->_nombres." ".$this->_apellidoPaterno." ".$this->_apellidoMaterno;
    }

    public function setApellidoPaterno($apellido){
        $this->_apellidoPaterno = $apellido;
    }

    public function setApellidoMaterno($apellido){
        $this->_apellidoMaterno = $apellido;
    }

    public function setNombres($nombres){
        $this->_nombres = $nombres;
    }
}