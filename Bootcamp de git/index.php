<?php
/**
 * Malleus Codeficarum — Índice dinámico
 * Lee la estructura de carpetas del proyecto y genera el menú de navegación
 * automáticamente. Al agregar un nuevo .html en cualquier carpeta,
 * aparece solo en el menú.
 */

// ── Configuración de temas ──
// Cada tema tiene: carpeta raíz, ícono Bootstrap, y color accent
$temas = [
    'Git' => [
        'icon'  => 'bi-git',
        'color' => '#f97316',
    ],
    // Futuros módulos:
    // 'HTML-CSS'    => ['icon' => 'bi-filetype-html', 'color' => '#e44d26'],
    // 'JavaScript'  => ['icon' => 'bi-filetype-js',   'color' => '#f0db4f'],
    // 'SQL'         => ['icon' => 'bi-database',       'color' => '#336791'],
];

/**
 * Escanea una carpeta de tema y devuelve su estructura.
 * Retorna: [ 'nombre_carpeta' => [ ['file'=>..., 'title'=>..., 'tag'=>...], ... ], ... ]
 */
function escanear_tema(string $ruta_tema): array {
    $estructura = [];

    if (!is_dir($ruta_tema)) return $estructura;

    $subcarpetas = array_filter(scandir($ruta_tema), function($item) use ($ruta_tema) {
        return $item !== '.' && $item !== '..' && is_dir($ruta_tema . '/' . $item);
    });

    foreach ($subcarpetas as $carpeta) {
        $ruta_carpeta = $ruta_tema . '/' . $carpeta;
        $lecciones = [];

        $archivos = array_filter(scandir($ruta_carpeta), function($item) {
            return pathinfo($item, PATHINFO_EXTENSION) === 'html';
        });

        foreach ($archivos as $archivo) {
            $ruta_archivo = $ruta_carpeta . '/' . $archivo;
            $contenido = file_get_contents($ruta_archivo);

            // Extraer título del <title>
            $titulo = $archivo; // fallback
            if (preg_match('/<title>(.*?)(?:\s*—.*)?<\/title>/i', $contenido, $m)) {
                $titulo = trim($m[1]);
                // Limpiar sufijo "— Malleus Codeficarum"
                $titulo = preg_replace('/\s*—\s*Malleus\s+Codeficarum$/i', '', $titulo);
            }

            // Detectar tipo de lección
            $tag = 'teoria';
            if (preg_match('/sim-fullpage-body/i', $contenido)) {
                $tag = 'laboratorio';
            } elseif (preg_match('/Evaluación Final/i', $contenido)) {
                $tag = 'evaluacion';
            } elseif (preg_match('/tag-workshop/i', $contenido)) {
                $tag = 'taller';
            }

            $lecciones[] = [
                'file'  => $archivo,
                'title' => $titulo,
                'tag'   => $tag,
                'path'  => $ruta_carpeta . '/' . $archivo,
            ];
        }

        // Ordenar lecciones por un orden lógico predefinido o alfabético
        $orden = [
            'que-es-git.html',
            'instalacion-y-configuracion.html',
            'repositorios-init-clone.html',
            'repositorios-init-clone-practica.html',
            'staging-y-commits.html',
            'staging-y-commits-practica.html',
            'ramas-y-merge.html',
            'ramas-y-merge-practica.html',
            'remotos-push-pull.html',
            'remotos-push-pull-practica.html',
            'evaluacion.html',
        ];

        usort($lecciones, function($a, $b) use ($orden) {
            $posA = array_search($a['file'], $orden);
            $posB = array_search($b['file'], $orden);
            if ($posA === false) $posA = 999;
            if ($posB === false) $posB = 999;
            return $posA - $posB;
        });

        $estructura[$carpeta] = $lecciones;
    }

    return $estructura;
}

// ── Escanear todos los temas ──
$dir_base = __DIR__;
$datos_temas = [];

foreach ($temas as $nombre_tema => $config) {
    $ruta = $dir_base . '/' . $nombre_tema;
    $datos_temas[$nombre_tema] = [
        'config'     => $config,
        'estructura' => escanear_tema($ruta),
    ];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Malleus Codeficarum — portal de código y desarrollo. Bootcamp interactivo para aprender Git y más.">
    <title>Malleus Codeficarum</title>

    <!-- Google Fonts — Fira Code (monospace / código) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="static/style/styles.css">
</head>

<body>

    <!-- ===== Header / Navbar ===== -->
    <nav class="navbar navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <span class="brand-symbol">&gt;_</span>Malleus Codeficarum
            </a>
        </div>
    </nav>

    <!-- ===== Hero Section ===== -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-card">
                <h1>&gt;_ Malleus Codeficarum<span class="cursor-blink"></span></h1>
                <p class="tagline">// el grimorio del código</p>
            </div>
        </div>
    </section>

    <!-- ===== Bootcamp / Roadmap Menu (Generado dinámicamente) ===== -->
    <section class="roadmap-section">
        <div class="container">

            <?php foreach ($datos_temas as $nombre_tema => $tema): ?>
            <?php
                $tema_id = 'topic' . preg_replace('/[^a-zA-Z0-9]/', '', $nombre_tema);
                $icon = $tema['config']['icon'];
                $total_lecciones = 0;
                foreach ($tema['estructura'] as $lecciones) {
                    $total_lecciones += count($lecciones);
                }
            ?>
            <!-- ── <?= htmlspecialchars($nombre_tema) ?> (Tema principal) ── -->
            <div class="roadmap-topic">

                <!-- Encabezado del tema -->
                <button class="roadmap-topic-header" type="button" data-bs-toggle="collapse"
                    data-bs-target="#<?= $tema_id ?>" aria-expanded="true" aria-controls="<?= $tema_id ?>">
                    <span class="topic-icon"><i class="bi <?= $icon ?>"></i></span>
                    <span class="topic-title"><?= htmlspecialchars($nombre_tema) ?></span>
                    <span class="topic-chevron"><i class="bi bi-chevron-up"></i></span>
                </button>

                <!-- Contenido colapsable del tema -->
                <div class="collapse show" id="<?= $tema_id ?>">

                    <?php foreach ($tema['estructura'] as $nombre_carpeta => $lecciones): ?>
                    <?php
                        $carpeta_id = 'folder' . preg_replace('/[^a-zA-Z0-9]/', '', $nombre_carpeta);
                        $num_lecciones = count($lecciones);
                    ?>
                    <!-- ── <?= htmlspecialchars($nombre_carpeta) ?> (Sub-carpeta) ── -->
                    <div class="roadmap-folder">
                        <button class="roadmap-folder-header" type="button" data-bs-toggle="collapse"
                            data-bs-target="#<?= $carpeta_id ?>" aria-expanded="true" aria-controls="<?= $carpeta_id ?>">
                            <span class="folder-chevron"><i class="bi bi-chevron-down"></i></span>
                            <span class="folder-icon"><i class="bi bi-folder2-open"></i></span>
                            <span class="folder-title"><?= htmlspecialchars($nombre_carpeta) ?></span>
                            <span class="folder-badge badge"><?= $num_lecciones ?> <?= $num_lecciones === 1 ? 'lección' : 'lecciones' ?></span>
                        </button>

                        <div class="collapse show" id="<?= $carpeta_id ?>">
                            <ul class="roadmap-lessons">
                                <?php foreach ($lecciones as $leccion): ?>
                                <?php
                                    $href = htmlspecialchars($nombre_tema . '/' . $nombre_carpeta . '/' . $leccion['file']);
                                    $tag_class = 'tag-theory';
                                    $tag_label = 'Teoría';
                                    if ($leccion['tag'] === 'evaluacion') {
                                        $tag_class = 'tag-exam';
                                        $tag_label = 'Evaluación';
                                    } elseif ($leccion['tag'] === 'taller') {
                                        $tag_class = 'tag-workshop';
                                        $tag_label = 'Taller';
                                    } elseif ($leccion['tag'] === 'laboratorio') {
                                        $tag_class = 'tag-lab';
                                        $tag_label = 'Laboratorio';
                                    }
                                ?>
                                <li>
                                    <a href="<?= $href ?>">
                                        <span class="lesson-dot"></span>
                                        <?= htmlspecialchars($leccion['title']) ?>
                                        <span class="lesson-tag <?= $tag_class ?>"><?= $tag_label ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- /<?= htmlspecialchars($nombre_carpeta) ?> -->

                    <?php endforeach; ?>

                </div>
            </div>
            <!-- /<?= htmlspecialchars($nombre_tema) ?> -->

            <?php endforeach; ?>

        </div>
    </section>

    <!-- ===== PDF Viewer Section ===== -->
    <section class="pdf-section">
        <div class="container">
            <h2 class="pdf-title">Proyecto de Investigación</h2>
            <div id="pdf-viewer">
                <a href="Proyecto_de_investigación.pdf" target="_blank" class="btn btn-primary btn-lg">
                    <i class="bi bi-file-pdf"></i> Ver PDF en navegador
                </a>
                <a href="Proyecto_de_investigación.pdf" download class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-download"></i> Descargar PDF
                </a>
            </div>
        </div>
    </section>

    <!-- ===== Footer ===== -->
    <footer class="site-footer">
        <div class="container">
            <p>&gt;_ Malleus Codeficarum <span class="footer-separator">|</span> El grimorio del código</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="static/js/main.js"></script>
</body>

</html>
