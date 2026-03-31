import { Base } from '../class/Base.js';
import { Usuario } from '../class/Usuario.js';

// Se inicializa la pseudo base de datos con la clase de Base
let BD = Base.cargar();

document.addEventListener('DOMContentLoaded', function() {
    // Se redirige al usuario segun si hay sesion activa o la ventana en que este
    if (BD.usuarioActivo !== null && window.location.pathname.includes('login.html')) {
        console.log('Usuario activo:', BD.usuarioActivo);
        window.location.href = 'dashboard.html';
    } else if (BD.usuarioActivo === null && (!window.location.pathname.includes('login.html') && !window.location.pathname.includes('registro.html'))) {
        window.location.href = 'login.html';
    }

    const loginForm = document.getElementById('loginForm');
    const registroForm = document.getElementById('registroForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const correoLogin = document.getElementById('correoLogin').value.trim();
            const contrasenaLogin = document.getElementById('contrasenaLogin').value.trim();
            // SweetAlert si los campos estan vacios
            if (!correoLogin || !contrasenaLogin) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Por favor, complete todos los campos.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-sweetalert'
                    }
                });
                return;
            }

            const usuario = BD.verificarCredenciales(correoLogin, contrasenaLogin);
            // SweetAlert por si los datos del login son correctos o no
            if (usuario) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Inicio de sesión exitoso!',
                    text: 'Bienvenido de nuevo.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-sweetalert'
                    }
                }).then(() => {
                    // Se redirige a la pagina de dashboard
                    window.location.href = 'dashboard.html';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Correo o contraseña incorrectos.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-sweetalert'
                    }
                });
            }
        });
    }

    if (registroForm) {
        registroForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const correoRegistro = document.getElementById('correoRegistro').value.trim();
            const contrasenaRegistro = document.getElementById('contrasenaRegistro').value.trim();
            const confirmarContrasenaRegistro = document.getElementById('confirmarContrasenaRegistro').value.trim();
            // SweetAlert si los campos estan vacios
            if (!correoRegistro || !contrasenaRegistro || !confirmarContrasenaRegistro) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Por favor, complete todos los campos.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-sweetalert'
                    }
                });
                return;
            }
            // Tmbien se pone si las contraseñas no coinciden
            if (contrasenaRegistro !== confirmarContrasenaRegistro) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Las contraseñas no coinciden.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-sweetalert'
                    }
                });
                return;
            }
            // Se verifica si el correo ya esta registrado con la funcion de la pseudobase
            const usuarioExistente = BD.buscarUsuarioMismoCorreo(correoRegistro);
            if (usuarioExistente) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'El correo ya está registrado.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-sweetalert'
                    }
                });
                return;
            }
            // Si todo esta bien se crea el nuevo usuario y se guarda en la pseudobase mandando sweetalrt
            const nuevoUsuario = new Usuario(BD.ultimoIdUsuario + 1, correoRegistro, contrasenaRegistro);
            BD.agregarUsuario(nuevoUsuario);

            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: 'Ahora puedes iniciar sesión.',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-sweetalert'
                }
            }).then(() => {
                window.location.href = 'login.html';
            });
        });
    }
    
    const nombreUsuarioDashboard = document.getElementById('nombreUsuarioDashboard');
    if (nombreUsuarioDashboard && BD.usuarioActivo) {
        nombreUsuarioDashboard.textContent = BD.usuarioActivo.email;
    }

    // SweetAlert de confirmacion para cerrar sesion de cierre de sesion
    const cerrarSesionBtn = document.getElementById('cerrarSesionBtn');
    if (cerrarSesionBtn) {
        cerrarSesionBtn.addEventListener('click', function() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: "¿Estás seguro de que deseas cerrar sesión?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    BD.usuarioActivo = null;
                    BD.guardar();
                    window.location.href = 'login.html';
                }
            });
        });
    }
});