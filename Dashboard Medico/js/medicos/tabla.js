// esto son las variables globales que voy a usar
var todosLosMedicos = [];
var medicosFiltrados = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";
var todasLasEspecialidades = [];

// esto carga los datos de los medicos desde el servidor
function cargarDatos() {
  fetch('php/Medicos/listar.php')
    .then(function(respuesta) {
      return respuesta.json();
    })
    .then(function(datos) {
      todosLosMedicos = datos;
      medicosFiltrados = datos;
      paginaActual = 1;
      actualizarEstadisticas();
      mostrarTabla();
    })
    .catch(function(error) {
      console.log('Error:', error);
      document.getElementById('tabla-medicos').innerHTML = 
        '<tr><td colspan="7">Error al cargar datos</td></tr>';
    });
}

// esto carga las especialidades para el select
function cargarEspecialidades() {
  fetch('php/Medicos/listar_especialidades.php')
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

// esto formatea el horario JSON a texto legible
function formatearHorario(horarioJson) {
  try {
    var horarios = JSON.parse(horarioJson);
    var dias = [];
    
    var nombresDias = {
      'lunes': 'Lun',
      'martes': 'Mar',
      'miercoles': 'Mié',
      'jueves': 'Jue',
      'viernes': 'Vie',
      'sabado': 'Sáb',
      'domingo': 'Dom'
    };
    
    for (var dia in nombresDias) {
      if (horarios[dia] && horarios[dia].trabaja) {
        dias.push(nombresDias[dia] + ' ' + horarios[dia].inicio + '-' + horarios[dia].fin);
      }
    }
    
    return dias.length > 0 ? dias.join(', ') : 'Sin horario';
  } catch(e) {
    return horarioJson || 'Sin horario';
  }
}

// esto calcula y muestra las estadisticas
function actualizarEstadisticas() {
  var totalMedicos = todosLosMedicos.length;
  var medicosActivos = 0;
  var medicosInactivos = 0;
  
  // aqui cuento cuantos estan activos y cuantos inactivos
  for (var i = 0; i < todosLosMedicos.length; i++) {
    if (todosLosMedicos[i].Estatus == 1) {
      medicosActivos++;
    } else {
      medicosInactivos++;
    }
  }
  
  // aqui actualizo los numeros en la pantalla
  document.getElementById('total-medicos').textContent = totalMedicos;
  document.getElementById('medicos-activos').textContent = medicosActivos;
  document.getElementById('medicos-inactivos').textContent = medicosInactivos;
}

// esto filtra los medicos cuando busco algo
function filtrarDatos() {
  var textoBusqueda = busqueda.toLowerCase();
  
  medicosFiltrados = todosLosMedicos.filter(function(medico) {
    var nombre = medico.NombreCompleto.toLowerCase();
    var cedula = medico.CedulaProfesional.toLowerCase();
    var especialidad = medico.NombreEspecialidad ? medico.NombreEspecialidad.toLowerCase() : '';
    var telefono = medico.Telefono.toLowerCase();
    var correo = medico.CorreoElectronico.toLowerCase();
    
    return nombre.includes(textoBusqueda) || 
           cedula.includes(textoBusqueda) ||
           especialidad.includes(textoBusqueda) ||
           telefono.includes(textoBusqueda) ||
           correo.includes(textoBusqueda);
  });
  
  paginaActual = 1;
  mostrarTabla();
}

// esto le da formato a la fecha para que se vea bien
function formatearFecha(fecha) {
  if (!fecha) return '-';
  var f = new Date(fecha);
  var dia = String(f.getDate()).padStart(2, '0');
  var mes = String(f.getMonth() + 1).padStart(2, '0');
  var anio = f.getFullYear();
  return dia + '/' + mes + '/' + anio;
}

// esto muestra los medicos en la tabla
function mostrarTabla() {
  var tbody = document.getElementById('tabla-medicos');
  var total = medicosFiltrados.length;
  var totalPaginas = Math.ceil(total / registrosPorPagina);
  
  if (totalPaginas === 0) {
    totalPaginas = 1;
  }
  
  if (paginaActual > totalPaginas) {
    paginaActual = totalPaginas;
  }
  
  // aqui calculo que medicos mostrar segun la pagina
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var fin = inicio + registrosPorPagina;
  var registrosMostrar = medicosFiltrados.slice(inicio, fin);
  
  var html = '';
  
  if (registrosMostrar.length === 0) {
    html = '<tr><td colspan="7">No hay médicos registrados</td></tr>';
  } else {
    // aqui creo las filas de la tabla
    for (var i = 0; i < registrosMostrar.length; i++) {
      var medico = registrosMostrar[i];
      var especialidad = medico.NombreEspecialidad || 'Sin especialidad';
      var badgeClass = medico.Estatus == 1 ? 'text-bg-success' : 'text-bg-secondary';
      var badgeText = medico.Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
      var fechaIngreso = formatearFecha(medico.FechaIngreso);
      
      html += '<tr>';
      html += '<td class="fw-semibold">' + medico.NombreCompleto + '</td>';
      html += '<td>' + medico.CedulaProfesional + '</td>';
      html += '<td>' + especialidad + '</td>';
      html += '<td>' + medico.Telefono + '</td>';
      html += '<td>' + medico.CorreoElectronico + '</td>';
      html += '<td><span class="badge ' + badgeClass + '">' + badgeText + '</span></td>';
      html += '<td class="text-end">';
      html += '<div class="btn-group btn-group-sm">';
      html += '<button class="btn btn-info btn-ver" ' +
              'data-id="' + medico.IdMedico + '" ' +
              'data-nombre="' + medico.NombreCompleto + '" ' +
              'data-cedula="' + medico.CedulaProfesional + '" ' +
              'data-especialidad="' + especialidad + '" ' +
              'data-telefono="' + medico.Telefono + '" ' +
              'data-correo="' + medico.CorreoElectronico + '" ' +
              'data-horario=\'' + (medico.HorarioAtencion || '{}') + '\' ' +
              'data-ingreso="' + fechaIngreso + '" ' +
              'data-estatus="' + badgeText + '">Ver</button>';
      html += '<button class="btn btn-primary btn-editar" ' +
              'data-id="' + medico.IdMedico + '" ' +
              'data-nombre="' + medico.NombreCompleto + '" ' +
              'data-cedula="' + medico.CedulaProfesional + '" ' +
              'data-especialidad="' + (medico.EspecialidadId || '') + '" ' +
              'data-telefono="' + medico.Telefono + '" ' +
              'data-correo="' + medico.CorreoElectronico + '" ' +
              'data-horario=\'' + (medico.HorarioAtencion || '{}') + '\' ' +
              'data-estatus="' + medico.Estatus + '">Editar</button>';
      html += '<button class="btn btn-danger btn-eliminar" ' +
              'data-id="' + medico.IdMedico + '" ' +
              'data-nombre="' + medico.NombreCompleto + '">Eliminar</button>';
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
  agregarEventosVer();
  agregarEventosEliminar();
  agregarEventosEditar();
}

// esto pone el evento click a los botones de ver
function agregarEventosVer() {
  var botonesVer = document.querySelectorAll('.btn-ver');
  
  for (var i = 0; i < botonesVer.length; i++) {
    botonesVer[i].addEventListener('click', function() {
      var nombre = this.getAttribute('data-nombre');
      var cedula = this.getAttribute('data-cedula');
      var especialidad = this.getAttribute('data-especialidad');
      var telefono = this.getAttribute('data-telefono');
      var correo = this.getAttribute('data-correo');
      var horarioJson = this.getAttribute('data-horario');
      var ingreso = this.getAttribute('data-ingreso');
      var estatus = this.getAttribute('data-estatus');
      verMedico(nombre, cedula, especialidad, telefono, correo, horarioJson, ingreso, estatus);
    });
  }
}

// esto muestra la info del medico
function verMedico(nombre, cedula, especialidad, telefono, correo, horarioJson, ingreso, estatus) {
  var horarioTexto = formatearHorario(horarioJson);
  
  Swal.fire({
    title: 'Información del Médico',
    html:
      '<div class="text-start">' +
      '<p><strong>Nombre:</strong> ' + nombre + '</p>' +
      '<p><strong>Cédula:</strong> ' + cedula + '</p>' +
      '<p><strong>Especialidad:</strong> ' + especialidad + '</p>' +
      '<p><strong>Teléfono:</strong> ' + telefono + '</p>' +
      '<p><strong>Correo:</strong> ' + correo + '</p>' +
      '<p><strong>Horario:</strong> ' + horarioTexto + '</p>' +
      '<p><strong>Fecha de ingreso:</strong> ' + ingreso + '</p>' +
      '<p><strong>Estado:</strong> ' + estatus + '</p>' +
      '</div>',
    confirmButtonText: 'Cerrar',
    width: '500px'
  });
}

// esto valida el formulario antes de enviarlo
function validarFormulario() {
  var formulario = document.getElementById('form-registro');
  if (!formulario) return;
  
  formulario.addEventListener('submit', function(e) {
    var nombre = document.getElementById('input-nombre').value;
    var cedula = document.getElementById('input-cedula').value;
    var especialidad = document.getElementById('select-especialidad').value;
    var telefono = document.getElementById('input-telefono').value;
    var correo = document.getElementById('input-correo').value;
    
    // aqui verifico que al menos un dia este marcado
    var algunDiaMarcado = false;
    var dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
    for (var i = 0; i < dias.length; i++) {
      if (document.getElementById(dias[i] + '_trabaja').checked) {
        algunDiaMarcado = true;
        break;
      }
    }
    
    // aqui valido que todos los campos esten llenos
    if (!nombre || !cedula || !especialidad || !telefono || !correo) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor complete todos los campos',
        confirmButtonText: 'Aceptar'
      });
      return false;
    }
    
    // aqui valido que al menos un dia este seleccionado
    if (!algunDiaMarcado) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: 'Debe seleccionar al menos un día de trabajo',
        confirmButtonText: 'Aceptar'
      });
      return false;
    }
  });
}

// esto pone el evento click a los botones de eliminar
function agregarEventosEliminar() {
  var botonesEliminar = document.querySelectorAll('.btn-eliminar');
  
  for (var i = 0; i < botonesEliminar.length; i++) {
    botonesEliminar[i].addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      var nombre = this.getAttribute('data-nombre');
      eliminarMedico(id, nombre);
    });
  }
}

// esto elimina un medico
function eliminarMedico(id, nombre) {
  Swal.fire({
    title: 'Eliminar médico',
    text: '¿Está seguro que desea eliminar a ' + nombre + '?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#d33'
  }).then(function(resultado) {
    if (resultado.isConfirmed) {
      window.location.href = 'php/Medicos/eliminar.php?id=' + id;
    }
  });
}

// esto pone el evento click a los botones de editar
function agregarEventosEditar() {
  var botonesEditar = document.querySelectorAll('.btn-editar');
  
  for (var i = 0; i < botonesEditar.length; i++) {
    botonesEditar[i].addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      var nombre = this.getAttribute('data-nombre');
      var cedula = this.getAttribute('data-cedula');
      var especialidadId = this.getAttribute('data-especialidad');
      var telefono = this.getAttribute('data-telefono');
      var correo = this.getAttribute('data-correo');
      var horarioJson = this.getAttribute('data-horario');
      var estatus = this.getAttribute('data-estatus');
      editarMedico(id, nombre, cedula, especialidadId, telefono, correo, horarioJson, estatus);
    });
  }
}

// esto muestra el formulario de edicion en sweetalert
function editarMedico(id, nombre, cedula, especialidadId, telefono, correo, horarioJson, estatus) {
  var horarios = {};
  try {
    horarios = JSON.parse(horarioJson);
  } catch(e) {
    horarios = {};
  }
  
  // aqui creo las opciones del select de especialidades
  var opcionesEspecialidad = '';
  for (var i = 0; i < todasLasEspecialidades.length; i++) {
    var selected = todasLasEspecialidades[i].IdEspecialidad == especialidadId ? 'selected' : '';
    opcionesEspecialidad += '<option value="' + todasLasEspecialidades[i].IdEspecialidad + '" ' + selected + '>' + 
                            todasLasEspecialidades[i].NombreEspecialidad + '</option>';
  }
  
  var dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
  var nombresDias = {
    'lunes': 'Lunes',
    'martes': 'Martes',
    'miercoles': 'Miércoles',
    'jueves': 'Jueves',
    'viernes': 'Viernes',
    'sabado': 'Sábado',
    'domingo': 'Domingo'
  };
  
  var horariosHtml = '';
  for (var i = 0; i < dias.length; i++) {
    var dia = dias[i];
    var checked = horarios[dia] && horarios[dia].trabaja ? 'checked' : '';
    var inicio = horarios[dia] && horarios[dia].inicio ? horarios[dia].inicio : '09:00';
    var fin = horarios[dia] && horarios[dia].fin ? horarios[dia].fin : '17:00';
    
    horariosHtml += '<tr>' +
      '<td class="align-middle"><small class="fw-semibold">' + nombresDias[dia] + '</small></td>' +
      '<td class="text-center"><input class="form-check-input" type="checkbox" id="edit_' + dia + '_trabaja" ' + checked + '></td>' +
      '<td><input type="time" class="form-control form-control-sm" id="edit_' + dia + '_inicio" value="' + inicio + '"></td>' +
      '<td><input type="time" class="form-control form-control-sm" id="edit_' + dia + '_fin" value="' + fin + '"></td>' +
      '</tr>';
  }
  
  var checkedEstatus = estatus == 1 ? 'checked' : '';
  
  Swal.fire({
    title: 'Editar médico',
    html:
      '<div class="text-start">' +
      '<div class="mb-3">' +
      '<label class="form-label small">Nombre:</label>' +
      '<input type="text" class="form-control" id="edit-nombre" value="' + nombre + '">' +
      '</div>' +
      '<div class="mb-3">' +
      '<label class="form-label small">Cédula:</label>' +
      '<input type="text" class="form-control" id="edit-cedula" value="' + cedula + '">' +
      '</div>' +
      '<div class="mb-3">' +
      '<label class="form-label small">Especialidad:</label>' +
      '<select class="form-select" id="edit-especialidad">' + opcionesEspecialidad + '</select>' +
      '</div>' +
      '<div class="mb-3">' +
      '<label class="form-label small">Teléfono:</label>' +
      '<input type="tel" class="form-control" id="edit-telefono" value="' + telefono + '">' +
      '</div>' +
      '<div class="mb-3">' +
      '<label class="form-label small">Correo:</label>' +
      '<input type="email" class="form-control" id="edit-correo" value="' + correo + '">' +
      '</div>' +
      '<div class="mb-3">' +
      '<label class="form-label small">Horarios:</label>' +
      '<div class="table-responsive" style="max-height: 300px; overflow-y: auto;">' +
      '<table class="table table-sm table-borderless">' +
      '<thead><tr><th style="width:100px;"></th><th class="text-center" style="width:80px;">Trabaja</th><th style="width:100px;">Inicio</th><th style="width:100px;">Fin</th></tr></thead>' +
      '<tbody>' + horariosHtml + '</tbody>' +
      '</table>' +
      '</div>' +
      '</div>' +
      '<div class="form-check">' +
      '<input class="form-check-input" type="checkbox" id="edit-estatus" ' + checkedEstatus + '>' +
      '<label class="form-check-label" for="edit-estatus">Activo</label>' +
      '</div>' +
      '</div>',
    width: '600px',
    showCancelButton: true,
    confirmButtonText: 'Guardar cambios',
    cancelButtonText: 'Cancelar',
    preConfirm: function() {
      var nuevoNombre = document.getElementById('edit-nombre').value;
      var nuevaCedula = document.getElementById('edit-cedula').value;
      var nuevaEspecialidad = document.getElementById('edit-especialidad').value;
      var nuevoTelefono = document.getElementById('edit-telefono').value;
      var nuevoCorreo = document.getElementById('edit-correo').value;
      var nuevoEstatus = document.getElementById('edit-estatus').checked ? 1 : 0;
      
      // aqui armo el objeto de horarios
      var nuevosHorarios = {};
      var algunDiaMarcado = false;
      for (var i = 0; i < dias.length; i++) {
        var dia = dias[i];
        var trabaja = document.getElementById('edit_' + dia + '_trabaja').checked;
        var inicio = document.getElementById('edit_' + dia + '_inicio').value;
        var fin = document.getElementById('edit_' + dia + '_fin').value;
        
        if (trabaja) {
          algunDiaMarcado = true;
          nuevosHorarios[dia] = {
            trabaja: true,
            inicio: inicio,
            fin: fin
          };
        } else {
          nuevosHorarios[dia] = {
            trabaja: false,
            inicio: inicio,
            fin: fin
          };
        }
      }
      
      // aqui valido que los campos no esten vacios
      if (!nuevoNombre || !nuevaCedula || !nuevaEspecialidad || !nuevoTelefono || !nuevoCorreo) {
        Swal.showValidationMessage('Por favor complete todos los campos');
        return false;
      }
      
      // aqui valido que al menos un dia este marcado
      if (!algunDiaMarcado) {
        Swal.showValidationMessage('Debe seleccionar al menos un día de trabajo');
        return false;
      }
      
      return {
        id: id,
        nombre: nuevoNombre,
        cedula: nuevaCedula,
        especialidad: nuevaEspecialidad,
        telefono: nuevoTelefono,
        correo: nuevoCorreo,
        horarios: JSON.stringify(nuevosHorarios),
        estatus: nuevoEstatus
      };
    }
  }).then(function(resultado) {
    if (resultado.isConfirmed && resultado.value) {
      // aqui creo el formulario y lo envio
      var form = document.createElement('form');
      form.method = 'POST';
      form.action = 'php/Medicos/editar.php';
      
      var campos = resultado.value;
      for (var campo in campos) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = campo;
        input.value = campos[campo];
        form.appendChild(input);
      }
      
      document.body.appendChild(form);
      form.submit();
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
    var totalPaginas = Math.ceil(medicosFiltrados.length / registrosPorPagina);
    if (paginaActual < totalPaginas) {
      paginaActual++;
      mostrarTabla();
    }
  });
});
