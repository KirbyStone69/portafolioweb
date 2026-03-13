/**
 * TENSE QUEST - Motor del juego mejorado
 * Todas las preguntas mezcladas en un solo quiz
 */

// ========================================
// ESTADO DEL JUEGO
// ========================================
const GameState = {
    questions: null,
    allQuestions: [],
    currentQuestions: [],
    currentQuestionIndex: 0,
    correctAnswers: 0,
    wrongAnswers: 0,
    gameMode: 'quick',
    difficulty: 'medium',
    startTime: null,
    timerInterval: null,
    autoAdvanceTimeout: null,
    autoAdvanceInterval: null,
    currentTime: 0,
    tenseStats: {},
    stats: {
        gamesPlayed: 0,
        totalCorrect: 0,
        bestScore: 0
    }
};

// ========================================
// CONSTANTES
// ========================================
const QUESTIONS_BY_MODE = {
    quick: 15,
    medium: 30,
    full: 120
};

const TIME_LIMITS = {
    easy: 0,
    medium: 60,
    hard: 60
};

const AUTO_ADVANCE_SECONDS = 5;

// Audio
const AUDIO = {
    correct: new Audio('assets/aprobado.mp3'),
    wrong: new Audio('assets/reprobado.mp3')
};

// Pre-cargar audios
Object.values(AUDIO).forEach(audio => {
    audio.load();
    audio.volume = 0.7;
});

// ========================================
// INICIALIZACION
// ========================================
document.addEventListener('DOMContentLoaded', async () => {
    await loadQuestions();
    loadStats();
    setupEventListeners();
    createParticles();
});

async function loadQuestions() {
    try {
        const response = await fetch('data/questions.json');
        const data = await response.json();
        GameState.questions = data;

        // Aplanar todas las preguntas
        GameState.allQuestions = [];
        for (const [tenseKey, tense] of Object.entries(data.tenses)) {
            tense.questions.forEach((q) => {
                GameState.allQuestions.push({
                    ...q,
                    tenseKey: tenseKey,
                    tenseName: tense.name,
                    tenseRealm: tense.realm,
                    tenseFormula: tense.formula
                });
            });
        }

        console.log('✓ Cargadas', GameState.allQuestions.length, 'preguntas');
    } catch (error) {
        console.error('Error cargando preguntas:', error);
    }
}

function loadStats() {
    const saved = localStorage.getItem('tensequest_stats');
    if (saved) {
        const data = JSON.parse(saved);
        GameState.stats = data;
        updateStatsDisplay();
    }
}

function saveStats() {
    localStorage.setItem('tensequest_stats', JSON.stringify(GameState.stats));
    updateStatsDisplay();
}

function updateStatsDisplay() {
    const { bestScore, gamesPlayed, totalCorrect } = GameState.stats;

    document.getElementById('best-score').textContent = bestScore + '%';
    document.getElementById('games-played').textContent = gamesPlayed;
    document.getElementById('total-correct').textContent = totalCorrect;

    if (gamesPlayed > 0) {
        document.getElementById('stats-card').style.display = 'block';
    }
}

// ========================================
// PARTICULAS DE FONDO
// ========================================
function createParticles() {
    const container = document.getElementById('particles-bg');
    const particleCount = 30;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDuration = (15 + Math.random() * 20) + 's';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.opacity = 0.1 + Math.random() * 0.3;
        container.appendChild(particle);
    }
}

// ========================================
// EVENT LISTENERS
// ========================================
function setupEventListeners() {
    // Seleccion de modo
    document.querySelectorAll('.mode-card').forEach(card => {
        card.addEventListener('click', () => {
            const mode = card.dataset.mode;
            selectMode(mode);
        });
    });

    // Boton salir
    document.getElementById('btn-quit').addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('quitModal'));
        modal.show();
    });

    document.getElementById('btn-confirm-quit').addEventListener('click', () => {
        bootstrap.Modal.getInstance(document.getElementById('quitModal')).hide();
        stopTimer();
        showScreen('screen-start');
    });

    // Boton siguiente
    document.getElementById('btn-next').addEventListener('click', nextQuestion);

    // Resultados
    document.getElementById('btn-play-again').addEventListener('click', () => {
        startGame(GameState.gameMode);
    });

    document.getElementById('btn-home').addEventListener('click', () => {
        showScreen('screen-start');
    });

    // Hover en modo cards
    document.querySelectorAll('.mode-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            anime({
                targets: card.querySelector('.mode-icon'),
                scale: [1, 1.1],
                duration: 200,
                easing: 'easeOutQuad'
            });
        });

        card.addEventListener('mouseleave', () => {
            anime({
                targets: card.querySelector('.mode-icon'),
                scale: [1.1, 1],
                duration: 200,
                easing: 'easeOutQuad'
            });
        });
    });
}

function selectMode(mode) {
    GameState.gameMode = mode;

    // Actualizar UI
    document.querySelectorAll('.mode-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelector(`[data-mode="${mode}"]`).classList.add('selected');

    // Obtener dificultad
    const diffInput = document.querySelector('input[name="difficulty"]:checked');
    GameState.difficulty = diffInput ? diffInput.value : 'medium';

    // Animacion y comenzar
    anime({
        targets: '.start-wrapper',
        opacity: [1, 0],
        translateY: [0, -20],
        duration: 300,
        easing: 'easeInQuad',
        complete: () => {
            startGame(mode);
        }
    });
}

// ========================================
// INICIO DEL JUEGO
// ========================================
function startGame(mode) {
    // Reiniciar estado
    GameState.currentQuestionIndex = 0;
    GameState.correctAnswers = 0;
    GameState.wrongAnswers = 0;
    GameState.tenseStats = {};
    GameState.startTime = Date.now();

    // Preparar preguntas
    let questions = [...GameState.allQuestions];
    shuffleArray(questions);

    const questionCount = QUESTIONS_BY_MODE[mode] || 15;
    questions = questions.slice(0, Math.min(questionCount, questions.length));

    GameState.currentQuestions = questions;

    // Configurar UI
    document.getElementById('total-questions').textContent = GameState.currentQuestions.length;
    document.getElementById('correct-count').textContent = '0';
    document.getElementById('wrong-count').textContent = '0';

    // Timer
    const timeLimit = TIME_LIMITS[GameState.difficulty];
    const timerSection = document.getElementById('timer-section');
    if (timeLimit > 0) {
        timerSection.style.display = 'flex';
    } else {
        timerSection.style.display = 'none';
    }

    // Mostrar pantalla
    showScreen('screen-game');

    // Delay para animacion
    setTimeout(() => {
        showQuestion();
    }, 100);
}

// ========================================
// LOGICA DEL JUEGO
// ========================================
function showQuestion() {
    const question = GameState.currentQuestions[GameState.currentQuestionIndex];

    // Actualizar contador
    document.getElementById('current-question').textContent = GameState.currentQuestionIndex + 1;

    // Barra de progreso
    const progress = ((GameState.currentQuestionIndex + 1) / GameState.currentQuestions.length) * 100;
    document.getElementById('question-progress').style.width = `${progress}%`;

    // Badge del tiempo verbal
    const badge = document.getElementById('current-tense-badge');
    badge.textContent = question.tenseName;
    badge.className = `tense-badge ${question.tenseRealm}`;

    // Formula
    document.getElementById('tense-formula').textContent = question.tenseFormula;

    // Tipo y texto de pregunta
    document.getElementById('question-type').innerHTML = question.type === 'complete'
        ? '<i class="bi bi-pencil-fill"></i> Completa la oración'
        : '<i class="bi bi-check2-square"></i> Elige la correcta';
    document.getElementById('question-text').textContent = question.question;

    // Generar respuestas
    const container = document.getElementById('answers-container');
    container.innerHTML = '';

    question.options.forEach((option, index) => {
        const btn = document.createElement('button');
        btn.className = 'answer-btn';
        btn.textContent = option;
        btn.addEventListener('click', () => checkAnswer(index));
        container.appendChild(btn);
    });

    // Ocultar feedback
    document.getElementById('feedback').style.display = 'none';

    // Animaciones
    anime({
        targets: '#question-card',
        opacity: [0, 1],
        translateY: [20, 0],
        duration: 400,
        easing: 'easeOutQuad'
    });

    anime({
        targets: '.answer-btn',
        opacity: [0, 1],
        translateY: [15, 0],
        delay: anime.stagger(60, { start: 150 }),
        duration: 350,
        easing: 'easeOutQuad'
    });

    // Iniciar timer
    if (TIME_LIMITS[GameState.difficulty] > 0) {
        startTimer();
    }
}

function checkAnswer(selectedIndex) {
    stopTimer();

    const question = GameState.currentQuestions[GameState.currentQuestionIndex];
    const isCorrect = selectedIndex === question.correct;

    // Stats por tiempo verbal
    if (!GameState.tenseStats[question.tenseKey]) {
        GameState.tenseStats[question.tenseKey] = {
            name: question.tenseName,
            correct: 0,
            total: 0
        };
    }
    GameState.tenseStats[question.tenseKey].total++;

    // Actualizar botones
    document.querySelectorAll('.answer-btn').forEach((btn, index) => {
        btn.classList.add('disabled');
        if (index === question.correct) {
            btn.classList.add('correct');
            btn.classList.add('show-correct');
        } else if (index === selectedIndex && !isCorrect) {
            btn.classList.add('wrong');
        }
    });

    // Actualizar puntuacion
    if (isCorrect) {
        GameState.correctAnswers++;
        GameState.tenseStats[question.tenseKey].correct++;
        document.getElementById('correct-count').textContent = GameState.correctAnswers;

        // Reproducir audio
        playSound('correct');

        // Animacion explosiva +1
        createExplosivePointAnimation(true);

        // Animacion del score
        anime({
            targets: '.score-item.correct',
            scale: [1, 1.5, 1],
            duration: 600,
            easing: 'easeOutElastic(1, .5)'
        });

        // Particulas de celebracion
        createAnswerParticles(true);

        // Flash verde en pantalla
        createScreenFlash('#22c55e');
    } else {
        GameState.wrongAnswers++;
        document.getElementById('wrong-count').textContent = GameState.wrongAnswers;

        // Reproducir audio
        playSound('wrong');

        // Animacion explosiva -1
        createExplosivePointAnimation(false);

        // Shake intenso
        anime({
            targets: '#question-card',
            translateX: [-15, 15, -15, 15, -10, 10, 0],
            duration: 500,
            easing: 'easeInOutQuad'
        });

        // Flash rojo en pantalla
        createScreenFlash('#ef4444');
    }

    // Mostrar feedback
    showFeedback(isCorrect, question.explanation, question.options[question.correct]);
}

function timeOut() {
    stopTimer();

    const question = GameState.currentQuestions[GameState.currentQuestionIndex];

    // Stats
    if (!GameState.tenseStats[question.tenseKey]) {
        GameState.tenseStats[question.tenseKey] = {
            name: question.tenseName,
            correct: 0,
            total: 0
        };
    }
    GameState.tenseStats[question.tenseKey].total++;
    GameState.wrongAnswers++;
    document.getElementById('wrong-count').textContent = GameState.wrongAnswers;

    // Mostrar respuesta correcta
    document.querySelectorAll('.answer-btn').forEach((btn, index) => {
        btn.classList.add('disabled');
        if (index === question.correct) {
            btn.classList.add('correct');
            btn.classList.add('show-correct');
        }
    });

    showFeedback(false, '⏰ ¡Se acabó el tiempo! ' + question.explanation, question.options[question.correct]);
}

function showFeedback(isCorrect, explanation, correctAnswer) {
    const feedback = document.getElementById('feedback');
    const header = document.getElementById('feedback-header');
    const icon = document.getElementById('feedback-icon');
    const text = document.getElementById('feedback-text');
    const exp = document.getElementById('feedback-explanation');
    const btnNext = document.getElementById('btn-next');

    header.className = `feedback-header ${isCorrect ? 'correct' : 'wrong'}`;
    icon.innerHTML = isCorrect
        ? '<i class="bi bi-check-circle-fill"></i>'
        : '<i class="bi bi-x-circle-fill"></i>';
    text.textContent = isCorrect ? '¡Correcto!' : '¡Incorrecto!';

    if (!isCorrect) {
        exp.innerHTML = `<strong>Respuesta correcta:</strong> ${correctAnswer}<br><br>${explanation}`;
    } else {
        exp.textContent = explanation;
    }

    feedback.style.display = 'block';

    anime({
        targets: feedback,
        opacity: [0, 1],
        translateY: [20, 0],
        duration: 350,
        easing: 'easeOutQuad'
    });

    // Auto-avance despues de 5 segundos
    startAutoAdvance(btnNext);
}

function nextQuestion() {
    // Cancelar auto-avance si se hace click manual
    stopAutoAdvance();

    GameState.currentQuestionIndex++;

    if (GameState.currentQuestionIndex >= GameState.currentQuestions.length) {
        finishGame();
    } else {
        showQuestion();
    }
}

// ========================================
// AUTO-AVANCE
// ========================================
function startAutoAdvance(btnNext) {
    let secondsLeft = AUTO_ADVANCE_SECONDS;

    // Mostrar contador en el boton
    updateNextButtonText(btnNext, secondsLeft);

    // Actualizar cada segundo
    GameState.autoAdvanceInterval = setInterval(() => {
        secondsLeft--;
        updateNextButtonText(btnNext, secondsLeft);

        if (secondsLeft <= 0) {
            stopAutoAdvance();
            nextQuestion();
        }
    }, 1000);
}

function stopAutoAdvance() {
    if (GameState.autoAdvanceInterval) {
        clearInterval(GameState.autoAdvanceInterval);
        GameState.autoAdvanceInterval = null;
    }

    // Restaurar texto del boton
    const btnNext = document.getElementById('btn-next');
    btnNext.innerHTML = '<span>Siguiente</span><i class="bi bi-arrow-right"></i>';
}

function updateNextButtonText(btn, seconds) {
    btn.innerHTML = `<span>Siguiente (${seconds}s)</span><i class="bi bi-arrow-right"></i>`;
}

// ========================================
// TIMER
// ========================================
function startTimer() {
    const timeLimit = TIME_LIMITS[GameState.difficulty];
    if (timeLimit <= 0) return;

    GameState.currentTime = timeLimit;
    updateTimerDisplay(timeLimit, timeLimit);

    const circle = document.getElementById('timer-circle');
    const circumference = 2 * Math.PI * 45;
    circle.style.strokeDasharray = circumference;
    circle.style.strokeDashoffset = 0;
    circle.classList.remove('warning', 'danger');

    GameState.timerInterval = setInterval(() => {
        GameState.currentTime--;
        updateTimerDisplay(GameState.currentTime, timeLimit);

        // Actualizar circulo
        const offset = ((timeLimit - GameState.currentTime) / timeLimit) * circumference;
        circle.style.strokeDashoffset = offset;

        // Colores de alerta
        if (GameState.currentTime <= 5) {
            circle.classList.add('danger');
            circle.classList.remove('warning');
        } else if (GameState.currentTime <= 10) {
            circle.classList.add('warning');
        }

        if (GameState.currentTime <= 0) {
            timeOut();
        }
    }, 1000);
}

function stopTimer() {
    if (GameState.timerInterval) {
        clearInterval(GameState.timerInterval);
        GameState.timerInterval = null;
    }
}

function updateTimerDisplay(timeLeft, total) {
    document.getElementById('timer-text').textContent = timeLeft;
}

// ========================================
// FIN DEL JUEGO
// ========================================
function finishGame() {
    stopTimer();

    const total = GameState.currentQuestions.length;
    const percentage = Math.round((GameState.correctAnswers / total) * 100);
    const elapsed = Math.floor((Date.now() - GameState.startTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;

    // Actualizar estadisticas globales
    GameState.stats.gamesPlayed++;
    GameState.stats.totalCorrect += GameState.correctAnswers;
    if (percentage > GameState.stats.bestScore) {
        GameState.stats.bestScore = percentage;
    }
    saveStats();

    // UI de resultados
    document.getElementById('final-correct').textContent = GameState.correctAnswers;
    document.getElementById('final-wrong').textContent = GameState.wrongAnswers;
    document.getElementById('total-time').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    document.getElementById('accuracy').textContent = percentage + '%';

    // Titulo e icono basado en resultado
    const badge = document.getElementById('results-badge');
    const title = document.getElementById('results-title');
    const subtitle = document.getElementById('results-subtitle');

    if (percentage >= 90) {
        badge.innerHTML = '<i class="bi bi-trophy-fill"></i>';
        badge.style.background = 'linear-gradient(135deg, #fbbf24, #f59e0b)';
        title.textContent = '¡Excelente!';
        subtitle.textContent = 'Dominas los tiempos verbales';
    } else if (percentage >= 70) {
        badge.innerHTML = '<i class="bi bi-emoji-smile-fill"></i>';
        badge.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        title.textContent = '¡Muy Bien!';
        subtitle.textContent = 'Buen conocimiento gramatical';
    } else if (percentage >= 50) {
        badge.innerHTML = '<i class="bi bi-emoji-neutral-fill"></i>';
        badge.style.background = 'linear-gradient(135deg, #fbbf24, #f59e0b)';
        title.textContent = '¡Bien!';
        subtitle.textContent = 'Sigue practicando';
    } else {
        badge.innerHTML = '<i class="bi bi-emoji-frown-fill"></i>';
        badge.style.background = 'linear-gradient(135deg, #8b5cf6, #7c3aed)';
        title.textContent = '¡A Practicar!';
        subtitle.textContent = 'No te rindas, mejora con práctica';
    }

    // Breakdown
    generateBreakdown();

    // Mostrar pantalla
    showScreen('screen-results');

    // Animar circulo
    setTimeout(() => {
        animateScoreCircle(percentage);
    }, 400);

    // Confetti si excelente
    if (percentage >= 80) {
        setTimeout(() => createConfetti(), 500);
    }
}

function generateBreakdown() {
    const grid = document.getElementById('breakdown-grid');
    grid.innerHTML = '';

    // Ordenar por nombre
    const entries = Object.entries(GameState.tenseStats).sort((a, b) =>
        a[1].name.localeCompare(b[1].name)
    );

    for (const [key, stats] of entries) {
        const pct = Math.round((stats.correct / stats.total) * 100);
        let scoreClass = 'poor';
        if (pct >= 80) scoreClass = 'perfect';
        else if (pct >= 50) scoreClass = 'good';

        const item = document.createElement('div');
        item.className = 'breakdown-item';
        item.innerHTML = `
            <span class="tense-name">${stats.name}</span>
            <span class="tense-score ${scoreClass}">${stats.correct}/${stats.total}</span>
        `;
        grid.appendChild(item);
    }
}

function animateScoreCircle(percentage) {
    const circle = document.getElementById('score-fill');
    const circumference = 2 * Math.PI * 85;
    const offset = circumference - (percentage / 100) * circumference;

    circle.style.strokeDasharray = circumference;
    circle.style.strokeDashoffset = circumference;

    anime({
        targets: circle,
        strokeDashoffset: offset,
        easing: 'easeOutQuad',
        duration: 1500
    });

    anime({
        targets: '#percentage',
        innerHTML: [0, percentage],
        round: 1,
        easing: 'easeOutQuad',
        duration: 1500
    });
}

// ========================================
// NAVEGACION
// ========================================
function showScreen(screenId) {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));

    const screen = document.getElementById(screenId);
    screen.classList.add('active');

    // Resetear animacion del wrapper si volvemos a inicio
    if (screenId === 'screen-start') {
        const wrapper = screen.querySelector('.start-wrapper');
        if (wrapper) {
            wrapper.style.opacity = 1;
            wrapper.style.transform = 'translateY(0)';
        }
    }

    anime({
        targets: `#${screenId}`,
        opacity: [0, 1],
        duration: 400,
        easing: 'easeOutQuad'
    });
}

// ========================================
// ANIMACIONES
// ========================================
function createAnswerParticles(isCorrect) {
    const container = document.getElementById('confetti-container');
    const color = isCorrect ? '#22c55e' : '#ef4444';

    for (let i = 0; i < 12; i++) {
        const particle = document.createElement('div');
        particle.className = 'confetti';
        particle.style.backgroundColor = color;
        particle.style.left = '50%';
        particle.style.top = '50%';
        particle.style.width = '8px';
        particle.style.height = '8px';
        container.appendChild(particle);

        anime({
            targets: particle,
            translateX: () => anime.random(-120, 120),
            translateY: () => anime.random(-120, 120),
            scale: [1, 0],
            opacity: [1, 0],
            easing: 'easeOutQuad',
            duration: 700,
            complete: () => particle.remove()
        });
    }
}

function createConfetti() {
    const container = document.getElementById('confetti-container');
    const colors = ['#10b981', '#8b5cf6', '#3b82f6', '#fbbf24', '#ec4899', '#22c55e'];

    for (let i = 0; i < 100; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.top = '-15px';
        confetti.style.width = (5 + Math.random() * 8) + 'px';
        confetti.style.height = confetti.style.width;
        container.appendChild(confetti);

        anime({
            targets: confetti,
            translateY: window.innerHeight + 50,
            translateX: () => anime.random(-60, 60),
            rotate: () => anime.random(0, 720),
            duration: () => anime.random(2000, 3500),
            delay: () => anime.random(0, 600),
            easing: 'linear',
            complete: () => confetti.remove()
        });
    }
}

// ========================================
// UTILIDADES
// ========================================
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

// ========================================
// AUDIO
// ========================================
function playSound(type) {
    try {
        const audio = AUDIO[type];
        if (audio) {
            audio.currentTime = 0;
            audio.play().catch(() => {
                // Ignorar errores de autoplay
            });
        }
    } catch (e) {
        console.log('Audio no disponible');
    }
}

// ========================================
// ANIMACIONES EXPLOSIVAS TIPO ANIME
// ========================================
function createExplosivePointAnimation(isCorrect) {
    const container = document.getElementById('confetti-container');

    // Crear el elemento del punto (+1 o -1)
    const pointElement = document.createElement('div');
    pointElement.className = 'explosive-point';
    pointElement.textContent = isCorrect ? '+1' : '-1';
    pointElement.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 0;
        font-weight: 900;
        font-family: 'Outfit', sans-serif;
        color: ${isCorrect ? '#22c55e' : '#ef4444'};
        text-shadow: 
            0 0 20px ${isCorrect ? '#22c55e' : '#ef4444'},
            0 0 40px ${isCorrect ? '#22c55e' : '#ef4444'},
            0 0 60px ${isCorrect ? '#22c55e' : '#ef4444'},
            0 0 80px ${isCorrect ? 'rgba(34, 197, 94, 0.5)' : 'rgba(239, 68, 68, 0.5)'};
        z-index: 10000;
        pointer-events: none;
        letter-spacing: -5px;
    `;
    container.appendChild(pointElement);

    // Animacion explosiva del punto
    anime.timeline({
        complete: () => pointElement.remove()
    })
        .add({
            targets: pointElement,
            fontSize: ['0px', '180px'],
            opacity: [0, 1],
            duration: 300,
            easing: 'easeOutExpo'
        })
        .add({
            targets: pointElement,
            fontSize: ['180px', '220px'],
            opacity: [1, 0],
            translateY: [0, -100],
            duration: 500,
            easing: 'easeOutQuad'
        });

    // Crear explosion de particulas estilo anime
    createAnimeExplosion(isCorrect);

    // Crear lineas de velocidad
    createSpeedLines(isCorrect);
}

function createAnimeExplosion(isCorrect) {
    const container = document.getElementById('confetti-container');
    const color = isCorrect ? '#22c55e' : '#ef4444';
    const secondaryColor = isCorrect ? '#10b981' : '#dc2626';
    const particleCount = 30;

    // Crear circulo de explosion central
    const explosionCircle = document.createElement('div');
    explosionCircle.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 0;
        height: 0;
        border-radius: 50%;
        background: radial-gradient(circle, ${color} 0%, transparent 70%);
        z-index: 9998;
        pointer-events: none;
    `;
    container.appendChild(explosionCircle);

    anime({
        targets: explosionCircle,
        width: ['0px', '400px'],
        height: ['0px', '400px'],
        opacity: [0.8, 0],
        duration: 600,
        easing: 'easeOutExpo',
        complete: () => explosionCircle.remove()
    });

    // Particulas explosivas
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        const size = 5 + Math.random() * 15;
        const angle = (i / particleCount) * 360;
        const distance = 150 + Math.random() * 200;

        particle.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            width: ${size}px;
            height: ${size}px;
            background: ${Math.random() > 0.5 ? color : secondaryColor};
            border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
            box-shadow: 0 0 10px ${color}, 0 0 20px ${color};
            z-index: 9999;
            pointer-events: none;
        `;
        container.appendChild(particle);

        const radians = angle * (Math.PI / 180);
        const targetX = Math.cos(radians) * distance;
        const targetY = Math.sin(radians) * distance;

        anime({
            targets: particle,
            translateX: [0, targetX],
            translateY: [0, targetY],
            scale: [1, 0],
            opacity: [1, 0],
            rotate: [0, Math.random() * 720 - 360],
            duration: 600 + Math.random() * 400,
            easing: 'easeOutExpo',
            complete: () => particle.remove()
        });
    }

    // Estrellas brillantes
    for (let i = 0; i < 8; i++) {
        const star = document.createElement('div');
        const angle = (i / 8) * 360;
        const distance = 100 + Math.random() * 150;

        star.innerHTML = '✦';
        star.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            font-size: 24px;
            color: ${isCorrect ? '#fbbf24' : '#f97316'};
            text-shadow: 0 0 10px currentColor;
            z-index: 10001;
            pointer-events: none;
        `;
        container.appendChild(star);

        const radians = angle * (Math.PI / 180);

        anime({
            targets: star,
            translateX: [0, Math.cos(radians) * distance],
            translateY: [0, Math.sin(radians) * distance],
            scale: [0, 1.5, 0],
            rotate: [0, 180],
            opacity: [0, 1, 0],
            duration: 800,
            easing: 'easeOutExpo',
            complete: () => star.remove()
        });
    }
}

function createSpeedLines(isCorrect) {
    const container = document.getElementById('confetti-container');
    const color = isCorrect ? 'rgba(34, 197, 94, 0.6)' : 'rgba(239, 68, 68, 0.6)';

    for (let i = 0; i < 12; i++) {
        const line = document.createElement('div');
        const angle = (i / 12) * 360;
        const length = 200 + Math.random() * 300;

        line.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            width: ${length}px;
            height: 3px;
            background: linear-gradient(90deg, ${color}, transparent);
            transform-origin: left center;
            transform: translate(0, -50%) rotate(${angle}deg);
            z-index: 9997;
            pointer-events: none;
            opacity: 0;
        `;
        container.appendChild(line);

        anime({
            targets: line,
            opacity: [0, 1, 0],
            scaleX: [0, 1],
            duration: 400,
            easing: 'easeOutExpo',
            complete: () => line.remove()
        });
    }
}

function createScreenFlash(color) {
    const flash = document.createElement('div');
    flash.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: ${color};
        z-index: 9996;
        pointer-events: none;
        opacity: 0;
    `;
    document.body.appendChild(flash);

    anime({
        targets: flash,
        opacity: [0.3, 0],
        duration: 400,
        easing: 'easeOutQuad',
        complete: () => flash.remove()
    });
}
