-- ═══════════════════════════════════════════════════════════
-- Malleus Codeficarum — Schema de Base de Datos
-- Motor: MySQL / MariaDB
-- ═══════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS malleus_codeficarum
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE malleus_codeficarum;

-- ── Tabla de usuarios ──
CREATE TABLE IF NOT EXISTS usuarios (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100)  NOT NULL,
    email           VARCHAR(150)  NOT NULL UNIQUE,
    password_hash   VARCHAR(255)  NOT NULL,
    fecha_registro  DATETIME      DEFAULT CURRENT_TIMESTAMP,
    activo          BOOLEAN       DEFAULT TRUE,

    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ── Catálogo de lecciones ──
CREATE TABLE IF NOT EXISTS lecciones (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    tema     VARCHAR(100) NOT NULL,        -- Ej: "Git"
    carpeta  VARCHAR(100) NOT NULL,        -- Ej: "Bases de Git"
    archivo  VARCHAR(150) NOT NULL,        -- Ej: "que-es-git.html"
    titulo   VARCHAR(200) NOT NULL,
    tipo     ENUM('teoria', 'taller') DEFAULT 'teoria',

    UNIQUE KEY uq_leccion (tema, carpeta, archivo)
) ENGINE=InnoDB;

-- ── Puntuaciones por quiz completado ──
CREATE TABLE IF NOT EXISTS puntuaciones (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id       INT       NOT NULL,
    leccion_id       INT       NOT NULL,
    puntaje          INT       NOT NULL,          -- Respuestas correctas
    total_preguntas  INT       NOT NULL,          -- Total de preguntas
    porcentaje       DECIMAL(5,2) GENERATED ALWAYS AS
                     (puntaje * 100.0 / total_preguntas) STORED,
    fecha            DATETIME  DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (leccion_id) REFERENCES lecciones(id) ON DELETE CASCADE,

    INDEX idx_usuario   (usuario_id),
    INDEX idx_leccion   (leccion_id),
    INDEX idx_usr_lec   (usuario_id, leccion_id)
) ENGINE=InnoDB;

-- ═══════════════════════════════════════════════════════════
-- Datos iniciales: lecciones del módulo Git
-- ═══════════════════════════════════════════════════════════

INSERT INTO lecciones (tema, carpeta, archivo, titulo, tipo) VALUES
    ('Git', 'Bases de Git', 'que-es-git.html',                  '¿Qué es Git y para qué sirve?',               'teoria'),
    ('Git', 'Bases de Git', 'instalacion-y-configuracion.html',  'Instalación y Configuración de Git',           'taller'),
    ('Git', 'Bases de Git', 'repositorios-init-clone.html',      'Repositorios: init y clone',                   'taller'),
    ('Git', 'Bases de Git', 'staging-y-commits.html',            'Staging Area y Commits',                       'teoria'),
    ('Git', 'Bases de Git', 'ramas-y-merge.html',                'Branches y Merge',                             'teoria'),
    ('Git', 'Bases de Git', 'remotos-push-pull.html',            'Repositorios Remotos: push, pull y fetch',     'taller'),
    ('Git', 'Bases de Git', 'github-vs-gitlab.html',             'GitHub vs GitLab',                             'teoria');
