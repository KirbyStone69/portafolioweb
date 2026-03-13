// esto son las variables globales que voy a usar
var todasLasTarifas = [];
var tarifasFiltradas = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";
var todasLasEspecialidades = [];

// esto carga los datos de las tarifas desde el servidor
function cargarDatos() {
  fetch('php/Tarifas/listar.php')
    .then(function(respuesta) {
      return respuesta.json();
    })
    .then(function(datos) {
      todasLasTarifas = datos;
      tarifasFiltradas = datos;
      paginaActual = 1;
      actualizarEstadisticas();
      mostrarTabla();
    })
    .catch(function(error) {
      console.log('Error:', error);
      document.getElementById('tabla-tarifas').innerHTML = 
        '<tr><td colspan="5">Error al cargar datos</td></tr>';
    });
}

// esto carga las especialidades para el select
function cargarEspecialidades() {
  fetch('php/Tarifas/listar_especialidades.php')
    .then(function(respuesta) {
      return respuesta.json();
    })
    .then(function(datos) {
      todasLasEspecialidades = datos;
      llenarSelectEspecialidades();
    })
    .catch(function(error) {
      console.log('Error al cargar especialidades:', error);
    });
}

// esto llena el select de especialidades con las opciones
function llenarSelectEspecialidades() {
  var select = document.getElementById('select-especialidad');
  if (!select) return;
  
  var html = '<option value="">Seleccione una especialidad</option>';
  for (var i = 0; i < todasLasEspecialidades.length; i++) {
    html += '<option value="' + todasLasEspecialidades[i].IdEspecialidad + '">' + 
            todasLasEspecialidades[i].NombreEspecialidad + '</option>';
  }
  select.innerHTML = html;
}

// esto calcula y muestra las estadisticas
function actualizarEstadisticas() {
  var totalTarifas = todasLasTarifas.length;
  var tarifasActivas = 0;
  var tarifasInactivas = 0;
  
  // aqui cuento cuantas estan activas y cuantas inactivas
  for (var i = 0; i < todasLasTarifas.length; i++) {
    if (todasLasTarifas[i].Estatus == 1) {
      tarifasActivas++;
    } else {
      tarifasInactivas++;
    }
  }
  
  // aqui actualizo los numeros en la pantalla
  document.getElementById('total-tarifas').textContent = totalTarifas;
  document.getElementById('tarifas-activas').textContent = tarifasActivas;
  document.getElementById('tarifas-inactivas').textContent = tarifasInactivas;
}

// esto filtra las tarifas cuando busco algo
function filtrarDatos() {
  var textoBusqueda = busqueda.toLowerCase();
  
  tarifasFiltradas = todasLasTarifas.filter(function(tarifa) {
    var descripcion = tarifa.DescripcionServicio.toLowerCase();
    var especialidad = tarifa.NombreEspecialidad ? tarifa.NombreEspecialidad.toLowerCase() : '';
    var costo = tarifa.CostoBase.toString();
    
    return descripcion.includes(textoBusqueda) || 
           especialidad.includes(textoBusqueda) ||
           costo.includes(textoBusqueda);
  });
  
  paginaActual = 1;
  mostrarTabla();
}

// esto le pone formato al precio para que se vea bien
function formatearPrecio(precio) {
  return '$' + parseFloat(precio).toFixed(2);
}

// esto muestra las tarifas en la tabla
function mostrarTabla() {
  var tbody = document.getElementById('tabla-tarifas');
  var total = tarifasFiltradas.length;
  var totalPaginas = Math.ceil(total / registrosPorPagina);
  
  if (totalPaginas === 0) {
    totalPaginas = 1;
  }
  
  if (paginaActual > totalPaginas) {
    paginaActual = totalPaginas;
  }
  
  // aqui calculo que tarifas mostrar segun la pagina
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var fin = inicio + registrosPorPagina;
  var registrosMostrar = tarifasFiltradas.slice(inicio, fin);
  
  var html = '';
  
  if (registrosMostrar.length === 0) {
    html = '<tr><td colspan="5">No hay tarifas registradas</td></tr>';
  } else {
    // aqui creo las filas de la tabla
    for (var i = 0; i < registrosMostrar.length; i++) {
      var tarifa = registrosMostrar[i];
      var nombreEsp = tarifa.NombreEspecialidad || 'Sin especialidad';
      var badgeClass = tarifa.Estatus == 1 ? 'text-bg-success' : 'text-bg-secondary';
      var badgeText = tarifa.Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
      
      html += '<tr>';
      html += '<td>' + nombreEsp + '</td>';
      html += '<td class="fw-semibold">' + tarifa.DescripcionServicio + '</td>';
      html += '<td class="text-end">' + formatearPrecio(tarifa.CostoBase) + '</td>';
      html += '<td><span class="badge ' + badgeClass + '">' + badgeText + '</span></td>';
      html += '<td class="text-end">';
      html += '<div class="btn-group btn-group-sm">';
      html += '<button class="btn btn-primary btn-editar" ' +
              'data-id="' + tarifa.IdTarifa + '" ' +
              'data-descripcion="' + tarifa.DescripcionServicio + '" ' +
              'data-costo="' + tarifa.CostoBase + '" ' +
              'data-especialidad="' + (tarifa.EspecialidadId || '') + '" ' +
              'data-estatus="' + tarifa.Estatus + '">Editar</button>';
      html += '<button class="btn btn-primary btn-eliminar" ' +
              'data-id="' + tarifa.IdTarifa + '" ' +
              'data-descripcion="' + tarifa.DescripcionServicio + '">Eliminar</button>';
      html += '</div>';
      html += '</td>';
      html += '</tr>';
    }
  }
  
  tbody.innerHTML = html;
  
  // aqui actualizo la info de paginacion
  var inicioMostrar = total === 0 ? 0 : inicio + 1;
  var finMostrar = total === 0 ? 0 : Math.min(fin, total);
  
  document.getElementById('info-registros').textContent = 
    'Mostrando ' + inicioMostrar + ' a ' + finMostrar + ' de ' + total + ' registros';
  
  document.getElementById('page-info').textContent = paginaActual + '/' + totalPaginas;
  
  // aqui activo o desactivo los botones de paginacion
  document.getElementById('btn-prev').disabled = (paginaActual === 1);
  document.getElementById('btn-next').disabled = (paginaActual === totalPaginas);
  
  // aqui pongo los eventos a los botones
  agregarEventosEliminar();
  agregarEventosEditar();
}

// esto pone el evento click a los botones de editar
function agregarEventosEditar() {
  var botonesEditar = document.querySelectorAll('.btn-editar');
  
  for (var i = 0; i < botonesEditar.length; i++) {
    botonesEditar[i].addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      var descripcion = this.getAttribute('data-descripcion');
      var costo = this.getAttribute('data-costo');
      var especialidadId = this.getAttribute('data-especialidad');
      var estatus = this.getAttribute('data-estatus');
      editarTarifa(id, descripcion, costo, especialidadId, estatus);
    });
  }
}

// esto muestra el popup para editar una tarifa
function editarTarifa(id, descripcion, costo, especialidadId, estatus) {
  // aqui creo las opciones del select de especialidades
  var opcionesEspecialidades = '<option value="">Seleccione una especialidad</option>';
  for (var i = 0; i < todasLasEspecialidades.length; i++) {
    var selected = todasLasEspecialidades[i].IdEspecialidad == especialidadId ? 'selected' : '';
    opcionesEspecialidades += '<option value="' + todasLasEspecialidades[i].IdEspecialidad + '" ' + selected + '>' + 
                               todasLasEspecialidades[i].NombreEspecialidad + '</option>';
  }
  
  var checkedEstatus = estatus == 1 ? 'checked' : '';
  
  // aqui muestro el popup con sweetalert
  Swal.fire({
    title: 'Editar tarifa',
    html:
      '<div class="text-start">' +
      '<label class="form-label small">Especialidad:</label>' +
      '<select id="swal-input-especialidad" class="form-select mb-3">' + opcionesEspecialidades + '</select>' +
      '<label class="form-label small">Descripción del servicio:</label>' +
      '<input id="swal-input-descripcion" class="form-control mb-3" placeholder="Descripción" value="' + descripcion + '">' +
      '<label class="form-label small">Costo base:</label>' +
      '<input id="swal-input-costo" type="number" step="0.01" class="form-control mb-3" placeholder="0.00" value="' + costo + '">' +
      '<div class="form-check">' +
      '<input class="form-check-input" type="checkbox" id="swal-input-estatus" ' + checkedEstatus + '>' +
      '<label class="form-check-label" for="swal-input-estatus">Activo</label>' +
      '</div>' +
      '</div>',
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    cancelButtonText: 'Cancelar',
    width: '600px',
    preConfirm: function() {
      var nuevaDescripcion = document.getElementById('swal-input-descripcion').value;
      var nuevoCosto = document.getElementById('swal-input-costo').value;
      var nuevaEspecialidad = document.getElementById('swal-input-especialidad').value;
      var nuevoEstatus = document.getElementById('swal-input-estatus').checked;
      
      // aqui valido que los campos no esten vacios
      if (!nuevaDescripcion || !nuevoCosto || !nuevaEspecialidad) {
        Swal.showValidationMessage('Por favor complete todos los campos obligatorios');
        return false;
      }
      
      // aqui valido que el costo sea positivo
      if (parseFloat(nuevoCosto) < 0) {
        Swal.showValidationMessage('El costo debe ser mayor o igual a 0');
        return false;
      }
      
      return { 
        descripcion: nuevaDescripcion, 
        costo: nuevoCosto,
        especialidad: nuevaEspecialidad,
        estatus: nuevoEstatus
      };
    }
  }).then(function(result) {
    if (result.isConfirmed) {
      // aqui creo un formulario para enviar los datos
      var formulario = document.createElement('form');
      formulario.method = 'POST';
      formulario.action = 'php/Tarifas/editar.php';
      
      var inputId = document.createElement('input');
      inputId.type = 'hidden';
      inputId.name = 'id';
      inputId.value = id;
      
      var inputDescripcion = document.createElement('input');
      inputDescripcion.type = 'hidden';
      inputDescripcion.name = 'descripcion';
      inputDescripcion.value = result.value.descripcion;
      
      var inputCosto = document.createElement('input');
      inputCosto.type = 'hidden';
      inputCosto.name = 'costo';
      inputCosto.value = result.value.costo;
      
      var inputEspecialidad = document.createElement('input');
      inputEspecialidad.type = 'hidden';
      inputEspecialidad.name = 'especialidad_id';
      inputEspecialidad.value = result.value.especialidad;
      
      if (result.value.estatus) {
        var inputEstatus = document.createElement('input');
        inputEstatus.type = 'hidden';
        inputEstatus.name = 'estatus';
        inputEstatus.value = '1';
        formulario.appendChild(inputEstatus);
      }
      
      formulario.appendChild(inputId);
      formulario.appendChild(inputDescripcion);
      formulario.appendChild(inputCosto);
      formulario.appendChild(inputEspecialidad);
      document.body.appendChild(formulario);
      formulario.submit();
    }
  });
}

// esto pone el evento click a los botones de eliminar
function agregarEventosEliminar() {
  var botonesEliminar = document.querySelectorAll('.btn-eliminar');
  
  for (var i = 0; i < botonesEliminar.length; i++) {
    botonesEliminar[i].addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      var descripcion = this.getAttribute('data-descripcion');
      eliminarTarifa(id, descripcion);
    });
  }
}

// esto muestra el popup para confirmar que quiero eliminar
function eliminarTarifa(id, descripcion) {
  Swal.fire({
    title: '¿Eliminar tarifa?',
    text: '¿Está seguro de eliminar "' + descripcion + '"?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then(function(result) {
    if (result.isConfirmed) {
      // aqui creo un formulario para enviar el id a eliminar
      var formulario = document.createElement('form');
      formulario.method = 'POST';
      formulario.action = 'php/Tarifas/eliminar.php';
      
      var inputId = document.createElement('input');
      inputId.type = 'hidden';
      inputId.name = 'id';
      inputId.value = id;
      
      formulario.appendChild(inputId);
      document.body.appendChild(formulario);
      formulario.submit();
    }
  });
}

// esto valida el formulario antes de enviarlo
function validarFormulario() {
  var formulario = document.getElementById('form-registro');
  if (!formulario) return;
  
  formulario.addEventListener('submit', function(e) {
    var especialidad = document.getElementById('select-especialidad').value;
    var descripcion = document.getElementById('input-descripcion').value;
    var costo = document.getElementById('input-costo').value;
    
    // aqui valido que todos los campos esten llenos
    if (!especialidad || !descripcion || !costo) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor complete todos los campos',
        confirmButtonText: 'Aceptar'
      });
      return false;
    }
    
    // aqui valido que el costo sea positivo
    if (parseFloat(costo) < 0) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'El costo debe ser mayor o igual a 0',
        confirmButtonText: 'Aceptar'
      });
      return false;
    }
  });
}

// esto se ejecuta cuando carga la pagina
document.addEventListener('DOMContentLoaded', function() {
  cargarEspecialidades();
  cargarDatos();
  validarFormulario();
  
  // aqui pongo el evento para cambiar cuantos registros ver
  document.getElementById('select-mostrar').addEventListener('change', function() {
    registrosPorPagina = parseInt(this.value);
    paginaActual = 1;
    mostrarTabla();
  });
  
  // aqui pongo el evento para el buscador
  document.getElementById('input-buscar').addEventListener('input', function() {
    busqueda = this.value;
    filtrarDatos();
  });
  
  // aqui pongo el evento para el boton anterior
  document.getElementById('btn-prev').addEventListener('click', function() {
    if (paginaActual > 1) {
      paginaActual--;
      mostrarTabla();
    }
  });
  
  // aqui pongo el evento para el boton siguiente
  document.getElementById('btn-next').addEventListener('click', function() {
    var totalPaginas = Math.ceil(tarifasFiltradas.length / registrosPorPagina);
    if (paginaActual < totalPaginas) {
      paginaActual++;
      mostrarTabla();
    }
  });
});
