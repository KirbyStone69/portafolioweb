// ============================================
// HELPERS GLOBALES PARA MEJORAR UX
// ============================================

// ========== MEJORA 1: Loading States ==========
// Agregar loading al enviar formularios
function agregarLoadingAlFormulario(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function () {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            const textoOriginal = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

            // Si el formulario falla, restaurar botón después de 5 segundos
            setTimeout(function () {
                btn.disabled = false;
                btn.innerHTML = textoOriginal;
            }, 5000);
        }
    });
}

// ========== MEJORA 2: Validación en Tiempo Real ==========
// Validar email mientras escribe
function validarEmail(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('blur', function () {
        if (this.value && !this.value.includes('@')) {
            this.classList.add('is-invalid');

            // Crear mensaje de error si no existe
            if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Por favor ingresa un email válido';
                this.parentNode.appendChild(feedback);
            }
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Quitar error al corregir
    input.addEventListener('input', function () {
        if (this.value.includes('@')) {
            this.classList.remove('is-invalid');
        }
    });
}

// Validar teléfono (solo números)
function validarTelefono(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('input', function () {
        // Solo permitir números, guiones y espacios
        this.value = this.value.replace(/[^0-9\-\s]/g, '');
    });
}

// ========== MEJORA 3: Confirmaciones Mejoradas ==========
// Confirmación detallada para eliminar
function confirmarEliminacion(nombreItem, nombreModulo, callback) {
    Swal.fire({
        title: '¿Eliminar ' + nombreModulo.toLowerCase() + '?',
        html: '<p class="mb-0">¿Estás seguro de eliminar a <strong>' + nombreItem + '</strong>?</p><p class="text-danger small mt-2 mb-0">Esta acción NO se puede deshacer</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then(function (result) {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// ========== MEJORA 4: Mensajes Informativos ==========
// Mensaje de éxito con detalles
function mostrarExito(accion, nombreItem) {
    Swal.fire({
        icon: 'success',
        title: accion + ' exitoso',
        text: nombreItem + ' ' + accion.toLowerCase() + ' correctamente',
        timer: 2000,
        showConfirmButton: false
    });
}

// ========== MEJORA 9: Sin Resultados ==========
// Mostrar mensaje cuando no hay resultados en búsqueda
function mostrarSinResultados(tbodyId, colspan) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;

    tbody.innerHTML = '<tr><td colspan="' + colspan + '" class="text-center py-5">' +
        '<i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>' +
        '<p class="text-muted mt-3 mb-0">No se encontraron resultados</p>' +
        '<p class="text-muted small">Intenta con otros términos de búsqueda</p>' +
        '</td></tr>';
}

// ========== MEJORA 8: Paginación Mejorada ==========
// Actualizar info de registros con badge
function actualizarInfoRegistros(inicio, fin, total, elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;

    let html = 'Mostrando <span class="badge bg-primary">' + inicio + '</span> a ' +
        '<span class="badge bg-primary">' + fin + '</span> de ' +
        '<span class="badge bg-success">' + total + '</span> registros';

    element.innerHTML = html;
}

// ========== MEJORA 7: Auto-focus ==========
// Auto-focus al abrir modal
function autoFocusModal(modalId, inputId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.addEventListener('shown.bs.modal', function () {
        const input = document.getElementById(inputId);
        if (input) {
            input.focus();
        }
    });
}
