<?php
// ====== CONEXIÓN ======
$DB_PASS = 'Rextow24.a';
@$link = new mysqli('127.0.0.1', 'root', $DB_PASS, 'marketzone');
if ($link->connect_errno) { die('Falló la conexión: '.$link->connect_error); }
$link->set_charset('utf8mb4');

// ====== PARÁMETRO ======
$tope = isset($_GET['tope']) ? intval($_GET['tope']) : 10;

// ====== CONSULTA ======
$stmt = $link->prepare(
  "SELECT id,nombre,marca,modelo,precio,unidades,detalles,imagen
   FROM productos
   WHERE unidades<=?
   ORDER BY unidades ASC, nombre ASC"
);
$stmt->bind_param("i", $tope);
$stmt->execute();
$res = $stmt->get_result();

// ====== ENCABEZADOS XHTML ======
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
      body{margin:0;padding:24px;background:#f8fafc;color:#111827;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif}
      h1{margin:0 0 16px 0;font-size:24px;letter-spacing:.02em}
      .table-wrapper{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 6px 16px rgba(17,24,39,.06);overflow:hidden}
      table{border-collapse:collapse;width:100%}
      thead th{background:#111827;color:#fff;text-align:left;font-weight:600;font-size:13px;padding:12px 16px}
      tbody td{padding:14px 16px;vertical-align:top;border-top:1px solid #e5e7eb}
      tbody tr:nth-child(even){background:#f9fafb}
      tbody tr:hover{background:#f3f4f6}
      .price{white-space:nowrap}
      .units-low{color:#b45309;font-weight:600}
      img.prod{max-width:72px;height:auto;border-radius:10px;border:1px solid #e5e7eb}
      .muted{color:#6b7280}
      caption{display:none}
    </style>
  </head>
  <body>
    <h1>PRODUCTO</h1>
    <div class="table-wrapper">
      <table>
        <caption>Listado de productos</caption>
        <thead>
          <tr>
            <th>#</th><th>Nombre</th><th>Marca</th><th>Modelo</th>
            <th>Precio</th><th>Unidades</th><th>Detalles</th><th>Imagen</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $res->fetch_assoc()) { ?>
          <tr>
            <td><?php echo (int)$row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['marca'],  ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['modelo'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="price">$<?php echo number_format((float)$row['precio'], 2); ?></td>
            <td><?php echo (int)$row['unidades'] <= 10
                    ? '<span class="units-low">'.(int)$row['unidades'].'</span>'
                    : (int)$row['unidades']; ?></td>
            <td class="muted"><?php echo htmlspecialchars((string)$row['detalles'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
              <?php if (!empty($row['imagen'])) { ?>
                <img class="prod"
                     src="<?php echo htmlspecialchars($row['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                     alt="imagen de <?php echo htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?>" />
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
