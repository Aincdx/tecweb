<?php
// concurso.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(400);
  echo 'Abre formulario.php y envía el formulario.';
  exit;
}
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
$features = isset($_POST['features']) && is_array($_POST['features']) ? $_POST['features'] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro Completado</title>
  <style>
    body{margin:20px;background:#C4DF9B;font-family:Verdana,Helvetica,sans-serif;font-size:90%}
    h1{color:#005825;border-bottom:1px solid #005825}
    h2{font-size:1.2em;color:#4A0048}
  </style>
</head>
<body>
  <h1>MUCHAS GRACIAS</h1>
  <p>Recibimos la siguiente información de tu registro:</p>

  <h2>Acerca de ti</h2>
  <ul>
    <li><strong>Nombre:</strong> <em><?= h($_POST['name']) ?></em></li>
    <li><strong>E-mail:</strong> <em><?= h($_POST['email']) ?></em></li>
    <li><strong>Teléfono:</strong> <em><?= h($_POST['phone']) ?></em></li>
  </ul>
  <p><strong>Tu historia:</strong> <em><?= h($_POST['story']) ?></em></p>

  <h2>Tu diseño</h2>
  <ul>
    <li><strong>Color:</strong> <em><?= h($_POST['color']) ?></em></li>
    <?php foreach ($features as $i => $f): ?>
      <li><strong>Característica <?= $i+1 ?>:</strong> <em><?= h($f) ?></em></li>
    <?php endforeach; ?>
    <li><strong>Talla:</strong> <em><?= h($_POST['size']) ?></em></li>
  </ul>
</body>
</html>
