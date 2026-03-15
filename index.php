<?php
$titulo = "KirbyStone";

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
    <link rel="icon" href="kirby.ico" type="image/x-icon">
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
                    <p>Hola, soy <strong>Eder Omar</strong>, Técnico Superior Universitario en Desarrollo de Software Multiplataforma. <br>
                        Cuento con experiencia en la creación de aplicaciones web enfocadas a servicios, aplicaciones de escritorio y aplicaciones móviles.
                         También tengo experiencia en el uso de servicios de AWS, como EC2, para el alojamiento de sistemas web en la nube.</p>
                </div>
                <div class="sobre-mi-stats">
                    <div class="stat-item">
                        <span class="stat-numero"><?= count($proyectos) ?></span>
                        <span class="stat-label">Proyectos</span>
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
                            <a class="proyecto-btn" href="<?= htmlspecialchars($proyecto['nombre']) ?>/" target="_blank" rel="noopener">
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
            <p class="footer-texto">Mi portafolio está en crecimiento y estoy buscando nuevos proyectos técnicos para desarrollar. 
                Si necesitas una solución de software o automatización contactame.</p>
            <div class="footer-links">
                <a href="https://github.com/KirbyStone69" target="_blank" class="footer-link">
                    <img src="img/github.svg" alt="GitHub" class="footer-link-icon-img"> GitHub
                </a>

                <a href="https://wa.me/+528342186956" target="_blank" class="footer-link">
                    <img src="img/whatsapp.svg" alt="WhatsApp" class="footer-link-icon-img"> WhatsApp
                </a>
                <a href="https://www.linkedin.com" target="_blank" class="footer-link">
                    <img src="img/linkedin.svg" alt="LinkedIn" class="footer-link-icon-img"> LinkedIn
                </a>
                <a class="footer-link">
                    <img src="img/gmail.svg" alt="Gmail personal" class="footer-link-icon-img"> Gmail Personal : edeveloco@gmail.com
                </a>
                <a class="footer-link">
                    <img src="img/gmail.svg" alt="Gmail institucional" class="footer-link-icon-img"> Gmail institucional : 2430206@upv.edu.mx
                </a>
            </div>
            <div class="footer-visitas">
                <span id="contador-visitas" data-total="<?= $visitas ?>">0</span> visitas
            </div>
            <div class="footer-copy">
                <p>&copy; <?= date('Y') ?> KirbyStone. Todos los derechos reservados. | Powered by Heroku</p>
            </div>
        </div>
    </footer>
    <!-- ==================== FIN PIE DE PAGINA ==================== -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="js.js"></script>
</body>
</html>