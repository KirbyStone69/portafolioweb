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
    <title>Dr Simi - Médicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
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
                <li class="active"><a href="Medicos.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-envelope-open-text"></i> Médicos</a></li>
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

<section class="container py-3">
  <div class="row g-3 align-items-stretch">
    <div class="col-12 col-lg-4">
      <div class="card border-1 shadow-sm h-100">
        <div class="card-body">
          <h5 class="mb-3">Registrar nuevo médico</h5>
            <form action="php/Medicos/insertar.php" method="post" id="form-registro">
              <div class="mb-3">
                <label class="form-label small text-muted">Nombre completo:</label>
                <input type="text" class="form-control" placeholder="Nombre completo" name="nombre" id="input-nombre" required>
              </div>
            
              <div class="mb-3">
                <label class="form-label small text-muted">Cédula profesional:</label>
                <input type="text" class="form-control" placeholder="Cédula" name="cedula" id="input-cedula" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Especialidad:</label>
                <select class="form-select" name="especialidad_id" id="select-especialidad" required>
                  <option value="">Cargando...</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Teléfono:</label>
                <input type="tel" class="form-control" placeholder="Teléfono" name="telefono" id="input-telefono" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Correo electrónico:</label>
                <input type="email" class="form-control" placeholder="correo@ejemplo.com" name="correo" id="input-correo" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Horario de atención:</label>
                <div class="card">
                  <div class="card-body p-3">
                    <small class="text-muted d-block mb-3">Selecciona los días y horarios de trabajo:</small>
                    
                    <div class="table-responsive">
                      <table class="table table-sm table-borderless mb-0">
                        <thead>
                          <tr>
                            <th style="width: 120px;"></th>
                            <th class="text-center" style="width: 100px;">Trabaja</th>
                            <th style="width: 120px;">Hora inicio</th>
                            <th style="width: 120px;">Hora fin</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="align-middle"><small class="fw-semibold">Lunes</small></td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="lunes_trabaja" id="lunes_trabaja">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="lunes_inicio" id="lunes_inicio" value="09:00">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="lunes_fin" id="lunes_fin" value="17:00">
                            </td>
                          </tr>
                          <tr>
                            <td class="align-middle"><small class="fw-semibold">Martes</small></td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="martes_trabaja" id="martes_trabaja">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="martes_inicio" id="martes_inicio" value="09:00">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="martes_fin" id="martes_fin" value="17:00">
                            </td>
                          </tr>
                          <tr>
                            <td class="align-middle"><small class="fw-semibold">Miércoles</small></td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="miercoles_trabaja" id="miercoles_trabaja">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="miercoles_inicio" id="miercoles_inicio" value="09:00">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="miercoles_fin" id="miercoles_fin" value="17:00">
                            </td>
                          </tr>
                          <tr>
                            <td class="align-middle"><small class="fw-semibold">Jueves</small></td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="jueves_trabaja" id="jueves_trabaja">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="jueves_inicio" id="jueves_inicio" value="09:00">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="jueves_fin" id="jueves_fin" value="17:00">
                            </td>
                          </tr>
                          <tr>
                            <td class="align-middle"><small class="fw-semibold">Viernes</small></td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="viernes_trabaja" id="viernes_trabaja">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="viernes_inicio" id="viernes_inicio" value="09:00">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="viernes_fin" id="viernes_fin" value="17:00">
                            </td>
                          </tr>
                          <tr>
                            <td class="align-middle"><small class="fw-semibold">Sábado</small></td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="sabado_trabaja" id="sabado_trabaja">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="sabado_inicio" id="sabado_inicio" value="09:00">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="sabado_fin" id="sabado_fin" value="13:00">
                            </td>
                          </tr>
                          <tr>
                            <td class="align-middle"><small class="fw-semibold">Domingo</small></td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="domingo_trabaja" id="domingo_trabaja">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="domingo_inicio" id="domingo_inicio" value="09:00">
                            </td>
                            <td>
                              <input type="time" class="form-control form-control-sm" name="domingo_fin" id="domingo_fin" value="13:00">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="mt-2">
                      <button type="button" class="btn btn-sm btn-outline-primary" id="btn-seleccionar-todos">
                        <i class="bi bi-check-all"></i> Seleccionar todos
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-limpiar-seleccion">
                        <i class="bi bi-x"></i> Limpiar
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="estatus" id="input-estatus" checked>
                  <label class="form-check-label" for="input-estatus">
                    Activo
                  </label>
                </div>
              </div>
            
              <button type="submit" class="btn btn-primary w-100" id="btn-registrar">Registrar</button>
            </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card border-1 shadow-sm h-100">
        <div class="card-body">
          <h5 class="mb-3">Estadísticas generales</h5>
          <div class="card border-1 mb-2">
            <div class="card-body d-flex justify-content-between align-items-center py-2">
              <span class="small text-muted">Total de médicos</span>
              <span class="fw-semibold" id="total-medicos">0</span>
            </div>
          </div>
          <div class="card border-1 mb-2">
            <div class="card-body d-flex justify-content-between align-items-center py-2">
              <span class="small text-muted">Médicos activos</span>
              <span class="fw-semibold" id="medicos-activos">0</span>
            </div>
          </div>
          <div class="card border-1">
            <div class="card-body d-flex justify-content-between align-items-center py-2">
              <span class="small text-muted">Médicos inactivos</span>
              <span class="fw-semibold" id="medicos-inactivos">0</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container py-3">
  <div class="card border-1 shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Médicos Registrados</h5>
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
              <th>Nombre</th>
              <th>Cédula</th>
              <th>Especialidad</th>
              <th>Teléfono</th>
              <th>Correo</th>
              <th>Status</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-medicos">
            <tr>
              <td colspan="7" class="text-center">Cargando...</td>
            </tr>
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
  (function() {
    const params = new URLSearchParams(window.location.search);
    const ok = params.get('ok');
    const url = new URL(window.location);

    if (ok === '0') {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo completar la operación',
        confirmButtonText: 'Aceptar'
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    } else if (ok === '1') {
      Swal.fire({
        icon: 'success',
        title: 'Registrado',
        text: 'El médico se registró correctamente',
        timer: 2000,
        showConfirmButton: false
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    } else if (ok === '2') {
      Swal.fire({
        icon: 'success',
        title: 'Actualizado',
        text: 'El médico se actualizó correctamente',
        timer: 2000,
        showConfirmButton: false
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    } else if (ok === '3') {
      Swal.fire({
        icon: 'success',
        title: 'Eliminado',
        text: 'El médico se eliminó correctamente',
        timer: 2000,
        showConfirmButton: false
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    }
  })();
  
  // esto selecciona todos los dias
  document.addEventListener('DOMContentLoaded', function() {
    var btnSeleccionarTodos = document.getElementById('btn-seleccionar-todos');
    var btnLimpiarSeleccion = document.getElementById('btn-limpiar-seleccion');
    
    if (btnSeleccionarTodos) {
      btnSeleccionarTodos.addEventListener('click', function() {
        var dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        for (var i = 0; i < dias.length; i++) {
          document.getElementById(dias[i] + '_trabaja').checked = true;
        }
      });
    }
    
    if (btnLimpiarSeleccion) {
      btnLimpiarSeleccion.addEventListener('click', function() {
        var dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        for (var i = 0; i < dias.length; i++) {
          document.getElementById(dias[i] + '_trabaja').checked = false;
        }
      });
    }
  });
  </script>
  <script src="js/medicos/tabla.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
