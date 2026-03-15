<?php
$titulo = "KirbyStone";
$items_a_ignorar = [
    '.', 
    '..', 
    '.git', 
    'plantilla.php', 
    '.heroku',      
    '.profile.d',   
    '.composer',    
    'vendor'        
];
$items = scandir('.');
$proyectos = [];

foreach ($items as $item) {
    if (is_dir($item) && !in_array($item, $items_a_ignorar)) {
        $preview_image = null;
        
        if (file_exists($item . '/preview.jpg')) {
            $preview_image = $item . '/preview.jpg';
        } elseif (file_exists($item . '/preview.png')) {
            $preview_image = $item . '/preview.png';
        } else {
            $images = glob($item . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            if (!empty($images)) {
                $preview_image = $images[0];
            }
        }
        
        $proyectos[] = [
            'nombre' => $item,
            'imagen' => $preview_image
        ];
    }
}

usort($proyectos, function($a, $b) {
    return strcmp($a['nombre'], $b['nombre']);
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="stylesheet" href="css.css">
</head>
<body>

    <!-- ==================== HEADER / NAVEGACION ==================== -->
    <header class="site-header">
        <div class="header-container">
            <a href="#" class="logo">KirbyStone</a>
            <nav class="nav-menu">
                <a href="#sobre-mi" class="nav-link">Sobre Mí</a>
                <a href="#mis-proyectos" class="nav-link">Mis Proyectos</a>
                <a href="#contacto" class="nav-link">Contacto</a>
            </nav>
            <button class="menu-toggle" aria-label="Abrir menú">☰</button>
        </div>
    </header>
    <!-- ==================== FIN HEADER ==================== -->

    <!-- ==================== SOBRE MI ==================== -->
    <section id="sobre-mi" class="seccion sobre-mi">
        <div class="container">
            <h2 class="seccion-titulo">Sobre Mí</h2>
            <div class="sobre-mi-contenido">
                <div class="sobre-mi-texto">
                    <p>¡Hola! Soy <strong>KirbyStone</strong>, desarrollador apasionado por la tecnología y la programación.</p>
                    <p>Me encanta crear proyectos web, explorar nuevas herramientas y aprender algo nuevo cada día.</p>
                </div>
                <div class="sobre-mi-stats">
                    <div class="stat-item">
                        <span class="stat-numero"><?= count($proyectos) ?></span>
                        <span class="stat-label">Proyectos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-numero">∞</span>
                        <span class="stat-label">Curiosidad</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== FIN SOBRE MI ==================== -->

    <!-- ==================== MIS PROYECTOS ==================== -->
    <section id="mis-proyectos" class="seccion mis-proyectos">
        <div class="container">
            <h2 class="seccion-titulo">Mis Proyectos</h2>
            <div id="lista-proyectos">
                <?php foreach ($proyectos as $proyecto): ?>
                    <div class="proyecto-card">
                        <div class="preview-container">
                            <?php if ($proyecto['imagen']): ?>
                                <img class="preview-image" src="<?= htmlspecialchars($proyecto['imagen']) ?>" alt="Preview de <?= htmlspecialchars($proyecto['nombre']) ?>">
                            <?php else: ?>
                                <div class="preview-placeholder">📁</div>
                            <?php endif; ?>
                            <div class="preview-overlay"></div>
                        </div>
                        <div class="proyecto-info">
                            <div class="proyecto-nombre"><?= htmlspecialchars($proyecto['nombre']) ?></div>
                            <a class="proyecto-btn" href="<?= htmlspecialchars($proyecto['nombre']) ?>/">
                                Ver Proyecto →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- ==================== FIN MIS PROYECTOS ==================== -->

    <!-- ==================== PIE DE PAGINA / CONTACTO ==================== -->
    <footer id="contacto" class="site-footer">
        <div class="container">
            <h2 class="seccion-titulo">Contacto</h2>
            <p class="footer-texto">¿Quieres colaborar o tienes alguna pregunta? ¡Contáctame!</p>
            <div class="footer-links">
                <a href="https://github.com/KirbyStone69" target="_blank" class="footer-link">
                    <span class="footer-link-icon">🐙</span> GitHub
                </a>
            </div>
            <div class="footer-copy">
                <p>&copy; <?= date('Y') ?> KirbyStone. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <!-- ==================== FIN PIE DE PAGINA ==================== -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script>
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
    </script>
</body>
</html>