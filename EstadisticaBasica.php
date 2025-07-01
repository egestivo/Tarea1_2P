<?php
abstract class Estadistica {
    abstract public function calcularMedia(array $datos): float;
    abstract public function calcularMediana(array $datos): array;
    abstract public function calcularModa(array $datos): array;
}

class EstadisticaBasica extends Estadistica
{
    //* atributos
    private array $datos;
    // Constructor que recibe un array de datos
    public function __construct(array $datos = [])
    {
        $this->datos = $datos;
    }
    // Implementación del método calcularMedia
    public function calcularMedia(array $datos): float
    {
        // Asegúrate de que el array no esté vacío para evitar divisiones por cero.
        if (empty($datos)) {
            return 0.0;
        }
        // array_sum() suma todos los elementos y count() obtiene la cantidad.
        return array_sum($datos) / count($datos);
    }

    // Implementación del método para calcular la mediana.
    // Encuentra el valor central de un conjunto de datos ordenado.
    public function calcularMediana(array $datos): array
    {
        // Si el array está vacío, no hay mediana.
        if (empty($datos)) {
            return [];
        }

        // ¡Paso crucial! Ordena el array numéricamente de forma ascendente.
        sort($datos);

        $cantidad = count($datos);
        // floor() asegura que el índice sea un número entero.
        $indiceMedio = floor($cantidad / 2);

        // Si la cantidad de elementos es par, la mediana es el promedio de los dos valores centrales.
        if ($cantidad % 2 === 0) {
            //devolvemos un array con el promedio de los dos valores centrales
            return [($datos[$indiceMedio - 1] + $datos[$indiceMedio]) / 2];
        } else {
            // Si la cantidad es impar, la mediana es el valor en la posición central.
            // devolvemos un array con el valor central
            return [$datos[$indiceMedio]];
        }
    }

    // Implementación del método para calcular la moda (o modas).
    // Encuentra los valores que aparecen con mayor frecuencia.
    public function calcularModa(array $datos): array
    {
        // Si el array está vacío, no hay moda.
        if (empty($datos)) {
            return [];
        }

        // contamos cuántas veces aparece un valor en el array
        //! IMPORTANTE SABER QUE DEVUELVE OTRO ASOCIATIVO, PILAS
        // array_count_values solo funciona con enteros, si hay decimales
        // se convierten a enteros, por lo que si hay decimales, se
        // salta una advertencia para la moda :'v
        // Convertimos todos los valores a string para evitar el warning de array_count_values
        // Filtramos solo valores que pueden ser convertidos apropiadamente
        $datosComoString = array_map(function($valor) {
            return (string)$valor;
        }, array_filter($datos, function($valor) {
            return is_numeric($valor) || is_string($valor);
        }));
        $frecuencias = array_count_values($datosComoString);

        // Encuentra la frecuencia más alta entre todos los valores.
        $maximaFrecuencia = 0;
        if (!empty($frecuencias)) {
            $maximaFrecuencia = max($frecuencias);
        }

        $modas = [];
        // * veamos qué valor tiene esta frecuencia
        foreach ($frecuencias as $valor => $frecuencia) {
            if ($frecuencia === $maximaFrecuencia) {
                // Si la frecuencia de este valor es la máxima, se añade a la moda o modas si hay varias
                $modas[] = $valor;
            }
        }
        // * retorna la o las modas xd
        return $modas;
    }

    // Implementación del método generarInforme.
    public function generarInforme(): array
    {
        $informeFinal = [];

        // Recorre cada conjunto de datos almacenado en la propiedad $conjuntosDatos.
        //* $nombreConjunto son las claves y $conjuntoDeNumeros los arrays de valores yesyesyes

        foreach ($this->datos as $nombreConjunto => $conjuntoDeNumeros) {
            // Para cada conjunto, calculamos sus estadísticas usando los métodos
            // de esta misma clase (por eso usamos $this->), si es fuera de clase sería
            // de la forma tradicional que sabes
            $informeConjunto = [
                'media' => $this->calcularMedia($conjuntoDeNumeros),
                'mediana' => $this->calcularMediana($conjuntoDeNumeros),
                'moda' => $this->calcularModa($conjuntoDeNumeros),
            ];

            // Añadimos las estadísticas de este conjunto al informe final,
            // usando el nombre del conjunto como clave.
            $informeFinal[$nombreConjunto] = $informeConjunto;
        }
        return $informeFinal;
    }

    // Método para ingresar datos por el usuario
    public function ingresarDatos(): void
    {
        // Solicita al usuario que ingrese los datos para cada conjunto.
        // cuántos conjuntos de datos va a ingresar
        echo "¿Cuántos conjuntos de datos desea ingresar? ";
        $cantidadConjuntos = (int)trim(fgets(STDIN));
        $datos = [];
        for ($i = 1; $i <= $cantidadConjuntos; $i++) {
            echo "Ingrese el nombre del conjunto $i: ";
            $nombreConjunto = trim(fgets(STDIN));
            echo "Ingrese los números del conjunto (separados por espacios): ";
            $entrada = trim(fgets(STDIN));
            // Convertimos la entrada en un array de números
            $numeros = array_map('floatval', explode(' ', $entrada));
            // Guardamos el conjunto en el array de datos
            $datos[$nombreConjunto] = $numeros;
        }
        $this->datos = $datos;
    }
}
