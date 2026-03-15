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
    <title>Dr Simi - Expedientes</title>
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
                <button class="btn d-md-none d-block close-btn px-1 py-0 text-white"><i class="fal fa-stream"></i></button>
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
                <li class=""><a href="agenda.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-users"></i> Agenda</a></li>
                
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
                <li class="active"><a href="Expedientes.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-folder"></i> Expedientes</a></li>
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
  <div class="row g-3 mb-3">
    <!-- Tarjetas de estadísticas -->
    <div class="col-12 col-md-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Total expedientes</div>
          <div class="display-6 fw-semibold" id="total-expedientes">0</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Consultas este mes</div>
          <div class="display-6 fw-semibold" id="expedientes-mes">0</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Consultas hoy</div>
          <div class="display-6 fw-semibold" id="expedientes-hoy">0</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Formulario para nuevo expediente -->
  <div class="card border-1 shadow-sm mb-3">
    <div class="card-body">
      <h5 class="mb-3">Registrar nuevo expediente clínico</h5>
      <form action="php/Expedientes/insertar.php" method="post" id="form-expediente">
        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <label class="form-label small text-muted">Paciente:</label>
            <select class="form-select" name="id_paciente" id="select-paciente" required>
              <option value="">Selecciona un paciente</option>
            </select>
          </div>
          <div class="col-12 col-lg-6">
            <label class="form-label small text-muted">Médico:</label>
            <select class="form-select" name="id_medico" id="select-medico" required>
              <option value="">Selecciona un médico</option>
            </select>
          </div>
          <div class="col-12 col-lg-6">
            <label class="form-label small text-muted">Fecha de consulta:</label>
            <input type="datetime-local" class="form-control" name="fecha_consulta" id="input-fecha" required>
          </div>
          <div class="col-12 col-lg-6">
            <label class="form-label small text-muted">Próxima cita:</label>
            <input type="datetime-local" class="form-control" name="proxima_cita" id="input-proxima">
          </div>
          <div class="col-12">
            <label class="form-label small text-muted">Síntomas:</label>
            <textarea class="form-control" rows="2" placeholder="Describe los síntomas del paciente" name="sintomas" id="text-sintomas" required></textarea>
          </div>
          <div class="col-12">
            <label class="form-label small text-muted">Diagnóstico:</label>
            <textarea class="form-control" rows="2" placeholder="Diagnóstico médico" name="diagnostico" id="text-diagnostico" required></textarea>
          </div>
          <div class="col-12">
            <label class="form-label small text-muted">Tratamiento:</label>
            <textarea class="form-control" rows="2" placeholder="Tratamiento prescrito" name="tratamiento" id="text-tratamiento" required></textarea>
          </div>
          <div class="col-12 col-lg-6">
            <label class="form-label small text-muted">Receta médica:</label>
            <textarea class="form-control" rows="2" placeholder="Medicamentos recetados" name="receta" id="text-receta"></textarea>
          </div>
          <div class="col-12 col-lg-6">
            <label class="form-label small text-muted">Notas adicionales:</label>
            <textarea class="form-control" rows="2" placeholder="Observaciones adicionales" name="notas" id="text-notas"></textarea>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary">Registrar Expediente</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<section class="container py-3">
  <div class="card border-1 shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Expedientes clínicos</h5>
        <div class="d-flex align-items-center gap-2">
          <label class="small text-muted">Mostrar</label>
          <select class="form-select form-select-sm" style="width:auto;" id="select-mostrar">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <span class="small text-muted">registros</span>
          <div class="ms-3">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar" id="input-buscar">
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Paciente</th>
              <th>Médico</th>
              <th>Fecha consulta</th>
              <th>Diagnóstico</th>
              <th>Próxima cita</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-expedientes">
            <tr><td colspan="6" class="text-center">Cargando...</td></tr>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-end align-items-center gap-2">
        <button class="btn btn-primary btn-sm" id="btn-prev" disabled>&laquo;</button>
        <span class="btn btn-outline-primary btn-sm disabled" id="page-info">1/1</span>
        <button class="btn btn-primary btn-sm" id="btn-next" disabled>&raquo;</button>
      </div>
      <div class="small text-muted mt-2" id="info-registros">Mostrando 0 a 0 de 0 registros</div>
    </div>
  </div>
</section>

        </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  // aqui manejan las alertas cuando se registra o actualiza algo
  (function() {
    const params = new URLSearchParams(window.location.search);
    const ok = params.get('ok');
    const url = new URL(window.location);

    if (ok === '0') {
      Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo completar la operación', confirmButtonText: 'Aceptar' });
      url.search = ''; window.history.replaceState({}, '', url);
    } else if (ok === '1') {
      Swal.fire({ icon: 'success', title: 'Registrado', text: 'El expediente se registró correctamente', timer: 2000, showConfirmButton: false });
      url.search = ''; window.history.replaceState({}, '', url);
    } else if (ok === '2') {
      Swal.fire({ icon: 'success', title: 'Actualizado', text: 'El expediente se actualizó correctamente', timer: 2000, showConfirmButton: false });
      url.search = ''; window.history.replaceState({}, '', url);
    } else if (ok === '3') {
      Swal.fire({ icon: 'success', title: 'Eliminado', text: 'El expediente se eliminó correctamente', timer: 2000, showConfirmButton: false });
      url.search = ''; window.history.replaceState({}, '', url);
    }
  })();
  </script>
  <!-- aqui carga el javascript que maneja la tabla -->
  <script src="js/expedientes/tabla.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
