<?php
// Funciones auxiliares
function h($s) { 
    return htmlspecialchars((string)$s ?? '', ENT_QUOTES, 'UTF-8'); 
}

// Obtener datos del POST (cuando viene de las tablas)
$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$precio = $_POST['precio'] ?? '';
$unidades = $_POST['unidades'] ?? '';
$detalles = $_POST['detalles'] ?? '';
$imagen = $_POST['imagen'] ?? '';

// Si no hay datos, mostrar formulario vacío
$modoEdicion = !empty($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $modoEdicion ? 'Editar Producto' : 'Registro de Producto' ?> - Tienda de Electrónicos</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      background: #ffffff;
      margin-top: 40px;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 420px;
      animation: fadeIn 0.4s ease-in-out;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
      font-size: 22px;
      letter-spacing: .5px;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
      color: #444;
    }

    input[type="text"],
    input[type="number"],
    input[type="hidden"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 16px;
      border: 1px solid #ddd;
      border-radius: 6px;
      box-sizing: border-box;
      transition: border-color 0.2s;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #007BFF;
      box-shadow: 0 0 4px rgba(0,123,255,0.3);
    }

    textarea {
      resize: vertical;
      min-height: 60px;
    }

    .btn-group {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }

    .btn {
      flex: 1;
      text-align: center;
      background: #007BFF;
      color: #fff;
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.2s;
      text-decoration: none;
      display: inline-block;
    }

    .btn:hover {
      background: #0056b3;
    }

    .btn-secondary {
      background: #6c757d;
    }

    .btn-secondary:hover {
      background: #5a6268;
    }

    .btn-success {
      background: #28a745;
    }

    .btn-success:hover {
      background: #218838;
    }

    .error {
      color: #dc3545;
      font-size: 12px;
      margin-top: -12px;
      margin-bottom: 12px;
      display: none;
    }

    .error.show {
      display: block;
    }

    .input-error {
      border-color: #dc3545 !important;
      box-shadow: 0 0 4px rgba(220,53,69,0.3) !important;
    }

    .info-box {
      background: #d1ecf1;
      border: 1px solid #bee5eb;
      color: #0c5460;
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-10px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="container">
    <?php if ($modoEdicion): ?>
      <div class="info-box">
        <strong>Modo Edición:</strong> Modificando producto ID: <?= h($id) ?>
      </div>
    <?php endif; ?>
    
    <h1><?= $modoEdicion ? 'Editar Producto' : 'Registro de Producto' ?> - Electrónicos</h1>
    
    <form id="formularioProducto" action="update_producto.php" method="post">
      <?php if ($modoEdicion): ?>
        <input type="hidden" name="id" value="<?= h($id) ?>">
      <?php endif; ?>
      
      <label for="nombre">Nombre: *</label>
      <input type="text" id="nombre" name="nombre" maxlength="100" value="<?= h($nombre) ?>" required>
      <div class="error" id="error-nombre"></div>

      <label for="marca">Marca: *</label>
      <select id="marca" name="marca" required>
        <option value="">Seleccione una marca</option>
        <option value="Samsung" <?= $marca == 'Samsung' ? 'selected' : '' ?>>Samsung</option>
        <option value="Apple" <?= $marca == 'Apple' ? 'selected' : '' ?>>Apple</option>
        <option value="Sony" <?= $marca == 'Sony' ? 'selected' : '' ?>>Sony</option>
        <option value="LG" <?= $marca == 'LG' ? 'selected' : '' ?>>LG</option>
        <option value="Xiaomi" <?= $marca == 'Xiaomi' ? 'selected' : '' ?>>Xiaomi</option>
        <option value="Huawei" <?= $marca == 'Huawei' ? 'selected' : '' ?>>Huawei</option>
        <option value="Dell" <?= $marca == 'Dell' ? 'selected' : '' ?>>Dell</option>
        <option value="HP" <?= $marca == 'HP' ? 'selected' : '' ?>>HP</option>
        <option value="Lenovo" <?= $marca == 'Lenovo' ? 'selected' : '' ?>>Lenovo</option>
        <option value="Asus" <?= $marca == 'Asus' ? 'selected' : '' ?>>Asus</option>
        <option value="Nintendo" <?= $marca == 'Nintendo' ? 'selected' : '' ?>>Nintendo</option>
        <option value="Microsoft" <?= $marca == 'Microsoft' ? 'selected' : '' ?>>Microsoft</option>
      </select>
      <div class="error" id="error-marca"></div>

      <label for="modelo">Modelo: *</label>
      <input type="text" id="modelo" name="modelo" maxlength="25" value="<?= h($modelo) ?>" required>
      <div class="error" id="error-modelo"></div>

      <label for="precio">Precio: *</label>
      <input type="number" id="precio" name="precio" step="0.01" min="99.99" value="<?= h($precio) ?>" required>
      <div class="error" id="error-precio"></div>

      <label for="unidades">Unidades: *</label>
      <input type="number" id="unidades" name="unidades" min="0" value="<?= h($unidades) ?>" required>
      <div class="error" id="error-unidades"></div>

      <label for="detalles">Detalles:</label>
      <textarea id="detalles" name="detalles" maxlength="250" placeholder="Descripción opcional del producto (máximo 250 caracteres)"><?= h($detalles) ?></textarea>
      <div class="error" id="error-detalles"></div>

      <label for="imagen">Imagen (ruta):</label>
      <input type="text" id="imagen" name="imagen" value="<?= h($imagen) ?>" placeholder="Ruta de la imagen (opcional)">
      <div class="error" id="error-imagen"></div>

      <div class="btn-group">
        <?php if ($modoEdicion): ?>
          <input type="submit" value="Actualizar" class="btn btn-success">
          <a href="get_productos_vigentes_v2.php" class="btn btn-secondary">Cancelar</a>
        <?php else: ?>
          <input type="submit" value="Guardar" class="btn">
          <input type="reset" value="Limpiar" class="btn btn-secondary">
        <?php endif; ?>
        <a href="get_productos_vigentes_v2.php" class="btn">Ver Vigentes</a>
      </div>
    </form>
  </div>

  <script>
    // Validaciones JavaScript según las especificaciones de la Práctica 10
    
    function mostrarError(campo, mensaje) {
      const input = document.getElementById(campo);
      const errorDiv = document.getElementById('error-' + campo);
      
      input.classList.add('input-error');
      errorDiv.textContent = mensaje;
      errorDiv.classList.add('show');
    }
    
    function limpiarError(campo) {
      const input = document.getElementById(campo);
      const errorDiv = document.getElementById('error-' + campo);
      
      input.classList.remove('input-error');
      errorDiv.classList.remove('show');
    }
    
    function validarNombre() {
      const nombre = document.getElementById('nombre').value.trim();
      
      if (nombre === '') {
        mostrarError('nombre', 'El nombre es requerido');
        return false;
      }
      
      if (nombre.length > 100) {
        mostrarError('nombre', 'El nombre debe tener 100 caracteres o menos');
        return false;
      }
      
      limpiarError('nombre');
      return true;
    }
    
    function validarMarca() {
      const marca = document.getElementById('marca').value;
      
      if (marca === '') {
        mostrarError('marca', 'La marca es requerida y debe seleccionarse de la lista');
        return false;
      }
      
      limpiarError('marca');
      return true;
    }
    
    function validarModelo() {
      const modelo = document.getElementById('modelo').value.trim();
      
      if (modelo === '') {
        mostrarError('modelo', 'El modelo es requerido');
        return false;
      }
      
      if (modelo.length > 25) {
        mostrarError('modelo', 'El modelo debe tener 25 caracteres o menos');
        return false;
      }
      
      // Validar que sea alfanumérico (letras y números solamente)
      const alfanumerico = /^[a-zA-Z0-9\s]+$/;
      if (!alfanumerico.test(modelo)) {
        mostrarError('modelo', 'El modelo debe ser texto alfanumérico (solo letras, números y espacios)');
        return false;
      }
      
      limpiarError('modelo');
      return true;
    }
    
    function validarPrecio() {
      const precio = parseFloat(document.getElementById('precio').value);
      
      if (isNaN(precio)) {
        mostrarError('precio', 'El precio es requerido');
        return false;
      }
      
      if (precio <= 99.99) {
        mostrarError('precio', 'El precio debe ser mayor a $99.99');
        return false;
      }
      
      limpiarError('precio');
      return true;
    }
    
    function validarUnidades() {
      const unidades = parseInt(document.getElementById('unidades').value);
      
      if (isNaN(unidades)) {
        mostrarError('unidades', 'Las unidades son requeridas');
        return false;
      }
      
      if (unidades < 0) {
        mostrarError('unidades', 'Las unidades deben ser mayor o igual a 0');
        return false;
      }
      
      limpiarError('unidades');
      return true;
    }
    
    function validarDetalles() {
      const detalles = document.getElementById('detalles').value.trim();
      
      if (detalles.length > 250) {
        mostrarError('detalles', 'Los detalles deben tener 250 caracteres o menos');
        return false;
      }
      
      limpiarError('detalles');
      return true;
    }
    
    function validarImagen() {
      let imagen = document.getElementById('imagen').value.trim();
      
      // Si no se registra imagen, usar imagen por defecto
      if (imagen === '') {
        document.getElementById('imagen').value = 'assets/img/default-product.png';
      }
      
      limpiarError('imagen');
      return true;
    }
    
    // Validación en tiempo real
    document.getElementById('nombre').addEventListener('blur', validarNombre);
    document.getElementById('marca').addEventListener('change', validarMarca);
    document.getElementById('modelo').addEventListener('blur', validarModelo);
    document.getElementById('precio').addEventListener('blur', validarPrecio);
    document.getElementById('unidades').addEventListener('blur', validarUnidades);
    document.getElementById('detalles').addEventListener('blur', validarDetalles);
    document.getElementById('imagen').addEventListener('blur', validarImagen);
    
    // Validación al enviar el formulario
    document.getElementById('formularioProducto').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const validaciones = [
        validarNombre(),
        validarMarca(),
        validarModelo(),
        validarPrecio(),
        validarUnidades(),
        validarDetalles(),
        validarImagen()
      ];
      
      const todoValido = validaciones.every(v => v === true);
      
      if (todoValido) {
        // Si todo está válido, enviar el formulario
        this.submit();
      } else {
        // Hacer scroll al primer error
        const primerError = document.querySelector('.error.show');
        if (primerError) {
          primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });
    
    // Limpiar errores al resetear el formulario
    const resetBtn = document.querySelector('input[type="reset"]');
    if (resetBtn) {
      resetBtn.addEventListener('click', function() {
        const errores = document.querySelectorAll('.error');
        const inputs = document.querySelectorAll('input, select, textarea');
        
        errores.forEach(error => error.classList.remove('show'));
        inputs.forEach(input => input.classList.remove('input-error'));
      });
    }
  </script>
</body>
</html>