  const moneyMX = n => "$" + new Intl.NumberFormat("es-MX").format(n);
  const pct     = n => n + "%";

//grafica semanal
  const ingresosEl = document.querySelector("#ingresosChart");

  const ingresosOptions = {
    chart:   { type: "area", height: 300, toolbar: { show: false } },
    series:  [{ name: "Recaudado", data: [3200, 1900, 1200, 1600, 2900, 2400, 3800] }],
    xaxis:   { categories: ["Lun","Mar","Mié","Jue","Vie","Sáb","Dom"] },
    dataLabels: { enabled: false },             // Oculta etiquetas sobre los puntos
    stroke:  { curve: "smooth", width: 3 },     // Línea suave y más gruesa
    fill:    {                                  // Degradado suave bajo la línea
      type: "gradient",
      gradient: { shadeIntensity: 0.3, opacityFrom: 0.5, opacityTo: 0.05 }
    },
    tooltip: { y: { formatter: moneyMX } },     // Tooltip: $ con separadores
    yaxis:   { labels: { formatter: moneyMX } } // Eje Y con formato de $
  };

  new ApexCharts(ingresosEl, ingresosOptions).render();


  const metodosEl = document.querySelector("#metodosChart");
//grafica de pastel
  const metodosOptions = {
    chart:  { type: "pie", height: 320 },
    labels: ["Efectivo","Tarjeta","Transferencia"],
    series: [45, 35, 20],
    legend: { position: "top" },                // Leyenda arriba
    tooltip: { y: { formatter: pct } }          // Tooltip: muestra 45%, 35%, 20%
  };

  new ApexCharts(metodosEl, metodosOptions).render();