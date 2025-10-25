// JSON base
var baseJSON = {
  "precio": 0.0,
  "unidades": 1,
  "modelo": "XX-000",
  "marca": "NA",
  "detalles": "NA",
  "imagen": "img/default.png"
};

// Estado edición
let editId = null;

// init exigido
function init() {
  $("#description").val(JSON.stringify(baseJSON, null, 2));
}

function arrayify(data){
  if (Array.isArray(data)) return data;
  if (data && typeof data === "object") return [data];
  return [];
}
function soloActivos(arr){
  return arr.filter(p => p && (p.eliminado === 0 || p.eliminado === false || p.eliminado === undefined) && (p.deleted_at == null));
}

$(function(){
  init();
  cargarProductos();

  // búsqueda en tiempo real
  $("#search").on("input", function(){
    const q = $(this).val().trim();
    if (!q) return cargarProductos();
    buscarProductos(q);
  });

  // submit buscar
  $("#product-search-form").on("submit", function(e){
    e.preventDefault();
    const q = $("#search").val().trim();
    if (!q) return cargarProductos();
    buscarProductos(q);
  });

  // botón búsqueda flexible
  $("#search-flex-btn").on("click", function(){
    const q = $("#search").val().trim();
    if (!q) return cargarProductos();
    buscarProductos(q);
  });

  // alta / actualización
  $("#product-form").on("submit", function(e){
    e.preventDefault();
    if (editId) actualizarProducto(editId);
    else agregarProducto();
  });

  // cancelar edición
  $("#cancel-edit-btn").on("click", function(){
    desactivarModoEdicion();
  });

  // eliminar delegado
  $(document).on("click", ".product-delete", function(){
    const id = $(this).closest("tr").data("productid");
    if (id && confirm("¿Eliminar el producto?")) eliminarProducto(id);
  });

  // editar delegado
  $(document).on("click", ".product-edit", function(){
    const $tr = $(this).closest("tr");
    const p = {
      id: $tr.data("productid"),
      nombre: $tr.find("td:nth-child(2)").text(),
    };
    const $lis = $tr.find("td:nth-child(3) li");
    p.precio   = parseFloat(($lis.eq(0).text().split("$")[1]||"").trim()) || 0;
    p.unidades = parseInt(($lis.eq(1).text().split(":")[1]||"").trim()) || 0;
    p.marca    = ($lis.eq(2).text().split(":")[1]||"").trim();
    p.detalles = ($lis.eq(3).text().split(":")[1]||"").trim();
    activarModoEdicion(p);
  });
});

// cargar todos
function cargarProductos(){
  $.ajax({
    url: "./backend/read.php",
    method: "GET",
    dataType: "json",
    success: function(res){
      const activos = soloActivos(arrayify(res));
      renderTabla(activos);
      renderBarraEstado(activos);
    },
    error: function(xhr){
      console.error("read.php error", xhr.status, xhr.responseText);
      renderTabla([]); renderBarraEstado([]);
    }
  });
}

// buscar
function buscarProductos(search){
  $.ajax({
    url: "./backend/read.php",
    method: "GET",
    data: { q: search },
    dataType: "json",
    success: function(res){
      const activos = soloActivos(arrayify(res));
      renderTabla(activos);
      renderBarraEstado(activos);
    },
    error: function(xhr){
      console.error("read.php error", xhr.status, xhr.responseText);
      renderTabla([]); renderBarraEstado([]);
    }
  });
}

// alta
function agregarProducto(){
  const nombre = $("#name").val().trim();
  const texto = $("#description").val().trim();

  const errores = [];
  let finalJSON;
  try { finalJSON = JSON.parse(texto); } catch { errores.push("El JSON no tiene un formato válido"); }
  if (!nombre || nombre.length < 3 || nombre.length > 80) errores.push("El nombre debe tener entre 3 y 80 caracteres");
  if (finalJSON){
    if (!finalJSON.precio || isNaN(finalJSON.precio) || parseFloat(finalJSON.precio) <= 0) errores.push("El precio debe ser un número mayor a 0");
    if (finalJSON.unidades === undefined || isNaN(finalJSON.unidades) || parseInt(finalJSON.unidades) < 0) errores.push("Las unidades deben ser un número entero mayor o igual a 0");
    if (!finalJSON.marca || finalJSON.marca.length < 2 || finalJSON.marca.length > 50) errores.push("La marca debe tener entre 2 y 50 caracteres");
    if (finalJSON.detalles && finalJSON.detalles.length > 255) errores.push("Los detalles no pueden exceder 255 caracteres");
    if (finalJSON.modelo && (finalJSON.modelo.length < 1 || finalJSON.modelo.length > 25)) errores.push("El modelo debe tener entre 1 y 25 caracteres");
  }
  if (errores.length) return mostrarErrores(errores);

  finalJSON.nombre   = nombre;
  finalJSON.precio   = parseFloat(finalJSON.precio);
  finalJSON.unidades = parseInt(finalJSON.unidades);
  finalJSON.detalles = finalJSON.detalles || "";
  finalJSON.imagen   = finalJSON.imagen || "img/default.png";

  // create.php manejará create (sin op) o update/delete con op
  $.ajax({
    url: "./backend/create.php",
    method: "POST",
    contentType: "application/json; charset=UTF-8",
    dataType: "json",
    data: JSON.stringify(finalJSON),
    success: function(res){
      alert(res.msg || res.message || "Operación completada");
      if (res.ok === true) {
        desactivarModoEdicion(); // por si acaso
        cargarProductos();
      }
    },
    error: function(xhr){
      let res = {};
      try { res = xhr.responseJSON || JSON.parse(xhr.responseText||"{}"); } catch {}
      alert(res.msg || res.message || "Error al agregar producto");
    }
  });
}

// activar edición
function activarModoEdicion(p){
  editId = p.id;
  $("#productId").val(p.id);
  $("#name").val(p.nombre);
  $("#description").val(JSON.stringify({
    precio: p.precio,
    unidades: p.unidades,
    modelo: p.modelo || "XX-000",
    marca: p.marca,
    detalles: p.detalles || "",
    imagen: p.imagen || "img/default.png"
  }, null, 2));
  $("#add-product-btn").text("Guardar cambios");
  $("#cancel-edit-btn").show();
}

// desactivar edición
function desactivarModoEdicion(){
  editId = null;
  $("#productId").val("");
  $("#name").val("");
  $("#description").val(JSON.stringify(baseJSON, null, 2));
  $("#add-product-btn").text("Agregar Producto");
  $("#cancel-edit-btn").hide();
}

// actualizar
function actualizarProducto(id){
  const nombre = $("#name").val().trim();
  const texto = $("#description").val().trim();

  const errores = [];
  let finalJSON;
  try { finalJSON = JSON.parse(texto); } catch { errores.push("El JSON no tiene un formato válido"); }
  if (!nombre || nombre.length < 3 || nombre.length > 80) errores.push("El nombre debe tener entre 3 y 80 caracteres");
  if (finalJSON){
    if (!finalJSON.precio || isNaN(finalJSON.precio) || parseFloat(finalJSON.precio) <= 0) errores.push("El precio debe ser un número mayor a 0");
    if (finalJSON.unidades === undefined || isNaN(finalJSON.unidades) || parseInt(finalJSON.unidades) < 0) errores.push("Las unidades deben ser un número entero mayor o igual a 0");
    if (!finalJSON.marca || finalJSON.marca.length < 2 || finalJSON.marca.length > 50) errores.push("La marca debe tener entre 2 y 50 caracteres");
    if (finalJSON.detalles && finalJSON.detalles.length > 255) errores.push("Los detalles no pueden exceder 255 caracteres");
    if (finalJSON.modelo && (finalJSON.modelo.length < 1 || finalJSON.modelo.length > 25)) errores.push("El modelo debe tener entre 1 y 25 caracteres");
  }
  if (errores.length) return mostrarErrores(errores);

  finalJSON.nombre   = nombre;
  finalJSON.precio   = parseFloat(finalJSON.precio);
  finalJSON.unidades = parseInt(finalJSON.unidades);
  finalJSON.detalles = finalJSON.detalles || "";
  finalJSON.imagen   = finalJSON.imagen || "img/default.png";
  finalJSON.id       = id;
  finalJSON.op       = "update";

  $.ajax({
    url: "./backend/create.php",
    method: "POST",
    contentType: "application/json; charset=UTF-8",
    dataType: "json",
    data: JSON.stringify(finalJSON),
    success: function(res){
      alert(res.msg || "Actualizado");
      if (res.ok) {
        desactivarModoEdicion();
        cargarProductos();
      }
    },
    error: function(xhr){
      let res = {};
      try { res = xhr.responseJSON || JSON.parse(xhr.responseText||"{}"); } catch {}
      alert(res.msg || "Error al actualizar");
    }
  });
}

// eliminar
function eliminarProducto(id){
  const payload = { op: "delete", id: id };
  $.ajax({
    url: "./backend/create.php",
    method: "POST",
    contentType: "application/json; charset=UTF-8",
    dataType: "json",
    data: JSON.stringify(payload),
    success: function(res){
      alert(res.msg || "Producto eliminado");
      cargarProductos();
    },
    error: function(xhr){
      let res = {};
      try { res = xhr.responseJSON || JSON.parse(xhr.responseText||"{}"); } catch {}
      alert(res.msg || "Error al eliminar");
    }
  });
}

// render tabla
function renderTabla(productos){
  const $tbody = $("#products");
  $tbody.empty();
  if (!productos || !productos.length){
    $tbody.append('<tr><td colspan="4">Sin coincidencias</td></tr>');
    return;
  }
  productos.forEach(function(p){
    const descripcion = `
      <li>precio: $${p.precio ?? "N/A"}</li>
      <li>unidades: ${p.unidades ?? "N/A"}</li>
      <li>marca: ${p.marca ?? "N/A"}</li>
      <li>detalles: ${p.detalles ?? "N/A"}</li>
    `;
    $tbody.append(`
      <tr data-productid="${p.id}">
        <td>${p.id}</td>
        <td>${p.nombre}</td>
        <td><ul>${descripcion}</ul></td>
        <td>
          <button type="button" class="product-edit">Editar</button>
          <button type="button" class="product-delete">Eliminar</button>
        </td>
      </tr>
    `);
  });
}

// barra estado
function renderBarraEstado(productos){
  let $barra = $("#product-result ul");
  if (!$barra.length){
    $("#product-result").remove();
    $('<div class="card my-4" id="product-result"><div class="card-body"><ul></ul></div></div>')
      .insertBefore("#products");
    $barra = $("#product-result ul");
  }
  $barra.empty();
  if (!productos || !productos.length){
    $barra.append("<li>Sin coincidencias</li>");
    return;
  }
  productos.forEach(p => $barra.append(`<li>${p.nombre}</li>`));
}

// errores
function mostrarErrores(errores){
  alert("Errores de validación:\n" + errores.join("\n"));
}
