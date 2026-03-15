<!doctype html> 
<html lang="es">
  <head>
    <meta charset="utf-8">
    <link rel="icon" href="img/ico.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dr Simi - Registro</title>
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

          <form action="php/login/registrar_paciente.php" method="POST" style="width: 30rem;" id="form-registro">

            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Registro de Paciente</h3>

            <!-- aqui va el nombre completo -->
            <div class="form-outline mb-3">
              <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
              <input type="text" name="nombre_completo" class="form-control form-control-lg" placeholder="Ingresa tu nombre completo" required autofocus />
            </div>

            <!-- aqui va el CURP -->
            <div class="form-outline mb-3">
              <label class="form-label">CURP <span class="text-danger">*</span></label>
              <input type="text" name="curp" class="form-control form-control-lg" placeholder="CURP de 18 caracteres" required maxlength="18" pattern="[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]" />
              <small class="text-muted">Ejemplo: GOCR900101HDFRRL09</small>
            </div>

            <!-- aqui va la fecha de nacimiento -->
            <div class="form-outline mb-3">
              <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
              <input type="date" name="fecha_nacimiento" class="form-control form-control-lg" required />
            </div>

            <!-- aqui va el sexo -->
            <div class="form-outline mb-3">
              <label class="form-label">Sexo <span class="text-danger">*</span></label>
              <select name="sexo" class="form-select form-select-lg" required>
                <option value="">Selecciona</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
              </select>
            </div>

            <!-- aqui va el telefono -->
            <div class="form-outline mb-3">
              <label class="form-label">Teléfono <span class="text-danger">*</span></label>
              <input type="tel" name="telefono" class="form-control form-control-lg" placeholder="10 dígitos" required />
            </div>

            <!-- aqui va el correo -->
            <div class="form-outline mb-3">
              <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
              <input type="email" name="correo" class="form-control form-control-lg" placeholder="correo@ejemplo.com" required />
            </div>

            <!-- aqui va la direccion -->
            <div class="form-outline mb-3">
              <label class="form-label">Dirección</label>
              <input type="text" name="direccion" class="form-control form-control-lg" placeholder="Calle, número, colonia" />
            </div>

            <!-- aqui va el usuario -->
            <div class="form-outline mb-3">
              <label class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
              <input type="text" name="usuario" class="form-control form-control-lg" placeholder="Usuario para iniciar sesión" required />
            </div>

            <!-- aqui va la contraseña -->
            <div class="form-outline mb-3">
              <label class="form-label">Contraseña <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" name="password" id="input-password" class="form-control form-control-lg" placeholder="Mínimo 6 caracteres" required minlength="6" />
                <button class="btn btn-outline-secondary" type="button" id="btn-toggle-password" title="Mostrar/Ocultar">
                  <i class="bi bi-eye" id="icon-password"></i>
                </button>
              </div>
            </div>

            <!-- aqui va confirmar contraseña -->
            <div class="form-outline mb-4">
              <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
              <input type="password" name="password_confirm" id="input-password-confirm" class="form-control form-control-lg" placeholder="Repite tu contraseña" required minlength="6" />
            </div>
            
            <center>
              <div class="pt-1 mb-4">
                <button type="submit" class="btn btn-info btn-lg btn-block">Registrarse</button>
              </div>
              
              <p class="mb-2 pb-lg-2" style="color: #393f81;">¿Ya tienes cuenta? <a href="index.php" style="color: #393f81;">Inicia sesión</a></p>
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
// aqui manejo las alertas de error o exito
(function() {
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    const ok = params.get('ok');
    
    if (error === '1') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden',
            confirmButtonText: 'Intentar de nuevo'
        });
    } else if (error === '2') {
        Swal.fire({
            icon: 'error',
            title: 'Usuario ya existe',
            text: 'El nombre de usuario o CURP ya están registrados',
            confirmButtonText: 'Intentar de nuevo'
        });
    } else if (error === '3') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al registrar. Intenta de nuevo.',
            confirmButtonText: 'Aceptar'
        });
    } else if (ok === '1') {
        Swal.fire({
            icon: 'success',
            title: '¡Registro exitoso!',
            text: 'Tu cuenta ha sido creada. Ahora puedes iniciar sesión.',
            confirmButtonText: 'Ir a inicio de sesión'
        }).then(() => {
            window.location.href = 'index.php';
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

// aqui valido que las contraseñas coincidan antes de enviar
document.getElementById('form-registro').addEventListener('submit', function(e) {
    const password = document.getElementById('input-password').value;
    const passwordConfirm = document.getElementById('input-password-confirm').value;
    
    // aqui verifico si las contraseñas son iguales
    if (password !== passwordConfirm) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden',
            confirmButtonText: 'Aceptar'
        });
    }
});
</script>
</body>
</html>
