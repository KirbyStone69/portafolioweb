// Dashboard JavaScript - Carga estadisticas y graficas dinamicas
document.addEventListener('DOMContentLoaded', function () {
    cargarEstadisticas();
    cargarIngresosSemana();
    cargarMetodosPago();
});

// Funcion para cargar las estadisticas generales
function cargarEstadisticas() {
    fetch('php/dashboard/estadisticas.php')
        .then(response => response.json())
        .then(data => {
            // Actualizar contadores en el DOM
            document.getElementById('stat-pacientes').textContent = data.pacientes_total || 0;
            document.getElementById('stat-medicos').textContent = data.medicos_total || 0;
            document.getElementById('stat-citas').textContent = data.citas_pendientes || 0;

            // Formatear ingresos con separadores de miles
            const ingresos = parseFloat(data.ingresos_total || 0);
            document.getElementById('stat-ingresos').textContent = new Intl.NumberFormat('es-MX', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(ingresos);
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
        });
}

// Funcion para cargar el grafico de ingresos semanales
function cargarIngresosSemana() {
    fetch('php/dashboard/ingresos_semana.php')
        .then(response => response.json())
        .then(data => {
            const moneyMX = n => "$" + new Intl.NumberFormat("es-MX").format(n);

            const ingresosEl = document.querySelector("#ingresosChart");
            const ingresosOptions = {
                chart: { type: "area", height: 300, toolbar: { show: false } },
                series: [{ name: "Recaudado", data: data.montos }],
                xaxis: { categories: data.dias },
                dataLabels: { enabled: false },
                stroke: { curve: "smooth", width: 3 },
                fill: {
                    type: "gradient",
                    gradient: { shadeIntensity: 0.3, opacityFrom: 0.5, opacityTo: 0.05 }
                },
                tooltip: { y: { formatter: moneyMX } },
                yaxis: { labels: { formatter: moneyMX } }
            };

            new ApexCharts(ingresosEl, ingresosOptions).render();
        })
        .catch(error => {
            console.error('Error al cargar ingresos semanales:', error);
        });
}

// Funcion para cargar el grafico de metodos de pago
function cargarMetodosPago() {
    fetch('php/dashboard/metodos_pago.php')
        .then(response => response.json())
        .then(data => {
            const metodosEl = document.querySelector("#metodosChart");
            const metodosOptions = {
                chart: { type: "pie", height: 320 },
                labels: data.labels,
                series: data.valores,
                legend: { position: "top" },
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return value + " pagos";
                        }
                    }
                }
            };

            new ApexCharts(metodosEl, metodosOptions).render();
        })
        .catch(error => {
            console.error('Error al cargar métodos de pago:', error);
        });
}
