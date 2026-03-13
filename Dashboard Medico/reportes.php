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
    <title>Dr Simi</title>
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
                <li class=""><a href="Usuarios.php" class="text-decoration-none px-3 py-2 d-block"><i class="fal fa-list"></i> Usuarios</a>
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
                  <i class="fal fa-dollar-sign"></i> Tarifas</a>
                </li>
                <hr class="h-color mx-2">

                <li class="active">
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
                                <li><a class="dropdown-item" href="php/auth/logout.php">Cerrar sesión</a></li>
                              </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

<!-- ======= SECCIÓN: Reportes (con pacientes inventados) ======= -->
<section class="container py-3">

  <!-- Métricas superiores -->
  <div class="row g-3 mb-3">
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Total reportes</span>
          <span class="display-6 fw-semibold"><span id="stat-total">0</span></span>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Reportes médicos</span>
          <span class="display-6 fw-semibold"><span id="stat-medicos">0</span></span>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Reportes financieros</span>
          <span class="display-6 fw-semibold"><span id="stat-financieros">0</span></span>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Reportes de citas</span>
          <span class="display-6 fw-semibold"><span id="stat-citas">0</span></span>
        </div>
      </div>
    </div>
  </div>

  <!-- Contenido: Generar nuevo (izq) + Tabla (der) -->
  <div class="row g-3 align-items-stretch">
    <!-- Generar nuevo reporte -->
    <div class="col-12 col-lg-4">
      <div class="card border-1 shadow-sm h-100">
        <div class="card-body">
          <h5 class="mb-3">Generar nuevo reporte</h5>

          <div class="mb-3">
            <label class="form-label small text-muted">Médico:</label>
            <select class="form-select" id="select-medico">
              <option value="">Selecciona un médico</option>
              <!-- cargado dinamicamente -->
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Paciente:</label>
            <select class="form-select" id="select-paciente">
              <option value="">Selecciona un paciente</option>
              <!-- cargado dinamicamente -->
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Tipo de reporte:</label>
            <select class="form-select" id="select-tipo">
              <option value="">Seleccionar tipo</option>
              <option value="Medico">Médico</option>
              <option value="Financiero">Financiero</option>
              <option value="Citas">Citas</option>
            </select>
          </div>

          <button type="button" id="btn-generar" class="btn btn-primary w-100">Generar</button>
        </div>
      </div>
    </div>

    <!-- Reportes generados -->
    <div class="col-12 col-lg-8">
      <div class="card border-1 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Reportes generados</h5>
            <div class="d-flex align-items-center gap-2">
              <label class="small text-muted">Mostrar</label>
              <select class="form-select form-select-sm" id="select-mostrar" style="width:auto;">
                <option>10</option>
                <option>25</option>
                <option>50</option>
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
                  <th>Tipo</th>
                  <th>Médico</th>
                  <th>Paciente</th>
                  <th>Fecha</th>
                  <th class="text-center">Archivo</th>
                  <th class="text-end">Visualizar</th>
                </tr>
              </thead>
              <tbody id="tabla-reportes">
                <!-- Ejemplo 1 -->
                <tr>
                  <td><span class="badge text-bg-light">CITAS</span></td>
                  <td>
                    <div class="fw-semibold">Dra. Natalia Romero Pérez</div>
                    <div class="small text-muted">Cédula: 8743321</div>
                  </td>
                  <td>
                    <div class="fw-semibold">Laura Hernández Cruz</div>
                    <div class="small text-muted">CURP: HECL950214MDFNRL05</div>
                  </td>
                  <td>08/11/2025 – 10:30</td>
                  <td class="text-center">
                    <button class="btn btn-primary btn-sm">PDF</button>
                  </td>
                  <td class="text-end">
                    <button class="btn btn-primary btn-sm">Ver</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
            <button class="btn btn-primary btn-sm" id="btn-prev" disabled>&laquo;</button>
            <span class="btn btn-outline-primary btn-sm disabled" id="page-info">1/1</span>
            <button class="btn btn-primary btn-sm" id="btn-next" disabled>&raquo;</button>
          </div>

          <div class="small text-muted mt-2" id="info-registros">Mostrando 0 a 0 de 0 registros</div>
        </div>
      </div>
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
        text: 'No se pudo generar el reporte',
        confirmButtonText: 'Aceptar'
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    } else if (ok === '1') {
      Swal.fire({
        icon: 'success',
        title: 'Generado',
        text: 'El reporte se generó correctamente',
        timer: 2000,
        showConfirmButton: false
      });
      url.search = '';
      window.history.replaceState({}, '', url);
    }
  })();
  </script>
  <!--obtiene los datos de listar.php y los plasma en la tabla-reportes-->
  <script src="js/reportes/tabla.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
