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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                <li class="active"><a href="Dashboard.php" class="text-decoration-none px-3 py-2 d-block"><i
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
                                <li><a class="dropdown-item" href="php/auth/logout.php">Cerrar sesión</a></li>
                              </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

<section class="container py-3">
  <header class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fs-5 mb-0">Bienvenido, <span class="fw-semibold">${user}</span></h2>
  </header>

  <div class="row row-cols-2 row-cols-md-4 g-3">
    <div class="col">
      <div class="card border-1 shadow-sm text-center">
        <div class="card-body">
          <div class="text-muted small">Pacientes registrados</div>
          <div class="display-6 fw-semibold mb-0"><span id="stat-pacientes">0</span></div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-1 shadow-sm text-center">
        <div class="card-body">
          <div class="text-muted small">Médicos registrados</div>
          <div class="display-6 fw-semibold mb-0"><span id="stat-medicos">0</span></div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-1 shadow-sm text-center">
        <div class="card-body">
          <div class="text-muted small">Citas pendientes</div>
          <div class="display-6 fw-semibold mb-0"><span id="stat-citas">0</span></div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-1 shadow-sm text-center">
        <div class="card-body ">
          <div class="text-muted small">Ingresos totales</div>
          <div class="display-6 fw-semibold mb-0">$<span id="stat-ingresos">0</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container py-3">
  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card border-1 shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Recadaudo en la semana</h5>
          <div id="ingresosChart"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Tipo de pagos</h5>
          <div id="metodosChart"></div>
        </div>
      </div>
    </div>
  </div>
</section>


            
        </div>
    </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="js/dashboard/dashboard.js"></script>
  </body>
</html>
