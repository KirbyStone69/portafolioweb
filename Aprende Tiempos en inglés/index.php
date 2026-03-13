<?php
// tense quest - juego unificado de tiempos verbales en ingles
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tense Quest - Aprende Inglés</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Particulas de fondo -->
    <div class="particles-bg" id="particles-bg"></div>

    <!-- Pantalla de Inicio -->
    <div id="screen-start" class="screen active">
        <div class="container">
            <div class="start-wrapper">
                <!-- Hero Section -->
                <div class="hero-section">
                    <div class="hero-badge">
                        <i class="bi bi-translate"></i>
                        <span>Aprende Inglés Jugando</span>
                    </div>
                    <div class="hero-icon">
                        <div class="icon-ring"></div>
                        <div class="icon-ring delay-1"></div>
                        <div class="icon-ring delay-2"></div>
                        <i class="bi bi-book-half"></i>
                    </div>
                    <h1 class="hero-title">Tense Quest</h1>
                    <p class="hero-subtitle">Domina los <span class="highlight">12 Tiempos Verbales</span> del Inglés</p>
                </div>

                <!-- Tenses Grid Preview -->
                <div class="tenses-grid-preview">
                    <div class="tense-group present">
                        <div class="group-header">
                            <i class="bi bi-sun-fill"></i>
                            <span>Present</span>
                        </div>
                        <div class="group-items">
                            <span>Simple</span>
                            <span>Continuous</span>
                            <span>Perfect</span>
                            <span>Perfect Cont.</span>
                        </div>
                    </div>
                    <div class="tense-group past">
                        <div class="group-header">
                            <i class="bi bi-hourglass-split"></i>
                            <span>Past</span>
                        </div>
                        <div class="group-items">
                            <span>Simple</span>
                            <span>Continuous</span>
                            <span>Perfect</span>
                            <span>Perfect Cont.</span>
                        </div>
                    </div>
                    <div class="tense-group future">
                        <div class="group-header">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                            <span>Future</span>
                        </div>
                        <div class="group-items">
                            <span>Simple</span>
                            <span>Continuous</span>
                            <span>Perfect</span>
                            <span>Perfect Cont.</span>
                        </div>
                    </div>
                </div>

                <!-- Game Mode Cards -->
                <div class="mode-section">
                    <h3 class="section-label">Elige tu Modo de Juego</h3>
                    <div class="game-modes">
                        <div class="mode-card" id="mode-quick" data-mode="quick">
                            <div class="mode-icon">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div class="mode-info">
                                <h4>Juego Rápido</h4>
                                <p>15 preguntas aleatorias</p>
                            </div>
                            <div class="mode-badge">~5 min</div>
                        </div>
                        <div class="mode-card" id="mode-medium" data-mode="medium">
                            <div class="mode-icon">
                                <i class="bi bi-stars"></i>
                            </div>
                            <div class="mode-info">
                                <h4>Juego Normal</h4>
                                <p>30 preguntas variadas</p>
                            </div>
                            <div class="mode-badge">~10 min</div>
                        </div>
                        <div class="mode-card" id="mode-full" data-mode="full">
                            <div class="mode-icon">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                            <div class="mode-info">
                                <h4>Juego Completo</h4>
                                <p>120 preguntas (todas)</p>
                            </div>
                            <div class="mode-badge">~30 min</div>
                        </div>
                    </div>
                </div>

                <!-- Difficulty Section -->
                <div class="difficulty-section">
                    <h3 class="section-label">Nivel de Dificultad</h3>
                    <div class="difficulty-options">
                        <label class="difficulty-option">
                            <input type="radio" name="difficulty" value="easy">
                            <div class="option-content">
                                <i class="bi bi-emoji-smile"></i>
                                <span>Fácil</span>
                                <small>Sin límite de tiempo</small>
                            </div>
                        </label>
                        <label class="difficulty-option">
                            <input type="radio" name="difficulty" value="medium" checked>
                            <div class="option-content">
                                <i class="bi bi-emoji-neutral"></i>
                                <span>Normal</span>
                                <small>30 seg por pregunta</small>
                            </div>
                        </label>
                        <label class="difficulty-option">
                            <input type="radio" name="difficulty" value="hard">
                            <div class="option-content">
                                <i class="bi bi-emoji-angry"></i>
                                <span>Difícil</span>
                                <small>15 seg por pregunta</small>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="stats-card" id="stats-card" style="display: none;">
                    <div class="stats-header">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>Tus Estadísticas</span>
                    </div>
                    <div class="stats-row">
                        <div class="stat-mini">
                            <span class="stat-num" id="best-score">0%</span>
                            <span class="stat-txt">Mejor</span>
                        </div>
                        <div class="stat-mini">
                            <span class="stat-num" id="games-played">0</span>
                            <span class="stat-txt">Partidas</span>
                        </div>
                        <div class="stat-mini">
                            <span class="stat-num" id="total-correct">0</span>
                            <span class="stat-txt">Correctas</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="start-footer">
                    <p>Selecciona un modo de juego para comenzar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pantalla de Juego -->
    <div id="screen-game" class="screen">
        <div class="game-container">
            <!-- Header del juego -->
            <div class="game-header">
                <button id="btn-quit" class="btn-icon" title="Salir">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="header-center">
                    <div class="question-num">
                        <span id="current-question">1</span>
                        <span class="separator">/</span>
                        <span id="total-questions">15</span>
                    </div>
                </div>
                <div class="score-display">
                    <div class="score-item correct">
                        <i class="bi bi-check-circle-fill"></i>
                        <span id="correct-count">0</span>
                    </div>
                    <div class="score-item wrong">
                        <i class="bi bi-x-circle-fill"></i>
                        <span id="wrong-count">0</span>
                    </div>
                </div>
            </div>

            <!-- Barra de progreso -->
            <div class="progress-section">
                <div class="progress-bar-custom">
                    <div class="progress-fill" id="question-progress"></div>
                </div>
            </div>

            <!-- Timer -->
            <div class="timer-section" id="timer-section" style="display: none;">
                <div class="timer-circle">
                    <svg viewBox="0 0 100 100">
                        <circle class="timer-bg" cx="50" cy="50" r="45"/>
                        <circle class="timer-fill" id="timer-circle" cx="50" cy="50" r="45"/>
                    </svg>
                    <span class="timer-text" id="timer-text">30</span>
                </div>
            </div>

            <!-- Tense Badge -->
            <div class="tense-section">
                <div class="tense-badge-wrapper">
                    <span class="tense-badge" id="current-tense-badge">Present Simple</span>
                </div>
                <div class="tense-formula" id="tense-formula">Subject + V(s/es)</div>
            </div>

            <!-- Question Card -->
            <div class="question-section">
                <div class="question-card" id="question-card">
                    <div class="question-type" id="question-type">
                        <i class="bi bi-pencil-fill"></i>
                        Completa la oración
                    </div>
                    <div class="question-text" id="question-text">
                        She ___ (play) tennis every Sunday.
                    </div>
                </div>
            </div>

            <!-- Answers -->
            <div class="answers-section">
                <div class="answers-grid" id="answers-container">
                    <!-- Se generan dinámicamente -->
                </div>
            </div>

            <!-- Feedback -->
            <div class="feedback-section" id="feedback" style="display: none;">
                <div class="feedback-card">
                    <div class="feedback-header" id="feedback-header">
                        <div class="feedback-icon" id="feedback-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h3 id="feedback-text">¡Correcto!</h3>
                    </div>
                    <div class="feedback-body">
                        <p id="feedback-explanation">Explicación aquí...</p>
                    </div>
                    <button id="btn-next" class="btn-next">
                        <span>Siguiente</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pantalla de Resultados -->
    <div id="screen-results" class="screen">
        <div class="container">
            <div class="results-wrapper">
                <!-- Resultado Principal -->
                <div class="results-hero">
                    <div class="results-badge" id="results-badge">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <h1 id="results-title">¡Excelente!</h1>
                    <p id="results-subtitle">Has completado el quiz</p>
                </div>

                <!-- Círculo de Puntuación -->
                <div class="score-circle-wrapper">
                    <div class="score-circle">
                        <svg viewBox="0 0 200 200">
                            <defs>
                                <linearGradient id="scoreGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#10b981"/>
                                    <stop offset="50%" style="stop-color:#8b5cf6"/>
                                    <stop offset="100%" style="stop-color:#3b82f6"/>
                                </linearGradient>
                            </defs>
                            <circle class="score-bg" cx="100" cy="100" r="85"/>
                            <circle class="score-fill" id="score-fill" cx="100" cy="100" r="85"/>
                        </svg>
                        <div class="score-content">
                            <span class="score-percent" id="percentage">0</span>
                            <span class="score-label">%</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="results-stats">
                    <div class="result-stat correct">
                        <i class="bi bi-check-circle-fill"></i>
                        <span class="stat-value" id="final-correct">0</span>
                        <span class="stat-label">Correctas</span>
                    </div>
                    <div class="result-stat wrong">
                        <i class="bi bi-x-circle-fill"></i>
                        <span class="stat-value" id="final-wrong">0</span>
                        <span class="stat-label">Incorrectas</span>
                    </div>
                    <div class="result-stat time">
                        <i class="bi bi-clock-fill"></i>
                        <span class="stat-value" id="total-time">0:00</span>
                        <span class="stat-label">Tiempo</span>
                    </div>
                    <div class="result-stat accuracy">
                        <i class="bi bi-bullseye"></i>
                        <span class="stat-value" id="accuracy">0%</span>
                        <span class="stat-label">Precisión</span>
                    </div>
                </div>

                <!-- Breakdown por Tense -->
                <div class="breakdown-section">
                    <h3>
                        <i class="bi bi-graph-up"></i>
                        Resultados por Tiempo Verbal
                    </h3>
                    <div class="breakdown-grid" id="breakdown-grid">
                        <!-- Se genera dinámicamente -->
                    </div>
                </div>

                <!-- Acciones -->
                <div class="results-actions">
                    <button id="btn-play-again" class="btn-primary-game">
                        <i class="bi bi-arrow-repeat"></i>
                        Jugar de Nuevo
                    </button>
                    <button id="btn-home" class="btn-secondary-game">
                        <i class="bi bi-house-fill"></i>
                        Inicio
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Salir -->
    <div class="modal fade" id="quitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-dark">
                <div class="modal-header">
                    <div class="modal-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h5 class="modal-title">¿Salir del juego?</h5>
                </div>
                <div class="modal-body">
                    <p>Perderás todo tu progreso actual en esta partida.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-modal-confirm" id="btn-confirm-quit">Salir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confetti Container -->
    <div id="confetti-container"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Anime.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <!-- Game JS -->
    <script src="js/game.js"></script>
</body>
</html>