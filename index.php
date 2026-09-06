<?php
$titulo = "KirbyStone — Eder Omar | Desarrollo Multiplataforma";

$archivo_visitas = __DIR__ . '/visitas.txt';
if (!file_exists($archivo_visitas)) {
    file_put_contents($archivo_visitas, '0');
}
$visitas = (int)file_get_contents($archivo_visitas);
$visitas++;
file_put_contents($archivo_visitas, $visitas);

$items_a_ignorar = [
    '.',
    '..',
    '.git',
    'plantilla.php',
    '.heroku',
    '.profile.d',
    '.composer',
    'vendor',
    'img'
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
    <meta name="description" content="Eder Omar — Técnico Superior Universitario en Desarrollo de Software Multiplataforma. Desarrollo aplicaciones web, móviles, de escritorio y sistemas en la nube (AWS).">
    <link rel="icon" href="kirby.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
</head>
<body>

    <!-- ==================== HEADER / NAVEGACION ==================== -->
    <header class="site-header" id="site-header">
        <div class="header-container">
            <a href="#inicio" class="logo">
                <span class="logo-text">KirbyStone</span>
            </a>
            <nav class="nav-menu" id="nav-menu">
                <a href="#inicio" class="nav-link">Inicio</a>
                <a href="#sobre-mi" class="nav-link">Sobre Mí</a>
                <a href="#mis-proyectos" class="nav-link">Proyectos</a>
                <a href="#contacto" class="nav-link nav-cta">Contáctame</a>
            </nav>
            <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú">☰</button>
        </div>
    </header>
    <!-- ==================== FIN HEADER ==================== -->

    <!-- ==================== HERO ==================== -->
    <section id="inicio" class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-intro">
                    <span class="hero-eyebrow"><span class="dot"></span> Disponible para nuevos proyectos</span>
                    <p class="hero-saludo">Hola, mi nombre es</p>
                    <h1 class="hero-nombre">Eder Omar</h1>
                    <p class="hero-tipo">
                        <span class="tipo-prompt">&gt;</span><span class="tipo-escrito" id="tipo-escrito"></span><span class="cursor"></span>
                    </p>
                    <p class="hero-desc">
                        Técnico Superior Universitario en <strong>Desarrollo de Software Multiplataforma</strong>.
                        Convierto ideas en <strong>sistemas web, móviles y de escritorio</strong>.
                        Esto es lo que hago; si te interesa, aquí estoy.
                    </p>
                    <div class="hero-ctas">
                        <a href="#mis-proyectos" class="btn btn-primary">Ver mis proyectos</a>
                        <a href="#contacto" class="btn btn-outline">Hablemos →</a>
                    </div>
                    <div class="hero-techs">
                        <span class="chip">PHP</span>
                        <span class="chip">JavaScript</span>
                        <span class="chip">MySQL</span>
                        <span class="chip">HTML / CSS</span>
                        <span class="chip">Git</span>
                        <span class="chip">AWS EC2</span>
                        <span class="chip">Android</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== FIN HERO ==================== -->

    <!-- ==================== MIS PROYECTOS ==================== -->
    <section id="mis-proyectos" class="seccion mis-proyectos">
        <div class="container">
            <h2 class="seccion-titulo reveal">Mis <span class="grad">proyectos</span></h2>
            <p class="seccion-subtitulo reveal">
                Sistemas reales que he desarrollado con diferentes tecnologías.
            </p>
            <div id="lista-proyectos">
                <?php foreach ($proyectos as $index => $proyecto): ?>
                    <a class="proyecto-card reveal reveal-retraso-<?= ($index % 4) + 1 ?>" href="<?= htmlspecialchars($proyecto['nombre']) ?>/" target="_blank" rel="noopener">
                        <div class="preview-container">
                            <?php if ($proyecto['imagen']): ?>
                                <img class="preview-image" src="<?= htmlspecialchars($proyecto['imagen']) ?>" alt="Preview de <?= htmlspecialchars($proyecto['nombre']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="preview-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#86efac" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" width="56" height="56" opacity="0.5">
                                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div class="preview-overlay">
                                <span class="proyecto-btn">Abrir proyecto →</span>
                            </div>
                        </div>
                        <div class="proyecto-info">
                            <div class="proyecto-nombre"><?= htmlspecialchars($proyecto['nombre']) ?></div>
                            <span class="proyecto-btn">Ver Proyecto →</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- ==================== FIN MIS PROYECTOS ==================== -->

    <!-- ==================== SOBRE MI ==================== -->
    <section id="sobre-mi" class="seccion sobre-mi">
        <div class="container">
            <h2 class="seccion-titulo reveal">Sobre <span class="grad">mí</span></h2>
            <div class="sobre-mi-contenido">
                <div class="sobre-mi-texto reveal">
                    <p>
                        Soy <strong>Eder Omar</strong>, Técnico Superior Universitario en <strong>Desarrollo de Software
                        Multiplataforma</strong>, de la Universidad Politécnica de Victoria.
                    </p>
                    <p>
                        Tengo experiencia creando <strong>aplicaciones web</strong> enfocadas a servicios,
                        <strong>aplicaciones de escritorio</strong> y <strong>aplicaciones móviles</strong>, además
                        del uso de <strong>AWS EC2</strong> para alojar sistemas en la nube.
                    </p>
                    <p>
                        En constante aprendizaje, siempre sumando <strong>nuevos retos técnicos</strong>
                        a lo que ya ves en este portafolio.
                    </p>
                </div>
                <div class="sobre-mi-bloque reveal">
                    <h3>Mi stack</h3>
                    <div class="techs-grid">
                        <span class="chip">PHP</span>
                        <span class="chip">Python</span>
                        <span class="chip">C++</span>
                        <span class="chip">JavaScript</span>
                        <span class="chip">MySQL</span>
                        <span class="chip">HTML5 / CSS3</span>
                        <span class="chip">Bootstrap</span>
                        <span class="chip">Git / GitHub</span>
                        <span class="chip">AWS EC2</span>
                        <span class="chip">Java</span>
                        <span class="chip">Android Studio</span>
                    </div>
                    <div class="stat-hilera">
                        <div class="stat-item">
                            <span class="stat-numero"><?= count($proyectos) ?></span>
                            <span class="stat-label">Proyectos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-numero">1+</span>
                            <span class="stat-label">Año con el stack</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-numero">24/7</span>
                            <span class="stat-label">Disponibilidad</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== FIN SOBRE MI ==================== -->

    <!-- ==================== CONTACTO ==================== -->
    <section id="contacto" class="seccion contacto">
        <div class="container">
            <h2 class="seccion-titulo reveal">¿Tienes un <span class="grad">proyecto en mente?</span></h2>
            <p class="seccion-subtitulo reveal">
                Cuéntame la idea y la convertimos en un sistema funcional. Escríbeme por el canal que prefieras.
            </p>
            <div class="contacto-grid">
                <a class="contacto-card reveal reveal-retraso-1" href="https://wa.me/+528342186956" target="_blank" rel="noopener">
                    <span class="contacto-icono"><img src="img/whatsapp.svg" alt="WhatsApp"></span>
                    <h3>WhatsApp</h3>
                    <p>Respuesta rápida<br>+52 834 218 6956</p>
                </a>
                <a class="contacto-card copia-correo reveal reveal-retraso-2" role="button" tabindex="0" data-correo="edeveloco@gmail.com">
                    <span class="contacto-icono"><img src="img/gmail.svg" alt="Gmail"></span>
                    <h3>Correo personal</h3>
                    <p>edeveloco@gmail.com</p>
                </a>
                <a class="contacto-card copia-correo reveal reveal-retraso-3" role="button" tabindex="0" data-correo="2430206@upv.edu.mx">
                    <span class="contacto-icono"><img src="img/gmail.svg" alt="Gmail institucional"></span>
                    <h3>Correo institucional</h3>
                    <p>2430206@upv.edu.mx</p>
                </a>
                <a class="contacto-card reveal reveal-retraso-4" href="https://github.com/KirbyStone69" target="_blank" rel="noopener">
                    <span class="contacto-icono"><img src="img/github.svg" alt="GitHub"></span>
                    <h3>GitHub</h3>
                    <p>github.com/KirbyStone69</p>
                </a>
            </div>
        </div>
    </section>
    <!-- ==================== FIN CONTACTO ==================== -->

    <!-- ==================== CTA FINAL ==================== -->
    <section class="seccion cta-final">
        <div class="container">
            <div class="cta-caja reveal">
                <h2>Esto es lo que hago</h2>
                <p>Si algo te interesa, aquí estoy. Y si no, sin problema.</p>
                <a href="https://wa.me/+528342186956?text=Hola%20Eder,%20me%20interesa%20un%20sistema." target="_blank" rel="noopener" class="btn btn-primary">
                    Escríbeme por WhatsApp
                </a>
            </div>
        </div>
    </section>
    <!-- ==================== FIN CTA FINAL ==================== -->

    <!-- ==================== PIE DE PAGINA ==================== -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-visitas">
                <span>Visitas al portafolio:</span>
                <span id="contador-visitas" class="visitas-contador" data-total="<?= $visitas ?>">0</span>
            </div>
            <div class="footer-copy">
                <p>&copy; <?= date('Y') ?> KirbyStone · Eder Omar. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <!-- ==================== FIN PIE DE PAGINA ==================== -->

    <script src="js.js"></script>
</body>
</html>