(function () {
    // Estado de la aplicación
    let totalMinutes = 14;
    let totalTime = totalMinutes * 60;
    let timeElapsed = 0;
    let isRunning = false;
    let currentRound = 1;
    let currentCycle = 1;
    let totalCyclesCompleted = 0;
    let soundEnabled = true;
    let preparationMode = true;
    let countdownWarning = false;
    let interval = null;

    // Configuración de ejercicios (cada ex de 20s + descanso de 20s)
    let config = {
        preparation: 6,
        hombro: 20,
        biceps: 20,
        descanso1: 20,
        triceps: 20,
        pecho: 20,
        descanso2: 20,
        rounds: 4
    };

    // 6 fases por ciclo: Hombro(20) + Bíceps(20) + Descanso(20) + Tríceps(20) + Pecho(20) + Descanso(20) = 120s
    const exercises = ["Press de Hombro", "Curls de Bíceps", "Descanso", "Tríceps", "Pecho", "Descanso"];
    let currentExerciseIndex = 0;
    let currentExercise = "Preparación";
    let exerciseTime = config.preparation;
    let exerciseTimeElapsed = 0;

    // Cargar configuración desde localStorage
    function loadConfig() {
        const saved = localStorage.getItem('pesasTimerConfig');
        if (saved) {
            try {
                const savedConfig = JSON.parse(saved);
                config = { ...config, ...savedConfig };
                totalMinutes = savedConfig.totalMinutes || 14;
                totalTime = totalMinutes * 60;
                soundEnabled = savedConfig.soundEnabled !== undefined ? savedConfig.soundEnabled : true;
            } catch (e) {
                console.error('Error loading config:', e);
            }
        }
    }

    // Guardar configuración en localStorage
    function saveConfig() {
        const configToSave = {
            ...config,
            totalMinutes: totalMinutes,
            soundEnabled: soundEnabled
        };
        localStorage.setItem('pesasTimerConfig', JSON.stringify(configToSave));
    }

    // Audio para notificaciones
    const beepAudio = new Audio('beep.mp3');

    // Crear partículas flotantes
    function createFloatingParticles() {
        const particlesContainer = document.getElementById('pesas-particles');
        if (!particlesContainer) return;

        for (let i = 0; i < 15; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            const size = Math.random() * 30 + 10;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.animationDelay = `${Math.random() * 15}s`;
            particle.style.animationDuration = `${Math.random() * 10 + 10}s`;
            particlesContainer.appendChild(particle);
        }
    }

    // Animaciones con Anime.js
    function animateExerciseChange() {
        anime({
            targets: '#pesas-exerciseCard',
            scale: [0.95, 1],
            opacity: [0.7, 1],
            duration: 500,
            easing: 'easeOutElastic(1, .6)'
        });

        anime({
            targets: '#pesas-exerciseIcon',
            rotate: [0, 360],
            scale: [0.8, 1],
            duration: 600,
            easing: 'easeOutBack'
        });

        anime({
            targets: '#pesas-exerciseName',
            translateY: [-20, 0],
            opacity: [0, 1],
            duration: 500,
            delay: 100,
            easing: 'easeOutQuad'
        });

        // Animar cambio de GIF
        anime({
            targets: '#pesas-exerciseGif',
            scale: [0.8, 1],
            opacity: [0, 1],
            duration: 600,
            easing: 'easeOutBack'
        });
    }

    function animateTimerUpdate() {
        anime({
            targets: '#pesas-exerciseTimer',
            scale: [1.1, 1],
            duration: 200,
            easing: 'easeOutQuad'
        });
    }

    function animateRoundChange() {
        anime({
            targets: '#pesas-roundInfo',
            scale: [1.2, 1],
            duration: 600,
            easing: 'easeOutElastic(1, .8)'
        });
    }

    function animateCompletion() {
        anime({
            targets: '#pesas-completionCard',
            scale: [0, 1],
            opacity: [0, 1],
            duration: 800,
            easing: 'easeOutElastic(1, .5)'
        });

        anime({
            targets: '#pesas-completionCard i',
            rotate: [0, 360],
            scale: [0, 1.2, 1],
            duration: 1000,
            delay: 200,
            easing: 'easeOutBack'
        });
    }

    function animateButton(buttonId) {
        anime({
            targets: buttonId,
            scale: [0.95, 1],
            duration: 200,
            easing: 'easeOutQuad'
        });
    }

    function animateProgressCircle(progress) {
        const circumference = 2 * Math.PI * 90;
        const offset = circumference * (1 - progress);

        anime({
            targets: '#pesas-progressCircle',
            strokeDashoffset: offset,
            duration: 1000,
            easing: 'easeInOutQuad'
        });
    }

    function animatePageLoad() {
        anime({
            targets: '#pesas-view .header-card',
            translateY: [-50, 0],
            opacity: [0, 1],
            duration: 800,
            easing: 'easeOutQuad'
        });

        anime({
            targets: '#pesas-view .exercise-card',
            scale: [0.8, 1],
            opacity: [0, 1],
            duration: 1000,
            delay: 200,
            easing: 'easeOutElastic(1, .6)'
        });

        anime({
            targets: '#pesas-view .circular-timer',
            scale: [0, 1],
            opacity: [0, 1],
            duration: 1000,
            delay: 300,
            easing: 'easeOutElastic(1, .6)'
        });

        anime({
            targets: '#pesas-view .exercise-visual-card',
            scale: [0, 1],
            opacity: [0, 1],
            duration: 1000,
            delay: 250,
            easing: 'easeOutElastic(1, .6)'
        });

        anime({
            targets: '#pesas-view .round-info-card',
            translateX: [-100, 0],
            opacity: [0, 1],
            duration: 800,
            delay: 400,
            easing: 'easeOutQuad'
        });

        anime({
            targets: '#pesas-view .btn-lg-custom',
            scale: [0, 1],
            opacity: [0, 1],
            duration: 600,
            delay: anime.stagger(100, { start: 500 }),
            easing: 'easeOutBack'
        });
    }

    // Elementos DOM
    const elements = {
        soundBtn: document.getElementById('pesas-soundBtn'),
        soundIcon: document.getElementById('pesas-soundIcon'),
        settingsBtn: document.getElementById('pesas-settingsBtn'),
        settingsPanel: document.getElementById('pesas-settingsPanel'),
        totalMinutesDisplay: document.getElementById('pesas-totalMinutesDisplay'),
        cyclesEstimate: document.getElementById('pesas-cyclesEstimate'),
        decreaseTotal: document.getElementById('pesas-decreaseTotal'),
        increaseTotal: document.getElementById('pesas-increaseTotal'),
        hombroTime: document.getElementById('pesas-hombroTime'),
        bicepsTime: document.getElementById('pesas-bicepsTime'),
        tricepsTime: document.getElementById('pesas-tricepsTime'),
        pechoTime: document.getElementById('pesas-pechoTime'),
        descanso1Time: document.getElementById('pesas-descanso1Time'),
        descanso2Time: document.getElementById('pesas-descanso2Time'),
        preparacionTime: document.getElementById('pesas-preparacionTime'),
        roundsNumber: document.getElementById('pesas-roundsNumber'),
        roundInfo: document.getElementById('pesas-roundInfo'),
        exerciseCard: document.getElementById('pesas-exerciseCard'),
        exerciseIcon: document.getElementById('pesas-exerciseIcon'),
        exerciseName: document.getElementById('pesas-exerciseName'),
        exerciseTimer: document.getElementById('pesas-exerciseTimer'),
        exerciseGif: document.getElementById('pesas-exerciseGif'),
        warningText: document.getElementById('pesas-warningText'),
        prepText: document.getElementById('pesas-prepText'),
        progressCircle: document.getElementById('pesas-progressCircle'),
        totalTimer: document.getElementById('pesas-totalTimer'),
        resetBtn: document.getElementById('pesas-resetBtn'),
        startBtn: document.getElementById('pesas-startBtn'),
        completionCard: document.getElementById('pesas-completionCard'),
        completionStats: document.getElementById('pesas-completionStats'),
        instructionsText: document.getElementById('pesas-instructionsText')
    };

    // Event Listeners
    elements.soundBtn.addEventListener('click', toggleSound);
    elements.settingsBtn.addEventListener('click', toggleSettings);
    elements.decreaseTotal.addEventListener('click', () => adjustTotalTime(-2));
    elements.increaseTotal.addEventListener('click', () => adjustTotalTime(2));
    elements.startBtn.addEventListener('click', toggleTimer);
    elements.resetBtn.addEventListener('click', resetTimer);

    // Actualizar configuración al cambiar inputs
    elements.hombroTime.addEventListener('change', updateConfig);
    elements.bicepsTime.addEventListener('change', updateConfig);
    elements.tricepsTime.addEventListener('change', updateConfig);
    elements.pechoTime.addEventListener('change', updateConfig);
    elements.descanso1Time.addEventListener('change', updateConfig);
    elements.descanso2Time.addEventListener('change', updateConfig);
    elements.preparacionTime.addEventListener('change', updateConfig);
    elements.roundsNumber.addEventListener('change', updateConfig);

    function toggleSound() {
        soundEnabled = !soundEnabled;
        elements.soundIcon.className = soundEnabled ? 'bi bi-volume-up-fill' : 'bi bi-volume-mute-fill';
        saveConfig();
    }

    function toggleSettings() {
        if (isRunning) return;
        elements.settingsPanel.classList.toggle('d-none');
    }

    function adjustTotalTime(minutes) {
        if (isRunning) return;
        totalMinutes = Math.max(2, Math.min(60, totalMinutes + minutes));
        totalTime = totalMinutes * 60;
        elements.totalMinutesDisplay.textContent = totalMinutes;
        updateCyclesEstimate();
        updateDisplay();
        saveConfig();
    }

    function updateCyclesEstimate() {
        const cycleTime = config.hombro + config.biceps + config.descanso1 + config.triceps + config.pecho + config.descanso2;
        const estimatedCycles = Math.floor((totalMinutes * 60) / cycleTime);
        elements.cyclesEstimate.innerHTML = `<i class="bi bi-arrow-repeat me-1"></i>${estimatedCycles} ciclos • ${config.rounds} rondas`;
    }

    function updateConfig() {
        if (isRunning) return;
        config.preparation = parseInt(elements.preparacionTime.value);
        config.hombro = parseInt(elements.hombroTime.value);
        config.biceps = parseInt(elements.bicepsTime.value);
        config.triceps = parseInt(elements.tricepsTime.value);
        config.pecho = parseInt(elements.pechoTime.value);
        config.descanso1 = parseInt(elements.descanso1Time.value);
        config.descanso2 = parseInt(elements.descanso2Time.value);
        config.rounds = parseInt(elements.roundsNumber.value);

        updateCyclesEstimate();
        updateInstructions();
        saveConfig();
    }

    function updateInstructions() {
        const cycleTime = config.hombro + config.biceps + config.descanso1 + config.triceps + config.pecho + config.descanso2;
        elements.instructionsText.innerHTML = `
        <p class="mb-2">
            <i class="bi bi-hourglass-split text-indigo me-2"></i>
            <strong>Preparación:</strong> ${config.preparation} segundos<br>
            <small class="text-muted ms-4">(Tiempo para prepararse antes de empezar)</small>
        </p>
        
        <p class="mb-2">
            <i class="bi bi-arrow-up-circle text-indigo me-2"></i>
            <strong>Press de Hombro:</strong> ${config.hombro} segundos<br>
            <small class="text-muted ms-4">(Controlado y con buena forma)</small>
        </p>

        <p class="mb-2">
            <i class="bi bi-arrow-up text-indigo me-2"></i>
            <strong>Curls de Bíceps:</strong> ${config.biceps} segundos<br>
            <small class="text-muted ms-4">(Sin balancear el cuerpo)</small>
        </p>
        
        <p class="mb-2">
            <i class="bi bi-pause-circle text-indigo me-2"></i>
            <strong>Descanso:</strong> ${config.descanso1} segundos<br>
            <small class="text-muted ms-4">(Sacude los brazos)</small>
        </p>
        
        <p class="mb-2">
            <i class="bi bi-arrow-down text-indigo me-2"></i>
            <strong>Tríceps:</strong> ${config.triceps} segundos<br>
            <small class="text-muted ms-4">(Extensión controlada)</small>
        </p>

        <p class="mb-2">
            <i class="bi bi-arrows-expand text-indigo me-2"></i>
            <strong>Pecho:</strong> ${config.pecho} segundos<br>
            <small class="text-muted ms-4">(Aprieta al final del movimiento)</small>
        </p>
        
        <p class="mb-2">
            <i class="bi bi-pause-circle text-indigo me-2"></i>
            <strong>Descanso:</strong> ${config.descanso2} segundos<br>
            <small class="text-muted ms-4">(Respira profundo)</small>
        </p>
        
        <p class="mb-0">
            <i class="bi bi-arrow-repeat text-indigo me-2"></i>
            <strong>Circuito:</strong> ${config.rounds} rondas completas<br>
            <i class="bi bi-stopwatch text-indigo me-2 ms-4"></i>
            <strong>Cada ciclo:</strong> ${cycleTime} segundos (${(cycleTime / 60).toFixed(1)} min)<br>
            <i class="bi bi-clock text-indigo me-2 ms-4"></i>
            <strong>Duración:</strong> ${totalMinutes} minutos<br>
            <i class="bi bi-volume-up text-indigo me-2 ms-4"></i>
            <strong>Sonido:</strong> Al cambiar de ejercicio
        </p>
    `;
    }

    function toggleTimer() {
        if (timeElapsed >= totalTime) return;

        isRunning = !isRunning;

        if (isRunning) {
            elements.startBtn.innerHTML = '<i class="bi bi-pause-fill me-2"></i>PAUSAR';
            elements.startBtn.classList.remove('btn-success-custom');
            elements.startBtn.classList.add('btn-warning-custom');
            elements.exerciseCard.classList.add('running');
            animateButton('#startBtn');
            startInterval();
        } else {
            elements.startBtn.innerHTML = '<i class="bi bi-play-fill me-2"></i>INICIAR';
            elements.startBtn.classList.remove('btn-warning-custom');
            elements.startBtn.classList.add('btn-success-custom');
            elements.exerciseCard.classList.remove('running');
            animateButton('#startBtn');
            stopInterval();
        }
    }

    function startInterval() {
        interval = setInterval(() => {
            if (preparationMode) {
                exerciseTimeElapsed++;
                const remaining = config.preparation - exerciseTimeElapsed;

                // Sonar beep en el último segundo de preparación
                if (remaining === 1) {
                    playBeep();
                }

                if (remaining <= 0) {
                    preparationMode = false;
                    currentExercise = exercises[currentExerciseIndex];
                    exerciseTime = getExerciseDuration(currentExercise, currentExerciseIndex);
                    exerciseTimeElapsed = 0;
                    animateExerciseChange();
                }
            } else {
                timeElapsed++;
                exerciseTimeElapsed++;

                const secondsRemaining = exerciseTime - exerciseTimeElapsed;
                countdownWarning = secondsRemaining <= 3 && secondsRemaining > 0;

                // Sonar beep en el último segundo del ejercicio
                if (secondsRemaining === 1) {
                    playBeep();
                }

                if (exerciseTimeElapsed >= exerciseTime) {
                    exerciseTimeElapsed = 0;
                    currentExerciseIndex = (currentExerciseIndex + 1) % exercises.length;

                    if (currentExerciseIndex === 0) {
                        totalCyclesCompleted++;
                        currentCycle++;
                        animateRoundChange();

                        // Calcular cuántos ciclos hay por ronda
                        const cycleTime = config.hombro + config.biceps + config.descanso1 + config.triceps + config.pecho + config.descanso2;
                        const totalCycles = Math.floor((totalMinutes * 60) / cycleTime);
                        const cyclesPerRound = Math.floor(totalCycles / config.rounds);

                        // Si completamos los ciclos de una ronda, pasar a la siguiente
                        if (cyclesPerRound > 0 && (currentCycle - 1) % cyclesPerRound === 0 && currentCycle > 1) {
                            currentRound++;
                        }
                    }

                    currentExercise = exercises[currentExerciseIndex];
                    exerciseTime = getExerciseDuration(currentExercise, currentExerciseIndex);
                    animateExerciseChange();
                }
            }

            updateDisplay();
            animateTimerUpdate();

            if (timeElapsed >= totalTime) {
                completeWorkout();
            }
        }, 1000);
    }

    function stopInterval() {
        if (interval) {
            clearInterval(interval);
            interval = null;
        }
    }

    function getExerciseDuration(exercise, index) {
        switch (exercise) {
            case "Press de Hombro": return config.hombro;
            case "Curls de Bíceps": return config.biceps;
            case "Tríceps": return config.triceps;
            case "Pecho": return config.pecho;
            case "Descanso": return index === 2 ? config.descanso1 : config.descanso2;
            default: return 20;
        }
    }

    function resetTimer() {
        stopInterval();
        isRunning = false;
        timeElapsed = 0;
        currentRound = 1;
        currentCycle = 1;
        totalCyclesCompleted = 0;
        currentExerciseIndex = 0;
        preparationMode = true;
        currentExercise = "Preparación";
        exerciseTime = config.preparation;
        exerciseTimeElapsed = 0;
        countdownWarning = false;

        elements.startBtn.innerHTML = '<i class="bi bi-play-fill me-2"></i>INICIAR';
        elements.startBtn.classList.remove('btn-warning-custom');
        elements.startBtn.classList.add('btn-success-custom');
        elements.startBtn.disabled = false;
        elements.exerciseCard.classList.remove('running');
        elements.completionCard.classList.add('d-none');

        animateButton('#resetBtn');
        animateExerciseChange();
        updateDisplay();
    }

    function completeWorkout() {
        stopInterval();
        isRunning = false;
        elements.startBtn.disabled = true;
        elements.completionCard.classList.remove('d-none');
        const icon = '<i class="bi bi-check-circle-fill me-2"></i>';
        elements.completionStats.innerHTML = `${icon}${totalCyclesCompleted} ciclos • ${config.rounds} rondas • ${totalMinutes} minutos`;
        animateCompletion();
    }

    function updateDisplay() {
        // Actualizar información de ronda
        elements.roundInfo.innerHTML = `<i class="bi bi-trophy me-2"></i>Ronda ${currentRound} de ${config.rounds} • Ciclo ${currentCycle}`;

        // Actualizar ejercicio actual
        elements.exerciseName.textContent = currentExercise.toUpperCase();
        elements.exerciseIcon.className = 'bi icon-large mb-2 ' + getExerciseIcon(currentExercise);

        // Actualizar GIF del ejercicio
        updateExerciseVisual();

        // Actualizar tiempo del ejercicio
        const exerciseRemaining = preparationMode ?
            (config.preparation - exerciseTimeElapsed) :
            (exerciseTime - exerciseTimeElapsed);
        elements.exerciseTimer.textContent = exerciseRemaining;

        // Actualizar clase de la tarjeta
        elements.exerciseCard.className = 'card exercise-card h-100';
        if (isRunning) elements.exerciseCard.classList.add('running');
        if (preparationMode) {
            elements.exerciseCard.classList.add('preparacion');
        } else if (countdownWarning) {
            elements.exerciseCard.classList.add('warning');
        } else {
            elements.exerciseCard.classList.add(getExerciseCardClass(currentExercise));
        }

        // Mostrar/ocultar textos de advertencia
        elements.warningText.classList.toggle('d-none', !countdownWarning || !isRunning);
        elements.prepText.classList.toggle('d-none', !preparationMode || !isRunning);

        // Actualizar tiempo total restante
        const timeRemaining = totalTime - timeElapsed;
        elements.totalTimer.textContent = formatTime(timeRemaining);

        // Actualizar círculo de progreso
        const progress = timeElapsed / totalTime;
        animateProgressCircle(progress);
    }

    function getExerciseCardClass(exercise) {
        switch (exercise) {
            case "Press de Hombro": return "hombro";
            case "Curls de Bíceps": return "biceps";
            case "Tríceps": return "triceps";
            case "Pecho": return "pecho";
            case "Descanso": return "descanso";
            case "Preparación": return "preparacion";
            default: return "hombro";
        }
    }

    function getExerciseIcon(exercise) {
        switch (exercise) {
            case "Press de Hombro": return "bi-arrow-up-circle-fill";
            case "Curls de Bíceps": return "bi-arrow-up";
            case "Tríceps": return "bi-arrow-down";
            case "Pecho": return "bi-arrows-expand";
            case "Descanso": return "bi-pause-circle-fill";
            case "Preparación": return "bi-hourglass-split";
            default: return "bi-trophy-fill";
        }
    }

    function getExerciseGif(exercise) {
        switch (exercise) {
            case "Press de Hombro": return "gif/hombro.gif";
            case "Curls de Bíceps": return "gif/bicep.gif";
            case "Tríceps": return "gif/tricep.gif";
            case "Pecho": return "gif/pecho.gif";
            case "Descanso": return "gif/descanzo.gif";
            case "Preparación": return "gif/descanzo.gif";
            default: return "gif/descanzo.gif";
        }
    }

    function updateExerciseVisual() {
        const gifPath = getExerciseGif(currentExercise);
        const currentSrc = elements.exerciseGif.src.split('/').pop();
        const newSrc = gifPath.split('/').pop();

        if (currentSrc !== newSrc) {
            elements.exerciseGif.src = gifPath;
        }
    }

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    function playBeep() {
        if (soundEnabled) {
            beepAudio.currentTime = 0;
            beepAudio.play().catch(e => console.log('Audio play failed:', e));
        }
    }

    // Inicializar
    loadConfig();

    // Actualizar UI con la configuración cargada
    elements.totalMinutesDisplay.textContent = totalMinutes;
    elements.hombroTime.value = config.hombro;
    elements.bicepsTime.value = config.biceps;
    elements.tricepsTime.value = config.triceps;
    elements.pechoTime.value = config.pecho;
    elements.descanso1Time.value = config.descanso1;
    elements.descanso2Time.value = config.descanso2;
    elements.preparacionTime.value = config.preparation;
    elements.roundsNumber.value = config.rounds;
    elements.soundIcon.className = soundEnabled ? 'bi bi-volume-up-fill' : 'bi bi-volume-mute-fill';

    updateCyclesEstimate();
    updateInstructions();
    updateDisplay();

    // Crear partículas y animar página
    createFloatingParticles();
    setTimeout(() => {
        animatePageLoad();
    }, 100);
})();
