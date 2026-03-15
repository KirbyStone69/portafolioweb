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
    <title>Dr Simi - Pagos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                <li class="active">
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

  <div class="row g-3 mb-3">
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Total general</span>
          <span class="fw-semibold" id="total-general">$0.00</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Ingresos del mes</span>
          <span class="fw-semibold" id="ingresos-mes">$0.00</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Ingresos de la semana</span>
          <span class="fw-semibold" id="ingresos-semana">$0.00</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-1 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="small text-muted">Ingresos de hoy</span>
          <span class="fw-semibold" id="ingresos-hoy">$0.00</span>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-1 shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
          <input type="text" id="input-busqueda" class="form-control" placeholder="Buscar..." style="width: 250px;">
          <select id="filtro-metodo" class="form-select" style="width: 180px;">
            <option value="">Todos los métodos</option>
            <option value="Efectivo">Efectivo</option>
            <option value="Tarjeta">Tarjeta</option>
            <option value="Transferencia">Transferencia</option>
          </select>
          <select id="filtro-estado" class="form-select" style="width: 180px;">
            <option value="">Todos los estados</option>
            <option value="Pendiente">Pendiente</option>
            <option value="Pagado">Pagado</option>
            <option value="Cancelado">Cancelado</option>
          </select>
        </div>
        <button class="btn btn-primary" onclick="abrirModalNuevo()">Nuevo Pago</button>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Pagos registrados</h5>
        <div class="d-flex align-items-center gap-2">
          <label class="small text-muted">Mostrar</label>
          <select class="form-select form-select-sm" id="select-registros" style="width:auto;">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <span class="small text-muted">registros</span>
          <div class="ms-3">
            <input type="text" class="form-control form-control-sm" id="input-busqueda" placeholder="Buscar">
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Paciente</th>
              <th>Médico</th>
              <th class="text-end">Monto</th>
              <th>Método de pago</th>
              <th>Fecha y hora</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-pagos">
            <tr><td colspan="7">Cargando...</td></tr>
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-end align-items-center gap-2">
        <button class="btn btn-primary btn-sm" id="btn-anterior">&laquo;</button>
        <button class="btn btn-primary btn-sm active" id="numero-pagina">1</button>
        <button class="btn btn-primary btn-sm" id="btn-siguiente">&raquo;</button>
      </div>

      <div class="small text-muted mt-2" id="info-paginacion">Mostrando 0 a 0 de 0 registros</div>
    </div>
  </div>
</section>


            
        </div>
    </div>



<!-- Modal para nuevo/editar pago -->
<div class="modal fade" id="modal-pago" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo-modal">Registrar Nuevo Pago</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="form-pago" method="POST">
          <input type="hidden" name="id_pago" id="id-pago">
          
          <div class="mb-3" id="grupo-cita">
            <label class="form-label">Cita</label>
            <select class="form-select" name="id_cita" id="select-cita" required>
              <option value="">Seleccione una cita</option>
            </select>
            <div class="form-text">Solo se muestran citas completadas sin pago</div>
          </div>

          <!-- aqui va la seccion de servicios -->
          <div class="mb-3">
            <label class="form-label">Servicios</label>
            <button type="button" class="btn btn-sm btn-success mb-2" onclick="mostrarModalServicio()">
              + Agregar Servicio
            </button>
            <div class="table-responsive">
              <table class="table table-sm table-bordered">
                <thead class="table-light">
                  <tr>
                    <th>Servicio</th>
                    <th width="80">Cant.</th>
                    <th width="100">Precio</th>
                    <th width="100">Subtotal</th>
                    <th width="50"></th>
                  </tr>
                </thead>
                <tbody id="tabla-servicios">
                  <tr>
                    <td colspan="5" class="text-center text-muted">No hay servicios agregados</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Total</label>
            <input type="number" class="form-control" name="monto" id="input-monto" step="0.01" readonly required>
            <input type="hidden" name="servicios" id="input-servicios">
            <input type="hidden" name="id_paciente" id="input-id-paciente">
          </div>
          
          <div class="mb-3">
            <label class="form-label">Metodo de Pago</label>
            <select class="form-select" name="metodo_pago" id="select-metodo" required>
              <option value="">Seleccione un metodo</option>
              <option value="Efectivo">Efectivo</option>
              <option value="Tarjeta">Tarjeta</option>
              <option value="Transferencia">Transferencia</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Referencia</label>
            <input type="text" class="form-control" name="referencia" id="input-referencia" maxlength="100">
            <div class="form-text">Opcional: numero de transaccion o nota</div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Estado del Pago</label>
            <select class="form-select" name="estatus_pago" id="select-estatus" required>
              <option value="Pendiente">Pendiente</option>
              <option value="Pagado" selected>Pagado</option>
              <option value="Cancelado">Cancelado</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn-guardar" onclick="guardarPago()">Registrar</button>
      </div>
    </div>
  </div>
</div>

<!-- aqui va el modal para agregar servicio -->
<div class="modal fade" id="modal-servicio" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Servicio</label>
          <select class="form-select" id="select-servicio-modal">
            <option value="">Cargando servicios...</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Cantidad</label>
          <input type="number" class="form-control" id="input-cantidad-modal" value="1" min="1">
        </div>
        <div class="mb-3">
          <label class="form-label">Precio Unitario</label>
          <input type="number" class="form-control" id="input-precio-modal" step="0.01" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Subtotal</label>
          <input type="number" class="form-control" id="input-subtotal-modal" step="0.01" readonly>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="agregarServicioATabla()">Agregar</button>
      </div>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/pagos/tabla.js"></script>
  </body>
</html>
