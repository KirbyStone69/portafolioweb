// esto son las variables globales que voy a usar
var todosLosPagos = [];
var pagosFiltrados = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";
var todasLasCitas = [];
var todasLasTarifas = [];
var serviciosAgregados = [];

// esto carga los datos de los pagos desde el servidor
function cargarDatos() {
  fetch('php/Pagos/listar.php')
    .then(function (respuesta) {
      return respuesta.json();
    })
    .then(function (datos) {
      todosLosPagos = datos;
      pagosFiltrados = datos;
      paginaActual = 1;
      cargarEstadisticas();
      mostrarTabla();
    })
    .catch(function (error) {
      console.log('Error:', error);
      document.getElementById('tabla-pagos').innerHTML =
        '<tr><td colspan="7">Error al cargar datos</td></tr>';
    });
}

// esto carga las estadisticas de ingresos
function cargarEstadisticas() {
  fetch('php/Pagos/estadisticas.php')
    .then(function (respuesta) {
      return respuesta.json();
    })
    .then(function (datos) {
      document.getElementById('total-general').textContent = '$' + parseFloat(datos.total_general).toFixed(2);
      document.getElementById('ingresos-mes').textContent = '$' + parseFloat(datos.mes).toFixed(2);
      document.getElementById('ingresos-semana').textContent = '$' + parseFloat(datos.semana).toFixed(2);
      document.getElementById('ingresos-hoy').textContent = '$' + parseFloat(datos.hoy).toFixed(2);
    })
    .catch(function (error) {
      console.log('Error al cargar estadísticas:', error);
    });
}

// esto carga las tarifas disponibles
function cargarTarifas() {
  fetch('php/Pagos/obtener_tarifas.php')
    .then(function (respuesta) {
      return respuesta.json();
    })
    .then(function (datos) {
      todasLasTarifas = datos;
      llenarSelectServicios();
    })
    .catch(function (error) {
      console.log('Error al cargar tarifas:', error);
    });
}

// esto llena el select de servicios
function llenarSelectServicios() {
  var select = document.getElementById('select-servicio-modal');
  if (!select) return;

  var html = '<option value="">Seleccione un servicio</option>';
  for (var i = 0; i < todasLasTarifas.length; i++) {
    html += '<option value="' + i + '">' +
      todasLasTarifas[i].DescripcionServicio + ' - $' + parseFloat(todasLasTarifas[i].CostoBase).toFixed(2) +
      '</option>';
  }
  select.innerHTML = html;
}

// esto carga las citas disponibles para registrar pago
function cargarCitas() {
  fetch('php/Pagos/listar_citas.php')
    .then(function (respuesta) {
      return respuesta.json();
    })
    .then(function (datos) {
      todasLasCitas = datos;
      llenarSelectCitas();
    })
    .catch(function (error) {
      console.log('Error al cargar citas:', error);
    });
}

// esto llena el select de citas con las opciones
function llenarSelectCitas() {
  var select = document.getElementById('select-cita');
  if (!select) return;

  var html = '<option value="">Seleccione una cita</option>';
  for (var i = 0; i < todasLasCitas.length; i++) {
    var fecha = new Date(todasLasCitas[i].FechaCita);
    var fechaFormato = fecha.toLocaleDateString('es-MX');
    html += '<option value="' + todasLasCitas[i].IdCita + '" data-paciente="' + todasLasCitas[i].IdPaciente + '">' +
      todasLasCitas[i].NombrePaciente + ' - ' + todasLasCitas[i].NombreMedico + ' (' + fechaFormato + ')' + '</option>';
  }
  select.innerHTML = html;
}

// esto muestra el modal para agregar servicio
function mostrarModalServicio() {
  if (todasLasTarifas.length === 0) {
    cargarTarifas();
  }

  document.getElementById('select-servicio-modal').value = '';
  document.getElementById('input-cantidad-modal').value = '1';
  document.getElementById('input-precio-modal').value = '';
  document.getElementById('input-subtotal-modal').value = '';

  var modal = new bootstrap.Modal(document.getElementById('modal-servicio'));
  modal.show();
}

// esto se ejecuta cuando selecciono un servicio en el modal
document.addEventListener('DOMContentLoaded', function () {
  var selectServicio = document.getElementById('select-servicio-modal');
  var inputCantidad = document.getElementById('input-cantidad-modal');

  if (selectServicio) {
    selectServicio.addEventListener('change', function () {
      var indice = this.value;
      if (indice !== '') {
        var tarifa = todasLasTarifas[indice];
        document.getElementById('input-precio-modal').value = parseFloat(tarifa.CostoBase).toFixed(2);
        calcularSubtotalModal();
      } else {
        document.getElementById('input-precio-modal').value = '';
        document.getElementById('input-subtotal-modal').value = '';
      }
    });
  }

  if (inputCantidad) {
    inputCantidad.addEventListener('input', calcularSubtotalModal);
  }
});

// esto calcula el subtotal en el modal
function calcularSubtotalModal() {
  var cantidad = parseFloat(document.getElementById('input-cantidad-modal').value) || 0;
  var precio = parseFloat(document.getElementById('input-precio-modal').value) || 0;
  var subtotal = cantidad * precio;
  document.getElementById('input-subtotal-modal').value = subtotal.toFixed(2);
}

// esto agrega el servicio a la tabla
function agregarServicioATabla() {
  var selectServicio = document.getElementById('select-servicio-modal');
  var indice = selectServicio.value;

  if (indice === '') {
    Swal.fire('Error', 'Debe seleccionar un servicio', 'error');
    return;
  }

  var tarifa = todasLasTarifas[indice];
  var cantidad = parseInt(document.getElementById('input-cantidad-modal').value);
  var precio = parseFloat(document.getElementById('input-precio-modal').value);
  var subtotal = parseFloat(document.getElementById('input-subtotal-modal').value);

  // aqui agrego el servicio al array
  serviciosAgregados.push({
    id_tarifa: tarifa.IdTarifa,
    descripcion: tarifa.DescripcionServicio,
    cantidad: cantidad,
    precio_unitario: precio,
    subtotal: subtotal
  });

  // aqui actualizo la tabla
  actualizarTablaServicios();

  // aqui cierro el modal
  var modal = bootstrap.Modal.getInstance(document.getElementById('modal-servicio'));
  modal.hide();
}

// esto actualiza la tabla de servicios
function actualizarTablaServicios() {
  var tbody = document.getElementById('tabla-servicios');
  if (!tbody) return;

  if (serviciosAgregados.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay servicios agregados</td></tr>';
    document.getElementById('input-monto').value = '0.00';
    document.getElementById('input-servicios').value = '';
    return;
  }

  var html = '';
  var total = 0;

  for (var i = 0; i < serviciosAgregados.length; i++) {
    var servicio = serviciosAgregados[i];
    total += servicio.subtotal;

    html += '<tr>';
    html += '<td>' + servicio.descripcion + '</td>';
    html += '<td>' + servicio.cantidad + '</td>';
    html += '<td>$' + servicio.precio_unitario.toFixed(2) + '</td>';
    html += '<td>$' + servicio.subtotal.toFixed(2) + '</td>';
    html += '<td><button type="button" class="btn btn-sm btn-danger" onclick="quitarServicio(' + i + ')">×</button></td>';
    html += '</tr>';
  }

  tbody.innerHTML = html;

  // aqui actualizo el total
  document.getElementById('input-monto').value = total.toFixed(2);

  // aqui guardo el JSON de servicios
  document.getElementById('input-servicios').value = JSON.stringify(serviciosAgregados);
}

// esto quita un servicio de la tabla
function quitarServicio(indice) {
  serviciosAgregados.splice(indice, 1);
  actualizarTablaServicios();
}

// esto filtra los pagos cuando busco algo
function filtrarDatos() {
  var textoBusqueda = busqueda.toLowerCase();
  var filtroMetodo = document.getElementById('filtro-metodo') ? document.getElementById('filtro-metodo').value : '';
  var filtroEstado = document.getElementById('filtro-estado') ? document.getElementById('filtro-estado').value : '';

  if (textoBusqueda === "" && filtroMetodo === "" && filtroEstado === "") {
    pagosFiltrados = todosLosPagos;
  } else {
    pagosFiltrados = [];
    for (var i = 0; i < todosLosPagos.length; i++) {
      var pago = todosLosPagos[i];

      // Filtro de búsqueda de texto
      var coincideBusqueda = textoBusqueda === "" || (
        pago.NombrePaciente.toLowerCase().indexOf(textoBusqueda) !== -1 ||
        pago.NombreMedico.toLowerCase().indexOf(textoBusqueda) !== -1 ||
        pago.CURPPaciente.toLowerCase().indexOf(textoBusqueda) !== -1 ||
        pago.MetodoPago.toLowerCase().indexOf(textoBusqueda) !== -1 ||
        pago.EstatusPago.toLowerCase().indexOf(textoBusqueda) !== -1
      );

      // Filtro de método
      var coincideMetodo = filtroMetodo === "" || pago.MetodoPago === filtroMetodo;

      // Filtro de estado
      var coincideEstado = filtroEstado === "" || pago.EstatusPago === filtroEstado;

      if (coincideBusqueda && coincideMetodo && coincideEstado) {
        pagosFiltrados.push(pago);
      }
    }
  }

  paginaActual = 1;
  mostrarTabla();
}

// esto muestra la tabla con los pagos
function mostrarTabla() {
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var fin = inicio + registrosPorPagina;
  var pagosAMostrar = pagosFiltrados.slice(inicio, fin);

  var tbody = document.getElementById('tabla-pagos');
  if (!tbody) return;

  if (pagosAMostrar.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7">No hay pagos registrados</td></tr>';
    actualizarPaginacion();
    return;
  }

  var html = '';
  for (var i = 0; i < pagosAMostrar.length; i++) {
    var pago = pagosAMostrar[i];
    var fecha = new Date(pago.FechaPago);
    var fechaFormato = fecha.toLocaleDateString('es-MX') + ' - ' + fecha.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });

    var badgeClass = 'bg-secondary';
    if (pago.EstatusPago === 'Pagado') badgeClass = 'bg-success';
    else if (pago.EstatusPago === 'Pendiente') badgeClass = 'bg-warning';
    else if (pago.EstatusPago === 'Cancelado') badgeClass = 'bg-danger';

    html += '<tr>';
    html += '<td><div class="fw-semibold">' + pago.NombrePaciente + '</div>';
    html += '<div class="small text-muted">CURP: ' + pago.CURPPaciente + '</div></td>';
    html += '<td><div class="fw-semibold">' + pago.NombreMedico + '</div>';
    html += '<div class="small text-muted">Cedula: ' + pago.CedulaMedico + '</div></td>';
    html += '<td class="text-end">$' + parseFloat(pago.Monto).toFixed(2) + '</td>';
    html += '<td>' + pago.MetodoPago + '</td>';
    html += '<td>' + fechaFormato + '</td>';
    html += '<td><span class="badge ' + badgeClass + '">' + pago.EstatusPago.toUpperCase() + '</span></td>';
    html += '<td class="text-end">';
    html += '<div class="btn-group btn-group-sm">';
    html += '<button class="btn btn-primary" onclick="verPago(' + i + ')">Ver</button>';
    html += '<button class="btn btn-primary" onclick="editarPago(' + i + ')">Editar</button>';
    html += '</div>';
    html += '</td>';
    html += '</tr>';
  }

  tbody.innerHTML = html;
  actualizarPaginacion();
}

// esto actualiza los controles de paginacion
function actualizarPaginacion() {
  var totalPaginas = Math.ceil(pagosFiltrados.length / registrosPorPagina);
  var inicio = (paginaActual - 1) * registrosPorPagina + 1;
  var fin = Math.min(inicio + registrosPorPagina - 1, pagosFiltrados.length);

  document.getElementById('info-paginacion').textContent =
    'Mostrando ' + inicio + ' a ' + fin + ' de ' + pagosFiltrados.length + ' registros';

  var btnAnterior = document.getElementById('btn-anterior');
  var btnSiguiente = document.getElementById('btn-siguiente');
  var numeroPagina = document.getElementById('numero-pagina');

  if (btnAnterior) btnAnterior.disabled = (paginaActual === 1);
  if (btnSiguiente) btnSiguiente.disabled = (paginaActual === totalPaginas || totalPaginas === 0);
  if (numeroPagina) numeroPagina.textContent = paginaActual;
}

// esto abre el modal para nuevo pago
function abrirModalNuevo() {
  document.getElementById('form-pago').reset();
  document.getElementById('id-pago').value = '';
  document.getElementById('titulo-modal').textContent = 'Registrar Nuevo Pago';
  document.getElementById('btn-guardar').textContent = 'Registrar';
  document.getElementById('grupo-cita').style.display = 'block';

  // aqui limpio los servicios
  serviciosAgregados = [];
  actualizarTablaServicios();

  cargarCitas();

  var modal = new bootstrap.Modal(document.getElementById('modal-pago'));
  modal.show();
}

// esto muestra el detalle del pago
function verPago(indice) {
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var pago = pagosFiltrados[inicio + indice];

  var fecha = new Date(pago.FechaPago);
  var fechaFormato = fecha.toLocaleDateString('es-MX') + ' ' + fecha.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });

  var fechaCita = new Date(pago.FechaCita);
  var fechaCitaFormato = fechaCita.toLocaleDateString('es-MX');

  // aqui cargo el desglose de servicios
  fetch('php/Pagos/listar_detalles.php?id_pago=' + pago.IdPago)
    .then(function (r) { return r.json(); })
    .then(function (detalles) {
      var htmlDetalles = '';

      if (detalles && detalles.length > 0) {
        htmlDetalles = '<hr><h6 class="mt-3">Desglose de Servicios:</h6>' +
          '<table class="table table-sm table-bordered">' +
          '<thead class="table-light"><tr>' +
          '<th>Servicio</th><th width="80">Cant.</th><th width="100">Precio Unit.</th><th width="100">Subtotal</th>' +
          '</tr></thead><tbody>';

        for (var i = 0; i < detalles.length; i++) {
          var d = detalles[i];
          htmlDetalles += '<tr>' +
            '<td>' + d.DescripcionServicio + '</td>' +
            '<td class="text-center">' + d.Cantidad + '</td>' +
            '<td class="text-end">$' + parseFloat(d.PrecioUnitario).toFixed(2) + '</td>' +
            '<td class="text-end">$' + parseFloat(d.Subtotal).toFixed(2) + '</td>' +
            '</tr>';
        }

        htmlDetalles += '</tbody></table>';
      }

      Swal.fire({
        title: 'Detalle del Pago',
        html: '<div class="text-start">' +
          '<p><strong>Paciente:</strong> ' + pago.NombrePaciente + '</p>' +
          '<p><strong>CURP:</strong> ' + pago.CURPPaciente + '</p>' +
          '<p><strong>Medico:</strong> ' + pago.NombreMedico + '</p>' +
          '<p><strong>Cedula:</strong> ' + pago.CedulaMedico + '</p>' +
          '<p><strong>Fecha de Cita:</strong> ' + fechaCitaFormato + '</p>' +
          '<p><strong>Motivo:</strong> ' + (pago.MotivoConsulta || 'N/A') + '</p>' +
          '<hr>' +
          '<p><strong>Metodo de Pago:</strong> ' + pago.MetodoPago + '</p>' +
          '<p><strong>Referencia:</strong> ' + (pago.Referencia || 'N/A') + '</p>' +
          '<p><strong>Fecha de Pago:</strong> ' + fechaFormato + '</p>' +
          '<p><strong>Estado:</strong> ' + pago.EstatusPago + '</p>' +
          '<p><strong>Recibido por:</strong> ' + (pago.RecibioPago || 'N/A') + '</p>' +
          htmlDetalles +
          '<hr>' +
          '<p class="h5"><strong>Total:</strong> $' + parseFloat(pago.Monto).toFixed(2) + '</p>' +
          '</div>',
        icon: 'info',
        confirmButtonText: 'Cerrar',
        width: '700px'
      });
    })
    .catch(function (error) {
      console.log('Error al cargar detalles:', error);
      // Si falla, mostrar sin desglose
      Swal.fire({
        title: 'Detalle del Pago',
        html: '<div class="text-start">' +
          '<p><strong>Paciente:</strong> ' + pago.NombrePaciente + '</p>' +
          '<p><strong>Monto:</strong> $' + parseFloat(pago.Monto).toFixed(2) + '</p>' +
          '<p class="text-muted">No se pudo cargar el desglose de servicios</p>' +
          '</div>',
        icon: 'info',
        confirmButtonText: 'Cerrar'
      });
    });
}

// esto abre el modal para editar pago
function editarPago(indice) {
  var inicio = (paginaActual - 1) * registrosPorPagina;
  var pago = pagosFiltrados[inicio + indice];

  document.getElementById('id-pago').value = pago.IdPago;
  document.getElementById('input-monto').value = pago.Monto;
  document.getElementById('select-metodo').value = pago.MetodoPago;
  document.getElementById('input-referencia').value = pago.Referencia || '';
  document.getElementById('select-estatus').value = pago.EstatusPago;

  document.getElementById('grupo-cita').style.display = 'none';

  document.getElementById('titulo-modal').textContent = 'Editar Pago';
  document.getElementById('btn-guardar').textContent = 'Actualizar';

  var modal = new bootstrap.Modal(document.getElementById('modal-pago'));
  modal.show();
}

// esto guarda el pago (crear o editar)
function guardarPago() {
  var idPago = document.getElementById('id-pago').value;
  var form = document.getElementById('form-pago');

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  if (idPago === '') {
    // aqui valido que haya al menos un servicio
    if (serviciosAgregados.length === 0) {
      Swal.fire('Error', 'Debe agregar al menos un servicio', 'error');
      return;
    }

    var selectCita = document.getElementById('select-cita');
    var citaSeleccionada = selectCita.value;
    var pacienteId = selectCita.options[selectCita.selectedIndex].getAttribute('data-paciente');

    if (!citaSeleccionada) {
      Swal.fire('Error', 'Debe seleccionar una cita', 'error');
      return;
    }

    // aqui agrego el id del paciente
    document.getElementById('input-id-paciente').value = pacienteId;

    form.action = 'php/Pagos/insertar.php';
  } else {
    form.action = 'php/Pagos/editar.php';
  }

  form.submit();
}

// esto cambia cuando selecciono una cita
function alSeleccionarCita() {
  var select = document.getElementById('select-cita');
  var citaId = select.value;

  if (citaId) {
    for (var i = 0; i < todasLasCitas.length; i++) {
      if (todasLasCitas[i].IdCita == citaId) {
        // aqui podria cargar servicios predeterminados si quisiera
        break;
      }
    }
  }
}

// esto se ejecuta cuando cambia el input de busqueda
document.addEventListener('DOMContentLoaded', function () {
  var inputBusqueda = document.getElementById('input-busqueda');
  if (inputBusqueda) {
    inputBusqueda.addEventListener('input', function () {
      busqueda = this.value;
      filtrarDatos();
    });
  }

  var selectRegistros = document.getElementById('select-registros');
  if (selectRegistros) {
    selectRegistros.addEventListener('change', function () {
      registrosPorPagina = parseInt(this.value);
      paginaActual = 1;
      mostrarTabla();
    });
  }

  var btnAnterior = document.getElementById('btn-anterior');
  if (btnAnterior) {
    btnAnterior.addEventListener('click', function () {
      if (paginaActual > 1) {
        paginaActual--;
        mostrarTabla();
      }
    });
  }

  var btnSiguiente = document.getElementById('btn-siguiente');
  if (btnSiguiente) {
    btnSiguiente.addEventListener('click', function () {
      var totalPaginas = Math.ceil(pagosFiltrados.length / registrosPorPagina);
      if (paginaActual < totalPaginas) {
        paginaActual++;
        mostrarTabla();
      }
    });
  }

  var selectCita = document.getElementById('select-cita');
  if (selectCita) {
    selectCita.addEventListener('change', alSeleccionarCita);
  }

  // aqui agrego listeners para los filtros
  var filtroMetodo = document.getElementById('filtro-metodo');
  if (filtroMetodo) {
    filtroMetodo.addEventListener('change', filtrarDatos);
  }

  var filtroEstado = document.getElementById('filtro-estado');
  if (filtroEstado) {
    filtroEstado.addEventListener('change', filtrarDatos);
  }

  cargarDatos();
});

// esto revisa si hay mensaje de exito o error en la URL
window.addEventListener('load', function () {
  var url = new URL(window.location.href);
  var ok = url.searchParams.get('ok');

  if (ok === '1') {
    Swal.fire('Exito', 'Operacion realizada correctamente', 'success');
    url.searchParams.delete('ok');
    window.history.replaceState({}, '', url);
  } else if (ok === '0') {
    var error = url.searchParams.get('error');
    Swal.fire('Error', error || 'Hubo un problema al realizar la operacion', 'error');
    url.searchParams.delete('ok');
    url.searchParams.delete('error');
    window.history.replaceState({}, '', url);
  }
});
