<!DOCTYPE html>
<html lang='en'>
<head> 
    <meta charset='utf-8'/>
    <link rel="icon" href="img/ico.ico" />
    <title>Dr Simi - Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body{ background: #eee; }
        #side_nav{ background: #000; min-width: 250px; max-width: 250px; transition: all 0.3s; }
        .content{ min-height: 100vh; width: 100%; }
        hr.h-color{ background: #eee; }
        .sidebar li.active{ background: #eee; border-radius: 8px; }
        .sidebar li.active a, .sidebar li.active a:hover { color: #000; }
        .sidebar li a{ color: #fff; }
        @media(max-width: 767px){
            #side_nav{ margin-left: -250px; position: absolute; min-height: 100vh; z-index: 1; }
            #side_nav.active{ margin-left: 0; }
        }
        .report-card { transition: all 0.3s; cursor: pointer; }
        .report-card:hover { transform: translateY(-5px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .icon-large { font-size: 3rem; }
    </style>
</head>
<body>
<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once 'php/login/verificar_sesion.php';
?>
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
            <button class="btn d-md-none d-block close-btn px-1 py-0 text-white"><i class="fal fa-stream"></i></button>
        </div>
        <ul class="list-unstyled px-2">
            <li class=""><a href="Dashboard.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-home"></i> Inicio</a></li>
            <hr class="h-color mx-2">
            <li class=""><a href="agenda.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-users"></i> Agenda</a></li>
            <li class=""><a href="Usuarios.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-list"></i> Usuarios</a></li>
            <li class=""><a href="Pacientes.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-comment"></i> Pacientes</a></li>
            <li class=""><a href="Medicos.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-envelope-open-text"></i> Medicos</a></li>
            <li class=""><a href="Especialidades.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-users"></i> Especialidades</a></li>
            <li class=""><a href="Tarifas.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-dollar-sign"></i> Tarifas</a></li>
            <hr class="h-color mx-2">
            <li class=""><a href="Expedientes.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-folder"></i> Expedientes</a></li>
            <hr class="h-color mx-2">
            <li class="active">
              <div class="dropdown">
                <a class="nav-link dropdown-toggle text-decoration-none px-3 py-2 d-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Otros</a>
                <ul class="dropdown-menu bg-primary">
                  <li><a class="dropdown-item text-white" href="Pagos.php">Pagos</a></li>
                  <li><a class="dropdown-item text-white" href="Bitacoras.php">Bitacora</a></li>
                  <li><a class="dropdown-item text-white" href="reportes.php">Reportes</a></li>
                </ul>
              </div>
            </li>
          </ul>
    </div>
    <div class="content">
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <div class="container-fluid">
                <div class="d-flex justify-content-between d-md-none d-block">
                  <button class="btn px-1 py-0 open-btn me-2"><i class="fal fa-stream"></i></button>
                    <a class="navbar-brand fs-4" href="#"><span class="bg-primary rounded px-2 py-0 text-white"><img src="img/icono.png" width="25" height="25" alt="Dr Simi"> Dr Simi</span></a>
                </div>
                <button class="navbar-toggler p-0 border-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"><i class="fal fa-bars"></i></button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Perfil</a>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Mi cuenta</a></li>
                            <li><a class="dropdown-item" href="#">Configuracion</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="php/login/logout.php">Cerrar sesion</a></li>
                          </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

<section class="container py-3">
    <!-- aqui pongo el header -->
    <div class="mb-4">
        <h1><i class="bi bi-file-earmark-text"></i> Reportes del Sistema</h1>
        <p class="text-muted">Genera reportes en PDF o Excel de todos los módulos</p>
    </div>

    <!-- aqui pongo el filtro de fechas global -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-calendar-range"></i> Filtro de Fechas (Opcional)
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="fechaInicio" class="form-label"><i class="bi bi-calendar-event"></i> Fecha Inicio</label>
                    <input type="date" class="form-control" id="fechaInicio">
                </div>
                <div class="col-md-4">
                    <label for="fechaFin" class="form-label"><i class="bi bi-calendar-event"></i> Fecha Fin</label>
                    <input type="date" class="form-control" id="fechaFin">
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="limpiarFechas()">
                        <i class="bi bi-x-circle"></i> Limpiar Fechas
                    </button>
                </div>
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="bi bi-info-circle"></i> Si no seleccionas fechas, se generará el reporte con todos los datos disponibles.
            </small>
        </div>
    </div>

    <!-- aqui pongo las cards de reportes -->
    <div class="row g-4">
        
        <!-- aqui va el reporte de pagos -->
        <div class="col-md-6 col-lg-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cash-coin text-success icon-large"></i>
                    <h4 class="mt-3">Pagos</h4>
                    <p class="text-muted">Reporte completo de todos los pagos registrados con totales</p>
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-danger" onclick="generarReporte('pagos', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button type="button" class="btn btn-success" onclick="generarReporte('pagos', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- aqui va el reporte de pacientes -->
        <div class="col-md-6 col-lg-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people text-primary icon-large"></i>
                    <h4 class="mt-3">Pacientes</h4>
                    <p class="text-muted">Listado de pacientes con estadísticas generales</p>
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-danger" onclick="generarReporte('pacientes', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button type="button" class="btn btn-success" onclick="generarReporte('pacientes', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- aqui va el reporte de medicos -->
        <div class="col-md-6 col-lg-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge text-info icon-large"></i>
                    <h4 class="mt-3">Médicos</h4>
                    <p class="text-muted">Catálogo de médicos por especialidad</p>
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-danger" onclick="generarReporte('medicos', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button type="button" class="btn btn-success" onclick="generarReporte('medicos', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- aqui va el reporte de agenda -->
        <div class="col-md-6 col-lg-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check text-warning icon-large"></i>
                    <h4 class="mt-3">Agenda</h4>
                    <p class="text-muted">Reporte de citas programadas, completadas y canceladas</p>
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-danger" onclick="generarReporte('agenda', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button type="button" class="btn btn-success" onclick="generarReporte('agenda', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- aqui va el reporte de bitacora -->
        <div class="col-md-6 col-lg-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-journal-text text-secondary icon-large"></i>
                    <h4 class="mt-3">Bitácora</h4>
                    <p class="text-muted">Historial de accesos y acciones del sistema</p>
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-danger" onclick="generarReporte('bitacora', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button type="button" class="btn btn-success" onclick="generarReporte('bitacora', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>


</section>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// aqui muestro mensaje de bienvenida
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de reportes cargado');
    
    // aqui pongo la fecha de hoy como fecha fin por defecto
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fechaFin').value = hoy;
    
    // aqui pongo el primer dia del mes como fecha inicio por defecto
    const primerDiaMes = new Date();
    primerDiaMes.setDate(1);
    document.getElementById('fechaInicio').value = primerDiaMes.toISOString().split('T')[0];
});

// aqui esta la funcion para generar el reporte con las fechas seleccionadas
function generarReporte(modulo, formato) {
    // aqui obtengo las fechas
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;
    
    // aqui valido que si hay fecha inicio tambien haya fecha fin y viceversa
    if ((fechaInicio && !fechaFin) || (!fechaInicio && fechaFin)) {
        Swal.fire({
            icon: 'warning',
            title: 'Fechas incompletas',
            text: 'Debes seleccionar ambas fechas o ninguna',
            confirmButtonColor: '#3085d6'
        });
        return;
    }
    
    // aqui valido que la fecha inicio no sea mayor a la fecha fin
    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
        Swal.fire({
            icon: 'error',
            title: 'Fechas inválidas',
            text: 'La fecha de inicio no puede ser mayor a la fecha de fin',
            confirmButtonColor: '#d33'
        });
        return;
    }
    
    // aqui construyo la URL con los parametros
    let url = `php/reportes/generar_${formato}_${modulo}.php`;
    
    // aqui agrego las fechas si estan definidas
    if (fechaInicio && fechaFin) {
        url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }
    
    // aqui abro el reporte en una nueva ventana
    if (formato === 'pdf') {
        window.open(url, '_blank');
    } else {
        // para excel uso un link temporal para forzar la descarga
        window.location.href = url;
    }
}

// aqui limpio las fechas
function limpiarFechas() {
    document.getElementById('fechaInicio').value = '';
    document.getElementById('fechaFin').value = '';
    
    Swal.fire({
        icon: 'success',
        title: 'Fechas limpiadas',
        text: 'Los reportes se generarán con todos los datos',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>
</body>
</html>
