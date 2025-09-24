<?php
require __DIR__ . '/src/funciones.php';
header('Content-Type: application/xhtml+xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
  <head>
    <title>P07 – E1: múltiplo de 5 y 7</title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <link rel="stylesheet" href="public/styles.css" />
  </head>
  <body>
    <h1>Práctica 7 – Ejercicio 1</h1>
    <p>Usa la URL: <code>?e=1&amp;numero=35</code></p>

    <hr />

    <div id="contenido">
      <?php
      $e = isset($_GET['e']) ? (int)$_GET['e'] : 0;

      if ($e === 1) {
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
      } else {
        echo '<p>Agrega <code>?e=1&amp;numero=35</code> a la URL para probar.</p>';
      }
      ?>
    </div>
  </body>
</html>
