<?php
$titulo = "Mis Proyectos";
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
    <div class="container">
        <h1><?= htmlspecialchars($titulo) ?></h1>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script>
        // Animación del título
        anime({
            targets: 'h1',
            translateY: [-50, 0],
            opacity: [0, 1],
            duration: 1200,
            easing: 'easeOutExpo'
        });

        // Animación de las tarjetas
        anime({
            targets: '.proyecto-card',
            translateY: [100, 0],
            opacity: [0, 1],
            delay: anime.stagger(100, {start: 300}),
            duration: 1000,
            easing: 'easeOutExpo'
        });



        // Animación del botón
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
    </script>
</body>
</html>