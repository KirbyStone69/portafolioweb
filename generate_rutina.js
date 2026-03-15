const fs = require('fs');

const cardioHTML = fs.readFileSync('Cardio/index.html', 'utf8');
const pesasHTML = fs.readFileSync('Pesas/index.html', 'utf8');

// Extract body contents
function extractBody(html, prefix) {
    let bodyMatch = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    if (!bodyMatch) return '';
    let body = bodyMatch[1];
    
    // Remove scripts from body
    body = body.replace(/<script[\s\S]*?<\/script>/gi, '');
    
    // Prefix all IDs
    const idsToPrefix = ['particles', 'soundBtn', 'soundIcon', 'settingsBtn', 'settingsPanel', 'totalMinutesDisplay', 'cyclesEstimate', 'decreaseTotal', 'increaseTotal', 'sentadillasTime', 'cardioTime', 'descanso1Time', 'descanso2Time', 'preparacionTime', 'roundsNumber', 'roundInfo', 'exerciseCard', 'exerciseIcon', 'exerciseName', 'exerciseTimer', 'exerciseGif', 'warningText', 'prepText', 'progressCircle', 'totalTimer', 'resetBtn', 'startBtn', 'completionCard', 'completionStats', 'instructionsText', 'hombroTime', 'bicepsTime', 'tricepsTime', 'pechoTime'];
    
    idsToPrefix.forEach(id => {
        const regex = new RegExp(`id="${id}"`, 'g');
        body = body.replace(regex, `id="${prefix}-${id}"`);
        
        const hrefRegex = new RegExp(`href="#${id}"`, 'g');
        body = body.replace(hrefRegex, `href="#${prefix}-${id}"`);
    });
    
    // Also inject the custom Switch Button inside the header-card
    const switchHTML = `
        <div class="form-check form-switch ms-3 d-inline-block">
            <input class="form-check-input switch-rutina" type="checkbox" role="switch" style="transform: scale(1.3); cursor: pointer;" ${prefix === 'pesas' ? 'checked' : ''}>
            <label class="form-check-label text-white ms-2" style="font-weight: bold;">[Modo ${prefix === 'cardio' ? 'Cardio' : 'Pesas'}]</label>
        </div>
    `;
    body = body.replace(/(<button class="btn btn-sm btn-outline-success-custom ms-2"[^>]+id="[^"]+settingsBtn"[^>]*>[\s\S]*?<\/button>)/, '$1 ' + switchHTML);

    return body;
}

const cardioBody = extractBody(cardioHTML, 'cardio');
const pesasBody = extractBody(pesasHTML, 'pesas');

// Build combined HTML
const htmlTemplate = `<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚡ Rutina Física</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Shared Styles */
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
            transition: background 0.5s ease;
        }

        .floating-particles { position: fixed; width: 100%; height: 100%; top: 0; left: 0; pointer-events: none; z-index: 0; }
        .particle { position: absolute; border-radius: 50%; animation: float 15s infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0; } 10%, 90% { opacity: 0.3; } 100% { transform: translateY(-100vh) translateX(50px) rotate(360deg); opacity: 0; } }
        
        .container { position: relative; z-index: 1; }
        .card { border-radius: 20px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95); position: relative; overflow: hidden; }
        .exercise-card { border-radius: 24px; transition: all 0.3s ease; border: 2px solid rgba(255, 255, 255, 0.3); position: relative; overflow: hidden; }
        
        .timer-display { font-size: 3.5rem; font-weight: 900; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); position: relative; display: inline-block; }
        .circular-timer { position: relative; width: 200px; height: 200px; }
        .circular-timer svg { transform: rotate(-90deg); }
        .circular-timer .timer-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
        
        .btn-lg-custom { height: 52px; font-size: 1.1rem; font-weight: 700; border-radius: 16px; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .icon-large { font-size: 3rem; animation: iconFloat 3s ease-in-out infinite; display: inline-block; }
        @keyframes iconFloat { 0%, 100% { transform: translateY(0) rotate(0deg); } 25% { transform: translateY(-10px) rotate(-5deg); } 75% { transform: translateY(-5px) rotate(5deg); } }

        /* Cardio Theme */
        body.theme-cardio {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
        }
        .theme-cardio .particle { background: rgba(16, 185, 129, 0.2); }
        .theme-cardio .btn-success-custom { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; }
        .theme-cardio .btn-success-custom:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4); }
        .theme-cardio .btn-warning-custom { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; }
        .theme-cardio .btn-danger-custom { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; }
        .theme-cardio .exercise-card.preparacion { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }
        .theme-cardio .exercise-card.sentadillas { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .theme-cardio .exercise-card.cardio { background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); }
        .theme-cardio .exercise-card.descanso { background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); }
        .theme-cardio .exercise-card.warning { background: linear-gradient(135deg, #dc2626 0%, #f97316 100%); }
        .theme-cardio .settings-panel { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 2px solid rgba(16, 185, 129, 0.3); }
        .theme-cardio .header-card { background: linear-gradient(135deg, rgba(16, 185, 129, 0.9) 0%, rgba(5, 150, 105, 0.9) 100%); border: 2px solid rgba(255, 255, 255, 0.2); }
        .theme-cardio .round-info-card { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 2px solid rgba(16, 185, 129, 0.3); }
        .theme-cardio .instructions-card { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid rgba(132, 204, 22, 0.3); }
        .theme-cardio .completion-card { background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%); color: white; }
        .theme-cardio .badge-green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 0.5rem 1rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); color: white; }
        .theme-cardio .text-success { color: #10b981 !important; }
        .theme-cardio .circular-timer svg { filter: drop-shadow(0 4px 6px rgba(16, 185, 129, 0.3)); }
        .theme-cardio .btn-outline-success-custom { color: white; border: 2px solid rgba(255, 255, 255, 0.5); background: rgba(255, 255, 255, 0.1); }
        .theme-cardio .exercise-visual-container { border: 3px solid rgba(16, 185, 129, 0.3); background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); }
        .theme-cardio .exercise-visual-card { background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%); border: 2px solid rgba(16, 185, 129, 0.2); }
        .theme-cardio .exercise-gif { width: 100%; height: 100%; object-fit: cover; border-radius: 18px; }

        /* Pesas Theme */
        body.theme-pesas {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #3730a3 100%);
        }
        .theme-pesas .particle { background: rgba(99, 102, 241, 0.2); }
        .theme-pesas .btn-success-custom { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; border: none; }
        .theme-pesas .btn-success-custom:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4); }
        .theme-pesas .btn-warning-custom { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; }
        .theme-pesas .btn-danger-custom { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; }
        .theme-pesas .exercise-card.preparacion { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }
        .theme-pesas .exercise-card.hombro { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); }
        .theme-pesas .exercise-card.biceps { background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%); }
        .theme-pesas .exercise-card.triceps { background: linear-gradient(135deg, #9333ea 0%, #a855f7 100%); }
        .theme-pesas .exercise-card.pecho { background: linear-gradient(135deg, #c026d3 0%, #d946ef 100%); }
        .theme-pesas .exercise-card.descanso { background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); }
        .theme-pesas .exercise-card.warning { background: linear-gradient(135deg, #dc2626 0%, #f97316 100%); }
        .theme-pesas .settings-panel { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); border: 2px solid rgba(99, 102, 241, 0.3); }
        .theme-pesas .header-card { background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(79, 70, 229, 0.9) 100%); border: 2px solid rgba(255, 255, 255, 0.2); }
        .theme-pesas .round-info-card { background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 2px solid rgba(99, 102, 241, 0.3); }
        .theme-pesas .instructions-card { background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 2px solid rgba(139, 92, 246, 0.3); }
        .theme-pesas .completion-card { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%); color: white; }
        .theme-pesas .badge-green { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); padding: 0.5rem 1rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); color: white; }
        .theme-pesas .text-indigo { color: #6366f1 !important; }
        .theme-pesas .circular-timer svg { filter: drop-shadow(0 4px 6px rgba(99, 102, 241, 0.3)); }
        .theme-pesas .btn-outline-success-custom { color: white; border: 2px solid rgba(255, 255, 255, 0.5); background: rgba(255, 255, 255, 0.1); }
        .theme-pesas .exercise-visual-container { border: 3px solid rgba(99, 102, 241, 0.3); background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
        .theme-pesas .exercise-visual-card { background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(79, 70, 229, 0.05) 100%); border: 2px solid rgba(99, 102, 241, 0.2); }
        .theme-pesas .exercise-gif { width: 100%; height: 100%; object-fit: cover; border-radius: 18px; }
        .theme-pesas .text-success { color: #6366f1 !important; }
        .theme-pesas .bg-success { background-color: #6366f1 !important; }
        .theme-pesas .btn-outline-success { color: #6366f1 !important; border-color: #6366f1 !important; }
    </style>
</head>
<body class="theme-cardio">
    
    <div id="cardio-container">
        ${cardioBody}
    </div>
    
    <div id="pesas-container" class="d-none theme-pesas" style="width: 100%;">
        ${pesasBody}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    
    <script>
        // Master view switcher
        const switches = document.querySelectorAll('.switch-rutina');
        
        switches.forEach(sw => {
            sw.addEventListener('change', (e) => {
                const isPesas = e.target.checked;
                // Sync switches
                switches.forEach(s => s.checked = isPesas);
                
                if (isPesas) {
                    document.body.className = 'theme-pesas';
                    document.getElementById('cardio-container').classList.add('d-none');
                    document.getElementById('pesas-container').classList.remove('d-none');
                } else {
                    document.body.className = 'theme-cardio';
                    document.getElementById('pesas-container').classList.add('d-none');
                    document.getElementById('cardio-container').classList.remove('d-none');
                }
            });
        });
    </script>
    
    <script src="cardio.js"></script>
    <script src="pesas.js"></script>

</body>
</html>`;

fs.writeFileSync('Rutina fisica/index.html', htmlTemplate);
console.log("Merged HTML generated.");
