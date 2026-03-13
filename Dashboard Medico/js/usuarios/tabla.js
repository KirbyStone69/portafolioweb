// esto son las variables globales que voy a usar
var todosLosUsuarios = [];
var usuariosFiltrados = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";
var todosLosMedicos = [];

// esto carga los datos de los usuarios desde el servidor
function cargarDatos() {
  fetch('php/Usuarios/listar.php')
    .then(function(respuesta) {
      return respuesta.json();
    })
    .then(function(datos) {
      todosLosUsuarios = datos;
      usuariosFiltrados = datos;
      paginaActual = 1;
      actualizarEstadisticas();
      mostrarTabla();
    })
    .catch(function(error) {
      console.log('Error:', error);
      document.getElementById('tabla-usuarios').innerHTML = 
        '<tr><td colspan="6">Error al cargar datos</td></tr>';
    });
}

// esto carga los medicos para el select
function cargarMedicos() {
  fetch('php/Usuarios/listar_medicos.php')
    .then(function(respuesta) {
      return respuesta.json();
    })
    .then(function(datos) {
      todosLosMedicos = datos;
      llenarSelectMedicos();
    })
    .catch(function(error) {
      console.log('Error al cargar medicos:', error);
      todosLosMedicos = [];
      llenarSelectMedicos();
    });
}

// esto llena el select de medicos con las opciones
function llenarSelectMedicos() {
  var select = document.getElementById('select-medico');
  if (!select) return;
  
  var html = '<option value="">Sin médico asignado</option>';
  
  if (todosLosMedicos.length > 0) {
    for (var i = 0; i < todosLosMedicos.length; i++) {
      html += '<option value="' + todosLosMedicos[i].IdMedico + '">' + 
              todosLosMedicos[i].NombreCompleto + '</option>';
    }
  } else {
    html += '<option value="" disabled>No hay médicos registrados</option>';
  }
  
  select.innerHTML = html;
}

// esto muestra u oculta los campos segun el rol
function controlarCampoMedico() {
  var selectRol = document.getElementById('select-rol');
  var grupoMedico = document.getElementById('grupo-medico');
  var grupoMedicosAsignados = document.getElementById('grupo-medicos-asignados');
  
  if (selectRol && grupoMedico && grupoMedicosAsignados) {
    selectRol.addEventListener('change', function() {
      if (this.value === 'Medico') {
        grupoMedico.style.display = 'block';
        grupoMedicosAsignados.style.display = 'none';
      } else if (this.value === 'Recepcionista') {
        grupoMedico.style.display = 'none';
        grupoMedicosAsignados.style.display = 'block';
        document.getElementById('select-medico').value = '';
        llenarCheckboxesMedicos();
      } else {
        grupoMedico.style.display = 'none';
        grupoMedicosAsignados.style.display = 'none';
        document.getElementById('select-medico').value = '';
      }
    });
  }
}

// esto llena los checkboxes de medicos para recepcionistas
function llenarCheckboxesMedicos() {
  var container = document.getElementById('lista-medicos-asignados');
  if (!container) return;
  
  var html = '';
  
  if (todosLosMedicos.length > 0) {
    for (var i = 0; i < todosLosMedicos.length; i++) {
      html += '<div class="form-check mb-1">';
      html += '<input class="form-check-input" type="checkbox" name="medicos_asignados[]" ';
      html += 'value="' + todosLosMedicos[i].IdMedico + '" ';
      html += 'id="medico-' + todosLosMedicos[i].IdMedico + '">';
      html += '<label class="form-check-label small" for="medico-' + todosLosMedicos[i].IdMedico + '">';
      html += todosLosMedicos[i].NombreCompleto;
      html += '</label>';
      html += '</div>';
    }
  } else {
    html = '<p class="text-muted small mb-0">No hay médicos registrados</p>';
  }
  
  container.innerHTML = html;
}


// esto calcula y muestra las estadisticas
function actualizarEstadisticas() {
  var totalUsuarios = todosLosUsuarios.length;
  var usuariosActivos = 0;
  var usuariosInactivos = 0;
  var porRol = {
    'Admin': 0,
    'Medico': 0,
    'Recepcionista': 0
  };
  
  // aqui cuento cuantos estan activos y cuantos inactivos
  for (var i = 0; i < todosLosUsuarios.length; i++) {
    if (todosLosUsuarios[i].Activo == 1) {
      usuariosActivos++;
    } else {
      usuariosInactivos++;
    }
    
    // aqui cuento por rol
    var rol = todosLosUsuarios[i].Rol;
    if (porRol[rol] !== undefined) {
      porRol[rol]++;
    }
  }
  
  // aqui actualizo los numeros en la pantalla
  document.getElementById('total-usuarios').textContent = totalUsuarios;
  document.getElementById('usuarios-activos').textContent = usuariosActivos;
  document.getElementById('usuarios-inactivos').textContent = usuariosInactivos;
  document.getElementById('usuarios-admin').textContent = porRol['Admin'];
  document.getElementById('usuarios-medico').textContent = porRol['Medico'];
  document.getElementById('usuarios-recepcionista').textContent = porRol['Recepcionista'];
}

// esto filtra los usuarios cuando busco algo
function filtrarDatos() {
  var textoBusqueda = busqueda.toLowerCase();
  
  usuariosFiltrados = todosLosUsuarios.filter(function(usuario) {
    var nombreUsuario = usuario.Usuario.toLowerCase();
    var rol = usuario.Rol.toLowerCase();
    var medico = usuario.NombreMedico ? usuario.NombreMedico.toLowerCase() : '';
    
    return nombreUsuario.includes(textoBusqueda) || 
           rol.includes(textoBusqueda) ||
           medico.includes(textoBusqueda);
  });
  
  paginaActual = 1;
  mostrarTabla();
}

// esto le da formato a la fecha para que se vea bien
function formatearFecha(fecha) {
  if (!fecha) return 'Nunca';
  var f = new Date(fecha);
  var dia = String(f.getDate()).padStart(2, '0');
  var mes = String(f.getMonth() + 1).padStart(2, '0');
  var anio = f.getFullYear();
  var hora = String(f.getHours()).padStart(2, '0');
  var min = String(f.getMinutes()).padStart(2, '0');
  return dia + '/' + mes + '/' + anio + ' ' + hora + ':' + min;
}

// esto obtiene el color del badge segun el rol
function getBadgeRol(rol) {
  if (rol === 'Admin') return 'text-bg-danger';
  if (rol === 'Medico') return 'text-bg-primary';
  if (rol === 'Recepcionista') return 'text-bg-info';
  return 'text-bg-secondary';
}

// esto muestra los usuarios en la tabla
function mostrarTabla() {
  var tbody = document.getElementById('tabla-usuarios');
  var total = usuariosFiltrados.length;
  var totalPaginas = Math.ceil(total / registrosPorPagina);
  
  if (totalPaginas === 0) {
    totalPaginas = 1;
  }
  
  if (paginaActual > totalPaginas) {
    paginaActual = totalPaginas;
  }
  
  // aqui calculo que usuarios mostrar segun la pagina
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var fin = inicio + registrosPorPagina;
  var registrosMostrar = usuariosFiltrados.slice(inicio, fin);
  
  var html = '';
  
  if (registrosMostrar.length === 0) {
    html = '<tr><td colspan="6">No hay usuarios registrados</td></tr>';
  } else {
    // aqui creo las filas de la tabla
    for (var i = 0; i < registrosMostrar.length; i++) {
      var usuario = registrosMostrar[i];
      var medico = usuario.NombreMedico || '-';
      var badgeEstatus = usuario.Activo == 1 ? 'text-bg-success' : 'text-bg-secondary';
      var textoEstatus = usuario.Activo == 1 ? 'ACTIVO' : 'INACTIVO';
      var badgeRol = getBadgeRol(usuario.Rol);
      var ultimoAcceso = formatearFecha(usuario.UltimoAcceso);
      
      // aqui obtengo info de medicos asignados si es recepcionista
      var infoMedicoCol = medico;
      if (usuario.Rol === 'Recepcionista' && usuario.MedicosAsignados && usuario.MedicosAsignados.length > 0) {
        infoMedicoCol = usuario.MedicosAsignados.length + ' médico(s)';
      }
      
      html += '<tr>';
      html += '<td class="fw-semibold">' + usuario.Usuario + '</td>';
      html += '<td><span class="badge ' + badgeRol + '">' + usuario.Rol + '</span></td>';
      html += '<td>' + infoMedicoCol + '</td>';
      html += '<td class="small">' + ultimoAcceso + '</td>';
      html += '<td><span class="badge ' + badgeEstatus + '">' + textoEstatus + '</span></td>';
      html += '<td class="text-end">';
      html += '<div class="btn-group btn-group-sm">';
      
      // aqui guardo los medicos asignados en formato JSON
      var medicosAsignadosJSON = '';
      if (usuario.MedicosAsignados) {
        medicosAsignadosJSON = JSON.stringify(usuario.MedicosAsignados);
      }
      
      html += '<button class="btn btn-info btn-ver" ' +
              'data-id="' + usuario.IdUsuario + '" ' +
              'data-usuario="' + usuario.Usuario + '" ' +
              'data-nombre="' + (usuario.NombreCompleto || '') + '" ' +
              'data-telefono="' + (usuario.Telefono || '') + '" ' +
              'data-correo="' + (usuario.CorreoElectronico || '') + '" ' +
              'data-rol="' + usuario.Rol + '" ' +
              'data-medico="' + medico + '" ' +
              'data-medicos-asignados=\'' + medicosAsignadosJSON + '\' ' +
              'data-activo="' + textoEstatus + '" ' +
              'data-acceso="' + ultimoAcceso + '">Ver</button>';
      html += '<button class="btn btn-primary btn-editar" ' +
              'data-id="' + usuario.IdUsuario + '" ' +
              'data-usuario="' + usuario.Usuario + '" ' +
              'data-nombre="' + (usuario.NombreCompleto || '') + '" ' +
              'data-telefono="' + (usuario.Telefono || '') + '" ' +
              'data-correo="' + (usuario.CorreoElectronico || '') + '" ' +
              'data-rol="' + usuario.Rol + '" ' +
              'data-medico="' + (usuario.IdMedico || '') + '" ' +
              'data-medicos-asignados=\'' + medicosAsignadosJSON + '\' ' +
              'data-activo="' + usuario.Activo + '">Editar</button>';
      html += '<button class="btn btn-danger btn-eliminar" ' +
              'data-id="' + usuario.IdUsuario + '" ' +
              'data-usuario="' + usuario.Usuario + '">Eliminar</button>';
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
      var usuario = this.getAttribute('data-usuario');
      var nombre = this.getAttribute('data-nombre');
      var telefono = this.getAttribute('data-telefono');
      var correo = this.getAttribute('data-correo');
      var rol = this.getAttribute('data-rol');
      var medico = this.getAttribute('data-medico');
      var medicosAsignados = this.getAttribute('data-medicos-asignados');
      var activo = this.getAttribute('data-activo');
      var acceso = this.getAttribute('data-acceso');
      verUsuario(usuario, nombre, telefono, correo, rol, medico, medicosAsignados, activo, acceso);
    });
  }
}

// esto muestra la info del usuario
function verUsuario(usuario, nombre, telefono, correo, rol, medico, medicosAsignados, activo, acceso) {
  var infoMedico = '';
  
  if (rol === 'Medico' && medico !== '-') {
    infoMedico = '<p><strong>Médico vinculado:</strong> ' + medico + '</p>';
  } else if (rol === 'Recepcionista' && medicosAsignados) {
    try {
      var medicos = JSON.parse(medicosAsignados);
      if (medicos && medicos.length > 0) {
        infoMedico = '<p><strong>Médicos asignados:</strong></p><ul class="text-start">';
        for (var i = 0; i < medicos.length; i++) {
          infoMedico += '<li>' + medicos[i].NombreCompleto + '</li>';
        }
        infoMedico += '</ul>';
      }
    } catch (e) {
      infoMedico = '';
    }
  }
  
  Swal.fire({
    title: 'Información del Usuario',
    html:
      '<div class="text-start">' +
      '<p><strong>Usuario:</strong> ' + usuario + '</p>' +
      '<p><strong>Nombre:</strong> ' + (nombre || 'No especificado') + '</p>' +
      '<p><strong>Teléfono:</strong> ' + (telefono || 'No especificado') + '</p>' +
      '<p><strong>Correo:</strong> ' + (correo || 'No especificado') + '</p>' +
      '<p><strong>Rol:</strong> ' + rol + '</p>' +
      infoMedico +
      '<p><strong>Estado:</strong> ' + activo + '</p>' +
      '<p><strong>Último acceso:</strong> ' + acceso + '</p>' +
      '</div>',
    confirmButtonText: 'Cerrar',
    width: '500px'
  });
}

// esto pone el evento click a los botones de editar
function agregarEventosEditar() {
  var botonesEditar = document.querySelectorAll('.btn-editar');
  
  for (var i = 0; i < botonesEditar.length; i++) {
    botonesEditar[i].addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      var usuario = this.getAttribute('data-usuario');
      var nombre = this.getAttribute('data-nombre');
      var telefono = this.getAttribute('data-telefono');
      var correo = this.getAttribute('data-correo');
      var rol = this.getAttribute('data-rol');
      var medicoId = this.getAttribute('data-medico');
      var medicosAsignados = this.getAttribute('data-medicos-asignados');
      var activo = this.getAttribute('data-activo');
      editarUsuario(id, usuario, nombre, telefono, correo, rol, medicoId, medicosAsignados, activo);
    });
  }
}

// esto muestra el popup para editar un usuario
function editarUsuario(id, usuario, nombre, telefono, correo, rol, medicoId, medicosAsignados, activo) {
  // aqui creo las opciones del select de rol
  var roles = ['Admin', 'Medico', 'Recepcionista'];
  var opcionesRol = '';
  for (var i = 0; i < roles.length; i++) {
    var selected = roles[i] == rol ? 'selected' : '';
    opcionesRol += '<option value="' + roles[i] + '" ' + selected + '>' + roles[i] + '</option>';
  }
  
  // aqui creo las opciones del select de medicos
  var opcionesMedicos = '<option value="">Sin médico asignado</option>';
  for (var i = 0; i < todosLosMedicos.length; i++) {
    var selected = todosLosMedicos[i].IdMedico == medicoId ? 'selected' : '';
    opcionesMedicos += '<option value="' + todosLosMedicos[i].IdMedico + '" ' + selected + '>' + 
                       todosLosMedicos[i].NombreCompleto + '</option>';
  }
  
  // aqui creo los checkboxes de medicos asignados
  var medicosAsignadosArray = [];
  try {
    if (medicosAsignados) {
      medicosAsignadosArray = JSON.parse(medicosAsignados);
    }
  } catch (e) {
    medicosAsignadosArray = [];
  }
  
  var checkboxesMedicos = '';
  for (var i = 0; i < todosLosMedicos.length; i++) {
    var checked = '';
    // aqui verifico si el medico esta en el array de asignados
    for (var j = 0; j < medicosAsignadosArray.length; j++) {
      if (medicosAsignadosArray[j].IdMedico == todosLosMedicos[i].IdMedico) {
        checked = 'checked';
        break;
      }
    }
    
    checkboxesMedicos += '<div class="form-check mb-1">';
    checkboxesMedicos += '<input class="form-check-input checkbox-medico-asignado" type="checkbox" ';
    checkboxesMedicos += 'value="' + todosLosMedicos[i].IdMedico + '" ';
    checkboxesMedicos += 'id="edit-medico-' + todosLosMedicos[i].IdMedico + '" ' + checked + '>';
    checkboxesMedicos += '<label class="form-check-label small" for="edit-medico-' + todosLosMedicos[i].IdMedico + '">';
    checkboxesMedicos += todosLosMedicos[i].NombreCompleto;
    checkboxesMedicos += '</label>';
    checkboxesMedicos += '</div>';
  }
  
  var checkedActivo = activo == 1 ? 'checked' : '';
  var mostrarMedico = rol === 'Medico' ? '' : 'style="display:none"';
  var mostrarMedicosAsignados = rol === 'Recepcionista' ? '' : 'style="display:none"';
  for (var i = 0; i < todosLosMedicos.length; i++) {
    var selected = todosLosMedicos[i].IdMedico == medicoId ? 'selected' : '';
    opcionesMedicos += '<option value="' + todosLosMedicos[i].IdMedico + '" ' + selected + '>' + 
                       todosLosMedicos[i].NombreCompleto + '</option>';
  }
  
  var checkedActivo = activo == 1 ? 'checked' : '';
  var mostrarMedico = rol === 'Medico' ? '' : 'style="display:none"';
  var mostrarMedicosAsignados = rol === 'Recepcionista' ? '' : 'style="display:none"';
  
  // aqui muestro el popup con sweetalert
  Swal.fire({
    title: 'Editar usuario',
    html:
      '<div class="text-start">' +
      '<label class="form-label small">Usuario:</label>' +
      '<input id="swal-input-usuario" class="form-control mb-3" placeholder="Usuario" value="' + usuario + '">' +
      '<label class="form-label small">Nombre completo:</label>' +
      '<input id="swal-input-nombre" class="form-control mb-3" placeholder="Nombre completo" value="' + (nombre || '') + '">' +
      '<label class="form-label small">Teléfono:</label>' +
      '<input id="swal-input-telefono" class="form-control mb-3" placeholder="Teléfono" value="' + (telefono || '') + '">' +
      '<label class="form-label small">Correo:</label>' +
      '<input id="swal-input-correo" type="email" class="form-control mb-3" placeholder="correo@ejemplo.com" value="' + (correo || '') + '">' +
      '<label class="form-label small">Nueva contraseña (dejar vacío para no cambiar):</label>' +
      '<input id="swal-input-password" type="password" class="form-control mb-3" placeholder="Nueva contraseña">' +
      '<label class="form-label small">Rol:</label>' +
      '<select id="swal-input-rol" class="form-select mb-3">' + opcionesRol + '</select>' +
      '<div id="div-medico" ' + mostrarMedico + '>' +
      '<label class="form-label small">Médico vinculado:</label>' +
      '<select id="swal-input-medico" class="form-select mb-3">' + opcionesMedicos + '</select>' +
      '<small class="text-muted">Solo para usuarios con rol Médico</small>' +
      '</div>' +
      '<div id="div-medicos-asignados" ' + mostrarMedicosAsignados + '>' +
      '<label class="form-label small">Médicos asignados:</label>' +
      '<div class="border rounded p-2 mb-2" style="max-height: 200px; overflow-y: auto;">' +
      checkboxesMedicos +
      '</div>' +
      '<small class="text-muted">Selecciona los médicos que atenderá esta recepcionista</small>' +
      '</div>' +
      '<div class="form-check mt-3">' +
      '<input class="form-check-input" type="checkbox" id="swal-input-activo" ' + checkedActivo + '>' +
      '<label class="form-check-label" for="swal-input-activo">Activo</label>' +
      '</div>' +
      '</div>',
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    cancelButtonText: 'Cancelar',
    width: '700px',
    didOpen: function() {
      // aqui controlo que se muestren los campos segun el rol
      document.getElementById('swal-input-rol').addEventListener('change', function() {
        var divMedico = document.getElementById('div-medico');
        var divMedicosAsignados = document.getElementById('div-medicos-asignados');
        
        if (this.value === 'Medico') {
          divMedico.style.display = 'block';
          divMedicosAsignados.style.display = 'none';
        } else if (this.value === 'Recepcionista') {
          divMedico.style.display = 'none';
          divMedicosAsignados.style.display = 'block';
          document.getElementById('swal-input-medico').value = '';
        } else {
          divMedico.style.display = 'none';
          divMedicosAsignados.style.display = 'none';
          document.getElementById('swal-input-medico').value = '';
        }
      });
    },
    preConfirm: function() {
      var nuevoUsuario = document.getElementById('swal-input-usuario').value;
      var nuevoNombre = document.getElementById('swal-input-nombre').value;
      var nuevoTelefono = document.getElementById('swal-input-telefono').value;
      var nuevoCorreo = document.getElementById('swal-input-correo').value;
      var nuevoPassword = document.getElementById('swal-input-password').value;
      var nuevoRol = document.getElementById('swal-input-rol').value;
      var nuevoMedico = document.getElementById('swal-input-medico').value;
      var nuevoActivo = document.getElementById('swal-input-activo').checked;
      
      // aqui obtengo los medicos asignados si es recepcionista
      var medicosAsignadosNuevos = [];
      if (nuevoRol === 'Recepcionista') {
        var checkboxes = document.querySelectorAll('.checkbox-medico-asignado:checked');
        for (var i = 0; i < checkboxes.length; i++) {
          medicosAsignadosNuevos.push(checkboxes[i].value);
        }
      }
      
      // aqui valido que los campos no esten vacios
      if (!nuevoUsuario || !nuevoRol || !nuevoNombre) {
        Swal.showValidationMessage('Por favor complete los campos obligatorios');
        return false;
      }
      
      // si el rol es Medico y no hay medicos, aviso
      if (nuevoRol === 'Medico' && todosLosMedicos.length === 0) {
        Swal.showValidationMessage('No hay médicos registrados. Primero registra médicos en el módulo de Médicos.');
        return false;
      }
      
      return { 
        usuario: nuevoUsuario,
        nombre: nuevoNombre,
        telefono: nuevoTelefono,
        correo: nuevoCorreo,
        password: nuevoPassword,
        rol: nuevoRol,
        medico: nuevoMedico,
        medicosAsignados: medicosAsignadosNuevos,
        activo: nuevoActivo
      };
    }
  }).then(function(result) {
    if (result.isConfirmed) {
      // aqui creo un formulario para enviar los datos
      var formulario = document.createElement('form');
      formulario.method = 'POST';
      formulario.action = 'php/Usuarios/editar.php';
      
      var inputId = document.createElement('input');
      inputId.type = 'hidden';
      inputId.name = 'id';
      inputId.value = id;
      
      var inputUsuario = document.createElement('input');
      inputUsuario.type = 'hidden';
      inputUsuario.name = 'usuario';
      inputUsuario.value = result.value.usuario;
      
      var inputNombre = document.createElement('input');
      inputNombre.type = 'hidden';
      inputNombre.name = 'nombre_completo';
      inputNombre.value = result.value.nombre;
      
      var inputTelefono = document.createElement('input');
      inputTelefono.type = 'hidden';
      inputTelefono.name = 'telefono';
      inputTelefono.value = result.value.telefono;
      
      var inputCorreo = document.createElement('input');
      inputCorreo.type = 'hidden';
      inputCorreo.name = 'correo';
      inputCorreo.value = result.value.correo;
      
      // solo envio password si ingresaron uno nuevo
      if (result.value.password) {
        var inputPassword = document.createElement('input');
        inputPassword.type = 'hidden';
        inputPassword.name = 'password';
        inputPassword.value = result.value.password;
        formulario.appendChild(inputPassword);
      }
      
      var inputRol = document.createElement('input');
      inputRol.type = 'hidden';
      inputRol.name = 'rol';
      inputRol.value = result.value.rol;
      
      var inputMedico = document.createElement('input');
      inputMedico.type = 'hidden';
      inputMedico.name = 'medico_id';
      inputMedico.value = result.value.medico;
      
      // aqui agrego los medicos asignados si es recepcionista
      if (result.value.rol === 'Recepcionista' && result.value.medicosAsignados.length > 0) {
        for (var i = 0; i < result.value.medicosAsignados.length; i++) {
          var inputMedicoAsignado = document.createElement('input');
          inputMedicoAsignado.type = 'hidden';
          inputMedicoAsignado.name = 'medicos_asignados[]';
          inputMedicoAsignado.value = result.value.medicosAsignados[i];
          formulario.appendChild(inputMedicoAsignado);
        }
      }
      
      if (result.value.activo) {
        var inputActivo = document.createElement('input');
        inputActivo.type = 'hidden';
        inputActivo.name = 'activo';
        inputActivo.value = '1';
        formulario.appendChild(inputActivo);
      }
      
      formulario.appendChild(inputId);
      formulario.appendChild(inputUsuario);
      formulario.appendChild(inputNombre);
      formulario.appendChild(inputTelefono);
      formulario.appendChild(inputCorreo);
      formulario.appendChild(inputRol);
      formulario.appendChild(inputMedico);
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
      var usuario = this.getAttribute('data-usuario');
      eliminarUsuario(id, usuario);
    });
  }
}

// esto muestra el popup para confirmar que quiero eliminar
function eliminarUsuario(id, usuario) {
  Swal.fire({
    title: '¿Eliminar usuario?',
    text: '¿Está seguro de eliminar "' + usuario + '"?',
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
      formulario.action = 'php/Usuarios/eliminar.php';
      
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
    var usuario = document.getElementById('input-usuario').value;
    var password = document.getElementById('input-password').value;
    var rol = document.getElementById('select-rol').value;
    var medico = document.getElementById('select-medico').value;
    
    // aqui valido que todos los campos obligatorios esten llenos
    if (!usuario || !password || !rol) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor complete los campos obligatorios (Usuario, Contraseña y Rol)',
        confirmButtonText: 'Aceptar'
      });
      return false;
    }
    
    // aqui valido que el password tenga minimo 4 caracteres
    if (password.length < 4) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'La contraseña debe tener al menos 4 caracteres',
        confirmButtonText: 'Aceptar'
      });
      return false;
    }
    
    // si el rol es Medico y no selecciono medico, aviso
    if (rol === 'Medico' && !medico && todosLosMedicos.length > 0) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: 'Has seleccionado rol Médico pero no asignaste un médico. ¿Deseas continuar?',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
      }).then(function(result) {
        if (result.isConfirmed) {
          formulario.submit();
        }
      });
      return false;
    }
  });
}

// esto se ejecuta cuando carga la pagina
document.addEventListener('DOMContentLoaded', function() {
  cargarMedicos();
  cargarDatos();
  validarFormulario();
  controlarCampoMedico();
  
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
    var totalPaginas = Math.ceil(usuariosFiltrados.length / registrosPorPagina);
    if (paginaActual < totalPaginas) {
      paginaActual++;
      mostrarTabla();
    }
  });
});
