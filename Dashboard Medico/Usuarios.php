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
    <title>Dr Simi - Usuarios</title>
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
            <ul class="list-unstyled px-2">
                <li class=""><a href="Dashboard.php" class="text-decoration-none px-3 py-2 d-block"><i
                            class="fal fa-home"></i> Inicio</a>
                </li>
                <hr class="h-color mx-2">
                <li class=""><a href="agenda.php" class="text-decoration-none px-3 py-2 d-block">
                  <i class="fal fa-users"></i> Agenda</a>
                </li>
                <li class="active"><a href="Usuarios.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-list"></i> Usuarios</a>
                </li>
                <li class=""><a href="Pacientes.php" class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fal fa-comment"></i> Pacientes</span></a>
                </li>
                <li class=""><a href="Medicos.php" class="text-decoration-none px-3 py-2 d-block"><i
                            class="fal fa-envelope-open-text"></i> Médicos</a>
                </li>
                <li class=""><a href="Especialidades.php" class="text-decoration-none px-3 py-2 d-block">
                  <i class="fal fa-users"></i> Especialidades</a>
                </li>
                <li class=""><a href="Tarifas.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-dollar-sign"></i> Tarifas</a>
                </li>
                <hr class="h-color mx-2">
                <li class=""><a href="Expedientes.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-folder"></i> Expedientes</a></li>

                <hr class="h-color mx-2">

                <li class="">
                  <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-decoration-none px-3 py-2 d-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Otros
                    </a>
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
          <h5 class="mb-3">Registrar nuevo usuario</h5>
            <form action="php/Usuarios/insertar.php" method="post" id="form-registro">
              <div class="mb-3">
                <label class="form-label small text-muted">Usuario:</label>
                <input type="text" class="form-control" placeholder="Nombre de usuario" name="usuario" id="input-usuario" required>
              </div>
            
              <div class="mb-3">
                <label class="form-label small text-muted">Contraseña:</label>
                <input type="password" class="form-control" placeholder="Contraseña" name="password" id="input-password" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Rol:</label>
                <select class="form-select" name="rol" id="select-rol" required>
                  <option value="">Seleccione un rol</option>
                  <option value="Admin">Admin</option>
                  <option value="Medico">Medico</option>
                  <option value="Recepcionista">Recepcionista</option>
                  <option value="Paciente">Paciente</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Nombre completo:</label>
                <input type="text" class="form-control" placeholder="Nombre completo" name="nombre_completo" id="input-nombre" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Teléfono:</label>
                <input type="text" class="form-control" placeholder="Teléfono" name="telefono" id="input-telefono">
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Correo electrónico:</label>
                <input type="email" class="form-control" placeholder="correo@ejemplo.com" name="correo" id="input-correo">
              </div>

              <div class="mb-3" id="grupo-medico" style="display:none;">
                <label class="form-label small text-muted">Médico vinculado:</label>
                <select class="form-select" name="medico_id" id="select-medico">
                  <option value="">Cargando...</option>
                </select>
                <small class="text-muted">Vincula este usuario a un médico registrado (solo para rol Médico)</small>
              </div>

              <!-- aqui va el selector de paciente (solo para rol Paciente) -->
              <div class="mb-3" id="grupo-paciente" style="display:none;">
                <label for="select-paciente" class="form-label">Paciente</label>
                <select class="form-select" name="paciente_id" id="select-paciente">
                  <option value="">Seleccione un paciente</option>
                </select>
                <small class="text-muted">Solo para usuarios con rol Paciente</small>
              </div>

              <div class="mb-3" id="grupo-recepcionista" style="display:none;">
                <label for="select-medicos" class="form-label">Médicos asignados</label>
                <select class="form-select" name="medicos_asignados[]" id="select-medicos" multiple size="4">
                </select>
                <small class="text-muted">Selecciona los médicos que atenderá esta recepcionista</small>
              </div>

              <div class="mb-3" id="grupo-medicos-asignados" style="display:none;">
                <label class="form-label small text-muted">Médicos asignados:</label>
                <div class="border rounded p-2" id="lista-medicos-asignados" style="max-height: 200px; overflow-y: auto;">
                  <p class="text-muted small mb-0">Cargando médicos...</p>
                </div>
                <small class="text-muted">Selecciona los médicos que atenderá esta recepcionista</small>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="activo" id="input-activo" checked>
                  <label class="form-check-label" for="input-activo">
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
          <div class="row">
            <div class="col-md-6">
              <div class="card border-1 mb-2">
                <div class="card-body d-flex justify-content-between align-items-center py-2">
                  <span class="small text-muted">Total usuarios</span>
                  <span class="fw-semibold" id="total-usuarios">0</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-1 mb-2">
                <div class="card-body d-flex justify-content-between align-items-center py-2">
                  <span class="small text-muted">Activos</span>
                  <span class="fw-semibold text-success" id="usuarios-activos">0</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-1 mb-2">
                <div class="card-body d-flex justify-content-between align-items-center py-2">
                  <span class="small text-muted">Inactivos</span>
                  <span class="fw-semibold text-secondary" id="usuarios-inactivos">0</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-1 mb-2">
                <div class="card-body d-flex justify-content-between align-items-center py-2">
                  <span class="small text-muted">Admins</span>
                  <span class="fw-semibold text-danger" id="usuarios-admin">0</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-1 mb-2">
                <div class="card-body d-flex justify-content-between align-items-center py-2">
                  <span class="small text-muted">Médicos</span>
                  <span class="fw-semibold text-primary" id="usuarios-medico">0</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-1 mb-2">
                <div class="card-body d-flex justify-content-between align-items-center py-2">
                  <span class="small text-muted">Recepcionistas</span>
                  <span class="fw-semibold text-info" id="usuarios-recepcionista">0</span>
                </div>
              </div>
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
        <h5 class="mb-0">Usuarios del Sistema</h5>
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
              <th>Usuario</th>
              <th>Rol</th>
              <th>Médico</th>
              <th>Último acceso</th>
              <th>Status</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-usuarios">
            <tr>
              <td colspan="6" class="text-center">Cargando...</td>
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
        text: 'El usuario se registró correctamente',
        timer: 2000,
        showConfirmButton: false
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    } else if (ok === '2') {
      Swal.fire({
        icon: 'success',
        title: 'Actualizado',
        text: 'El usuario se actualizó correctamente',
        timer: 2000,
        showConfirmButton: false
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    } else if (ok === '3') {
      Swal.fire({
        icon: 'success',
        title: 'Eliminado',
        text: 'El usuario se eliminó correctamente',
        timer: 2000,
        showConfirmButton: false
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    }
  })();
  </script>
  <script src="js/usuarios/tabla.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
