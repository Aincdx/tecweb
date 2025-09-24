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

// ---------- Ejercicio 6 ----------
function parqueVehicular(): array {
    // Clave = matrícula (LLLNNNN)
    // Valores:
    //  'Auto' => ['marca'=>..., 'modelo'=>..., 'tipo'=>...]
    //  'Propietario' => ['nombre'=>..., 'ciudad'=>..., 'direccion'=>...]
    return [
        'UBN6338' => [
            'Auto' => ['marca' => 'HONDA', 'modelo' => 2020, 'tipo' => 'camioneta'],
            'Propietario' => ['nombre' => 'Alfonzo Esparza', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'C.U., Jardines de San Manuel']
        ],
        'UBN6339' => [
            'Auto' => ['marca' => 'MAZDA', 'modelo' => 2019, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Ma. del Consuelo Molina', 'ciudad' => 'Puebla, Pue.', 'direccion' => '97 oriente']
        ],
        'ABC1234' => [
            'Auto' => ['marca' => 'NISSAN', 'modelo' => 2018, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'Aldair Iglesias', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'Col. Universidades']
        ],
        'XYZ5678' => [
            'Auto' => ['marca' => 'CHEVROLET', 'modelo' => 2016, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Carmen Cerón', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'FC de la Computación']
        ],
        'MNO2468' => [
            'Auto' => ['marca' => 'VW', 'modelo' => 2021, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Juan Pérez', 'ciudad' => 'Cholula, Pue.', 'direccion' => 'Centro']
        ],
        'PUE2025' => [
            'Auto' => ['marca' => 'KIA', 'modelo' => 2022, 'tipo' => 'camioneta'],
            'Propietario' => ['nombre' => 'María López', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'Angelópolis']
        ],
        'QRS1357' => [
            'Auto' => ['marca' => 'TOYOTA', 'modelo' => 2017, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Luis García', 'ciudad' => 'Atlixco, Pue.', 'direccion' => 'Centro']
        ],
        'HJK7890' => [
            'Auto' => ['marca' => 'RENAULT', 'modelo' => 2015, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'Ana Torres', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'La Noria']
        ],
        'CIA4321' => [
            'Auto' => ['marca' => 'FORD', 'modelo' => 2014, 'tipo' => 'camioneta'],
            'Propietario' => ['nombre' => 'Carlos Sánchez', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'Hist. Centro']
        ],
        'BUA2024' => [
            'Auto' => ['marca' => 'SEAT', 'modelo' => 2020, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Sofía Ramírez', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'Zavaleta']
        ],
        'TEC7001' => [
            'Auto' => ['marca' => 'HYUNDAI', 'modelo' => 2019, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Miguel Ángel', 'ciudad' => 'Huejotzingo, Pue.', 'direccion' => 'Centro']
        ],
        'RFC7788' => [
            'Auto' => ['marca' => 'PEUGEOT', 'modelo' => 2018, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'Diana Cruz', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'Las Ánimas']
        ],
        'JCR1200' => [
            'Auto' => ['marca' => 'HONDA', 'modelo' => 2013, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Ernesto Domínguez', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'Satélite']
        ],
        'LMS9090' => [
            'Auto' => ['marca' => 'MAZDA', 'modelo' => 2016, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Laura Medina', 'ciudad' => 'San Andrés Cholula', 'direccion' => 'UDLAP']
        ],
        'PQR6543' => [
            'Auto' => ['marca' => 'VW', 'modelo' => 2012, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'Pedro Juárez', 'ciudad' => 'Puebla, Pue.', 'direccion' => 'Mayorazgo']
        ],
    ];
}

function buscarPorMatricula(array $parque, string $matricula): ?array {
    $m = strtoupper($matricula);
    return $parque[$m] ?? null;
}

function renderVehiculo(string $matricula, array $info): string {
    $a = $info['Auto'];
    $p = $info['Propietario'];
    $html  = '<div class="card">';
    $html .= '<h4>Matrícula: <code>' . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . '</code></h4>';
    $html .= '<ul>';
    $html .= '<li><strong>Auto:</strong> ' . htmlspecialchars($a['marca'], ENT_QUOTES, 'UTF-8')
           . ' ' . (int)$a['modelo'] . ' (' . htmlspecialchars($a['tipo'], ENT_QUOTES, 'UTF-8') . ')</li>';
    $html .= '<li><strong>Propietario:</strong> ' . htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') . '</li>';
    $html .= '<li><strong>Ciudad:</strong> ' . htmlspecialchars($p['ciudad'], ENT_QUOTES, 'UTF-8') . '</li>';
    $html .= '<li><strong>Dirección:</strong> ' . htmlspecialchars($p['direccion'], ENT_QUOTES, 'UTF-8') . '</li>';
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

