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
