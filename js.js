/* ==================== MENÚ MÓVIL ==================== */
const menuToggle = document.getElementById('menu-toggle');
const navMenu = document.getElementById('nav-menu');

menuToggle.addEventListener('click', function () {
    navMenu.classList.toggle('nav-activo');
});

navMenu.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () {
        navMenu.classList.remove('nav-activo');
    });
});

/* ==================== HEADER CON SOMBRA AL SCROLL ==================== */
const header = document.getElementById('site-header');

window.addEventListener('scroll', function () {
    header.classList.toggle('scrolleado', window.scrollY > 30);
}, { passive: true });

/* ==================== EFECTO MÁQUINA DE ESCRIBIR ==================== */
const frases = [
    'Aplicaciones Web',
    'Aplicaciones Móviles',
    'Aplicaciones de Escritorio',
    'Sistemas en la Nube'
];
const tipoEl = document.getElementById('tipo-escrito');
const promptEl = document.querySelector('.tipo-prompt');
let indiceFrase = 0;
let indiceChar = 0;
let borrando = false;

function escribir() {
    const fraseActual = frases[indiceFrase];

    if (!borrando) {
        indiceChar++;
        tipoEl.textContent = fraseActual.slice(0, indiceChar);
        if (indiceChar === fraseActual.length) {
            borrando = true;
            setTimeout(escribir, 1800);
            return;
        }
        setTimeout(escribir, 90);
    } else {
        indiceChar--;
        tipoEl.textContent = fraseActual.slice(0, indiceChar);
        if (indiceChar === 0) {
            borrando = false;
            indiceFrase = (indiceFrase + 1) % frases.length;
            setTimeout(escribir, 350);
            return;
        }
        setTimeout(escribir, 50);
    }
}

if (tipoEl) {
    setTimeout(escribir, 400);
}

/* ==================== APARICIÓN AL HACER SCROLL ==================== */
const elementosReveal = document.querySelectorAll('.reveal');

const observador = new IntersectionObserver(function (entradas) {
    entradas.forEach(function (entrada) {
        if (entrada.isIntersecting) {
            entrada.target.classList.add('visible');
            observador.unobserve(entrada.target);
        }
    });
}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

elementosReveal.forEach(function (el) {
    observador.observe(el);
});

/* ==================== COPIAR CORREO CON TOAST ==================== */
let toastTimer;

function mostrarToast(mensaje) {
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'toast';
        document.body.appendChild(toast);
    }
    toast.textContent = mensaje;
    requestAnimationFrame(function () {
        toast.classList.add('visible');
    });
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () {
        toast.classList.remove('visible');
    }, 2400);
}

document.querySelectorAll('.copia-correo').forEach(function (el) {
    el.addEventListener('click', function (e) {
        e.preventDefault();
        const correo = el.getAttribute('data-correo') || '';

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(correo).then(function () {
                mostrarToast('Correo copiado: ' + correo);
            });
        } else {
            const area = document.createElement('textarea');
            area.value = correo;
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            document.execCommand('copy');
            document.body.removeChild(area);
            mostrarToast('Correo copiado: ' + correo);
        }
    });

    el.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            el.click();
        }
    });
});

/* ==================== CONTADOR DE VISITAS ANIMADO ==================== */
const contador = document.getElementById('contador-visitas');

if (contador) {
    const total = parseInt(contador.dataset.total, 10) || 0;
    const duracion = 2000;
    const inicio = performance.now();

    function animarContador(ahora) {
        const progreso = Math.min((ahora - inicio) / duracion, 1);
        const ease = 1 - Math.pow(1 - progreso, 3);
        contador.textContent = Math.floor(ease * total).toLocaleString();
        if (progreso < 1) {
            requestAnimationFrame(animarContador);
        } else {
            contador.textContent = total.toLocaleString();
        }
    }

    requestAnimationFrame(animarContador);
}