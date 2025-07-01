<?php
require_once 'SistemaLineal.php';
require_once 'EstadisticaBasica.php';
require_once 'Polinomio.php';
require_once 'Matrices.php';
require_once 'EcuacionDiferencial.php';

do {
    echo "||=========TAREA 1 SEGUNDO PARCIAL=========||\n";
    echo "|| 1. Sistema Lineal                                      ||\n";
    echo "|| 2. Estadística Básica                                ||\n";
    echo "|| 3. Polinomio                                            ||\n";
    echo "|| 4. Matrices                                            ||\n";
    echo "|| 5. Ecuación Diferencial                             ||\n";
    echo "|| 6. Salir                                                 ||\n";
    echo "||=======================================||\n";
    $opcion = readline("Seleccione una opción: ");

    //validar la opción ingresada sea un número entre 1 y 6
    if (!is_numeric($opcion) || $opcion < 1 || $opcion > 6) {
        echo "Opción inválida. Por favor, ingrese un número entre 1 y 6.\n";
        continue;
    }

    //switch
    switch ($opcion) {
        case 1:
            $sistemaLineal = new SistemaLineal([], []);
            $sistemaLineal->introducirCoeficientes();
            $resultado = $sistemaLineal->calcularResultado();
            echo "El resultado del sistema lineal es:\n";
            if ($resultado['x'] !== null && $resultado['y'] !== null) {
                echo "x = " . $resultado['x'] . "\n";
                echo "y = " . $resultado['y'] . "\n";
            } else {
                echo "No se pudo resolver el sistema.\n";
            }
            break;
        case 2:
            echo "Nota. Se hizo lo que se pudo pero solo funciona con enteros\n";
            $estadistica = new EstadisticaBasica();
            $estadistica->ingresarDatos();
            $informe = $estadistica->generarInforme();
            foreach ($informe as $nombreConjunto => $estadisticas) {
                echo "Conjunto: $nombreConjunto\n";
                echo "Media: " . $estadisticas['media'] . "\n";
                echo "Mediana: ";
                if (is_array($estadisticas['mediana'])) {
                    echo implode(', ', $estadisticas['mediana']);
                } else {
                    echo $estadisticas['mediana'];
                }
                echo "\nModa: " . implode(', ', $estadisticas['moda']) . "\n";
                echo "#--------------------------------------#\n";
            }
            break;
        case 3:
            // Almacenar los polinomios ingresados en un array.
            $polinomiosGuardados = [];
            $contadorPolinomios = 1;

            echo "--- Ingresar Polinomio " . $contadorPolinomios . " ---\n";
            $tempPolinomio = new Polinomio([]); 
            $polinomiosGuardados["polinomio" . $contadorPolinomios] = $tempPolinomio->ingresarPolinomio();
            echo "Polinomio " . $contadorPolinomios . " ingresado: " . $polinomiosGuardados["polinomio" . $contadorPolinomios]->mostrarPolinomio() . "\n";
            $contadorPolinomios++;

            echo "Desea agregar otro polinomio? (s/n): ";
            $respuesta = strtolower(trim(readline()));

            while ($respuesta === 's') {
                echo "--- Ingresar Polinomio " . $contadorPolinomios . " ---\n";
                $tempPolinomio = new Polinomio([]); 
                $polinomiosGuardados["polinomio" . $contadorPolinomios] = $tempPolinomio->ingresarPolinomio();
                echo "Polinomio " . $contadorPolinomios . " ingresado: " . $polinomiosGuardados["polinomio" . $contadorPolinomios]->mostrarPolinomio() . "\n";
                $contadorPolinomios++;
                echo "Desea agregar otro polinomio? (s/n): ";
                $respuesta = strtolower(trim(readline()));
            }

            if (empty($polinomiosGuardados)) {
                echo "No se ingresó ningún polinomio. Volviendo al menú principal.\n";
                break;
            }
            echo "\n--- Polinomios disponibles ---\n";
            foreach ($polinomiosGuardados as $key => $pol) {
                echo " $key: " . $pol->mostrarPolinomio() . "\n";
            }
            echo "#----------------------------#\n";


            // Aquí el menú para las operaciones con polinomios
            do {
                echo "\nSeleccione una operación:\n";
                echo "1. Sumar polinomios\n";
                echo "2. Evaluar polinomio en x\n";
                echo "3. Derivada del polinomio\n";
                echo "4. Mostrar todos los polinomios ingresados\n";
                echo "5. Volver al menú principal\n";
                $operacion = readline("Ingrese el número de la operación: ");

                switch ($operacion) {
                    case 1:
                        if ($contadorPolinomios < 2) {
                            echo "Necesitas al menos dos polinomios para sumar.\n";
                            break;
                        }
                        echo "Ingrese el identificador del PRIMER polinomio (ej: polinomio1): ";
                        $idPolinomio1 = strtolower(trim(readline()));
                        echo "Ingrese el identificador del SEGUNDO polinomio (ej: polinomio2): ";
                        $idPolinomio2 = strtolower(trim(readline()));

                        if (isset($polinomiosGuardados[$idPolinomio1]) && isset($polinomiosGuardados[$idPolinomio2])) {
                            $polinomio1 = $polinomiosGuardados[$idPolinomio1];
                            $polinomio2 = $polinomiosGuardados[$idPolinomio2];
                            $suma = $polinomio1->sumar($polinomio2);
                            echo "La suma de (" . $polinomio1->mostrarPolinomio() . ") y (" . $polinomio2->mostrarPolinomio() . ") es: " . $suma->mostrarPolinomio() . "\n";
                        } else {
                            echo "Uno o ambos polinomios no encontrados. Revise los identificadores.\n";
                        }
                        break;
                    case 2:
                        echo "Ingrese el identificador del polinomio a evaluar (ej: polinomio1): ";
                        $idPolinomioEvaluar = strtolower(trim(readline()));
                        if (isset($polinomiosGuardados[$idPolinomioEvaluar])) {
                            $polinomioAEvaluar = $polinomiosGuardados[$idPolinomioEvaluar];
                            echo "Ingrese el punto x para evaluar el polinomio (" . $polinomioAEvaluar->mostrarPolinomio() . "): ";
                            $punto = (float)readline(); 
                            $resultadoEvaluacion = $polinomioAEvaluar->evaluar($punto);
                            echo "Resultado de la evaluación en x=$punto: " . $resultadoEvaluacion . "\n";
                        } else {
                            echo "Polinomio no encontrado. Revise el identificador.\n";
                        }
                        break;
                    case 3:
                        echo "Ingrese el identificador del polinomio a derivar (ej: polinomio1): ";
                        $idPolinomioDerivar = strtolower(trim(readline()));
                        if (isset($polinomiosGuardados[$idPolinomioDerivar])) {
                            $polinomioADerivar = $polinomiosGuardados[$idPolinomioDerivar];
                            $derivada = $polinomioADerivar->derivada();
                            echo "La derivada de (" . $polinomioADerivar->mostrarPolinomio() . ") es: " . $derivada->mostrarPolinomio() . "\n";
                        } else {
                            echo "Polinomio no encontrado. Revise el identificador.\n";
                        }
                        break;
                    case 4: // Opción para mostrar todos los polinomios
                        echo "\n--- Polinomios Ingresados ---\n";
                        if (empty($polinomiosGuardados)) {
                            echo "No hay polinomios ingresados aún.\n";
                        } else {
                            foreach ($polinomiosGuardados as $key => $pol) {
                                echo " $key: " . $pol->mostrarPolinomio() . "\n";
                            }
                        }
                        echo "#----------------------------#\n";
                        break;
                    case 5:
                        echo "Volviendo al menú principal de la Tarea 1.\n";
                        break;
                    default:
                        echo "Operación no válida. Por favor, ingrese un número entre 1 y 5.\n";
                }
            } while ($operacion != 5);

            break;
        case 4:
            $matricesGuardadas = []; 
            $contadorMatrices = 1;

            do {
                echo "\n--- Ingresar Matriz " . $contadorMatrices . " ---\n";
                $matrizTemporal = new Matriz([]); 
                $matrizIngresada = $matrizTemporal->ingresarMatriz(); 
                echo "Matriz " . $contadorMatrices . " ingresada:\n";
                $matrizIngresada->imprimir();
                $matricesGuardadas["matriz" . $contadorMatrices] = $matrizIngresada;
                $contadorMatrices++;

                echo "Desea agregar otra matriz? (s/n): ";
                $respuesta = strtolower(trim(readline()));
            } while ($respuesta === 's');
            if (empty($matricesGuardadas)) {
                echo "No se ingresó ninguna matriz. Volviendo al menú principal.\n";
                break;
            }
            echo "\n--- Matrices disponibles para operaciones ---\n";
            foreach ($matricesGuardadas as $key => $matriz) {
                echo " $key:\n";
                $matriz->imprimir();
                echo "#----------------------------#\n";
            }
            do {
                echo "\nSeleccione una operación con Matrices:\n";
                echo "1. Multiplicar matrices\n";
                echo "2. Calcular inversa de una matriz\n";
                echo "3. Calcular determinante de una matriz\n";
                echo "4. Mostrar todas las matrices ingresadas\n";
                echo "5. Volver al menú principal\n";
                $operacionMatriz = readline("Ingrese el número de la operación: ");

                switch ($operacionMatriz) {
                    case 1: 
                        if ($contadorMatrices < 2) {
                            echo "Necesita al menos dos matrices para multiplicar.\n";
                            break;
                        }
                        echo "Ingrese el identificador de la PRIMERA matriz (ej: matriz1): ";
                        $idMatriz1 = strtolower(trim(readline()));
                        echo "Ingrese el identificador de la SEGUNDA matriz (ej: matriz2): ";
                        $idMatriz2 = strtolower(trim(readline()));

                        if (isset($matricesGuardadas[$idMatriz1]) && isset($matricesGuardadas[$idMatriz2])) {
                            $matriz1 = $matricesGuardadas[$idMatriz1];
                            $matriz2 = $matricesGuardadas[$idMatriz2];
                            echo "Intentando multiplicar la matriz '$idMatriz1' por la matriz '$idMatriz2'.\n";
                            $resultadoMultiplicacion = $matriz1->multiplicar($matriz2);
                            if ($resultadoMultiplicacion instanceof Matriz) {
                                echo "Resultado de la multiplicación:\n";
                                $resultadoMultiplicacion->imprimir();
                            } else {
                                echo "No se pudo realizar la multiplicación debido a dimensiones incompatibles o un error.\n";
                            }
                        } else {
                            echo "Uno o ambos identificadores de matriz no son válidos. Revise su entrada.\n";
                        }
                        break;

                    case 2: 
                        echo "Ingrese el identificador de la matriz para calcular su inversa (ej: matriz1): ";
                        $idMatrizInversa = strtolower(trim(readline()));

                        if (isset($matricesGuardadas[$idMatrizInversa])) {
                            $matrizOriginal = $matricesGuardadas[$idMatrizInversa];
                            $elementos = $matrizOriginal->getElementos();
                            $filas = count($elementos);
                            $columnas = ($filas > 0) ? count($elementos[array_key_first($elementos)]) : 0;

                            if ($filas != $columnas || $filas == 0) {
                                echo "La matriz '$idMatrizInversa' no es cuadrada o está vacía, no se puede calcular su inversa.\n";
                            } else {
                                echo "Calculando la inversa de la matriz '$idMatrizInversa':\n";
                                $inversa = $matrizOriginal->inversa();
                                if ($inversa instanceof Matriz) {
                                    echo "Inversa de la matriz:\n";
                                    $inversa->imprimir();
                                } else {
                                    
                                    echo "No se pudo calcular la inversa (la matriz podría no ser invertible).\n";
                                }
                            }
                        } else {
                            echo "Identificador de matriz no válido. Revise su entrada.\n";
                        }
                        break;

                    case 3:
                        echo "Ingrese el identificador de la matriz para calcular su determinante (ej: matriz1): ";
                        $idMatrizDeterminante = strtolower(trim(readline()));

                        if (isset($matricesGuardadas[$idMatrizDeterminante])) {
                            $matrizOriginal = $matricesGuardadas[$idMatrizDeterminante];
                            echo "Calculando el determinante de la matriz '$idMatrizDeterminante':\n";
                            $determinante = $matrizOriginal->determinante(); 
                            if ($determinante !== 0) { 
                                echo "Determinante de la matriz: $determinante\n";
                            } else {
                                echo "No se pudo calcular el determinante (la matriz podría no ser cuadrada o es singular).\n";
                            }
                        } else {
                            echo "Identificador de matriz no válido. Revise su entrada.\n";
                        }
                        break;

                    case 4:
                        echo "\n--- Matrices Ingresadas ---\n";
                        if (empty($matricesGuardadas)) {
                            echo "No hay matrices ingresadas aún.\n";
                        } else {
                            foreach ($matricesGuardadas as $key => $matriz) {
                                echo " $key:\n";
                                $matriz->imprimir();
                                echo "#----------------------------#\n";
                            }
                        }
                        break;
                            }
                    } while ($operacionMatriz != 5);
        
                    break; // <-- Esta llave cierra el case 4 correctamente
        
                case 5:
                    echo "--- Resolución de Ecuaciones Diferenciales (Método de Euler) ---\n";
                    echo "Seleccione una ecuación diferencial de ejemplo para resolver:\n";
                    echo "1. dy/dx = x + y     (y(0)=1, x0=0)\n";
                    echo "2. dy/dx = -2xy      (y(0)=1, x0=0)\n";
                    echo "3. dy/dx = x - y     (y(1)=2)\n";
                    echo "4. dy/dx = 2x - 3y + 1 (y(1)=5)\n";
                    echo "5. Volver al menú principal\n";
        
                    $opcionEcuacion = readline("Ingrese el número de la ecuación: ");
        
                    $funcion_f = null;
                    $nombreEcuacion = "";
                    $x0_ejemplo = 0;
                    $y0_ejemplo = 0;
        
                    switch ($opcionEcuacion) {
                        case 1: // Era la opción 3 original
                            $funcion_f = function($x, $y) {
                                return $x + $y;
                            };
                            $nombreEcuacion = "dy/dx = x + y";
                            $x0_ejemplo = 0;
                            $y0_ejemplo = 1;
                            break;
                        case 2: // Era la opción 4 original
                            $funcion_f = function($x, $y) {
                                return -2 * $x * $y;
                            };
                            $nombreEcuacion = "dy/dx = -2xy";
                            $x0_ejemplo = 0;
                            $y0_ejemplo = 1;
                            break;
                        case 3: // Era la opción 5 original
                            $funcion_f = function($x, $y) {
                                return $x - $y;
                            };
                            $nombreEcuacion = "dy/dx = x - y";
                            $x0_ejemplo = 1;
                            $y0_ejemplo = 2;
                            break;
                        case 4: // Era la opción 6 original
                            $funcion_f = function($x, $y) {
                                return 2 * $x - 3 * $y + 1;
                            };
                            $nombreEcuacion = "dy/dx = 2x - 3y + 1";
                            $x0_ejemplo = 1;
                            $y0_ejemplo = 5;
                            break;
                        case 5:
                            echo "Volviendo al menú principal.\n";
                            break;
                        default:
                            echo "Opción de ecuación no válida. Volviendo al menú principal.\n";
                            break;
                    }
        
                    echo "\nHa seleccionado: " . $nombreEcuacion . " con condiciones iniciales x0=" . $x0_ejemplo . ", y0=" . $y0_ejemplo . "\n";
                    echo "Ahora, ingrese los parámetros de paso para la resolución (Método de Euler):\n";
        
                    $h = (float)readline("Ingrese el tamaño del paso (h): ");
                    $n = (int)readline("Ingrese el número de pasos (n): ");
        
                    if ($h <= 0 || $n <= 0) {
                        echo "Error: El tamaño del paso (h) y el número de pasos (n) deben ser mayores que cero.\n";
                        readline("Presiona Enter para continuar...");
                        break;
                    }
        
                    $condicionesIniciales = [
                        'x0' => $x0_ejemplo,
                        'y0' => $y0_ejemplo
                    ];
        
                    $parametrosEuler = [
                        'h' => $h,
                        'n' => $n
                    ];
        
                    $solverEuler = new EulerNumerico();
                    $solucion = $solverEuler->resolverEuler($funcion_f, $condicionesIniciales, $parametrosEuler);
        
                    echo "\nSolución numérica (x, y) para " . $nombreEcuacion . ":\n";
                    echo "---------------------------------------------------\n";
                    foreach ($solucion as $x_val => $y_val) {
                        echo "x = " . sprintf("%.4f", $x_val) . ", y = " . sprintf("%.4f", $y_val) . "\n";
                    }
                    echo "---------------------------------------------------\n";
                    readline("Presiona Enter para continuar...");
                    break;
                default:
                    echo "Opción no válida. Por favor, ingrese un número entre 1 y 6.\n";
                    break;
            }
        } while ($opcion != 6);
