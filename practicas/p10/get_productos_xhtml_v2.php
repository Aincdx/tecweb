<?php
// ====== CONEXIÓN ======
$DB_PASS = '';
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
      /*<![CDATA[*/
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
      .btn-edit{
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        min-width: 80px;
      }
      .btn-edit:hover{
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
      }
      .btn-edit:active{
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
      }
      /*]]>*/
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
            <th>Modificar</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $res->fetch_assoc()) { ?>
          <tr id="<?php echo (int)$row['id']; ?>">
            <td class="row-data"><?php echo (int)$row['id']; ?></td>
            <td class="row-data"><?php echo htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="row-data"><?php echo htmlspecialchars($row['marca'],  ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="row-data"><?php echo htmlspecialchars($row['modelo'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="row-data price">$<?php echo number_format((float)$row['precio'], 2); ?></td>
            <td class="row-data"><?php echo (int)$row['unidades'] <= 10
                    ? '<span class="units-low">'.(int)$row['unidades'].'</span>'
                    : (int)$row['unidades']; ?></td>
            <td class="row-data muted"><?php echo htmlspecialchars((string)$row['detalles'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="row-data">
              <?php 
                $imagePath = 'assets/default.png'; // Imagen por defecto
                
                if (!empty($row['imagen'])) { 
                  // Ajustar ruta desde p10 hacia p08
                  $originalPath = $row['imagen'];
                  if (strpos($originalPath, 'http') !== 0 && strpos($originalPath, '/') !== 0) {
                    $fullPath = '../p08/' . $originalPath;
                    // Verificar si el archivo existe
                    $serverPath = $_SERVER['DOCUMENT_ROOT'] . '/tecweb/practicas/p08/' . $originalPath;
                    if (file_exists($serverPath)) {
                      $imagePath = $fullPath;
                    }
                  } else {
                    $imagePath = $originalPath;
                  }
                }
              ?>
              <img class="prod"
                   src="<?php echo htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); ?>"
                   alt="imagen de <?php echo htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                   onerror="this.src='assets/default.png';" />
            </td>
            <td>
              <input type="button" value="Modificar" class="btn-edit" onclick="editarProducto()" />
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    <script type="text/javascript">
      //<![CDATA[
      function editarProducto() {
        // se obtiene el id de la fila donde está el botón presionado
        var rowId = event.target.parentNode.parentNode.id;
        
        // se obtienen los datos de la fila en forma de arreglo
        var data = document.getElementById(rowId).querySelectorAll(".row-data");
        
        var id = data[0].innerHTML;
        var nombre = data[1].innerHTML;
        var marca = data[2].innerHTML;
        var modelo = data[3].innerHTML;
        var precio = data[4].innerHTML.replace('$', '').replace(/,/g, '');
        var unidades = data[5].innerHTML.replace(/<[^>]*>/g, '');
        var detalles = data[6].innerHTML;
        var imagen = '';
        
        // Extraer la URL de la imagen si existe
        var imgElement = data[7].querySelector('img');
        if (imgElement) {
          imagen = imgElement.getAttribute('src') || '';
          // Quitar el prefijo ../p08/ si existe para mantener la ruta original
          if (imagen.startsWith('../p08/')) {
            imagen = imagen.substring(7);
          }
        }
        
        // Enviar datos al formulario
        enviarDatosAlFormulario(id, nombre, marca, modelo, precio, unidades, detalles, imagen);
      }
      
      function enviarDatosAlFormulario(id, nombre, marca, modelo, precio, unidades, detalles, imagen) {
        var datos = {
          'id': id,
          'nombre': nombre,
          'marca': marca,
          'modelo': modelo,
          'precio': precio,
          'unidades': unidades,
          'detalles': detalles,
          'imagen': imagen
        };
        
        // Crear formulario y enviar datos via POST
        var form = document.createElement("form");
        
        for (var key in datos) {
          var input = document.createElement("input");
          input.type = 'hidden';
          input.name = key;
          input.value = datos[key];
          form.appendChild(input);
        }
        form.method = 'POST';
        form.action = 'formulario_productos_v2.php';
        
        document.body.appendChild(form);
        form.submit();
      }
      //]]>
    </script>
  </body>
</html>
