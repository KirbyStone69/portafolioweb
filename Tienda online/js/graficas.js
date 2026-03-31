import { Base } from "../class/Base.js";
let BD = Base.cargar();

document.addEventListener("DOMContentLoaded", function() {
    /* ====================================== GRAFICA DE PRODUCTOS MAS VENDIDOS ====================================== */
    const productosMasVendidos = document.getElementById('productosMasVendidos').getContext('2d');

    // De pedidos, sacamos los productos que se repitan mas en el idproductos de cada pedido
    const conteoProductos = {};
    BD.pedidos.forEach(pedido => {
        pedido.idproductos.forEach(idProducto => {
            // Si ya existe en el conteo, se incrementa, si no, se inicializa en 1
            if (conteoProductos[idProducto]) {
                conteoProductos[idProducto]++;
            } else {
                conteoProductos[idProducto] = 1;
            }
        });
    });

    // Convertimos el conteo a un array y lo ordenamos de mayor a menor, luego tomamos los 6 primeros
    const productosOrdenados = Object.entries(conteoProductos).sort((a, b) => b[1] - a[1]).slice(0, 6);
    // Por cada uno, obtenemos el nombre del producto desde la BD
    const etiquetas_productos = productosOrdenados.map(entry => {
        const producto = BD.productos.find(p => p.id === parseInt(entry[0]));
        return producto ? producto.nombre : 'Desconocido';
    });
    // Extraemos solamente las cantidades vendidas, las etiquetas ya van ordenadas
    const datos_productos = productosOrdenados.map(entry => entry[1]);
    // Se inicializa la grafica tipo bar
    const productosMasVendidosCtx = new Chart(productosMasVendidos, {
        type: 'bar',
        data: {
            labels: etiquetas_productos,
            datasets: [{
                label: 'Ventas de producto',
                data: datos_productos,
                backgroundColor: [
                    // Colores para las barras                    
                    'rgba(255, 99, 132, 0.6)',   
                    'rgba(54, 162, 235, 0.6)',   
                    'rgba(255, 206, 86, 0.6)',   
                    'rgba(75, 192, 192, 0.6)',   
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    // Colores para los bordes de las barras
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 3,
                borderRadius: 30
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });

    /* ========================================= GRAFICA DE INGRESOS POR DIA ========================================= */
    const ingresosPorDia = document.getElementById('ingresosPorDia').getContext('2d');

    // Se obtiene la fecha actual
    const fechaActual = new Date();
    fechaActual.setHours(0,0,0,0);
    // Se define el formato de la fecha, para formatear lo que estan en la pseudobase de datos
    const formatDate = d => d.toISOString().split('T')[0];

    const etiquetas_ingresos = [];
    // En el arreglo se ponen las fechas de los ultimos 14 dias, incluyendo hoy
    for (let i = 0; i < 14; i++) {
        const d = new Date(fechaActual);
        d.setDate(fechaActual.getDate() - (13 - i));
        etiquetas_ingresos.push(formatDate(d));
    }
    // Se inicializan los datos de ingresos por dia en 0
    const datos_ingresos = new Array(14).fill(0);
    // Se obtienen los indices de las fechas para asignar los ingresos correctamente 
    const indexFecha = Object.fromEntries(etiquetas_ingresos.map((lab, idx) => [lab, idx]));

    BD.pedidos.forEach(pedido => {
        // Por cada pedido, se obtiene la fecha y se formatea
        const pd = new Date(pedido.fecha);
        pd.setHours(0,0,0,0);
        const clave = formatDate(pd);
        // Se busca el index que contenga esa fecha, si no existe, es decir, si el pedido no es de los ultimos 14 dias, se ignora
        const index = indexFecha[clave];
        if (index !== undefined) {
            // Si se encuentra, se suman los ingresos del pedido al dia correspondiente
            datos_ingresos[index] += pedido.total;
        } 
    });
    // Se inicializa la grafica tipo line
    const ingresosPorDiaCtx = new Chart(ingresosPorDia, {
        type: 'line',
        data: {
            labels: etiquetas_ingresos,
            datasets: [{
                label: 'Ingresos',
                data: datos_ingresos,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });

    /* ====================================== GRAFICA DE INGRESOS POR CATEGORIA ====================================== */
    const ingresosPorCategoria = document.getElementById('ingresosPorCategoria').getContext('2d');

    // Se inicializa un objeto para llevar el conteo de ingresos por categoria
    const conteoCategorias = {};
    BD.pedidos.forEach(pedido => {
        // Por cada pedido, se recorre el idproductos para obtener la categoria de cada producto
        pedido.idproductos.forEach(idProducto => {
            const producto = BD.productos.find(p => p.id === parseInt(idProducto));
            if (producto) {
                // Se suma el total del producto a la categoria correspondiente, si no existe, se inicializa, si existe, se suma
                const categoria = producto.categoria;
                if (conteoCategorias[categoria]) {
                    conteoCategorias[categoria] += producto.precio;
                } else {
                    conteoCategorias[categoria] = producto.precio;
                }
            }
        });
    });
    // etiquetas_categorias sera igual a las llaves del objeto conteoCategorias, en este caso los nombres de las categorias
    const etiquetas_categorias = Object.keys(conteoCategorias);
    // Por otro lado, datos_categorias sera igual a los valores, es decir, los ingresos por cada categoria
    const datos_categorias = Object.values(conteoCategorias);
    // Se inicializa la grafica tipo pie
    const ingresosPorCategoriaCtx = new Chart(ingresosPorCategoria, {
        type: 'pie',
        data: {
            labels: etiquetas_categorias,
            datasets: [{
                label: 'Ingresos por categoría',
                data: datos_categorias,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Ingresos por categoría'
                }
            }
        }
    });
});
