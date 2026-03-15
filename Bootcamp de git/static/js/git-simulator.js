/**
 * Malleus Codeficarum — Git Simulator v2
 * Motor reutilizable para ejercicios interactivos de Git.
 * Layout: 2 paneles (instrucciones + terminal unificada).
 * Incluye sistema de archivos virtual para ls, pwd, cd.
 *
 * USO: Cada lección define un array de pasos y llama a:
 *   GitSimulator.init({ pasos: [...] })
 *
 * Cada paso tiene:
 *   - titulo:            Título corto del paso
 *   - instruccion:       HTML con la explicación
 *   - comando_esperado:  String o Array de strings aceptados
 *   - salida:            String con la salida en terminal
 *   - pista:             Texto de ayuda opcional
 *   - fs_cambios:        (Opcional) Función que modifica el filesystem virtual
 */

const GitSimulator = (function () {
    'use strict';

    let config = {};
    let pasoActual = 0;
    let historialComandos = [];
    let posHistorial = -1;
    let completado = false;

    // ── Sistema de archivos virtual ──
    let cwd = '~';
    let fs = {};

    function initFS() {
        cwd = '~';
        fs = {
            '~': {
                type: 'dir',
                children: {}
            }
        };
    }

    function getDir(path) {
        const parts = path.split('/').filter(Boolean);
        let node = fs['~'];
        if (parts[0] === '~') parts.shift();
        for (const p of parts) {
            if (!node.children || !node.children[p]) return null;
            node = node.children[p];
        }
        return node;
    }

    function getCwdNode() {
        if (cwd === '~') return fs['~'];
        return getDir(cwd);
    }

    function listFiles() {
        const dir = getCwdNode();
        if (!dir || !dir.children) return '(carpeta vacía)';
        const names = Object.keys(dir.children);
        if (names.length === 0) return '(carpeta vacía)';
        return names.map(name => {
            const item = dir.children[name];
            if (item.type === 'dir') {
                return name + '/';
            }
            return name;
        }).join('    ');
    }

    function listFilesDetailed() {
        const dir = getCwdNode();
        if (!dir || !dir.children) return 'total 0';
        const names = Object.keys(dir.children);
        if (names.length === 0) return 'total 0';
        let lines = ['total ' + names.length];
        for (const name of names) {
            const item = dir.children[name];
            if (item.type === 'dir') {
                lines.push('drwxr-xr-x  ' + name + '/');
            } else {
                lines.push('-rw-r--r--  ' + name);
            }
        }
        return lines.join('\n');
    }

    // ── Elementos del DOM ──
    let elPasoNum, elPasoTotal, elTituloPaso, elContenidoPaso;
    let elPista, elBtnPista, elProgressBar, elProgressText;
    let elTerminalBody, elTerminalInput;
    let elBtnEnviar, elBtnReset;

    function init(opciones) {
        config = opciones;
        pasoActual = 0;
        historialComandos = [];
        posHistorial = -1;
        completado = false;
        initFS();

        // Cachear elementos
        elPasoNum = document.getElementById('simPasoNum');
        elPasoTotal = document.getElementById('simPasoTotal');
        elTituloPaso = document.getElementById('simTituloPaso');
        elContenidoPaso = document.getElementById('simContenidoPaso');
        elPista = document.getElementById('simPista');
        elBtnPista = document.getElementById('simBtnPista');
        elProgressBar = document.getElementById('simProgressBar');
        elProgressText = document.getElementById('simProgressText');
        elTerminalBody = document.getElementById('simTerminalBody');
        elTerminalInput = document.getElementById('simTerminalInput');
        elBtnEnviar = document.getElementById('simBtnEnviar');
        elBtnReset = document.getElementById('simBtnReset');

        if (elPasoTotal) elPasoTotal.textContent = config.pasos.length;

        // Eventos
        elBtnEnviar.addEventListener('click', ejecutarComando);
        elBtnReset.addEventListener('click', resetSimulador);
        if (elBtnPista) elBtnPista.addEventListener('click', mostrarPista);

        elTerminalInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ejecutarComando();
            }
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                navegarHistorial(-1);
            }
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navegarHistorial(1);
            }
        });

        renderPaso();
        elTerminalInput.focus();

        // Welcome
        agregarOutput('// Simulador de Git listo — escribe los comandos indicados', 'comment');
        agregarOutput('// También puedes usar: ls, ls -la, pwd, clear', 'comment');
        agregarOutput('', 'blank');
    }

    function navegarHistorial(dir) {
        if (historialComandos.length === 0) return;
        posHistorial += dir;
        if (posHistorial < 0) posHistorial = 0;
        if (posHistorial >= historialComandos.length) {
            posHistorial = historialComandos.length;
            elTerminalInput.value = '';
            return;
        }
        elTerminalInput.value = historialComandos[historialComandos.length - 1 - posHistorial];
    }

    function renderPaso() {
        const paso = config.pasos[pasoActual];
        if (elPasoNum) elPasoNum.textContent = pasoActual + 1;
        if (elTituloPaso) elTituloPaso.textContent = paso.titulo;
        if (elContenidoPaso) elContenidoPaso.innerHTML = paso.instruccion;

        if (elPista) {
            elPista.style.display = 'none';
            elPista.textContent = '';
        }
        if (elBtnPista) {
            elBtnPista.style.display = paso.pista ? 'inline-flex' : 'none';
        }

        actualizarProgreso();
        elTerminalInput.value = '';
        elTerminalInput.disabled = false;
        elTerminalInput.focus();
    }

    function actualizarProgreso() {
        const total = config.pasos.length;
        const pct = Math.round((pasoActual / total) * 100);
        if (elProgressBar) elProgressBar.style.width = pct + '%';
        if (elProgressText) elProgressText.textContent = pasoActual + ' / ' + total;
    }

    function ejecutarComando() {
        if (completado) return;

        const input = elTerminalInput.value.trim();
        if (!input) return;

        historialComandos.push(input);
        posHistorial = -1;

        // Mostrar prompt + comando
        agregarPrompt(input);

        const cmd = input.replace(/\s+/g, ' ').toLowerCase();

        // ── Clear siempre funciona ──
        if (cmd === 'clear') {
            elTerminalBody.innerHTML = '';
            elTerminalInput.value = '';
            elTerminalInput.focus();
            return;
        }

        // ── Validar contra el paso actual PRIMERO ──
        const paso = config.pasos[pasoActual];
        const esperados = Array.isArray(paso.comando_esperado)
            ? paso.comando_esperado
            : [paso.comando_esperado];

        const normalizado = input.replace(/\s+/g, ' ').toLowerCase();
        const esValido = esperados.some(c =>
            normalizado === c.replace(/\s+/g, ' ').toLowerCase()
        );

        if (esValido) {
            // Aplicar cambios al filesystem virtual
            if (paso.fs_cambios) {
                paso.fs_cambios(fs, cwd);
            }

            // Actualizar cwd si fue un cd
            if (paso.nuevo_cwd) {
                cwd = paso.nuevo_cwd;
            }

            // Si el comando es ls/ls -la, mostrar la salida real del filesystem
            if (cmd === 'ls') {
                agregarOutput(listFiles(), 'output');
            } else if (cmd === 'ls -la' || cmd === 'ls -al' || cmd === 'ls -l') {
                agregarOutput(listFilesDetailed(), 'output');
            } else if (paso.salida) {
                agregarOutput(paso.salida, 'output');
            }

            pasoActual++;

            if (pasoActual >= config.pasos.length) {
                completado = true;
                actualizarProgreso();
                elTerminalInput.disabled = true;
                elTerminalInput.value = '';

                if (elProgressBar) elProgressBar.style.width = '100%';
                if (elProgressText) elProgressText.textContent = config.pasos.length + ' / ' + config.pasos.length;

                agregarOutput('', 'blank');
                agregarOutput('✓ ¡Todos los pasos completados! 🎉', 'success');

                if (elTituloPaso) elTituloPaso.textContent = '¡Ejercicio completado!';
                if (elPasoNum) elPasoNum.textContent = '✓';
                if (elContenidoPaso) {
                    elContenidoPaso.innerHTML = `
                        <div class="sim-complete-msg">
                            <i class="bi bi-trophy-fill"></i>
                            <p>Has completado todos los pasos de este ejercicio.
                            Ya sabes cómo usar estos comandos de Git.</p>
                        </div>`;
                }
                if (elBtnPista) elBtnPista.style.display = 'none';
            } else {
                agregarOutput('', 'blank');
                agregarOutput('// ✓ Paso ' + pasoActual + ' completado', 'comment');
                agregarOutput('', 'blank');
                renderPaso();
            }
        } else {
            // ── Comandos libres (no coinciden con el paso, pero son válidos) ──
            if (cmd === 'pwd') {
                agregarOutput(cwd, 'output');
            } else if (cmd === 'ls') {
                agregarOutput(listFiles(), 'output');
            } else if (cmd === 'ls -la' || cmd === 'ls -al' || cmd === 'ls -l') {
                agregarOutput(listFilesDetailed(), 'output');
            } else {
                agregarOutput('bash: comando no válido para este paso', 'error');
                agregarOutput('// Revisa las instrucciones. Usa "pista" si necesitas ayuda.', 'comment');
            }
        }

        elTerminalInput.value = '';
        elTerminalInput.focus();
    }

    function agregarPrompt(texto) {
        const linea = document.createElement('div');
        linea.className = 'sim-terminal-line';

        const cwdShort = cwd === '~' ? '~' : cwd.split('/').pop();
        linea.innerHTML =
            '<span class="sim-prompt-path">' + escapeHtml(cwdShort) + '</span>' +
            '<span class="sim-prompt-echo">$</span> ' +
            escapeHtml(texto);

        elTerminalBody.appendChild(linea);
        scrollTerminal();
    }

    function agregarOutput(texto, tipo) {
        if (tipo === 'blank') {
            const linea = document.createElement('div');
            linea.className = 'sim-terminal-line blank';
            linea.innerHTML = '&nbsp;';
            elTerminalBody.appendChild(linea);
            scrollTerminal();
            return;
        }

        const lines = texto.split('\n');
        for (const line of lines) {
            const el = document.createElement('div');
            el.className = 'sim-terminal-line ' + (tipo || '');
            el.textContent = line;
            elTerminalBody.appendChild(el);
        }
        scrollTerminal();
    }

    function scrollTerminal() {
        elTerminalBody.scrollTop = elTerminalBody.scrollHeight;
    }

    function mostrarPista() {
        const paso = config.pasos[pasoActual];
        if (!paso || !paso.pista) return;
        if (elPista) {
            elPista.textContent = '💡 ' + paso.pista;
            elPista.style.display = 'block';
        }
    }

    function resetSimulador() {
        pasoActual = 0;
        historialComandos = [];
        posHistorial = -1;
        completado = false;
        initFS();

        elTerminalBody.innerHTML = '';
        renderPaso();

        agregarOutput('// Simulador reiniciado', 'comment');
        agregarOutput('', 'blank');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    return { init };
})();
