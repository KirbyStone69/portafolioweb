// Variables globales para manejar la paginacion y filtros
var todosLosExpedientes = [];
var expedientesFiltrados = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";

// Funcion para cargar datos desde el servidor
function cargarDatos() {
    fetch('php/Expedientes/listar.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (datos) {
            todosLosExpedientes = datos;
            expedientesFiltrados = datos;
            paginaActual = 1;
            actualizarEstadisticas();
            mostrarTabla();
            cargarPacientes();
            cargarMedicos();
        })
        .catch(function (error) {
            console.log('Error:', error);
            document.getElementById('tabla-expedientes').innerHTML =
                '<tr><td colspan="6">Error al cargar datos</td></tr>';
        });
}

// Funcion para actualizar las estadisticas de la pagina
function actualizarEstadisticas() {
    fetch('php/Expedientes/estadisticas.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (data) {
            document.getElementById('total-expedientes').textContent = data.total_expedientes;
            document.getElementById('expedientes-mes').textContent = data.expedientes_mes;
            document.getElementById('expedientes-hoy').textContent = data.expedientes_hoy;
        })
        .catch(function (error) {
            console.log('Error al cargar estadísticas:', error);
        });
}

// Funcion para cargar pacientes en el select
function cargarPacientes() {
    fetch('php/Pacientes/listar.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (pacientes) {
            var select = document.getElementById('select-paciente');
            var html = '<option value="">Selecciona un paciente</option>';

            for (var i = 0; i < pacientes.length; i++) {
                if (pacientes[i].Estatus == 1) {
                    html += '<option value="' + pacientes[i].IdPaciente + '">';
                    html += pacientes[i].NombreCompleto + ' - ' + (pacientes[i].CURP || 'Sin CURP');
                    html += '</option>';
                }
            }

            select.innerHTML = html;
        })
        .catch(function (error) {
            console.log('Error al cargar pacientes:', error);
        });
}

// Funcion para cargar medicos en el select
function cargarMedicos() {
    fetch('php/Medicos/listar.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (medicos) {
            var select = document.getElementById('select-medico');
            var html = '<option value="">Selecciona un médico</option>';

            for (var i = 0; i < medicos.length; i++) {
                if (medicos[i].Estatus == 1) {
                    html += '<option value="' + medicos[i].IdMedico + '">';
                    html += medicos[i].NombreCompleto + ' - ' + medicos[i].NombreEspecialidad;
                    html += '</option>';
                }
            }

            select.innerHTML = html;
        })
        .catch(function (error) {
            console.log('Error al cargar médicos:', error);
        });
}

// Funcion para filtrar los datos de la tabla
function filtrarDatos() {
    var textoBusqueda = busqueda.toLowerCase();

    expedientesFiltrados = todosLosExpedientes.filter(function (expediente) {
        var paciente = expediente.NombrePaciente.toLowerCase();
        var medico = expediente.NombreMedico.toLowerCase();
        var diagnostico = (expediente.Diagnostico || '').toLowerCase();
        return paciente.includes(textoBusqueda) || medico.includes(textoBusqueda) || diagnostico.includes(textoBusqueda);
    });

    paginaActual = 1;
    mostrarTabla();
}

// Funcion para mostrar la tabla con paginacion
function mostrarTabla() {
    var tbody = document.getElementById('tabla-expedientes');
    var total = expedientesFiltrados.length;
    var totalPaginas = Math.ceil(total / registrosPorPagina);

    if (totalPaginas === 0) {
        totalPaginas = 1;
    }

    if (paginaActual > totalPaginas) {
        paginaActual = totalPaginas;
    }

    var inicio = (paginaActual - 1) * registrosPorPagina;
    var fin = inicio + registrosPorPagina;
    var registrosMostrar = expedientesFiltrados.slice(inicio, fin);

    var html = '';

    if (registrosMostrar.length === 0) {
        html = '<tr><td colspan="6">No hay expedientes registrados</td></tr>';
    } else {
        for (var i = 0; i < registrosMostrar.length; i++) {
            var expediente = registrosMostrar[i];
            var fechaConsulta = new Date(expediente.FechaConsulta).toLocaleString();
            var proximaCita = expediente.ProximaCita ? new Date(expediente.ProximaCita).toLocaleString() : 'Sin cita';

            html += '<tr>';
            html += '<td>';
            html += '<div class="fw-semibold">' + expediente.NombrePaciente + '</div>';
            html += '<div class="small text-muted">CURP: ' + (expediente.CURP || 'N/A') + '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="fw-semibold">' + expediente.NombreMedico + '</div>';
            html += '<div class="small text-muted">' + (expediente.NombreEspecialidad || 'Sin especialidad') + '</div>';
            html += '</td>';
            html += '<td>' + fechaConsulta + '</td>';
            html += '<td>' + (expediente.Diagnostico || 'N/A').substring(0, 50) + '...</td>';
            html += '<td>' + proximaCita + '</td>';
            html += '<td class="text-end">';
            html += '<div class="btn-group btn-group-sm">';
            html += '<button class="btn btn-primary btn-ver" data-id="' + expediente.IdExpediente + '">Ver</button>';
            html += '<button class="btn btn-primary btn-eliminar" data-id="' + expediente.IdExpediente + '" data-paciente="' + expediente.NombrePaciente + '">Eliminar</button>';
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

    agregarEventosVer();
    agregarEventosEliminar();
}

// Funcion para agregar eventos a botones ver
function agregarEventosVer() {
    var botonesVer = document.querySelectorAll('.btn-ver');

    for (var i = 0; i < botonesVer.length; i++) {
        botonesVer[i].addEventListener('click', function () {
            var idExpediente = this.getAttribute('data-id');
            verExpediente(idExpediente);
        });
    }
}

// Funcion para ver detalles del expediente
function verExpediente(id) {
    // aqui busco el expediente por id
    var expediente = todosLosExpedientes.find(function (e) { return e.IdExpediente == id; });

    if (!expediente) {
        Swal.fire('Error', 'Expediente no encontrado', 'error');
        return;
    }

    // aqui muestro el modal con toda la informacion
    var html = '<div class="text-start">';
    html += '<p><strong>Paciente:</strong> ' + expediente.NombrePaciente + '</p>';
    html += '<p><strong>Médico:</strong> ' + expediente.NombreMedico + '</p>';
    html += '<p><strong>Fecha de consulta:</strong> ' + new Date(expediente.FechaConsulta).toLocaleString() + '</p>';
    html += '<hr>';
    html += '<p><strong>Síntomas:</strong><br>' + (expediente.Sintomas || 'N/A') + '</p>';
    html += '<p><strong>Diagnóstico:</strong><br>' + (expediente.Diagnostico || 'N/A') + '</p>';
    html += '<p><strong>Tratamiento:</strong><br>' + (expediente.Tratamiento || 'N/A') + '</p>';
    html += '<p><strong>Receta médica:</strong><br>' + (expediente.RecetaMedica || 'N/A') + '</p>';
    html += '<p><strong>Notas adicionales:</strong><br>' + (expediente.NotasAdicionales || 'N/A') + '</p>';
    html += '<p><strong>Próxima cita:</strong> ' + (expediente.ProximaCita ? new Date(expediente.ProximaCita).toLocaleString() : 'Sin cita programada') + '</p>';
    html += '</div>';

    Swal.fire({
        title: 'Expediente Clínico',
        html: html,
        width: '600px',
        confirmButtonText: 'Cerrar'
    });
}

// Funcion para agregar eventos a botones eliminar
function agregarEventosEliminar() {
    var botonesEliminar = document.querySelectorAll('.btn-eliminar');

    for (var i = 0; i < botonesEliminar.length; i++) {
        botonesEliminar[i].addEventListener('click', function () {
            var idExpediente = this.getAttribute('data-id');
            var nombrePaciente = this.getAttribute('data-paciente');
            eliminarExpediente(idExpediente, nombrePaciente);
        });
    }
}

// Funcion para eliminar expediente
function eliminarExpediente(id, nombrePaciente) {
    Swal.fire({
        title: '¿Eliminar expediente?',
        text: '¿Está seguro de eliminar el expediente de ' + nombrePaciente + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            // aqui creo el formulario para enviar
            var formulario = document.createElement('form');
            formulario.method = 'POST';
            formulario.action = 'php/Expedientes/eliminar.php';

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
document.addEventListener('DOMContentLoaded', function () {
    cargarDatos();

    // Evento para cambiar registros por pagina
    document.getElementById('select-mostrar').addEventListener('change', function () {
        registrosPorPagina = parseInt(this.value);
        paginaActual = 1;
        mostrarTabla();
    });

    // Evento para el buscador
    var inputBuscar = document.getElementById('input-buscar');
    inputBuscar.addEventListener('input', function () {
        busqueda = this.value;
        filtrarDatos();
    });

    // Evento boton anterior
    document.getElementById('btn-prev').addEventListener('click', function () {
        if (paginaActual > 1) {
            paginaActual--;
            mostrarTabla();
        }
    });

    // Evento boton siguiente
    document.getElementById('btn-next').addEventListener('click', function () {
        var totalPaginas = Math.ceil(expedientesFiltrados.length / registrosPorPagina);
        if (paginaActual < totalPaginas) {
            paginaActual++;
            mostrarTabla();
        }
    });
});
