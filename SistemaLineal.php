<?php
abstract class SistemaEcuaciones {
        // Método calcular resultado abstracto
        abstract public function calcularResultado(): array;
        abstract public function validarConsistencia(): bool;
    }
class SistemaLineal extends SistemaEcuaciones
{
    //atributos
    private array $coeficientesEq1;
    private array $coeficientesEq2;

    //constructor
    public function __construct(array $coeficientesEq1, array $coeficientesEq2)
    {
        $this->coeficientesEq1 = $coeficientesEq1;
        $this->coeficientesEq2 = $coeficientesEq2;
    }

    //getters
    public function getCoeficientesEq1(): array
    {
        return $this->coeficientesEq1;
    }
    public function getCoeficientesEq2(): array
    {
        return $this->coeficientesEq2;
    }
    //setters
    public function setCoeficientesEq1(array $coeficientesEq1): void
    {
        $this->coeficientesEq1 = $coeficientesEq1;
    }
    public function setCoeficientesEq2(array $coeficientesEq2): void
    {
        $this->coeficientesEq2 = $coeficientesEq2;
    }
    //requiero un metodo para una validación de input, numérico int o flotante pero no letra
    public function numInput(string $num): float
    {
        $input = readline($num);
        while (!is_numeric($input)) {
            echo "Entrada no válida. Por favor, introduzca un número.\n";
            $input = readline($num);
        }
        return (float) $input;
    }
    //**metodo para que el usuario introduzca los coeficientes de las ecuaciones por consola
    public function introducirCoeficientes(): void
    {
        $eq1 = [];
        $eq2 = [];
        echo "Introduzca los coeficientes de la primera ecuación (x1, y1, k1):\n";
        $x1 = $this->numInput("x1: ");
        $y1 = $this->numInput("y1: ");
        $k1 = $this->numInput("k1: ");
        $eq1 = [
            'x' => $x1,
            'y' => $y1,
            'k' => $k1
        ];
        $this->setCoeficientesEq1($eq1);

        echo "Introduzca los coeficientes de la segunda ecuación (x2, y2, k2):\n";
        $x2 = $this->numInput("x2: ");
        $y2 = $this->numInput("y2: ");
        $k2 = $this->numInput("k2: ");
        $eq2 = [
            'x' => $x2,
            'y' => $y2,
            'k' => $k2
        ];
        $this->setCoeficientesEq2($eq2);
    }
    //método para validar la consistencia del sistema
    public function validarConsistencia(): bool
    {
        $coef1 = $this->getCoeficientesEq1();
        $coef2 = $this->getCoeficientesEq2();
        $x1 = $coef1['x'];
        $y1 = $coef1['y'];
        $x2 = $coef2['x'];
        $y2 = $coef2['y'];
        $determinante = $x1 * $y2 - $y1 * $x2;
        echo "Determinante: $determinante\n";
        return $determinante == 0 ? false : true;
    }
    //método para calcular el resultado
    public function calcularResultado(): array {
        //verificamos si el sistema es consistente
        if (!$this->validarConsistencia()) {
            echo "El sistema es inconsistente, no se puede resolver.\n";
            return ['x' => null, 'y' => null];
        }
        //Obtenemos los coeficientes de las ecuaciones e iniciamos x e y en 0
        $coef1 = $this->getCoeficientesEq1();
        $coef2 = $this->getCoeficientesEq2();
        $x1 = $coef1['x'];
        $y1 = $coef1['y'];
        $k1 = $coef1['k'];
        $x2 = $coef2['x'];
        $y2 = $coef2['y'];
        $k2 = $coef2['k'];
        $x = 0;
        $y = 0;
        // Caso 1: Si x1 no es 0, entonces despejamos x en eq1 para reemplazar en eq2
        if ($x1 != 0) {
            //como sabemos que no es 0 entonces despejamos, tenemos x1x + y1y = k1
            //* x se despeja como x = (k1 - y1y) / x1
            //al reemplazar en eq2 tenemos x2((k1 - y1y) / x1) + y2y = k2
            //multiplicamos x1 parar eliminar el denominador
            //x2(k1 - y1y) + x1y2y = k2 * x1 y aplicamos distributiva
            // x2k1 - x2y1y + x1y2y = k2 * x1 y asociativa x2k1 - (x2y1 - x1y2)y = k2 * x1
            // despejamos y = x2k1 - k2 * x1 / (x2y1 - x1y2) aqu+i tenemos ya valores conocidos
            //por lo tanto calculamos y
            //! DEBUGGEA AQUÍ ESTIVO, g3i si no
            $y = ($x2 * $k1 - $k2 * $x1) / ($x2 * $y1 - $x1 * $y2);
            //* con el valor de y calculamos x de la marcada arriba
            $x = ($k1 - $y1 * $y) / $x1;
            return ['x' => $x, 'y' => $y];
        } else if ($y1 != 0) {
            //Caso 2: Si salta acá es porque x1 = 0 entonces verificamos si y1 no es 0
            //de lo contrario el sistema es inconsistente w, pinche validarConsistencia valepapurañonga
            //entonces despejamos y en eq1 para reemplazar en eq2
            //* despejamos y = k1 / y1
            //reemplzamos en eq2 x2x + y2(k1 / y1) = k2
            //multiplicamos por y1 para eliminar denominador
            //y1x2x + y2k1 = k2 * y1
            //despejamos x = (k2 * y1 - y2k1) / y1x2
            $x = ($k2 * $y1 - $y2 * $k1) / ($y1 * $x2);
            //* como y = k1 / y1 ya lo tenemos calculado
            $y = $k1 / $y1;
            return ['x' => $x, 'y' => $y];
        } else {
            //en caso de que validarConsistencia no haya funcionado y x1 = 0 e y1 = 0
            //entonces el sistema es inconsistente, no se puede resolver
            //concluyo que validarConsistencia valió pa purañonga
            echo "No se puede resolver el sistema debido a que es inconsistente.\n
            El determinante es: {[$x1 * $y2 - $y1 * $x2]}\n";
            return ['x'=>"xd", 'y'=>"xd"];
        }
    }
}
?>