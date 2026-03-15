// Variables globales
var todosLosReportes = [];
var reportesFiltrados = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";

// Funcion para cargar estadisticas
function cargarEstadisticas() {
    fetch('php/reportes/estadisticas.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (data) {
            document.getElementById('stat-total').textContent = data.total_reportes;
            document.getElementById('stat-medicos').textContent = data.reportes_medicos;
            document.getElementById('stat-financieros').textContent = data.reportes_financieros;
            document.getElementById('stat-citas').textContent = data.reportes_citas;
        })
        .catch(function (error) {
            console.log('Error al cargar estadísticas:', error);
        });
}

// Funcion para cargar medicos en select
function cargarMedicos() {
    fetch('php/reportes/obtener_medicos.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (medicos) {
            var select = document.getElementById('select-medico');
            var html = '<option value="">Selecciona un médico</option>';

            for (var i = 0; i < medicos.length; i++) {
                html += '<option value="' + medicos[i].IdMedico + '">';
                html += medicos[i].NombreCompleto + ' — Cédula: ' + medicos[i].CedulaProfesional;
                html += '</option>';
            }

            select.innerHTML = html;
        })
        .catch(function (error) {
            console.log('Error al cargar médicos:', error);
        });
}

// Funcion para cargar pacientes en select
function cargarPacientes() {
    fetch('php/reportes/obtener_pacientes.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (pacientes) {
            var select = document.getElementById('select-paciente');
            var html = '<option value="">Selecciona un paciente</option>';

            for (var i = 0; i < pacientes.length; i++) {
                html += '<option value="' + pacientes[i].IdPaciente + '">';
                html += pacientes[i].NombreCompleto + ' — CURP: ' + pacientes[i].CURP;
                html += '</option>';
            }

            select.innerHTML = html;
        })
        .catch(function (error) {
            console.log('Error al cargar pacientes:', error);
        });
}

// Funcion para cargar datos de reportes
function cargarDatos() {
    fetch('php/reportes/listar.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (datos) {
            todosLosReportes = datos;
            reportesFiltrados = datos;
            paginaActual = 1;
            mostrarTabla();
        })
        .catch(function (error) {
            console.log('Error:', error);
            document.getElementById('tabla-reportes').innerHTML =
                '<tr><td colspan="6">Error al cargar datos</td></tr>';
        });
}

// Funcion para filtrar datos
function filtrarDatos() {
    var textoBusqueda = busqueda.toLowerCase();

    reportesFiltrados = todosLosReportes.filter(function (reporte) {
        var tipo = reporte.TipoReporte ? reporte.TipoReporte.toLowerCase() : '';
        var medico = reporte.NombreMedico ? reporte.NombreMedico.toLowerCase() : '';
        var paciente = reporte.NombrePaciente ? reporte.NombrePaciente.toLowerCase() : '';
        var generadoPor = reporte.GeneradoPor ? reporte.GeneradoPor.toLowerCase() : '';

        return tipo.includes(textoBusqueda) ||
            medico.includes(textoBusqueda) ||
            paciente.includes(textoBusqueda) ||
            generadoPor.includes(textoBusqueda);
    });

    paginaActual = 1;
    mostrarTabla();
}

// Funcion para mostrar la tabla
function mostrarTabla() {
    var tbody = document.getElementById('tabla-reportes');
    var total = reportesFiltrados.length;
    var totalPaginas = Math.ceil(total / registrosPorPagina);

    if (totalPaginas === 0) {
        totalPaginas = 1;
    }

    if (paginaActual > totalPaginas) {
        paginaActual = totalPaginas;
    }

    var inicio = (paginaActual - 1) * registrosPorPagina;
    var fin = inicio + registrosPorPagina;
    var registrosMostrar = reportesFiltrados.slice(inicio, fin);

    var html = '';

    if (registrosMostrar.length === 0) {
        html = '<tr><td colspan="6">No hay reportes generados</td></tr>';
    } else {
        for (var i = 0; i < registrosMostrar.length; i++) {
            var reporte = registrosMostrar[i];
            html += '<tr>';
            html += '<td><span class="badge text-bg-light">' + reporte.TipoReporte + '</span></td>';
            html += '<td>';
            if (reporte.NombreMedico) {
                html += '<div class="fw-semibold">' + reporte.NombreMedico + '</div>';
                html += '<div class="small text-muted">Cédula: ' + reporte.CedulaProfesional + '</div>';
            } else {
                html += '<span class="text-muted">N/A</span>';
            }
            html += '</td>';
            html += '<td>';
            if (reporte.NombrePaciente) {
                html += '<div class="fw-semibold">' + reporte.NombrePaciente + '</div>';
                html += '<div class="small text-muted">CURP: ' + reporte.CURP + '</div>';
            } else {
                html += '<span class="text-muted">N/A</span>';
            }
            html += '</td>';
            html += '<td>' + reporte.FechaGeneracion + '</td>';
            html += '<td class="text-center"><button class="btn btn-primary btn-sm">PDF</button></td>';
            html += '<td class="text-end"><button class="btn btn-primary btn-sm">Ver</button></td>';
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
}

// Funcion para generar reporte
function generarReporte() {
    var tipo = document.getElementById('select-tipo').value;
    var medico = document.getElementById('select-medico').value;
    var paciente = document.getElementById('select-paciente').value;

    // aqui valido que tipo esté seleccionado
    if (!tipo) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar un tipo de reporte'
        });
        return;
    }

    // aqui creo el formulario para enviar
    var formulario = document.createElement('form');
    formulario.method = 'POST';
    formulario.action = 'php/reportes/insertar.php';

    var inputTipo = document.createElement('input');
    inputTipo.type = 'hidden';
    inputTipo.name = 'tipo_reporte';
    inputTipo.value = tipo;
    formulario.appendChild(inputTipo);

    var inputDescripcion = document.createElement('input');
    inputDescripcion.type = 'hidden';
    inputDescripcion.name = 'descripcion';
    inputDescripcion.value = 'Reporte de ' + tipo;
    formulario.appendChild(inputDescripcion);

    var inputGeneradoPor = document.createElement('input');
    inputGeneradoPor.type = 'hidden';
    inputGeneradoPor.name = 'generado_por';
    inputGeneradoPor.value = 'Sistema';
    formulario.appendChild(inputGeneradoPor);

    // aqui agrego medico y paciente si están seleccionados
    if (medico) {
        var inputMedico = document.createElement('input');
        inputMedico.type = 'hidden';
        inputMedico.name = 'id_medico';
        inputMedico.value = medico;
        formulario.appendChild(inputMedico);
    }

    if (paciente) {
        var inputPaciente = document.createElement('input');
        inputPaciente.type = 'hidden';
        inputPaciente.name = 'id_paciente';
        inputPaciente.value = paciente;
        formulario.appendChild(inputPaciente);
    }

    document.body.appendChild(formulario);
    formulario.submit();
}

// Cuando carga la pagina
document.addEventListener('DOMContentLoaded', function () {
    cargarEstadisticas();
    cargarMedicos();
    cargarPacientes();
    cargarDatos();

    // Evento para el boton generar
    document.getElementById('btn-generar').addEventListener('click', generarReporte);

    // Evento para cambiar registros por pagina
    document.getElementById('select-mostrar').addEventListener('change', function () {
        registrosPorPagina = parseInt(this.value);
        paginaActual = 1;
        mostrarTabla();
    });

    // Evento para el buscador
    var inputBuscar = document.querySelector('input[placeholder="Buscar"]');
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
        var totalPaginas = Math.ceil(reportesFiltrados.length / registrosPorPagina);
        if (paginaActual < totalPaginas) {
            paginaActual++;
            mostrarTabla();
        }
    });
});
