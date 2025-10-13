<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
      ol, ul { 
      list-style-type: none;
      }
    </style>
    <title>Formulario</title>
</head>
<body>
    <h1>Datos Personales</h1>
    
    <?php 
    // Mostrar datos recibidos si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
        echo '<div style="background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px; color: #155724;">';
        echo '<h3>Datos Actualizados:</h3>';
        echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p><strong>Edad:</strong> ' . htmlspecialchars($_POST['age'] ?? '', ENT_QUOTES, 'UTF-8') . '</p>';
        echo '</div>';
    }
    
    // Mostrar datos recibidos por GET o POST al cargar
    $nombreRecibido = $_POST['nombre'] ?? $_GET['nombre'] ?? '';
    $edadRecibida = $_POST['edad'] ?? $_GET['edad'] ?? '';
    
    if ($nombreRecibido || $edadRecibida) {
        echo '<div style="background: #cce7ff; padding: 15px; margin: 10px 0; border-radius: 5px; color: #004085;">';
        echo '<h3>Datos Recibidos:</h3>';
        echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($nombreRecibido, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p><strong>Edad:</strong> ' . htmlspecialchars($edadRecibida, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p><strong>Método:</strong> ' . $_SERVER['REQUEST_METHOD'] . '</p>';
        echo '</div>';
    }
    ?>

    <form id="miFormulario" onsubmit="" method="post">
        <fieldset>
            <legend>Actualiza los datos personales de esta persona:</legend>
            <ul>
                <li><label>Nombre:</label> <input type="text" name="name" value="<?= htmlspecialchars($_POST['nombre'] ?? $_GET['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></li>
                <li><label>Edad:</label> <input type="text" name="age" value="<?= htmlspecialchars($_POST['edad'] ?? $_GET['edad'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></li>
            </ul>
        </fieldset>
        <p>
            <input type="submit" value="ENVIAR">
        </p>
    </form>
</body>
</html>