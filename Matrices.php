<?php

abstract class MatrizAbstracta {
    protected array $elementos;

    abstract public function multiplicar($matriz);
    abstract public function inversa();

    // getter
    public function getElementos() {
        return $this->elementos;
    }
}

class Matriz extends MatrizAbstracta {
    public function __construct($elementos) {
        $this->elementos = $elementos;
    }

    // Multiplicación de matrices
    public function multiplicar($matriz) {
        $a = $this->elementos;
        $b = $matriz->getElementos();

        $columnasA = count(current($a));
        $filasB = count($b);

        if ($columnasA != $filasB) {
            echo "No se pueden multiplicar las matrices: dimensiones incompatibles.";
            return [];
        }

        $resultado = [];
        foreach ($a as $i => $filaA) {
            foreach (array_keys(current($b)) as $j) {
                $resultado[$i][$j] = 0;
                foreach (array_keys($filaA) as $k) {
                    $resultado[$i][$j] += $a[$i][$k] * $b[$k][$j];
                }
            }
        }
        return new Matriz($resultado);
    }

    // Inversa de la matriz
    public function inversa() {
        $matriz = $this->elementos;
        $n = count($matriz);
        // Convertir a matriz numérica indexada
        $A = [];
        foreach ($matriz as $i => $fila) {
            $A[] = array_values($fila);
        }
        $I = [];
        for ($i = 0; $i < $n; $i++) {
            $I[$i] = array_fill(0, $n, 0);
            $I[$i][$i] = 1;
        }
        // Gauss-Jordan
        for ($i = 0; $i < $n; $i++) {
            // Buscar el pivote que no sea cero
            $pivote = $A[$i][$i];
            if ($pivote == 0) {
                echo "La matriz no es invertible.";
                return [];
            }
            //aquí divide en ambas matrices.
            for ($j = 0; $j < $n; $j++) {
                $A[$i][$j] /= $pivote;
                $I[$i][$j] /= $pivote;
            }
            for ($k = 0; $k < $n; $k++) {
                if ($k != $i) {
                    $factor = $A[$k][$i];
                    for ($j = 0; $j < $n; $j++) {
                        $A[$k][$j] -= $factor * $A[$i][$j];
                        $I[$k][$j] -= $factor * $I[$i][$j];
                    }
                }
            }
        }
        // Convertir de vuelta a array asociativo
        $inversa = [];
        $rowKeys = array_keys($matriz);
        $colKeys = array_keys(current($matriz));
        foreach ($rowKeys as $i => $rowKey) {
            foreach ($colKeys as $j => $colKey) {
                $inversa[$rowKey][$colKey] = $I[$i][$j];
            }
        }
        return new Matriz($inversa);
    }

    // Determinante de una matriz cuadrada
    public function determinante($matriz = null): float {
        // Si no se pasa matriz, usar los elementos del objeto
        if ($matriz === null) {
            $matriz = $this->elementos;
        }
        //comprobar si la matriz es cuadrada
        $n = count($matriz);
        foreach ($matriz as $fila) {
            if (count($fila) != $n) {
                echo "La matriz no es cuadrada, no se puede calcular el determinante.";
                return 0;
            }
        }
        // Convertir a matriz numérica indexada
        $A = [];
        foreach ($matriz as $fila) {
            $A[] = array_values($fila);
        }
        $n = count($A);
        if ($n == 1) {
            return $A[0][0];
        }
        if ($n == 2) {
            return $A[0][0]*$A[1][1] - $A[0][1]*$A[1][0];
        }
        $det = 0;
        for ($c = 0; $c < $n; $c++) {
            $submatriz = [];
            for ($i = 1; $i < $n; $i++) {
                $fila = [];
                for ($j = 0; $j < $n; $j++) {
                    if ($j != $c) {
                        $fila[] = $A[$i][$j];
                    }
                }
                $submatriz[] = $fila;
            }
            // Llamada recursiva usando el mismo objeto
            $det += pow(-1, $c) * $A[0][$c] * $this->determinante($submatriz);
        }
        return $det;
    }

    // Método para imprimir la matriz
    public function imprimir() {
        foreach ($this->elementos as $fila) {
            echo implode("\t", $fila) . "\n";
        }
    }

    //Metodo para que el usuario ingrese la matriz
    // este debe mostrar mensaje de que si desea obtener el determinante debe ser cuadrada
    // si quiere multiplicar dos matrices, estas deben ser compatibles
    public function ingresarMatriz() {
        // Solicita dimensiones
        echo "Ingrese el número de filas y columnas de la matriz (formato: filas columnas): ";
        echo "Recuerde que para calcular el determinante debe ser una matriz cuadrada.\n";
        echo "Para multiplicar dos matrices, las columnas de la primera deben coincidir con las filas de la segunda.\n";
        $input = trim(fgets(STDIN));
        $dimensiones = explode(" ", $input);
        $filas = (int)$dimensiones[0];
        $columnas = (int)$dimensiones[1];
        $matriz = [];
        echo "Ingrese los elementos de la matriz (separados por espacios, una fila por línea):\n";
        for ($i = 0; $i < $filas; $i++) {
            $input = trim(fgets(STDIN));
            $fila = explode(" ", $input);
            if (count($fila) != $columnas) {
                echo "Error: La fila debe tener $columnas elementos.\n";
                $i--;
                continue;
            }
            $matriz[] = array_map('floatval', $fila);
        }
        $this->elementos = $matriz;
        return $this;
    }
}
// // Ejemplo de uso
// $matriz1 = new Matriz([]);
// $matriz1 = $matriz1->ingresarMatriz();
// $matriz1->imprimir();
// $matriz2 = new Matriz([]);
// $matriz2 = $matriz2->ingresarMatriz();
// $matriz2->imprimir();
// // Multiplicación de matrices
// $resultado = $matriz1->multiplicar($matriz2);
// if ($resultado) {
//     echo "Resultado de la multiplicación:\n";
//     $resultado->imprimir();
// }
// // Inversa de la matriz
// $inversa = $matriz1->inversa();
// if ($inversa) {
//     echo "Inversa de la matriz:\n";
//     $inversa->imprimir();
// }
// // Determinante de la matriz
// $determinante = $matriz1->determinante();
// if ($determinante !== null) {
//     echo "Determinante de la matriz: $determinante\n";
// } else {
//     echo "No se pudo calcular el determinante.\n";
// }
// // Determinante de la segunda matriz
// $determinante2 = $matriz2->determinante();
// if ($determinante2 !== null) {
//     echo "Determinante de la segunda matriz: $determinante2\n";
// } else {
//     echo "No se pudo calcular el determinante de la segunda matriz.\n";
// }