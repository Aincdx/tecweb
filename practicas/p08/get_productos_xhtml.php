<?php
@$link = new mysqli('127.0.0.1','root','Rextow24.a','marketzone');
if ($link->connect_errno) { die('Falló la conexión: '.$link->connect_error); }
$link->set_charset('utf8mb4');

$tope = isset($_GET['tope']) ? intval($_GET['tope']) : 10;

$stmt = $link->prepare(
  "SELECT id,nombre,marca,modelo,precio,unidades,imagen
   FROM productos WHERE unidades<=? ORDER BY unidades ASC, nombre ASC"
);
$stmt->bind_param("i",$tope);
$stmt->execute();
$res = $stmt->get_result();

header('Content-Type: application/xhtml+xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <title>Productos (≤ <?php echo $tope; ?>)</title>
    <style type="text/css">
      table{border-collapse:collapse;width:100%}
      th,td{border:1px solid #999;padding:6px;text-align:left}
      caption{font-weight:bold;margin:8px 0}
      img{max-width:60px}
    </style>
  </head>
  <body>
    <table>
      <caption>Listado de productos con ≤ <?php echo $tope; ?> unidades</caption>
      <thead><tr>
        <th>id</th><th>nombre</th><th>marca</th><th>modelo</th>
        <th>precio</th><th>unidades</th><th>imagen</th>
      </tr></thead>
      <tbody>
      <?php while($row=$res->fetch_assoc()){ ?>
        <tr>
          <td><?php echo (int)$row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['nombre'],ENT_QUOTES,'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars($row['marca'],ENT_QUOTES,'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars($row['modelo'],ENT_QUOTES,'UTF-8'); ?></td>
          <td><?php echo number_format((float)$row['precio'],2); ?></td>
          <td><?php echo (int)$row['unidades']; ?></td>
          <td><?php if(!empty($row['imagen'])){ ?>
            <img src="<?php echo htmlspecialchars($row['imagen'],ENT_QUOTES,'UTF-8'); ?>" alt="img" />
          <?php } ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </body>
</html>
