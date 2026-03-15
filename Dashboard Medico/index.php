<!doctype html> 
<html lang="es">
  <head>
    <meta charset="utf-8">
    <link rel="icon" href="img/ico.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dr Simi - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  </head>
  <body style="background-color: #fdfdfd;">

<section class="vh-100">
  <div class="container-fluid">
    <div class="row">
      
      <div class="col-sm-6 text-black">

        <div class="px-5 ms-xl-4">
          <center>
            <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
            <span style="margin: 20px;" class="h1 fw-bold mb-0"><img src="img/logo.png" width="200" height="80" alt="logo"></span>
          </center>
        </div>

        <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

          <form action="php/login/validar_login.php" method="POST" style="width: 23rem;" id="form-login">

            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Ingresar</h3>
            <div class="alert alert-info py-2 text-center" role="alert" style="font-size: 0.9em;">
              Usuario: <strong>admin</strong> | Contraseña: <strong>admin</strong>
            </div>

            <div class="form-outline mb-4">
              <label class="form-label">Usuario <span class="text-danger">*</span></label>
              <input type="text" name="usuario" id="input-usuario" class="form-control form-control-lg" placeholder="Ingresa tu usuario" required autofocus />
            </div>

            <div class="form-outline mb-4">
              <label class="form-label">Contraseña <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" name="password" id="input-password" class="form-control form-control-lg" placeholder="Ingresa tu contraseña" required />
                <button class="btn btn-outline-secondary" type="button" id="btn-toggle-password" title="Mostrar/Ocultar">
                  <i class="bi bi-eye" id="icon-password"></i>
                </button>
              </div>
            </div>
            
            <center>
              <div class="pt-1 mb-4">
                <button type="submit" class="btn btn-info btn-lg btn-block">Ingresar</button>
              </div>
              
              <p class="mb-2 pb-lg-2" style="color: #393f81;">¿No tienes cuenta? <a href="registro.php" style="color: #393f81;">Regístrate aquí</a></p>
            </center>
            
          </form>
          
        </div>

      </div>

      <div class="col-sm-6 px-0 d-none d-sm-block">
        <img src="img/dibujo.png"
          alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
// aqui verifico si ya hay sesion activa en el servidor
(function verificarSesionActiva() {
    fetch('php/login/verificar_sesion_ajax.php')
        .then(response => response.json())
        .then(data => {
            if (data.sesion_activa) {
                // Si hay sesion activa, redirigir al dashboard
                window.location.href = 'Dashboard.php';
            }
        })
        .catch(error => {
            // Si hay error, continuar normal en login
            console.log('No hay sesión activa');
        });
})();

// aqui manejo las alertas de error
(function() {
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    
    if (error === '1') {
        Swal.fire({
            icon: 'error',
            title: 'Error de acceso',
            text: 'Usuario o contraseña incorrectos',
            confirmButtonText: 'Intentar de nuevo'
        });
    } else if (error === '2') {
        // Limpiar localStorage al cerrar sesion
        localStorage.removeItem('usuario_sesion');
        Swal.fire({
            icon: 'warning',
            title: 'Sesión cerrada',
            text: 'Tu sesión ha expirado. Inicia sesión nuevamente.',
            confirmButtonText: 'Aceptar'
        });
    }
})();

// aqui manejo el boton de mostrar/ocultar contraseña
document.getElementById('btn-toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('input-password');
    const icon = document.getElementById('icon-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});

// aqui guardo el usuario en localStorage cuando inicia sesion exitosamente
document.getElementById('form-login').addEventListener('submit', function(e) {
    const usuario = document.getElementById('input-usuario').value;
    // Guardar usuario en localStorage para recordar sesion
    localStorage.setItem('usuario_sesion', usuario);
});
</script>
</body>
</html>
