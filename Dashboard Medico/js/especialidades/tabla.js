// Variables globales
var todasLasEspecialidades = [];
var especialidadesFiltradas = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";

// Funcion para cargar datos desde el servidor
function cargarDatos() {
  fetch('php/Especialidades/listar.php')
    .then(function(respuesta) {
      return respuesta.json();
    })
    .then(function(datos) {
      todasLasEspecialidades = datos;
      especialidadesFiltradas = datos;
      paginaActual = 1;
      actualizarEstadisticas();
      mostrarTabla();
    })
    .catch(function(error) {
      console.log('Error:', error);
      document.getElementById('tabla-especialidades').innerHTML = 
        '<tr><td colspan="3">Error al cargar datos</td></tr>';
    });
}

// Funcion para actualizar estadisticas
function actualizarEstadisticas() {
  var totalEspecialidades = todasLasEspecialidades.length;
  var elementoEstadistica = document.getElementById('total-especialidades');
  if (elementoEstadistica) {
    elementoEstadistica.textContent = totalEspecialidades;
  }
}

// Funcion para filtrar datos
function filtrarDatos() {
  var textoBusqueda = busqueda.toLowerCase();
  
  especialidadesFiltradas = todasLasEspecialidades.filter(function(especialidad) {
    var nombre = especialidad.NombreEspecialidad.toLowerCase();
    var descripcion = especialidad.Descripcion.toLowerCase();
    return nombre.includes(textoBusqueda) || descripcion.includes(textoBusqueda);
  });
  
  paginaActual = 1;
  mostrarTabla();
}

// Funcion para mostrar la tabla
function mostrarTabla() {
  var tbody = document.getElementById('tabla-especialidades');
  var total = especialidadesFiltradas.length;
  var totalPaginas = Math.ceil(total / registrosPorPagina);
  
  if (totalPaginas === 0) {
    totalPaginas = 1;
  }
  
  if (paginaActual > totalPaginas) {
    paginaActual = totalPaginas;
  }
  
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var fin = inicio + registrosPorPagina;
  var registrosMostrar = especialidadesFiltradas.slice(inicio, fin);
  
  var html = '';
  
  if (registrosMostrar.length === 0) {
    html = '<tr><td colspan="3">No hay especialidades</td></tr>';
  } else {
    for (var i = 0; i < registrosMostrar.length; i++) {
      var especialidad = registrosMostrar[i];
      html += '<tr>';
      html += '<td class="fw-semibold">' + especialidad.NombreEspecialidad + '</td>';
      html += '<td>' + especialidad.Descripcion + '</td>';
      html += '<td class="text-end">';
      html += '<div class="btn-group btn-group-sm">';
      html += '<button class="btn btn-primary btn-editar" data-id="' + especialidad.IdEspecialidad + '" data-nombre="' + especialidad.NombreEspecialidad + '" data-descripcion="' + especialidad.Descripcion + '">Editar</button>';
      html += '<button class="btn btn-primary btn-eliminar" data-id="' + especialidad.IdEspecialidad + '" data-nombre="' + especialidad.NombreEspecialidad + '">Eliminar</button>';
      html += '</div>';
      html += '</td>';
      html += '</tr>';
    }
  }
  
  tbody.innerHTML = html;
  
  var inicioMostrar = total === 0 ? 0 : inicio + 1;
  var finMostrar = total === 0 ? 0 : Math.min(fin, total);
  
  document.getElementById('info-registros').textContent = 
    'Mostrando ' + inicioMostrar + ' a ' + finMostrar + ' de ' + total + ' registros';
  
  document.getElementById('page-info').textContent = paginaActual + '/' + totalPaginas;
  
  document.getElementById('btn-prev').disabled = (paginaActual === 1);
  document.getElementById('btn-next').disabled = (paginaActual === totalPaginas);
  
  agregarEventosEliminar();
  agregarEventosEditar();
}

// Funcion para agregar eventos a botones editar
function agregarEventosEditar() {
  var botonesEditar = document.querySelectorAll('.btn-editar');
  
  for (var i = 0; i < botonesEditar.length; i++) {
    botonesEditar[i].addEventListener('click', function() {
      var idEspecialidad = this.getAttribute('data-id');
      var nombreEspecialidad = this.getAttribute('data-nombre');
      var descripcionEspecialidad = this.getAttribute('data-descripcion');
      editarEspecialidad(idEspecialidad, nombreEspecialidad, descripcionEspecialidad);
    });
  }
}

// Funcion para editar especialidad
function editarEspecialidad(id, nombre, descripcion) {
  Swal.fire({
    title: 'Editar especialidad',
    html:
      '<input id="swal-input-nombre" class="swal2-input" placeholder="Nombre" value="' + nombre + '">' +
      '<textarea id="swal-input-descripcion" class="swal2-textarea" placeholder="Descripción">' + descripcion + '</textarea>',
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    cancelButtonText: 'Cancelar',
    preConfirm: function() {
      var nuevoNombre = document.getElementById('swal-input-nombre').value;
      var nuevaDescripcion = document.getElementById('swal-input-descripcion').value;
      
      if (!nuevoNombre || !nuevaDescripcion) {
        Swal.showValidationMessage('Por favor complete todos los campos');
        return false;
      }
      
      return { nombre: nuevoNombre, descripcion: nuevaDescripcion };
    }
  }).then(function(result) {
    if (result.isConfirmed) {
      var formulario = document.createElement('form');
      formulario.method = 'POST';
      formulario.action = 'php/Especialidades/editar.php';
      
      var inputId = document.createElement('input');
      inputId.type = 'hidden';
      inputId.name = 'id';
      inputId.value = id;
      
      var inputNombre = document.createElement('input');
      inputNombre.type = 'hidden';
      inputNombre.name = 'nombre';
      inputNombre.value = result.value.nombre;
      
      var inputDescripcion = document.createElement('input');
      inputDescripcion.type = 'hidden';
      inputDescripcion.name = 'descripcion';
      inputDescripcion.value = result.value.descripcion;
      
      formulario.appendChild(inputId);
      formulario.appendChild(inputNombre);
      formulario.appendChild(inputDescripcion);
      document.body.appendChild(formulario);
      formulario.submit();
    }
  });
}

// Funcion para agregar eventos a botones eliminar
function agregarEventosEliminar() {
  var botonesEliminar = document.querySelectorAll('.btn-eliminar');
  
  for (var i = 0; i < botonesEliminar.length; i++) {
    botonesEliminar[i].addEventListener('click', function() {
      var idEspecialidad = this.getAttribute('data-id');
      var nombreEspecialidad = this.getAttribute('data-nombre');
      eliminarEspecialidad(idEspecialidad, nombreEspecialidad);
    });
  }
}

// Funcion para eliminar especialidad
function eliminarEspecialidad(id, nombre) {
  Swal.fire({
    title: '¿Eliminar especialidad?',
    text: '¿Está seguro de eliminar "' + nombre + '"?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then(function(result) {
    if (result.isConfirmed) {
      var formulario = document.createElement('form');
      formulario.method = 'POST';
      formulario.action = 'php/Especialidades/eliminar.php';
      
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

// Cuando carga la pagina
document.addEventListener('DOMContentLoaded', function() {
  cargarDatos();
  
  // Evento para cambiar registros por pagina
  document.getElementById('select-mostrar').addEventListener('change', function() {
    registrosPorPagina = parseInt(this.value);
    paginaActual = 1;
    mostrarTabla();
  });
  
  // Evento para el buscador
  var inputBuscar = document.querySelector('input[placeholder="Buscar"]');
  inputBuscar.addEventListener('input', function() {
    busqueda = this.value;
    filtrarDatos();
  });
  
  // Evento boton anterior
  document.getElementById('btn-prev').addEventListener('click', function() {
    if (paginaActual > 1) {
      paginaActual--;
      mostrarTabla();
    }
  });
  
  // Evento boton siguiente
  document.getElementById('btn-next').addEventListener('click', function() {
    var totalPaginas = Math.ceil(especialidadesFiltradas.length / registrosPorPagina);
    if (paginaActual < totalPaginas) {
      paginaActual++;
      mostrarTabla();
    }
  });
});
