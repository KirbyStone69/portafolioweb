// Variables globales para el calendario y modal
let calendar;
let modalCita;
let editando = false;
let todasLasCitas = [];

// Al cargar la pagina
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar modal
    modalCita = new bootstrap.Modal(document.getElementById('modalCita'));
    
    // Inicializar calendario
    inicializarCalendario();
    
    // Cargar datos de pacientes y medicos
    cargarPacientes();
    cargarMedicos();
    cargarMedicosFiltro();
    
    // Cargar tabla de citas
    cargarTablaCitas();
    
    // Eventos de botones
    document.getElementById('btnNuevaCita').addEventListener('click', abrirModalNuevo);
    document.getElementById('btnGuardarCita').addEventListener('click', guardarCita);
    document.getElementById('btnEliminarCita').addEventListener('click', function() {
        const idCita = document.getElementById('idCita').value;
        if (idCita) {
            eliminarCita(idCita);
        }
    });
    
    // Eventos de filtros
    document.getElementById('filtroMedico').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroEstado').addEventListener('change', aplicarFiltros);
    document.getElementById('btnLimpiarFiltros').addEventListener('click', limpiarFiltros);
    document.getElementById('filtroEstadoTabla').addEventListener('change', cargarTablaCitas);
    
    // Evento al cambiar de tab
    document.getElementById('citas-tab').addEventListener('shown.bs.tab', function() {
        cargarTablaCitas();
    });
});

// Inicializar el calendario con FullCalendar
function inicializarCalendario() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap5',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        height: 600,
        contentHeight: 550,
        aspectRatio: 1.8,
        editable: false,
        selectable: true,
        nowIndicator: true,
        dayMaxEvents: 3,
        moreLinkClick: 'popover',
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '08:00',
            endTime: '18:00'
        },
        
        // Cargar eventos desde el servidor
        events: function(info, successCallback, failureCallback) {
            fetch('php/agenda/tabla.php')
                .then(response => response.json())
                .then(data => {
                    todasLasCitas = data;
                    successCallback(aplicarFiltrosACitas(data));
                })
                .catch(error => {
                    console.error('Error al cargar eventos:', error);
                    failureCallback(error);
                });
        },
        
        // Al hacer click en un evento
        eventClick: function(info) {
            abrirModalEditar(info.event);
        },
        
        // Al hacer click en una fecha
        dateClick: function(info) {
            abrirModalNuevo(info.dateStr);
        },
        
        // Formato de hora
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        
        // Contenido personalizado del evento con colores
        eventContent: function(arg) {
            const estado = arg.event.extendedProps.estado;
            const hora = arg.timeText;
            const paciente = arg.event.title;
            
            // Definir color de fondo segun el estado
            const bgColor = 
                estado === 'Completada' ? '#28a745' :
                estado === 'Programada' ? '#ffc107' :
                '#dc3545';
                
            const textColor = estado === 'Programada' ? '#000' : '#fff';
            
            const html = `
                <div style="background: ${bgColor}; color: ${textColor}; padding: 2px 4px; border-radius: 3px; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <div style="font-weight: 600; font-size: 0.7rem;">${hora}</div>
                    <div style="font-size: 0.65rem;">${paciente.substring(0, 25)}</div>
                </div>
            `;
            return { html };
        },
        
        // Color del evento
        eventColor: '#007bff',
        eventBackgroundColor: 'transparent',
        eventBorderColor: 'transparent'
    });
    
    calendar.render();
}

// Cargar lista de pacientes
function cargarPacientes() {
    fetch('php/agenda/obtener_pacientes.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('selectPaciente');
            select.innerHTML = '<option value="">Seleccione un paciente</option>';
            
            data.forEach(paciente => {
                const option = document.createElement('option');
                option.value = paciente.IdPaciente;
                option.textContent = paciente.NombreCompleto;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar pacientes:', error);
        });
}

// Cargar lista de medicos
function cargarMedicos() {
    fetch('php/agenda/obtener_medicos.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('selectMedico');
            select.innerHTML = '<option value="">Seleccione un médico</option>';
            
            data.forEach(medico => {
                const option = document.createElement('option');
                option.value = medico.IdMedico;
                option.textContent = medico.NombreCompleto + 
                    (medico.NombreEspecialidad ? ' - ' + medico.NombreEspecialidad : '');
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar médicos:', error);
        });
}

// Cargar lista de medicos para el filtro
function cargarMedicosFiltro() {
    fetch('php/agenda/obtener_medicos.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('filtroMedico');
            select.innerHTML = '<option value="">Todos los médicos</option>';
            
            data.forEach(medico => {
                const option = document.createElement('option');
                option.value = medico.IdMedico;
                option.textContent = medico.NombreCompleto + 
                    (medico.NombreEspecialidad ? ' - ' + medico.NombreEspecialidad : '');
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar médicos:', error);
        });
}

// Aplicar filtros a las citas
function aplicarFiltrosACitas(citas) {
    const filtroMedico = document.getElementById('filtroMedico').value;
    const filtroEstado = document.getElementById('filtroEstado').value;
    
    return citas.filter(cita => {
        const cumpleMedico = !filtroMedico || cita.extendedProps.idMedico == filtroMedico;
        const cumpleEstado = !filtroEstado || cita.extendedProps.estado === filtroEstado;
        
        return cumpleMedico && cumpleEstado;
    });
}

// Aplicar filtros y recargar calendario
function aplicarFiltros() {
    calendar.refetchEvents();
}

// Limpiar filtros
function limpiarFiltros() {
    document.getElementById('filtroMedico').value = '';
    document.getElementById('filtroEstado').value = '';
    aplicarFiltros();
}

// Abrir modal para nueva cita
function abrirModalNuevo(fecha = null) {
    editando = false;
    document.getElementById('tituloModal').textContent = 'Nueva Cita';
    document.getElementById('formCita').reset();
    document.getElementById('idCita').value = '';
    document.getElementById('estadoCita').value = 'Programada';
    document.getElementById('btnEliminarCita').style.display = 'none';
    
    // Si se proporciona una fecha, establecerla
    if (fecha) {
        const fechaObj = new Date(fecha);
        fechaObj.setHours(9, 0, 0, 0);
        const fechaLocal = new Date(fechaObj.getTime() - (fechaObj.getTimezoneOffset() * 60000));
        document.getElementById('fechaCita').value = fechaLocal.toISOString().slice(0, 16);
    }
    
    modalCita.show();
}

// Abrir modal para editar cita
function abrirModalEditar(evento) {
    editando = true;
    document.getElementById('tituloModal').textContent = 'Editar Cita';
    
    // Llenar el formulario con los datos del evento
    document.getElementById('idCita').value = evento.id;
    document.getElementById('selectPaciente').value = evento.extendedProps.idPaciente;
    document.getElementById('selectMedico').value = evento.extendedProps.idMedico;
    
    // Formatear fecha para input datetime-local
    const fecha = new Date(evento.start);
    const fechaLocal = new Date(fecha.getTime() - (fecha.getTimezoneOffset() * 60000));
    document.getElementById('fechaCita').value = fechaLocal.toISOString().slice(0, 16);
    
    document.getElementById('motivoConsulta').value = evento.extendedProps.motivo || '';
    document.getElementById('estadoCita').value = evento.extendedProps.estado;
    document.getElementById('observaciones').value = evento.extendedProps.observaciones || '';
    
    document.getElementById('btnEliminarCita').style.display = 'block';
    modalCita.show();
}

// Guardar cita (crear o actualizar)
function guardarCita() {
    const form = document.getElementById('formCita');
    
    // Validar formulario
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Recoger datos del formulario
    const formData = new FormData();
    const idCita = document.getElementById('idCita').value;
    
    if (editando && idCita) {
        formData.append('idCita', idCita);
    }
    
    formData.append('idPaciente', document.getElementById('selectPaciente').value);
    formData.append('idMedico', document.getElementById('selectMedico').value);
    formData.append('fechaCita', document.getElementById('fechaCita').value);
    formData.append('motivoConsulta', document.getElementById('motivoConsulta').value);
    formData.append('estadoCita', document.getElementById('estadoCita').value);
    formData.append('observaciones', document.getElementById('observaciones').value);
    
    // Determinar URL segun si es nuevo o editar
    const url = editando ? 'php/agenda/actualizar.php' : 'php/agenda/insertar.php';
    
    // Enviar datos al servidor
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            
            // Cerrar modal y recargar calendario
            modalCita.hide();
            calendar.refetchEvents();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'Error al guardar la cita'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión con el servidor'
        });
    });
}

// Eliminar cita
function eliminarCita(idCita) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('idCita', idCita);
            
            fetch('php/agenda/eliminar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    modalCita.hide();
                    calendar.refetchEvents();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión con el servidor'
                });
            });
        }
    });
}

// Cargar tabla de citas pendientes
function cargarTablaCitas() {
    const estadoFiltro = document.getElementById('filtroEstadoTabla').value;
    const tbody = document.getElementById('tablaCitasBody');
    
    fetch('php/agenda/tabla.php')
        .then(response => response.json())
        .then(data => {
            tbody.innerHTML = '';
            
            // Filtrar por estado si hay filtro
            let citasFiltradas = data;
            if (estadoFiltro) {
                citasFiltradas = data.filter(cita => cita.extendedProps.estado === estadoFiltro);
            }
            
            // Ordenar por fecha
            citasFiltradas.sort((a, b) => new Date(a.start) - new Date(b.start));
            
            if (citasFiltradas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay citas registradas</td></tr>';
                return;
            }
            
            citasFiltradas.forEach(cita => {
                const fecha = new Date(cita.start);
                const fechaFormateada = fecha.toLocaleDateString('es-MX', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const badgeClass = 
                    cita.extendedProps.estado === 'Completada' ? 'bg-success' :
                    cita.extendedProps.estado === 'Programada' ? 'bg-warning text-dark' :
                    'bg-danger';
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${fechaFormateada}</td>
                    <td>${cita.title}</td>
                    <td>${cita.extendedProps.nombreMedico}</td>
                    <td>${cita.extendedProps.nombreEspecialidad || 'N/A'}</td>
                    <td>${cita.extendedProps.motivo || 'Sin motivo'}</td>
                    <td><span class="badge ${badgeClass}">${cita.extendedProps.estado}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="verDetalleCita('${cita.id}')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Error al cargar tabla de citas:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al cargar las citas</td></tr>';
        });
}

// Ver detalle de cita desde la tabla
function verDetalleCita(idCita) {
    fetch('php/agenda/tabla.php')
        .then(response => response.json())
        .then(data => {
            const cita = data.find(c => c.id == idCita);
            if (cita) {
                // Crear objeto similar al evento de FullCalendar
                const evento = {
                    id: cita.id,
                    start: cita.start,
                    extendedProps: cita.extendedProps
                };
                abrirModalEditar(evento);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}