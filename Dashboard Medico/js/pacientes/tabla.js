// Variables globales
var todosLosPacientes = [];
var pacientesFiltrados = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";

// Funcion para cargar datos desde el servidor
function cargarDatos() {
  fetch('php/Pacientes/listar.php')
    .then(function(respuesta) {
      return respuesta.json();
    })
    .then(function(datos) {
      todosLosPacientes = datos;
      pacientesFiltrados = datos;
      paginaActual = 1;
      actualizarEstadisticas();
      mostrarTabla();
    })
    .catch(function(error) {
      console.log('Error:', error);
      document.getElementById('tabla-pacientes').innerHTML = 
        '<tr><td colspan="6">Error al cargar datos</td></tr>';
    });
}

// Funcion para actualizar estadisticas
function actualizarEstadisticas() {
  var totalPacientes = todosLosPacientes.length;
  var pacientesActivos = todosLosPacientes.filter(function(p) { return p.Estatus == 1; }).length;
  var pacientesInactivos = todosLosPacientes.filter(function(p) { return p.Estatus == 0; }).length;
  
  var elementoTotal = document.getElementById('total-pacientes');
  var elementoActivos = document.getElementById('pacientes-activos');
  var elementoInactivos = document.getElementById('pacientes-inactivos');
  
  if (elementoTotal) {
    elementoTotal.textContent = totalPacientes;
  }
  if (elementoActivos) {
    elementoActivos.textContent = pacientesActivos;
  }
  if (elementoInactivos) {
    elementoInactivos.textContent = pacientesInactivos;
  }
}

// Funcion para filtrar datos
function filtrarDatos() {
  var textoBusqueda = busqueda.toLowerCase();
  
  pacientesFiltrados = todosLosPacientes.filter(function(paciente) {
    var nombre = paciente.NombreCompleto.toLowerCase();
    var curp = (paciente.CURP || '').toLowerCase();
    var telefono = (paciente.Telefono || '').toLowerCase();
    var correo = (paciente.CorreoElectronico || '').toLowerCase();
    return nombre.includes(textoBusqueda) || curp.includes(textoBusqueda) || telefono.includes(textoBusqueda) || correo.includes(textoBusqueda);
  });
  
  paginaActual = 1;
  mostrarTabla();
}

// Funcion para mostrar la tabla
function mostrarTabla() {
  var tbody = document.getElementById('tabla-pacientes');
  var total = pacientesFiltrados.length;
  var totalPaginas = Math.ceil(total / registrosPorPagina);
  
  if (totalPaginas === 0) {
    totalPaginas = 1;
  }
  
  if (paginaActual > totalPaginas) {
    paginaActual = totalPaginas;
  }
  
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var fin = inicio + registrosPorPagina;
  var registrosMostrar = pacientesFiltrados.slice(inicio, fin);
  
  var html = '';
  
  if (registrosMostrar.length === 0) {
    html = '<tr><td colspan="6">No hay pacientes registrados</td></tr>';
  } else {
    for (var i = 0; i < registrosMostrar.length; i++) {
      var paciente = registrosMostrar[i];
      var fecha = new Date(paciente.FechaRegistro);
      var fechaFormateada = fecha.toLocaleDateString();
      
      html += '<tr>';
      html += '<td class="fw-semibold">' + paciente.NombreCompleto + '</td>';
      html += '<td>' + (paciente.CURP || 'N/A') + '</td>';
      html += '<td>' + (paciente.Telefono || 'N/A') + '</td>';
      html += '<td>' + (paciente.CorreoElectronico || 'N/A') + '</td>';
      html += '<td>' + fechaFormateada + '</td>';
      html += '<td class="text-end">';
      html += '<div class="btn-group btn-group-sm">';
      html += '<button class="btn btn-primary btn-editar" data-id="' + paciente.IdPaciente + '">Editar</button>';
      html += '<button class="btn btn-primary btn-eliminar" data-id="' + paciente.IdPaciente + '" data-nombre="' + paciente.NombreCompleto + '">Eliminar</button>';
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
      var idPaciente = this.getAttribute('data-id');
      editarPaciente(idPaciente);
    });
  }
}

// Funcion para editar paciente
function editarPaciente(id) {
  // busca el paciente por id
  var paciente = todosLosPacientes.find(function(p) { return p.IdPaciente == id; });
  
  if (!paciente) {
    Swal.fire('Error', 'Paciente no encontrado', 'error');
    return;
  }
  
  // muestra formulario de edicion
  Swal.fire({
    title: 'Editar paciente',
    html:
      '<div class="text-start">' +
      '<label class="form-label small">Nombre completo:</label>' +
      '<input id="swal-nombre" class="swal2-input w-100" value="' + paciente.NombreCompleto + '">' +
      '<label class="form-label small">CURP:</label>' +
      '<input id="swal-curp" class="swal2-input w-100" value="' + (paciente.CURP || '') + '" maxlength="18">' +
      '<label class="form-label small">Fecha nacimiento:</label>' +
      '<input id="swal-fecha" type="date" class="swal2-input w-100" value="' + (paciente.FechaNacimiento || '') + '">' +
      '<label class="form-label small">Sexo:</label>' +
      '<select id="swal-sexo" class="swal2-input w-100">' +
      '<option value="">Seleccionar</option>' +
      '<option value="M" ' + (paciente.Sexo === 'M' ? 'selected' : '') + '>Masculino</option>' +
      '<option value="F" ' + (paciente.Sexo === 'F' ? 'selected' : '') + '>Femenino</option>' +
      '</select>' +
      '<label class="form-label small">Telefono:</label>' +
      '<input id="swal-telefono" class="swal2-input w-100" value="' + (paciente.Telefono || '') + '">' +
      '<label class="form-label small">Correo:</label>' +
      '<input id="swal-correo" type="email" class="swal2-input w-100" value="' + (paciente.CorreoElectronico || '') + '">' +
      '<label class="form-label small">Direccion:</label>' +
      '<textarea id="swal-direccion" class="swal2-textarea w-100">' + (paciente.Direccion || '') + '</textarea>' +
      '<label class="form-label small">Contacto emergencia:</label>' +
      '<input id="swal-contacto" class="swal2-input w-100" value="' + (paciente.ContactoEmergencia || '') + '">' +
      '<label class="form-label small">Telefono emergencia:</label>' +
      '<input id="swal-tel-emerg" class="swal2-input w-100" value="' + (paciente.TelefonoEmergencia || '') + '">' +
      '<label class="form-label small">Alergias:</label>' +
      '<textarea id="swal-alergias" class="swal2-textarea w-100">' + (paciente.Alergias || '') + '</textarea>' +
      '<label class="form-label small">Antecedentes medicos:</label>' +
      '<textarea id="swal-antecedentes" class="swal2-textarea w-100">' + (paciente.AntecedentesMedicos || '') + '</textarea>' +
      '</div>',
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    cancelButtonText: 'Cancelar',
    width: '600px',
    preConfirm: function() {
      var nombre = document.getElementById('swal-nombre').value;
      var curp = document.getElementById('swal-curp').value;
      var fecha = document.getElementById('swal-fecha').value;
      var sexo = document.getElementById('swal-sexo').value;
      var telefono = document.getElementById('swal-telefono').value;
      var correo = document.getElementById('swal-correo').value;
      var direccion = document.getElementById('swal-direccion').value;
      var contacto = document.getElementById('swal-contacto').value;
      var telEmerg = document.getElementById('swal-tel-emerg').value;
      var alergias = document.getElementById('swal-alergias').value;
      var antecedentes = document.getElementById('swal-antecedentes').value;
      
      if (!nombre) {
        Swal.showValidationMessage('El nombre es requerido');
        return false;
      }
      
      return { nombre: nombre, curp: curp, fecha: fecha, sexo: sexo, telefono: telefono, correo: correo, direccion: direccion, contacto: contacto, telEmerg: telEmerg, alergias: alergias, antecedentes: antecedentes };
    }
  }).then(function(result) {
    if (result.isConfirmed) {
      // crea formulario y envia
      var formulario = document.createElement('form');
      formulario.method = 'POST';
      formulario.action = 'php/Pacientes/editar.php';
      
      var inputId = document.createElement('input');
      inputId.type = 'hidden';
      inputId.name = 'id';
      inputId.value = id;
      formulario.appendChild(inputId);
      
      var inputNombre = document.createElement('input');
      inputNombre.type = 'hidden';
      inputNombre.name = 'nombre_completo';
      inputNombre.value = result.value.nombre;
      formulario.appendChild(inputNombre);
      
      var inputCurp = document.createElement('input');
      inputCurp.type = 'hidden';
      inputCurp.name = 'curp';
      inputCurp.value = result.value.curp;
      formulario.appendChild(inputCurp);
      
      var inputFecha = document.createElement('input');
      inputFecha.type = 'hidden';
      inputFecha.name = 'fecha_nacimiento';
      inputFecha.value = result.value.fecha;
      formulario.appendChild(inputFecha);
      
      var inputSexo = document.createElement('input');
      inputSexo.type = 'hidden';
      inputSexo.name = 'sexo';
      inputSexo.value = result.value.sexo;
      formulario.appendChild(inputSexo);
      
      var inputTelefono = document.createElement('input');
      inputTelefono.type = 'hidden';
      inputTelefono.name = 'telefono';
      inputTelefono.value = result.value.telefono;
      formulario.appendChild(inputTelefono);
      
      var inputCorreo = document.createElement('input');
      inputCorreo.type = 'hidden';
      inputCorreo.name = 'correo';
      inputCorreo.value = result.value.correo;
      formulario.appendChild(inputCorreo);
      
      var inputDireccion = document.createElement('input');
      inputDireccion.type = 'hidden';
      inputDireccion.name = 'direccion';
      inputDireccion.value = result.value.direccion;
      formulario.appendChild(inputDireccion);
      
      var inputContacto = document.createElement('input');
      inputContacto.type = 'hidden';
      inputContacto.name = 'contacto_emergencia';
      inputContacto.value = result.value.contacto;
      formulario.appendChild(inputContacto);
      
      var inputTelEmerg = document.createElement('input');
      inputTelEmerg.type = 'hidden';
      inputTelEmerg.name = 'telefono_emergencia';
      inputTelEmerg.value = result.value.telEmerg;
      formulario.appendChild(inputTelEmerg);
      
      var inputAlergias = document.createElement('input');
      inputAlergias.type = 'hidden';
      inputAlergias.name = 'alergias';
      inputAlergias.value = result.value.alergias;
      formulario.appendChild(inputAlergias);
      
      var inputAntecedentes = document.createElement('input');
      inputAntecedentes.type = 'hidden';
      inputAntecedentes.name = 'antecedentes';
      inputAntecedentes.value = result.value.antecedentes;
      formulario.appendChild(inputAntecedentes);
      
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
      var idPaciente = this.getAttribute('data-id');
      var nombrePaciente = this.getAttribute('data-nombre');
      eliminarPaciente(idPaciente, nombrePaciente);
    });
  }
}

// Funcion para eliminar paciente
function eliminarPaciente(id, nombre) {
  Swal.fire({
    title: 'Eliminar paciente?',
    text: 'Esta seguro de eliminar a "' + nombre + '"?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Si, eliminar',
    cancelButtonText: 'Cancelar'
  }).then(function(result) {
    if (result.isConfirmed) {
      var formulario = document.createElement('form');
      formulario.method = 'POST';
      formulario.action = 'php/Pacientes/eliminar.php';
      
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
  var inputBuscar = document.getElementById('input-buscar');
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
    var totalPaginas = Math.ceil(pacientesFiltrados.length / registrosPorPagina);
    if (paginaActual < totalPaginas) {
      paginaActual++;
      mostrarTabla();
    }
  });
});
