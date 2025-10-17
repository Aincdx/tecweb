// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

// FUNCIÓN CALLBACK DE BOTÓN "Buscar"
function buscarID(e) {

    e.preventDefault();

    var id = document.getElementById('search').value;

    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);    // similar a eval('('+client.responseText+')');
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                let descripcion = '';
                    descripcion += '<li>precio: '+productos.precio+'</li>';
                    descripcion += '<li>unidades: '+productos.unidades+'</li>';
                    descripcion += '<li>modelo: '+productos.modelo+'</li>';
                    descripcion += '<li>marca: '+productos.marca+'</li>';
                    descripcion += '<li>detalles: '+productos.detalles+'</li>';
                
                // SE CREA UNA PLANTILLA PARA CREAR LA(S) FILA(S) A INSERTAR EN EL DOCUMENTO HTML
                let template = '';
                    template += `
                        <tr>
                            <td>${productos.id}</td>
                            <td>${productos.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id="+id);
}

// FUNCIÓN CALLBACK DE BOTÓN "Agregar Producto"
function agregarProducto(e) {
    e.preventDefault();

    // VALIDACIONES DEL CLIENTE
    var errores = [];
    
    // Obtener valores del formulario
    var nombre = document.getElementById('name').value.trim();
    var productoJsonString = document.getElementById('description').value.trim();
    
    // Validar nombre
    if (!nombre || nombre.length < 3 || nombre.length > 80) {
        errores.push("El nombre debe tener entre 3 y 80 caracteres");
    }
    
    // Validar y parsear JSON
    var finalJSON;
    try {
        finalJSON = JSON.parse(productoJsonString);
    } catch (error) {
        errores.push("El JSON no tiene un formato válido");
        mostrarErrores(errores);
        return;
    }
    
    // Validar campos del JSON
    // Precio (debe ser numérico y mayor a 0)
    if (!finalJSON.precio || isNaN(finalJSON.precio) || parseFloat(finalJSON.precio) <= 0) {
        errores.push("El precio debe ser un número mayor a 0");
    }
    
    // Unidades (debe ser entero >= 0)
    if (finalJSON.unidades === undefined || isNaN(finalJSON.unidades) || parseInt(finalJSON.unidades) < 0) {
        errores.push("Las unidades deben ser un número entero mayor o igual a 0");
    }
    
    // Marca (2-50 caracteres)
    if (!finalJSON.marca || finalJSON.marca.length < 2 || finalJSON.marca.length > 50) {
        errores.push("La marca debe tener entre 2 y 50 caracteres");
    }
    
    // Detalles (0-255 caracteres, puede estar vacío)
    if (finalJSON.detalles && finalJSON.detalles.length > 255) {
        errores.push("Los detalles no pueden exceder 255 caracteres");
    }
    
    // Modelo (opcional, pero si existe debe tener longitud válida)
    if (finalJSON.modelo && (finalJSON.modelo.length < 1 || finalJSON.modelo.length > 25)) {
        errores.push("El modelo debe tener entre 1 y 25 caracteres");
    }
    
    // Si hay errores, mostrarlos y no enviar
    if (errores.length > 0) {
        mostrarErrores(errores);
        return;
    }
    
    // Agregar nombre al JSON
    finalJSON['nombre'] = nombre;
    
    // Normalizar valores
    finalJSON.precio = parseFloat(finalJSON.precio);
    finalJSON.unidades = parseInt(finalJSON.unidades);
    finalJSON.detalles = finalJSON.detalles || '';
    finalJSON.imagen = finalJSON.imagen || 'img/default.png';
    
    // Convertir a string JSON final
    productoJsonString = JSON.stringify(finalJSON);

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/create.php', true);
    client.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4) {
            if (client.status == 200 || client.status == 201) {
                try {
                    var response = JSON.parse(client.responseText);
                    
                    // Mostrar mensaje del servidor
                    window.alert(response.msg || response.message || "Operación completada");
                    
                    if (response.ok === true) {
                        // Éxito: limpiar formulario y buscar el producto recién creado
                        document.getElementById('name').value = '';
                        document.getElementById('description').value = JSON.stringify(baseJSON, null, 2);
                        
                        // Buscar el producto recién insertado
                        document.getElementById('search').value = nombre;
                        buscarProducto();
                    }
                } catch (error) {
                    console.error('Error parsing response:', error);
                    window.alert("Error en la respuesta del servidor");
                }
            } else if (client.status == 400) {
                try {
                    var response = JSON.parse(client.responseText);
                    window.alert("Error de validación: " + (response.msg || response.message || "Datos inválidos"));
                } catch (error) {
                    window.alert("Error de validación en el servidor");
                }
            } else if (client.status == 409) {
                try {
                    var response = JSON.parse(client.responseText);
                    window.alert("Producto duplicado: " + (response.msg || response.message || "Ya existe un producto con ese nombre"));
                } catch (error) {
                    window.alert("Ya existe un producto con ese nombre");
                }
            } else {
                window.alert("Error del servidor (código " + client.status + ")");
            }
        }
    };
    client.send(productoJsonString);
}

// SE CREA EL OBJETO DE CONEXIÓN COMPATIBLE CON EL NAVEGADOR
function getXMLHttpRequest() {
    var objetoAjax;

    try{
        objetoAjax = new XMLHttpRequest();
    }catch(err1){
        /**
         * NOTA: Las siguientes formas de crear el objeto ya son obsoletas
         *       pero se comparten por motivos historico-académicos.
         */
        try{
            // IE7 y IE8
            objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(err2){
            try{
                // IE5 y IE6
                objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(err3){
                objetoAjax = false;
            }
        }
    }
    return objetoAjax;
}

function init() {

    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
    
    // Agregar debounce para búsqueda en tiempo real (opcional)
    var searchInput = document.getElementById('search');
    if (searchInput) {
        var debounceTimer;
        searchInput.addEventListener('keyup', function(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                if (searchInput.value.length >= 2) {
                    buscarProducto(e);
                }
            }, 300);
        });
    }
}

// NUEVA FUNCIÓN: Búsqueda flexible de productos por nombre, marca o detalles
function buscarProducto(e) {
    if (e) {
        e.preventDefault();
    }

    // Obtener término de búsqueda
    var termino = document.getElementById('search').value.trim();
    
    // Validar longitud mínima
    if (termino.length < 2) {
        setEstado('error');
        renderResultados([]);
        return;
    }

    // Establecer estado de carga
    setEstado('cargando');

    // Crear objeto de conexión
    var client = getXMLHttpRequest();
    client.open('GET', './backend/read.php?q=' + encodeURIComponent(termino), true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    client.onreadystatechange = function () {
        if (client.readyState == 4) {
            if (client.status == 200) {
                try {
                    console.log('[BÚSQUEDA]\n' + client.responseText);
                    
                    // Parsear respuesta JSON
                    let productos = JSON.parse(client.responseText);
                    
                    // Verificar si es array
                    if (Array.isArray(productos)) {
                        setEstado('ok');
                        renderResultados(productos);
                    } else {
                        setEstado('error');
                        renderResultados([]);
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    setEstado('error');
                    renderResultados([]);
                }
            } else {
                console.error('Error HTTP:', client.status);
                setEstado('error');
                renderResultados([]);
            }
        }
    };
    
    client.send();
}

// FUNCIÓN UTILITARIA: Renderizar lista de resultados
function renderResultados(productos) {
    var contenedor = document.getElementById("productos");
    
    if (!productos || productos.length === 0) {
        contenedor.innerHTML = '<tr><td colspan="3">Sin coincidencias</td></tr>';
        return;
    }
    
    var template = '';
    productos.forEach(function(producto) {
        var descripcion = '';
        descripcion += '<li>precio: $' + (producto.precio || 'N/A') + '</li>';
        descripcion += '<li>unidades: ' + (producto.unidades || 'N/A') + '</li>';
        descripcion += '<li>marca: ' + (producto.marca || 'N/A') + '</li>';
        descripcion += '<li>detalles: ' + (producto.detalles || 'N/A') + '</li>';
        
        template += `
            <tr>
                <td>${producto.id}</td>
                <td>${producto.nombre}</td>
                <td><ul>${descripcion}</ul></td>
            </tr>
        `;
    });
    
    contenedor.innerHTML = template;
}

// FUNCIÓN UTILITARIA: Manejar estados de la aplicación
function setEstado(estado) {
    var searchInput = document.getElementById('search');
    
    switch(estado) {
        case 'cargando':
            if (searchInput) {
                searchInput.style.backgroundColor = '#fff3cd';
                searchInput.style.borderColor = '#ffeaa7';
            }
            break;
        case 'ok':
            if (searchInput) {
                searchInput.style.backgroundColor = '#d4edda';
                searchInput.style.borderColor = '#c3e6cb';
            }
            break;
        case 'error':
            if (searchInput) {
                searchInput.style.backgroundColor = '#f8d7da';
                searchInput.style.borderColor = '#f5c6cb';
            }
            break;
        default:
            if (searchInput) {
                searchInput.style.backgroundColor = '';
                searchInput.style.borderColor = '';
            }
    }
}

// FUNCIÓN UTILITARIA: Mostrar errores de validación
function mostrarErrores(errores) {
    var mensaje = "Errores de validación:\n\n";
    errores.forEach(function(error, index) {
        mensaje += (index + 1) + ". " + error + "\n";
    });
    
    window.alert(mensaje);
    
    // Resaltar campos con error
    var nameInput = document.getElementById('name');
    var descriptionInput = document.getElementById('description');
    
    if (nameInput) {
        nameInput.style.borderColor = '#dc3545';
        nameInput.style.backgroundColor = '#f8d7da';
    }
    
    if (descriptionInput) {
        descriptionInput.style.borderColor = '#dc3545';
        descriptionInput.style.backgroundColor = '#f8d7da';
    }
    
    // Quitar resaltado después de 3 segundos
    setTimeout(function() {
        if (nameInput) {
            nameInput.style.borderColor = '';
            nameInput.style.backgroundColor = '';
        }
        if (descriptionInput) {
            descriptionInput.style.borderColor = '';
            descriptionInput.style.backgroundColor = '';
        }
    }, 3000);
}