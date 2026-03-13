<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once 'php/auth/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang='en'>
  <head> 
    <meta charset='utf-8'/>
    <link rel="icon" href="img/ico.ico" />
    <title>Dr Simi - Pacientes</title>
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
            <ul class="list-unstyled px-2">
                <li class=""><a href="Dashboard.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-home"></i> Inicio</a></li>
                <hr class="h-color mx-2">
                <li class=""><a href="agenda.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-users"></i> Agenda</a></li>
                <li class=""><a href="Usuarios.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-list"></i> Usuarios</a></li>
                <li class="active"><a href="Pacientes.php" class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fal fa-comment"></i> Pacientes</span></a>
                </li>
                <li class=""><a href="Medicos.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-envelope-open-text"></i> Medicos</a></li>
                <li class=""><a href="Especialidades.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-users"></i> Especialidades</a></li>
                <li class=""><a href="Tarifas.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-dollar-sign"></i> Tarifas</a></li>
                <hr class="h-color mx-2">
                <li class=""><a href="Expedientes.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-folder"></i> Expedientes</a></li>
                <hr class="h-color mx-2">
                <li class="">
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
                                <li><a class="dropdown-item" href="#">Cerrar sesion</a></li>
                              </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

<section class="container py-3">
  <div class="row g-3 mb-3">
    <div class="col-12 col-md-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Pacientes totales</div>
          <div class="display-6 fw-semibold" id="total-pacientes">0</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Pacientes activos</div>
          <div class="display-6 fw-semibold" id="pacientes-activos">0</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Pacientes inactivos</div>
          <div class="display-6 fw-semibold" id="pacientes-inactivos">0</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-1 shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Registrar nuevo Paciente</h5>
      <form action="php/Pacientes/insertar.php" method="post" id="form-paciente">
        <div class="row g-4">
          <div class="col-12 col-lg-4">
            <div class="small text-muted mb-2">Informacion personal</div>
            <div class="mb-3">
              <label class="form-label small text-muted">Nombre Completo:</label>
              <input type="text" class="form-control" placeholder="Nombre Completo" name="nombre_completo" id="nombre-completo" required>
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">CURP:</label>
              <input type="text" class="form-control" placeholder="CURP" name="curp" id="curp" maxlength="18">
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Fecha de Nacimiento:</label>
              <input type="date" class="form-control" name="fecha_nacimiento" id="fecha-nacimiento">
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Genero:</label>
              <select class="form-select" name="sexo" id="sexo">
                <option value="">Selecciona un genero</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="Otro">Otro</option>
                <option value="Prefiero no decir">Prefiero no decir</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
          </div>

          <div class="col-12 col-lg-4">
            <div class="small text-muted mb-2">Datos de contacto</div>
            <div class="mb-3">
              <label class="form-label small text-muted">Telefono:</label>
              <input type="text" class="form-control" placeholder="Telefono" name="telefono" id="telefono">
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Email:</label>
              <input type="email" class="form-control" placeholder="Email" name="correo" id="correo">
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Contacto de emergencia:</label>
              <input type="text" class="form-control" placeholder="Contacto de emergencia" name="contacto_emergencia" id="contacto-emergencia">
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Telefono de emergencia:</label>
              <input type="text" class="form-control" placeholder="Telefono de emergencia" name="telefono_emergencia" id="telefono-emergencia">
            </div>
          </div>

          <div class="col-12 col-lg-4">
            <div class="small text-muted mb-2">Informacion adicional</div>
            <div class="mb-3">
              <label class="form-label small text-muted">Direccion:</label>
              <input type="text" class="form-control" placeholder="Direccion" name="direccion" id="direccion">
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Alergias:</label>
              <input type="text" class="form-control" placeholder="Alergias" name="alergias" id="alergias">
            </div>
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
        <h5 class="mb-0">Pacientes registrados</h5>
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
              <th>CURP</th>
              <th>Telefono</th>
              <th>Correo</th>
              <th>Fecha registro</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-pacientes">
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
  (function() {
    const params = new URLSearchParams(window.location.search);
    const ok = params.get('ok');
    const url = new URL(window.location);
    if (ok === '0') {
      Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo completar la operacion', confirmButtonText: 'Aceptar' });
      url.search = ''; window.history.replaceState({}, '', url);
    } else if (ok === '1') {
      Swal.fire({ icon: 'success', title: 'Registrado', text: 'El paciente se registro correctamente', timer: 2000, showConfirmButton: false });
      url.search = ''; window.history.replaceState({}, '', url);
    } else if (ok === '2') {
      Swal.fire({ icon: 'success', title: 'Actualizado', text: 'El paciente se actualizo correctamente', timer: 2000, showConfirmButton: false });
      url.search = ''; window.history.replaceState({}, '', url);
    } else if (ok === '3') {
      Swal.fire({ icon: 'success', title: 'Eliminado', text: 'El paciente se elimino correctamente', timer: 2000, showConfirmButton: false });
      url.search = ''; window.history.replaceState({}, '', url);
    }
  })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/helpers.js"></script>
  <script src="js/pacientes/tabla.js"></script>
  <script>
    // aqui activo las mejoras de UX
    agregarLoadingAlFormulario('form-paciente');
    validarEmail('correo');
    validarTelefono('telefono');
    validarTelefono('telefono_emergencia');
  </script>
  </body>
</html>
