// ====== Configuración inicial del JSON de ejemplo para el textarea (si lo usas) ======
var baseJSON = {
  "precio": 0.0,
  "unidades": 1,
  "modelo": "XX-000",
  "marca": "NA",
  "detalles": "NA",
  "imagen": "img/default.png"
};

$(document).ready(function () {
  let editando = false;

  // Convierte respuesta a objeto si viene como string
  function aObjeto(resp) {
    return (typeof resp === 'string') ? JSON.parse(resp) : resp;
  }

  // ====== UI: barra de estado ======
  function mostrarBarra() { $('#product-result').show(); }
  function ocultarBarra() { $('#product-result').hide(); }
  function limpiarBarra() { $('#container').html(''); }
  function lineaEstado(texto, ok) {
    const color = ok ? '#28a745' : '#dc3545';
    $('#container').append(`<li style="list-style:none;color:${color}">${texto}</li>`);
  }

  // ====== Estilos de validación por campo ======
  // Requiere solo CSS de Bootstrap: .is-valid / .is-invalid
  function marcarCampo(idCampo, esValido, mensaje) {
    const $c = $('#' + idCampo);
    $c.toggleClass('is-valid', esValido)
      .toggleClass('is-invalid', !esValido)
      .attr('title', mensaje || '');
  }

  // ====== Reglas de validación ======
  function vNombre() {
    const v = $('#name').val().trim();
    const ok = v.length > 0;
    marcarCampo('name', ok, ok ? 'Correcto' : 'Obligatorio');
    return ok;
  }
  function vMarca() {
    const v = $('#brand').val().trim();
    const ok = v.length > 0;
    marcarCampo('brand', ok, ok ? 'Correcto' : 'Obligatorio');
    return ok;
  }
  function vModelo() {
    const v = $('#model').val().trim();
    const ok = v.length > 0;
    marcarCampo('model', ok, ok ? 'Correcto' : 'Obligatorio');
    return ok;
  }
  function vPrecio() {
    const v = $('#price').val().trim();
    const n = parseFloat(v);
    const ok = v !== '' && !isNaN(n) && n >= 0;
    marcarCampo('price', ok, ok ? 'Correcto' : 'Número >= 0');
    return ok;
  }
  function vUnidades() {
    const v = $('#units').val().trim();
    const n = parseInt(v, 10);
    const ok = v !== '' && Number.isInteger(n) && n >= 1;
    marcarCampo('units', ok, ok ? 'Correcto' : 'Entero >= 1');
    return ok;
  }
  function vDetalles() {
    const v = $('#details').val().trim();
    const ok = v.length > 0;
    marcarCampo('details', ok, ok ? 'Correcto' : 'Obligatorio');
    return ok;
  }
  function vImagen() {
    const v = $('#image').val().trim();
    const ok = v.length > 0;
    marcarCampo('image', ok, ok ? 'Correcto' : 'Obligatorio');
    return ok;
  }

  // Validación “on blur” para pintar el campo y mostrar barra
  const campos = ['name', 'brand', 'model', 'price', 'details', 'units', 'image'];
  const mapaValidadores = {
    name: vNombre, brand: vMarca, model: vModelo, price: vPrecio,
    details: vDetalles, units: vUnidades, image: vImagen
  };
  campos.forEach(id => {
    $('#' + id).on('blur', function () {
      limpiarBarra();
      const ok = mapaValidadores[id]();
      mostrarBarra();
      lineaEstado(`${etiqueta(id)}: ${ok ? 'válido' : 'inválido'}`, ok);
    });
  });

  // Etiquetas legibles para la barra
  function etiqueta(id) {
    return {
      name: 'Nombre',
      brand: 'Marca',
      model: 'Modelo',
      price: 'Precio',
      details: 'Detalles',
      units: 'Unidades',
      image: 'Imagen'
    }[id] || id;
  }

  // ====== Validación asíncrona de “Nombre” (único) mientras se escribe ======
  let temporizador = null;
  $('#name').on('input', function () {
    clearTimeout(temporizador);
    const escrito = $(this).val().trim();
    const idEdicion = ($('#productId').val() || '').trim();
    if (!escrito) {
      // vacío → solo marca requerido
      vNombre();
      return;
    }
    temporizador = setTimeout(() => {
      $.get('./backend/product-search.php', { search: escrito }, function (resp) {
        const arr = aObjeto(resp);
        const existe = Array.isArray(arr) && arr.some(p => {
          const mismoNombre = String(p.nombre || '').trim().toLowerCase() === escrito.toLowerCase();
          const mismoId = String(p.id || '') === idEdicion;
          // si estoy editando y es el mismo id, no cuenta como duplicado
          return mismoNombre && (!idEdicion || !mismoId);
        });

        limpiarBarra();
        if (existe) {
          marcarCampo('name', false, 'Ya registrado en la BD');
          mostrarBarra();
          lineaEstado('Nombre: ya existe en la base de datos', false);
        } else {
          marcarCampo('name', true, 'Disponible');
          mostrarBarra();
          lineaEstado('Nombre: disponible', true);
        }
      });
    }, 300);
  });

  // ====== Carga inicial del textarea (si existe) y tabla ======
  if ($('#details').length === 0 && $('#description').length) {
    // En caso de que sigas usando textarea JSON, prepáralo
    $('#description').val(JSON.stringify(baseJSON, null, 2));
  }
  ocultarBarra();
  listarProductos();

  // ====== Listado ======
  function listarProductos() {
    $.ajax({
      url: './backend/product-list.php',
      type: 'GET',
      success: function (response) {
        const productos = aObjeto(response);
        if (Object.keys(productos).length > 0) {
          let template = '';
          productos.forEach(p => {
            let desc = '';
            desc += `<li>precio: ${p.precio}</li>`;
            desc += `<li>unidades: ${p.unidades}</li>`;
            desc += `<li>modelo: ${p.modelo}</li>`;
            desc += `<li>marca: ${p.marca}</li>`;
            desc += `<li>detalles: ${p.detalles}</li>`;

            template += `
              <tr productId="${p.id}">
                <td>${p.id}</td>
                <td><a href="#" class="product-item">${p.nombre}</a></td>
                <td><ul>${desc}</ul></td>
                <td>
                  <button class="product-delete btn btn-danger">Eliminar</button>
                </td>
              </tr>
            `;
          });
          $('#products').html(template);
        } else {
          $('#products').html('');
        }
      }
    });
  }

  // ====== Búsqueda superior ======
  $('#search').keyup(function () {
    const q = $('#search').val();
    if (q) {
      $.ajax({
        url: './backend/product-search.php?search=' + q,
        data: { search: q },
        type: 'GET',
        success: function (response) {
          const productos = aObjeto(response);
          if (Object.keys(productos).length > 0) {
            let template = '';
            let barra = '';
            productos.forEach(p => {
              let desc = '';
              desc += `<li>precio: ${p.precio}</li>`;
              desc += `<li>unidades: ${p.unidades}</li>`;
              desc += `<li>modelo: ${p.modelo}</li>`;
              desc += `<li>marca: ${p.marca}</li>`;
              desc += `<li>detalles: ${p.detalles}</li>`;

              template += `
                <tr productId="${p.id}">
                  <td>${p.id}</td>
                  <td><a href="#" class="product-item">${p.nombre}</a></td>
                  <td><ul>${desc}</ul></td>
                  <td>
                    <button class="product-delete btn btn-danger">Eliminar</button>
                  </td>
                </tr>
              `;
              barra += `<li style="list-style:none">${p.nombre}</li>`;
            });
            mostrarBarra();
            $('#container').html(barra);
            $('#products').html(template);
          }
        }
      });
    } else {
      ocultarBarra();
    }
  });

  // ====== Envío (agregar / editar) ======
  $('#product-form').submit(e => {
    e.preventDefault();
    limpiarBarra();

    // Ejecuta todas las validaciones
    const ok =
      vNombre() & vMarca() & vModelo() & vPrecio() &
      vDetalles() & vUnidades() & vImagen();

    if (!ok) {
      mostrarBarra();
      lineaEstado('Revisa los campos marcados en rojo', false);
      return;
    }

    const postData = {
      nombre: $('#name').val().trim(),
      marca: $('#brand').val().trim(),
      modelo: $('#model').val().trim(),
      precio: parseFloat($('#price').val()),
      detalles: $('#details').val().trim(),
      unidades: parseInt($('#units').val(), 10),
      imagen: $('#image').val().trim(),
      id: $('#productId').val()
    };

    const url = editando === false ? './backend/product-add.php' : './backend/product-edit.php';

    $.post(url, postData, (response) => {
      const r = aObjeto(response);
      mostrarBarra();
      $('#container').html(`
        <li style="list-style:none">estado: ${r.status}</li>
        <li style="list-style:none">mensaje: ${r.message}</li>
      `);

      // Limpia y refresca
      campos.forEach(id => { $('#' + id).val('').removeClass('is-valid is-invalid').attr('title',''); });
      $('#productId').val('');
      editando = false;
      listarProductos();
    });
  });

  // ====== Eliminar ======
  $(document).on('click', '.product-delete', function (e) {
    e.preventDefault();
    if (!confirm('¿Deseas eliminar el producto?')) return;
    const id = $(this).closest('tr').attr('productId');
    $.post('./backend/product-delete.php', { id }, () => {
      ocultarBarra();
      listarProductos();
    });
  });

  // ====== Cargar en formulario para editar ======
  $(document).on('click', '.product-item', function (e) {
    e.preventDefault();
    const id = $(this).closest('tr').attr('productId');
    $.post('./backend/product-single.php', { id }, (response) => {
      const p = aObjeto(response);
      $('#name').val(p.nombre);
      $('#brand').val(p.marca);
      $('#model').val(p.modelo);
      $('#price').val(p.precio);
      $('#details').val(p.detalles);
      $('#units').val(p.unidades);
      $('#image').val(p.imagen);
      $('#productId').val(p.id);

      // Limpia estados de validación previos
      campos.forEach(cid => $('#' + cid).removeClass('is-valid is-invalid').attr('title',''));
      editando = true;
    });
  });
});
