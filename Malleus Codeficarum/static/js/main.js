// Malleus Codeficarum - main.js

// ── Rotar chevron de los temas principales al colapsar/expandir ──
document.querySelectorAll('.roadmap-topic-header').forEach(btn => {
    const target = document.querySelector(btn.dataset.bsTarget);
    if (!target) return;

    target.addEventListener('show.bs.collapse', () => btn.classList.remove('collapsed'));
    target.addEventListener('hide.bs.collapse', () => btn.classList.add('collapsed'));
});

// ── Rotar chevron de las carpetas y cambiar icono de folder ──
document.querySelectorAll('.roadmap-folder-header').forEach(btn => {
    const target = document.querySelector(btn.dataset.bsTarget);
    if (!target) return;

    target.addEventListener('show.bs.collapse', () => btn.classList.remove('collapsed'));
    target.addEventListener('hide.bs.collapse', () => btn.classList.add('collapsed'));
});

// ── Copiar bloques de código al clipboard ──
function copyCode(btn) {
    const codeBlock = btn.closest('.code-block') || btn.closest('.code-header')?.parentElement;
    if (!codeBlock) return;

    const codeEl = codeBlock.querySelector('pre code');
    if (!codeEl) return;

    const text = codeEl.textContent;

    navigator.clipboard.writeText(text).then(() => {
        // Feedback visual
        const icon = btn.querySelector('i');
        const originalClass = icon.className;

        btn.classList.add('copied');
        icon.className = 'bi bi-check-lg';

        setTimeout(() => {
            btn.classList.remove('copied');
            icon.className = originalClass;
        }, 2000);
    }).catch(() => {
        // Fallback para navegadores sin clipboard API
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);

        const icon = btn.querySelector('i');
        const originalClass = icon.className;
        btn.classList.add('copied');
        icon.className = 'bi bi-check-lg';

        setTimeout(() => {
            btn.classList.remove('copied');
            icon.className = originalClass;
        }, 2000);
    });
}

console.log('Malleus Codeficarum loaded.');
