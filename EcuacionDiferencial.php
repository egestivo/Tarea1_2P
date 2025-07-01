<?php

abstract class EcuacionDiferencial
{
    abstract public function resolverEuler(callable $f, array $condicionesIniciales, array $parametros): array;
}

class EulerNumerico extends EcuacionDiferencial
{
    /**
     * Método de Euler: y_{n+1} = y_n + h*f(x_n, y_n)
     */
    public function resolverEuler(callable $f, array $condicionesIniciales, array $parametros): array
    {
        $x = $condicionesIniciales['x0']; // x inicial
        $y = $condicionesIniciales['y0']; // y inicial
        $h = $parametros['h'];            // paso
        $n = $parametros['n'];            // cantidad de pasos
        $solucion = [];
        $solucion[$x] = $y; // primer punto
        // recuerdas que hacias pasito a pasito según la h :v
        // entonces iterar aquí es cada pasito que das
        for ($i = 0; $i < $n; $i++) {
            // aquí iniciamos aplicando euler con la evaluación de la función
            // y_{n+1} = y_n + h*f(x_n, y_n)
            $y = $y + $h * $f($x, $y);
            // acá es el nuevo x que es sumado el h que diste
            // x_{n+1} = x_n + h
            $x = $x + $h;
            //agregamos la solución pe :v
            $solucion[$x] = $y;
        }

        return $solucion;
    }

    public function aplicarMetodo(callable $f, array $condicionesIniciales, array $parametros): array
    {
        return $this->resolverEuler($f, $condicionesIniciales, $parametros);
    }
}

//al ejecutar como es dificil el ingreso de la función se puede hacer de la siguiente manera
// cuando se ejecute con php se muestra un mensaje de que se ejecute manualmente después de definir la función
// y los parámetros, así que la ejecució nde este es manual
