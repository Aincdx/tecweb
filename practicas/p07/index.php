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
        <li><a href="?e=3&amp;div=7">Ejercicio 3 (while / do-while)</a></li>
        <li><a href="?e=4">Ejercicio 4 (ASCII 97–122)</a></li>
        <li><a href="?e=5">Ejercicio 5 (POST: edad/sexo)</a></li>
        <li><a href="?e=6">Ejercicio 6 (Parque vehicular)</a></li>
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

            // ------------------ Ejercicio 3 ------------------
      if ($e === 3) {
          echo '<h2>Ejercicio 3: Primer múltiplo con while y do-while</h2>';
          $div = isset($_GET['div']) ? (int)$_GET['div'] : null;
          if ($div === null || $div <= 0) {
              echo '<p>Proporciona un divisor en la URL, ejemplo: <code>?e=3&amp;div=7</code></p>';
          } else {
              $w = encontrarMultiploWhile($div);
              $d = encontrarMultiploDoWhile($div);

              echo '<h3>Versión while</h3>';
              echo '<p>Número: <strong>' . $w['numero'] . '</strong> — Intentos: ' . $w['intentos'] . '</p>';

              echo '<h3>Versión do-while</h3>';
              echo '<p>Número: <strong>' . $d['numero'] . '</strong> — Intentos: ' . $d['intentos'] . '</p>';
          }
      }
            // ------------------ Ejercicio 4 ------------------
      if ($e === 4) {
        echo '<h2>Ejercicio 4: Arreglo ASCII 97–122</h2>';
        $arr = arregloAsciiAZ();

        // Tabla con for (índices 97..122)
        echo '<h3>Con for</h3>';
        echo '<table class="tabla"><thead><tr><th>Índice</th><th>Valor</th></tr></thead><tbody>';
        for ($i = 97; $i <= 122; $i++) {
          echo '<tr><td>' . $i . '</td><td>' . htmlspecialchars($arr[$i], ENT_QUOTES, 'UTF-8') . '</td></tr>';
        }
        echo '</tbody></table>';

        // Tabla con foreach (recorriendo el arreglo asociativo)
        echo '<h3>Con foreach</h3>';
        echo '<table class="tabla"><thead><tr><th>Índice</th><th>Valor</th></tr></thead><tbody>';
        foreach ($arr as $k => $v) {
          echo '<tr><td>' . (int)$k . '</td><td>' . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . '</td></tr>';
        }
        echo '</tbody></table>';
      }
            //-- ------------------ Ejercicio 5 ------------------ -->
      if ($e === 5): ?>
        <h2>Ejercicio 5: Validación por edad y sexo (HTML5 POST → XHTML)</h2>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
          <?php
            // “Solicitud” recibida: procesamos y devolvemos XHTML
            $edad = isset($_POST['edad']) ? (int)$_POST['edad'] : null;
            $sexo = isset($_POST['sexo']) ? (string)$_POST['sexo'] : '';
            $msg  = validarPersona($edad, $sexo);
            $clase = str_starts_with($msg, 'Bienvenida') ? 'ok' : 'error';
          ?>
          <p><strong>Datos recibidos:</strong> Edad=
            <?= htmlspecialchars((string)$edad, ENT_QUOTES, 'UTF-8') ?>,
            Sexo=<?= htmlspecialchars($sexo, ENT_QUOTES, 'UTF-8') ?></p>
          <p class="<?= $clase ?>">
            <?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?>
          </p>
          <p><a href="?e=5">Nueva solicitud</a></p>

        <?php else: ?>
          <!-- “Solicitud” HTML5 -->
          <form method="post" action="?e=5" class="form" novalidate="novalidate">
            <fieldset>
              <legend>Datos</legend>

              <label for="edad">Edad:</label>
              <input type="number" name="edad" id="edad"
                    min="0" max="120" step="1" required="required"
                    inputmode="numeric" />

              <label for="sexo">Sexo:</label>
              <select name="sexo" id="sexo" required="required">
                <option value="">— Selecciona —</option>
                <option value="femenino">Femenino</option>
                <option value="masculino">Masculino</option>
                <option value="otro">Otro</option>
              </select>
            </fieldset>

            <button type="submit">Enviar</button>
          </form>
        <?php endif; ?>
      <?php endif; ?>
        
      
      <?php if ($e === 6): ?>
        <h2>Ejercicio 6: Parque vehicular (HTML5 POST → XHTML)</h2>
        <p>Usa uno de los formularios para enviar tu <em>solicitud</em> (HTML5). La <em>respuesta</em> es XHTML generado por PHP.</p>

        <!-- Solicitud: Buscar por matrícula -->
        <form method="post" action="?e=6" class="form" novalidate="novalidate">
          <fieldset>
            <legend>Búsqueda por matrícula</legend>

            <label for="matricula">Matrícula (formato LLLNNNN):</label>
            <input
              type="text"
              name="matricula"
              id="matricula"
              maxlength="7"
              required="required"
              pattern="^[A-Za-z]{3}\d{4}$"
              title="3 letras seguidas de 4 dígitos, ej. ABC1234"
              placeholder="ABC1234"
              inputmode="latin" />
          </fieldset>
          <button type="submit" name="accion" value="buscar">Buscar</button>
        </form>

        <!-- Solicitud: Ver todo -->
        <form method="post" action="?e=6" class="form">
          <fieldset>
            <legend>Listado completo</legend>
            <p>Enviar esta solicitud para mostrar todos los registros.</p>
          </fieldset>
          <button type="submit" name="accion" value="todo">Ver todos</button>
        </form>

        <hr />

        <?php
          // Procesar la “solicitud” HTML5 y generar la respuesta XHTML
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $parque = parqueVehicular();
              $accion = isset($_POST['accion']) ? $_POST['accion'] : '';

              if ($accion === 'buscar') {
                  $mat = isset($_POST['matricula']) ? strtoupper(trim($_POST['matricula'])) : '';
                  $valida = (bool)preg_match('/^[A-Z]{3}\d{4}$/', $mat);

                  if (!$valida) {
                      echo '<p class="error">Formato inválido. Usa 3 letras y 4 dígitos (ej. ABC1234).</p>';
                  } else {
                      $info = buscarPorMatricula($parque, $mat);
                      if ($info === null) {
                          echo '<p class="error">No se encontró la matrícula solicitada.</p>';
                      } else {
                          echo renderVehiculo($mat, $info);
                      }
                  }

              } elseif ($accion === 'todo') {
                  echo '<h3>Todos los autos registrados (' . count($parque) . ')</h3>';
                  foreach ($parque as $m => $info) {
                      echo renderVehiculo($m, $info);
                  }

                  // Imprime la estructura del arreglo como texto seguro para XHTML
                  echo '<h3>Estructura del arreglo (print_r)</h3>';
                  echo '<pre>' . htmlspecialchars(print_r($parque, true), ENT_QUOTES, 'UTF-8') . '</pre>';

              } else {
                  echo '<p>Envía una solicitud con alguno de los formularios.</p>';
              }
          } else {
              echo '<p>Envía una solicitud con alguno de los formularios para ver la respuesta.</p>';
          }
        ?>
      <?php endif; ?>


      
    </div>
  </body>
</html>
