<?php
// ---- Conexión
$DB_HOST='127.0.0.1'; $DB_USER='root'; $DB_PASS=''; $DB_NAME='marketzone';
@$link = new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if($link->connect_errno){ die('Falló la conexión: '.$link->connect_error); }
$link->set_charset('utf8mb4');

// ---- Utilidades
function x($k){ return isset($_POST[$k]) ? trim($_POST[$k]) : null; }
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ---- Datos recibidos
$nombre   = x('nombre');
$marca    = x('marca');
$modelo   = x('modelo');
$precio   = x('precio');
$unidades = x('unidades');
$detalles = x('detalles');
$imagen   = x('imagen');

// ---- Validación mínima
if($nombre===''||$marca===''||$modelo===''||$precio===null||$unidades===null){
  renderPage('warn','Datos incompletos.');
  exit;
}

// Si contiene comillas o signos raros en nombre/marca/modelo
$pattern = '/[\'\"\?\!;]/';
if(preg_match($pattern,$nombre) || preg_match($pattern,$marca) || preg_match($pattern,$modelo)){
  renderPage('warn','Advertencia: No uses comillas ni signos de interrogación/exclamación en Nombre, Marca o Modelo.');
  exit;
}

// ---- Verifica duplicado lógico
$stmt=$link->prepare("SELECT COUNT(*) FROM productos WHERE nombre=? AND marca=? AND modelo=? AND eliminado=0");
$stmt->bind_param("sss",$nombre,$marca,$modelo);
$stmt->execute(); $stmt->bind_result($c); $stmt->fetch(); $stmt->close();
if($c>0){
  renderPage('warn','Duplicado: ya existe un producto con ese Nombre/Marca/Modelo.');
  exit;
}

// ---- Inserción
// Versión anterior (comentada):
// $stmt=$link->prepare("INSERT INTO productos
//  (nombre,marca,modelo,precio,detalles,unidades,imagen,eliminado)
//  VALUES (?,?,?,?,?,?,?,0)");
// $stmt->bind_param("sssdsis",$nombre,$marca,$modelo,$precio,$detalles,$unidades,$imagen);

// Nueva versión con column names (sin id ni eliminado):
$stmt = $link->prepare("INSERT INTO productos
 (nombre,marca,modelo,precio,detalles,unidades,imagen)
 VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssdsis",$nombre,$marca,$modelo,$precio,$detalles,$unidades,$imagen);

if(!$stmt->execute()){ die('Error insert: '.$stmt->error); }
$id=$stmt->insert_id; $stmt->close();

// ---- Éxito
renderPage('ok',null,[
  'id'=>$id,'nombre'=>$nombre,'marca'=>$marca,'modelo'=>$modelo,
  'precio'=>$precio,'unidades'=>$unidades,'detalles'=>$detalles,'imagen'=>$imagen
]);


function renderPage($type,$msg=null,$data=null){
  header('Content-Type: text/html; charset=UTF-8'); ?>
  <!doctype html>
  <html lang="es">
  <head>
    <meta charset="utf-8">
    <title><?= $type==='ok'?'Producto insertado':'Aviso' ?></title>
    <style>
      body{font-family:Arial,Helvetica,sans-serif;background:#f9fafb;padding:24px;}
      .card{max-width:600px;margin:auto;background:#fff;border:1px solid #ddd;border-radius:10px;padding:20px;}
      h1{font-size:20px;margin-top:0;}
      .ok{color:#16a34a;}
      .warn{color:#b45309;}
      ul{padding-left:18px;}
      a{color:#0ea5e9;text-decoration:none;font-weight:bold;}
    </style>
  </head>
  <body>
    <div class="card">
      <?php if($type==='ok'): ?>
        <h1 class="ok">✔ Producto insertado</h1>
        <ul>
          <li>ID: <?= (int)$data['id'] ?></li>
          <li>Nombre: <?= h($data['nombre']) ?></li>
          <li>Marca: <?= h($data['marca']) ?></li>
          <li>Modelo: <?= h($data['modelo']) ?></li>
          <li>Precio: $<?= number_format((float)$data['precio'],2) ?></li>
          <li>Unidades: <?= (int)$data['unidades'] ?></li>
        </ul>
      <?php else: ?>
        <h1 class="warn">⚠ <?= $msg ?></h1>
      <?php endif; ?>
      <p>
        <a href="formulario_productos.html">Volver al formulario</a> |
        <a href="get_productos_vigentes.php">Ver productos</a>
      </p>
    </div>
  </body>
  </html>
<?php }
