<?php
abstract class PolinomioAbstracto {
    abstract public function evaluar($x): float;
    abstract public function derivada(): Polinomio;
}
class Polinomio extends PolinomioAbstracto {
    private $terminos; // array asociativo: grado => coeficiente
    // Constructor que recibe un array asociativo de términos
    public function __construct($terminos = []) {
        $this->terminos = $terminos;
    }
    //getter 
    public function getTerminos(): array {
        return $this->terminos;
    }

    public function evaluar($x): float {
        $resultado = 0;
        foreach ($this->terminos as $grado => $coef) {
            $resultado += $coef * pow($x, $grado);
        }
        return $resultado;
    }

    public function derivada(): Polinomio {
        $derivada = [];
        foreach ($this->terminos as $grado => $coef) {
            if ($grado > 0) {
                $derivada[$grado - 1] = $coef * $grado;
            }
        }
        return new Polinomio($derivada);
    }

    //funcion sumar polinomios
    public function sumar(Polinomio $otro): Polinomio {
        $nuevoTerminos = $this->terminos;
        foreach ($otro->getTerminos() as $grado => $coef) {
            if (isset($nuevoTerminos[$grado])) {
                $nuevoTerminos[$grado] += $coef;
            } else {
                $nuevoTerminos[$grado] = $coef;
            }
        }
        // Opcional: Limpiar términos con coeficiente 0 para una representación más limpia
        $nuevoTerminos = array_filter($nuevoTerminos, function($coef) {
            return $coef != 0;
        });
        return new Polinomio($nuevoTerminos);
    }

    // funcion para que el usuario ingrese un polinomio
    // devuelve un nuevo polinomio que es un array asociativo
    public function ingresarPolinomio() { // ¡Este método NO es estático!
        $terminos = [];
        echo "Ingrese el grado del polinomio a ingresar: ";
        $grado = (int)readline();
        for ($i = 0; $i <= $grado; $i++) {
            echo "Ingrese el coeficiente para x^$i: ";
            $coeficiente = (float)readline();
            if ($coeficiente != 0) {
                $terminos[$i] = $coeficiente;
            }
        }
        return new Polinomio($terminos); // Devuelve un NUEVO Polinomio
    }

    // Agrego esta función para imprimir bonito, es muy útil.
    public function mostrarPolinomio(): string {
        if (empty($this->terminos)) {
            return "0";
        }
        krsort($this->terminos); // Ordenar por grado de forma descendente
        $partes = [];
        foreach ($this->terminos as $grado => $coef) {
            if ($coef == 0) continue;

            $signo = $coef > 0 ? '+' : '-';
            $absCoef = abs($coef);

            if (empty($partes) && $signo == '+') { // No mostrar '+' para el primer término positivo
                $signo = '';
            }

            if ($grado == 0) {
                $partes[] = $signo . $absCoef;
            } elseif ($grado == 1) {
                $partes[] = $signo . ($absCoef == 1 ? '' : $absCoef) . 'x';
            } else {
                $partes[] = $signo . ($absCoef == 1 ? '' : $absCoef) . 'x^' . $grado;
            }
        }
        return implode(' ', $partes);
    }
}