<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once 'php/login/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang='en'>
  <head> 
    <meta charset='utf-8'/>
    <link rel="icon" href="img/ico.ico" />
    <title>Dr Simi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.19/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.19/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.19/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.19/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.19/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.19/locales/es.global.min.js'></script>
    <style>
      body{
        background: #eee;
      }
      #side_nav{
        background: #000;
        min-width: 250px;
        max-width: 250px;
        transition: all 0.3s;
      }
      .content{
        min-height: 100vh;
        width: 100%;
      }
      hr.h-color{
        background: #eee;
      }
      .sidebar li.active{
          background: #eee;
          border-radius: 8px;
      }
      .sidebar li.active a, .sidebar li.active a:hover {
        color: #000;
      }
      .sidebar li a{
        color: #fff;
      }
      @media(max-width: 767px){
        #side_nav{
          margin-left: -250px;
          position: absolute;
          min-height: 100vh;
          z-index: 1;
      }
      #side_nav.active{
        margin-left: 0;
        }
      }
      
      /* Estilos del calendario optimizados */
      .calendar-container {
        height: calc(100vh - 220px);
        overflow: hidden;
      }
      
      #calendar {
        height: 100%;
      }
      
      .fc {
        font-size: 0.8rem;
        height: 100% !important;
      }
      
      .fc-view-harness {
        height: 100% !important;
      }
      
      .fc-header-toolbar {
        margin-bottom: 0.5rem !important;
        padding: 0.3rem;
      }
      
      .fc-toolbar-title {
        font-size: 1.1rem !important;
      }
      
      .fc-button {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.8rem !important;
      }
      
      .fc-daygrid-day-number {
        font-size: 0.85rem;
        padding: 0.2rem;
      }
      
      .fc-col-header-cell {
        padding: 0.3rem 0;
      }
      
      .fc-event {
        font-size: 0.7rem;
        padding: 1px 3px;
        margin-bottom: 1px;
        cursor: pointer;
        border-radius: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
      }
      
      .fc-event-title {
        font-weight: 500;
      }
      
      .fc-daygrid-event {
        white-space: nowrap;
        overflow: hidden;
      }
      
      .fc-daygrid-day-events {
        margin-top: 1px;
      }
      
      .fc-day-today {
        background-color: #fff3cd !important;
      }
      
      .fc-daygrid-day-frame {
        min-height: 60px;
        max-height: 100px;
        overflow: hidden;
      }
      
      .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events {
        min-height: 1em;
      }
      
      .fc-scrollgrid {
        border-collapse: collapse !important;
      }
      
      .fc-scroller {
        overflow-y: hidden !important;
      }
      
      /* Estilos para los enlaces de dias */
      .fc-daygrid-day-number {
        cursor: pointer;
        transition: all 0.2s ease;
      }
      
      .fc-daygrid-day-number:hover {
        color: #007bff !important;
        font-weight: bold;
        transform: scale(1.1);
      }
      
      .fc-day-today .fc-daygrid-day-number {
        color: #856404 !important;
        font-weight: bold;
      }
    </style>

  </head>
  <body>

  <div class="main-container d-flex">
        <div class="sidebar bg-primary" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                <h1 class="fs-4 m-0">
                  <div class="card brand-card border-0">
                    <div class="card-body py-2 px-3 d-flex align-items-center">
                      <span class="brand-logo me-2">
                        <img src="img/icono.png" width="35" height="35" alt="Dr Simi">
                      </span>
                      <span class="brand-text">Dr Simi</span>
                    </div>
                  </div>
                </h1>
                <button class="btn d-md-none d-block close-btn px-1 py-0 text-white"><i
                        class="fal fa-stream"></i></button>
            </div>
            <?php
            // Control de acceso por rol
            $rol = $_SESSION['rol_usuario'] ?? 'Paciente';
            ?>
            <ul class="list-unstyled px-2">
                <?php if ($rol !== 'Paciente'): ?>
                <!-- Inicio - Solo Staff -->
                <li class=""><a href="Dashboard.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-home"></i> Inicio</a></li>
                <hr class="h-color mx-2">
                <?php endif; ?>
                
                <!-- Agenda - Todos -->
                <li class="active"><a href="agenda.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-users"></i> Agenda</a></li>
                
                <?php if ($rol !== 'Paciente'): ?>
                <!-- Usuarios - Solo Admin -->
                <?php if ($rol === 'Admin'): ?>
                <li class=""><a href="Usuarios.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-list"></i> Usuarios</a></li>
                <?php endif; ?>
                
                <!-- Pacientes - Admin, Médico, Recepcionista -->
                <li class=""><a href="Pacientes.php" class="text-decoration-none px-3 py-2 d-block"><span><i class="fal fa-comment"></i> Pacientes</span></a></li>
                
                <!-- Médicos - Admin, Recepcionista -->
                <?php if ($rol === 'Admin' || $rol === 'Recepcionista'): ?>
                <li class=""><a href="Medicos.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-envelope-open-text"></i> Médicos</a></li>
                <?php endif; ?>
                
                <!-- Especialidades/Tarifas - Solo Admin -->
                <?php if ($rol === 'Admin'): ?>
                <li class=""><a href="Especialidades.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-users"></i> Especialidades</a></li>
                <li class=""><a href="Tarifas.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-dollar-sign"></i> Tarifas</a></li>
                <?php endif; ?>
                
                <hr class="h-color mx-2">
                
                <!-- Expedientes - Admin, Médico -->
                <?php if ($rol === 'Admin' || $rol === 'Medico'): ?>
                <li class=""><a href="Expedientes.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-folder"></i> Expedientes</a></li>
                <?php endif; ?>
                
                <!-- Otros - Admin, Recepcionista -->
                <?php if ($rol === 'Admin' || $rol === 'Recepcionista'): ?>
                <hr class="h-color mx-2">
                <li class="">
                  <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-decoration-none px-3 py-2 d-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Otros</a>
                    <ul class="dropdown-menu bg-primary">
                      <li><a class="dropdown-item text-white" href="Pagos.php">Pagos</a></li>
                      <?php if ($rol === 'Admin'): ?>
                      <li><a class="dropdown-item text-white" href="Bitacoras.php">Bitacora</a></li>
                      <li><a class="dropdown-item text-white" href="reportes.php">Reportes</a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </li>
                <?php endif; ?>
                <?php endif; ?>
              </ul>
        </div>
        <div class="content">
            <nav class="navbar navbar-expand-md navbar-light bg-light">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between d-md-none d-block">
                      <button class="btn px-1 py-0 open-btn me-2"><i class="fal fa-stream"></i></button>
                        <a class="navbar-brand fs-4" href="#"><span class="bg-primary rounded px-2 py-0 text-white"><img src="img/icono.png" width="25" height="25" alt="Dr Simi"> Dr Simi</span></a>
                    </div>
                    <button class="navbar-toggler p-0 border-1" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fal fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <ul class="navbar-nav mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Perfil
                              </a>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ&list=RDdQw4w9WgXcQ&start_radio=1">Mi cuenta</a></li>
                                <li><a class="dropdown-item" href="#">Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="php/login/logout.php">Cerrar sesión</a></li>
                              </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

<!-- Contenedor principal -->
<div class="container-fluid px-4">
    <div class="row my-4">
        <div class="col-12">
            <h3 class="fs-4 mb-3">Gestión de Agenda</h3>
            
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" id="agendaTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="calendario-tab" data-bs-toggle="tab" data-bs-target="#calendario" type="button">
                        <i class="bi bi-calendar"></i> Calendario
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="citas-tab" data-bs-toggle="tab" data-bs-target="#citas" type="button">
                        <i class="bi bi-list-ul"></i> Citas Pendientes
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="agendaTabContent">
                <!-- Tab Calendario -->
                <div class="tab-pane fade show active" id="calendario" role="tabpanel">
                    <?php if ($rol !== 'Paciente'): ?>
                    <button type="button" class="btn btn-primary mb-3" id="btnNuevaCita">
                        <i class="bi bi-plus-circle"></i> Nueva Cita
                    </button>
                    <?php endif; ?>
                    
                    <!-- Filtros -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Filtrar por Médico</label>
                                    <select class="form-select" id="filtroMedico">
                                        <option value="">Todos los médicos</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Filtrar por Estado</label>
                                    <select class="form-select" id="filtroEstado">
                                        <option value="">Todos los estados</option>
                                        <option value="Programada">Programada</option>
                                        <option value="Completada">Completada</option>
                                        <option value="Cancelada">Cancelada</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button class="btn btn-secondary" id="btnLimpiarFiltros">
                                        <i class="bi bi-x-circle"></i> Limpiar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="calendar-container">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- Tab Citas Pendientes -->
                <div class="tab-pane fade" id="citas" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Listado de Citas</h5>
                            
                            <!-- Filtro de estado para la tabla -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select class="form-select" id="filtroEstadoTabla">
                                        <option value="">Todos los estados</option>
                                        <option value="Programada" selected>Programada</option>
                                        <option value="Completada">Completada</option>
                                        <option value="Cancelada">Cancelada</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaCitas">
                                    <thead>
                                        <tr>
                                            <th>Fecha y Hora</th>
                                            <th>Paciente</th>
                                            <th>Médico</th>
                                            <th>Especialidad</th>
                                            <th>Motivo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaCitasBody">
                                        <!-- Se llena dinamicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar cita -->
<div class="modal fade" id="modalCita" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModal">Nueva Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCita">
                    <input type="hidden" id="idCita">
                    
                    <div class="mb-3">
                        <label for="selectPaciente" class="form-label">Paciente *</label>
                        <select class="form-select" id="selectPaciente" required>
                            <option value="">Seleccione un paciente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="selectMedico" class="form-label">Médico *</label>
                        <select class="form-select" id="selectMedico" required>
                            <option value="">Seleccione un médico</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fechaCita" class="form-label">Fecha y Hora *</label>
                        <input type="datetime-local" class="form-control" id="fechaCita" required>
                    </div>

                    <div class="mb-3">
                        <label for="motivoConsulta" class="form-label">Motivo de Consulta</label>
                        <textarea class="form-control" id="motivoConsulta" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="estadoCita" class="form-label">Estado *</label>
                        <select class="form-select" id="estadoCita" required>
                            <option value="Programada">Programada</option>
                            <option value="Completada">Completada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="btnEliminarCita" style="display:none;">Eliminar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarCita">Guardar</button>
            </div>
        </div>
    </div>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// aqui paso el rol del usuario al javascript
var rolUsuario = '<?php echo $rol; ?>';
</script>
<script src="js/agenda/agenda.js?v=<?php echo time(); ?>"></script>
</body>
</html>
