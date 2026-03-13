// Malleus Codeficarum — quiz.js
// Script reutilizable para cuestionarios de evaluación.
// Lee las respuestas correctas de data-answer en cada .quiz-question.

(function () {
    'use strict';

    const form = document.getElementById('quizForm');
    if (!form) return;

    const btnCheck = document.getElementById('btnCheckQuiz');
    const btnRetry = document.getElementById('btnRetryQuiz');
    const resultsBox = document.getElementById('quizResults');
    const questions = form.querySelectorAll('.quiz-question');
    const total = questions.length;

    // ── Comprobar respuestas ──
    btnCheck.addEventListener('click', () => {
        let correct = 0;
        let allAnswered = true;

        questions.forEach(q => {
            const answer = q.dataset.answer;
            const selected = q.querySelector('input[type="radio"]:checked');
            const feedback = q.querySelector('.quiz-feedback');
            const options = q.querySelectorAll('.quiz-option');

            // Limpiar estado previo
            options.forEach(o => o.classList.remove('correct', 'incorrect'));
            q.classList.remove('answered-correct', 'answered-incorrect', 'unanswered');

            if (!selected) {
                allAnswered = false;
                q.classList.add('unanswered');
                feedback.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Selecciona una respuesta.';
                feedback.className = 'quiz-feedback show warning';
                return;
            }

            // Marcar la opción correcta visualmente
            options.forEach(o => {
                const radio = o.querySelector('input[type="radio"]');
                if (radio.value === answer) {
                    o.classList.add('correct');
                }
            });

            if (selected.value === answer) {
                correct++;
                q.classList.add('answered-correct');
                feedback.innerHTML = '<i class="bi bi-check-circle-fill"></i> ¡Correcto!';
                feedback.className = 'quiz-feedback show success';
            } else {
                selected.closest('.quiz-option').classList.add('incorrect');
                q.classList.add('answered-incorrect');
                feedback.innerHTML = '<i class="bi bi-x-circle-fill"></i> Incorrecto.';
                feedback.className = 'quiz-feedback show error';
            }

            // Deshabilitar radios después de comprobar
            q.querySelectorAll('input[type="radio"]').forEach(r => r.disabled = true);
        });

        if (!allAnswered) return;

        // Mostrar resultados
        btnCheck.style.display = 'none';
        btnRetry.style.display = 'inline-flex';

        const pct = Math.round((correct / total) * 100);
        const icon = document.getElementById('resultsIcon');
        const title = document.getElementById('resultsTitle');
        const text = document.getElementById('resultsText');
        const score = document.getElementById('resultsScore');

        score.textContent = correct + ' / ' + total;

        if (pct === 100) {
            icon.innerHTML = '<i class="bi bi-trophy-fill"></i>';
            icon.className = 'results-icon perfect';
            title.textContent = '¡Perfecto!';
            text.textContent = 'Dominaste todos los conceptos de esta lección. ¡Sigue adelante!';
        } else if (pct >= 70) {
            icon.innerHTML = '<i class="bi bi-emoji-smile-fill"></i>';
            icon.className = 'results-icon good';
            title.textContent = '¡Buen trabajo!';
            text.textContent = 'Entiendes bien los conceptos principales. Revisa las respuestas incorrectas para reforzar.';
        } else if (pct >= 50) {
            icon.innerHTML = '<i class="bi bi-emoji-neutral-fill"></i>';
            icon.className = 'results-icon okay';
            title.textContent = 'Puedes mejorar';
            text.textContent = 'Tienes una base, pero te recomendamos releer las secciones e intentar de nuevo.';
        } else {
            icon.innerHTML = '<i class="bi bi-emoji-frown-fill"></i>';
            icon.className = 'results-icon low';
            title.textContent = 'Sigue practicando';
            text.textContent = 'No te desanimes. Vuelve a leer la lección con calma e inténtalo de nuevo.';
        }

        resultsBox.style.display = 'block';
        resultsBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    // ── Reintentar quiz ──
    btnRetry.addEventListener('click', () => {
        questions.forEach(q => {
            const options = q.querySelectorAll('.quiz-option');
            const feedback = q.querySelector('.quiz-feedback');

            options.forEach(o => o.classList.remove('correct', 'incorrect'));
            q.classList.remove('answered-correct', 'answered-incorrect', 'unanswered');
            q.querySelectorAll('input[type="radio"]').forEach(r => {
                r.checked = false;
                r.disabled = false;
            });
            feedback.className = 'quiz-feedback';
            feedback.innerHTML = '';
        });

        resultsBox.style.display = 'none';
        btnRetry.style.display = 'none';
        btnCheck.style.display = 'inline-flex';

        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    // ── Efecto visual al seleccionar (hover ya está en CSS, pero selección activa) ──
    form.querySelectorAll('.quiz-option').forEach(opt => {
        opt.addEventListener('click', () => {
            const parent = opt.closest('.quiz-options');
            parent.querySelectorAll('.quiz-option').forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
        });
    });
})();
