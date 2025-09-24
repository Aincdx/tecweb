<?php
// Una función por solución (PDF). E1: múltiplo de 5 y 7.
function esMultiploDe5y7(int $n): bool {
    return $n % 5 === 0 && $n % 7 === 0;
}
