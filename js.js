anime({
    targets: '.logo',
    translateY: [-30, 0],
    opacity: [0, 1],
    duration: 1000,
    easing: 'easeOutExpo'
});

anime({
    targets: '.nav-link',
    translateY: [-20, 0],
    opacity: [0, 1],
    delay: anime.stagger(100, {start: 200}),
    duration: 800,
    easing: 'easeOutExpo'
});

anime({
    targets: '.seccion-titulo',
    translateX: [-50, 0],
    opacity: [0, 1],
    delay: anime.stagger(200, {start: 400}),
    duration: 1000,
    easing: 'easeOutExpo'
});

anime({
    targets: '.proyecto-card',
    translateY: [100, 0],
    opacity: [0, 1],
    delay: anime.stagger(100, {start: 600}),
    duration: 1000,
    easing: 'easeOutExpo'
});

document.querySelectorAll('.proyecto-btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        anime({
            targets: this,
            scale: 1.1,
            duration: 300,
            easing: 'easeOutQuad'
        });
    });
    
    btn.addEventListener('mouseleave', function() {
        anime({
            targets: this,
            scale: 1,
            duration: 300,
            easing: 'easeOutQuad'
        });
    });
});

document.querySelector('.menu-toggle').addEventListener('click', function() {
    document.querySelector('.nav-menu').classList.toggle('nav-activo');
});

document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
        document.querySelector('.nav-menu').classList.remove('nav-activo');
    });
});

const contador = document.getElementById('contador-visitas');
const total = parseInt(contador.dataset.total);
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
