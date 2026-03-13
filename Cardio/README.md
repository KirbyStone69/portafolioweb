# ⚡ Cronómetro HIIT - Entrenamiento por Intervalos

Aplicación web moderna para entrenamientos HIIT (High Intensity Interval Training) con diseño verde vibrante y animaciones fluidas.

## 🌟 Características

- ⏱️ **Cronómetro Personalizable**: Configura tiempos de ejercicio, descanso y duración total
- 🎨 **Diseño Moderno**: Interfaz verde esmeralda con animaciones suaves usando Anime.js
- 🏋️ **Visuales de Ejercicios**: GIFs/WebP que muestran cada ejercicio en tiempo real
- 🔊 **Alertas de Audio**: Notificación sonora al cambiar de fase
- 💾 **Persistencia Local**: Guarda tu configuración automáticamente
- 📱 **Responsive**: Funciona perfectamente en cualquier dispositivo
- 🎯 **4 Fases por Ciclo**:
  - Sentadillas (40s por defecto)
  - Descanso (20s)
  - Cardio - Saltos/Escaladores (40s)
  - Descanso (20s)

## 🚀 Uso

1. Abre `index.html` en tu navegador
2. Configura los tiempos usando el panel de ajustes ⚙️
3. Presiona **INICIAR** para comenzar
4. Sigue las instrucciones visuales y auditivas
5. ¡Disfruta tu entrenamiento! 💪

## ⚙️ Configuración

- **Duración Total**: De 2 a 60 minutos (ajuste de 2 en 2 minutos)
- **Tiempo de Ejercicios**: Personaliza cada fase
- **Número de Rondas**: Define cuántas repeticiones del circuito
- **Sonido**: Activa/desactiva notificaciones de audio

## 🛠️ Tecnologías

- HTML5
- CSS3 (Animaciones, Gradientes, Glassmorphism)
- JavaScript (ES6+)
- Bootstrap 5.3
- Anime.js 3.2.1
- Google Fonts (Poppins)
- LocalStorage API

## 📁 Estructura de Archivos

```
├── index.html          # Página principal
├── script.js           # Lógica del cronómetro
├── beep.mp3           # Sonido de notificación
├── gif/               # Visuales de ejercicios
│   ├── sentadilla.webp
│   ├── saltos.gif
│   └── descanzo.gif
└── README.md          # Este archivo
```

## 🎯 Funcionalidades Técnicas

- **Animaciones Avanzadas**: Uso de Anime.js para transiciones fluidas
- **Partículas Flotantes**: Efecto de fondo animado con CSS
- **Timer Circular**: Visualización del progreso con SVG
- **Estado Reactivo**: Actualizaciones en tiempo real
- **Almacenamiento Local**: Configuración persistente entre sesiones

## 🎨 Paleta de Colores Verde

- Verde Primario: `#10b981`
- Verde Oscuro: `#059669`
- Verde Claro: `#34d399`
- Verde Bosque: `#065f46`
- Verde Menta: `#6ee7b7`

## 📝 Notas

- El cronómetro ejecuta un ciclo de 2 minutos (120 segundos)
- Los archivos visuales soportan GIF y WebP
- El audio suena 1 segundo antes del cambio de fase
- Modo de advertencia visual en los últimos 3 segundos

## 🤝 Contribuciones

Este es un proyecto personal de entrenamiento. Siéntete libre de fork y adaptar a tus necesidades.

---

**Desarrollado con 💚 para mantenerme en forma**
