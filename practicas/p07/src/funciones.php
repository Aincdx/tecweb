<?php
// Una función por solución (PDF). E1: múltiplo de 5 y 7.
function esMultiploDe5y7(int $n): bool {
    return $n % 5 === 0 && $n % 7 === 0;
}

// E2: genera filas [a,b,c] hasta lograr impar, par, impar.
// Devuelve ['matriz' => Mx3, 'iteraciones' => M]
function generarSecuenciaImparParImpar(): array {
    $matriz = [];
    $iters  = 0;
    do {
        $a = mt_rand(0, 999);
        $b = mt_rand(0, 999);
        $c = mt_rand(0, 999);
        $matriz[] = [$a, $b, $c];
        $iters++;
    } while (!(($a % 2 !== 0) && ($b % 2 === 0) && ($c % 2 !== 0)));
    return ['matriz' => $matriz, 'iteraciones' => $iters];
}

// E3: buscar primer múltiplo usando while
function encontrarMultiploWhile(int $div): array {
    $intentos = 0;
    $n = mt_rand(1, 1000000);
    while ($n % $div !== 0) {
        $intentos++;
        $n = mt_rand(1, 1000000);
    }
    $intentos++; // cuenta el intento exitoso
    return ['numero' => $n, 'intentos' => $intentos];
}

// E3: buscar primer múltiplo usando do-while
function encontrarMultiploDoWhile(int $div): array {
    $intentos = 0;
    do {
        $n = mt_rand(1, 1000000);
        $intentos++;
    } while ($n % $div !== 0);
    return ['numero' => $n, 'intentos' => $intentos];
}

// E4: arreglo con índices 97..122 y valores 'a'..'z'
function arregloAsciiAZ(): array {
    $arr = [];
    for ($i = 97; $i <= 122; $i++) {
        $arr[$i] = chr($i);
    }
    return $arr;
}

// E5
function to_lower_utf8(string $s): string {
    return function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s);
}

function validarPersona(?int $edad, string $sexo): string {
    if ($edad === null || $edad < 0 || $edad > 120) {
        return 'Edad inválida.';
    }
    $sx = to_lower_utf8(trim($sexo));
    $esF = ($sx === 'femenino' || $sx === 'f' || $sx === 'mujer');
    if ($esF && $edad >= 18 && $edad <= 35) {
        return 'Bienvenida, usted está en el rango de edad permitido.';
    }
    return 'No cumple los criterios (sexo femenino y edad entre 18 y 35).';
}

