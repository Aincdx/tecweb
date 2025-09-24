<?php
require __DIR__ . '/src/funciones.php';
header('Content-Type: application/xhtml+xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
  <head>
    <title>P07 – Ejercicios 1 y 2</title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <link rel="stylesheet" href="public/styles.css" />
  </head>
  <body>
    <h1>Práctica 7 – PHP (Funciones, GET, POST, arreglos y ciclos)</h1>

    <nav>
      <ul>
        <li><a href="?e=1&amp;numero=35">Ejercicio 1 (múltiplo de 5 y 7)</a></li>
        <li><a href="?e=2">Ejercicio 2 (impar, par, impar)</a></li>
      </ul>
    </nav>

    <hr />

    <div id="contenido">
      <?php
      $e = isset($_GET['e']) ? (int)$_GET['e'] : 0;

      // ------------------ Ejercicio 1 ------------------
      if ($e === 1) {
        echo '<h2>Ejercicio 1: ¿Es múltiplo de 5 y 7?</h2>';
        $nStr = isset($_GET['numero']) ? $_GET['numero'] : null;
        if ($nStr === null || $nStr === '') {
          echo '<p>Falta el parámetro <code>numero</code> en la URL.</p>';
        } else {
          $n = (int)$nStr;
          $ok = esMultiploDe5y7($n);
          echo '<p>Número ingresado: <strong>' . htmlspecialchars((string)$n, ENT_QUOTES, 'UTF-8') . '</strong></p>';
          echo $ok
            ? '<p class="ok">Sí, es múltiplo de 5 y 7.</p>'
            : '<p class="error">No es múltiplo de 5 y 7.</p>';
        }
      }

      // ------------------ Ejercicio 2 ------------------
      if ($e === 2) {
        echo '<h2>Ejercicio 2: Generación hasta impar, par, impar</h2>';
        $res = generarSecuenciaImparParImpar();
        $mat = $res['matriz'];
        $iters = $res['iteraciones'];

        echo '<table class="tabla"><thead><tr><th>n1</th><th>n2</th><th>n3</th></tr></thead><tbody>';
        foreach ($mat as $fila) {
          echo '<tr><td>' . (int)$fila[0] . '</td><td>' . (int)$fila[1] . '</td><td>' . (int)$fila[2] . '</td></tr>';
        }
        echo '</tbody></table>';

        $totalNums = 3 * $iters;
        echo '<p>Total: <strong>' . $totalNums . '</strong> números en <strong>' . $iters . '</strong> iteraciones.</p>';
      }

      if ($e === 0) {
        echo '<p>Selecciona un ejercicio en el menú superior.</p>';
      }
      ?>
    </div>
  </body>
</html>
