import { Base } from "../class/Base.js";

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const vaciarAllBtn = document.getElementById('VaciarAll');
    // Se oculta el sidebar inicialmente
    sidebar.style.transform = 'translateX(100%)';
    sidebar.style.transition = 'transform 0.3s ease-in-out';

    sidebarToggle.addEventListener('click', function() {
        // Si el sidebar está oculto, se muestra y viceversa
        if(sidebar.style.transform === 'translateX(100%)') {
            sidebar.style.transform = 'translateX(0)';
        } else {
            sidebar.style.transform = 'translateX(100%)';
        }
    });

    // Evento para el botón VaciarAll
    if (vaciarAllBtn) {
        vaciarAllBtn.addEventListener('click', function() {
            // con la libreria math o bueno aqui parece mas una funcion, toma elementos al azar de la cadena de datos
            //toma numeros al azar y genera un codigo para confirmar la eliminacion
            const generarCodigo = () => {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let out = '';
                for (let i = 0; i < 6; i++) {
                    out += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return out;
            };

            const codigo = generarCodigo();

            Swal.fire({
                title: 'Confirmación adicional',
                html: `
                    <p>Para confirmar la eliminación, escribe el siguiente código:</p>
                    <strong style="letter-spacing:4px; font-size:18px;">${codigo}</strong>
                    <input id="confirmInput" class="swal2-input" placeholder="Escribe el código aquí">
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    const inputValue = document.getElementById('confirmInput').value.trim();
                    // Verifica si el código ingresado coincide con el generado arribita
                    if (inputValue !== codigo) {
                        Swal.showValidationMessage('El código no coincide');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const BD = Base.cargar();
                    BD.VaciarAll();//mi funcion de vaciar todo menos los usuarios
                    Swal.fire({
                        title: '¡Datos eliminados!',
                        text: 'Todos los datos han sido eliminados correctamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        });
    } else {
        console.warn('Botón VaciarAll no encontrado en el DOM.');
    }

});