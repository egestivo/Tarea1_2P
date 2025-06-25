<?php
    //clase abstracta
    abstract class SistemaEcuaciones {
        // Atributos
        protected $ecuaciones;

        // Constructor
        public function __construct($ecuaciones) {
            $this->ecuaciones = $ecuaciones;
        }
        // Método calcular resultado abstracto
        abstract public function calcularResultado(): void;
        abstract public function validarConsistencia(): bool;
    }
?>