import { Base } from "../class/Base.js";
// Se carga lo que esta en la presudo base de datos
let BD = Base.cargar();
let dataTable;

document.addEventListener("DOMContentLoaded", function () {
    const pedidosTabla = document.getElementById('pedidosTabla');
    // Se iniciliaza la dataTable de pedidos
    dataTable = new DataTable("#pedidosTabla", {
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros por página",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
            zeroRecords: "No se encontraron pedidos"
        },
        columns: [
            { data: 'folio' },
            { data: 'fecha' },
            { data: 'usuarioId' },
            { data: 'total' },
            { data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    return `<button class="btn btn-primary btn-sm btn-ver-detalles" data-folio="${row.folio}" id="btn-ver-detalles"><i class="bi bi-eye-fill"></i></button>`;
                }
            }
        ],
        data: BD.pedidos.map(pedido => ({
            folio: pedido.folio,
            // Se formatea la fecha al formato deseado (mexico cd victoria tiempo local)
            fecha: new Date(pedido.fecha).toLocaleString('es-MX', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }),
            usuarioId: pedido.usuarioId,
            total: pedido.total.toFixed(2)
        }))
    });

    // Clics en los botones de ver detalles del pedido
    pedidosTabla.addEventListener("click", function (event) {
        if(event.target.id === "btn-ver-detalles") {
            // Optiene el folio del pedido a mostrar y lo busca en el array de pedidos
            const folio = event.target.getAttribute("data-folio");
            const pedido = BD.pedidos.find(p => p.folio === folio);
            if (pedido) {
                // Si existe el pedido mostramos el modal
                const modalPedido = new bootstrap.Modal(document.getElementById('modalPedido'));
                modalPedido.show();
                // Se vacia el modal antes de poner lo nuevo (osea lo del nuevo pedido del que se dio clic en el boton de ver detalles)
                const contenidoPedido = document.getElementById("contenidoPedido");
                contenidoPedido.innerHTML = "";
                let totalCalculado = 0;
                // Cuenta las veces que sale cada id de producto en el pedido
                const conteoProductos = {};
                pedido.idproductos.forEach(id => {
                    // Si ya lo encuentra, le suma 1, si no, lo inicializa en 0 y le suma 1
                    conteoProductos[id] = (conteoProductos[id] || 0) + 1;
                });
                // Mostrar productos únicos con su cantidad, es decir, se vuelve un clave-valor con id y cantidad
                Object.entries(conteoProductos).forEach(([idProducto, cantidad]) => {
                    const producto = BD.productos.find(p => p.id === parseInt(idProducto));
                    if (producto) {
                        const itemDiv = document.createElement("div");
                        itemDiv.classList.add("d-flex", "justify-content-between", "align-items-center", "mb-3");
                        itemDiv.innerHTML = `
                            <div class="d-flex align-items-center">
                                <img src="${producto.urlImagen}" alt="${producto.nombre}" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px;" class="me-3">
                                <div>
                                    <h5 class="mb-1">${producto.nombre}</h5>
                                    <p class="mb-0">Cantidad: ${cantidad}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <p class="mb-0">$${(producto.precio * cantidad).toFixed(2)}</p>
                            </div>
                        `;
                        contenidoPedido.appendChild(itemDiv);
                        totalCalculado += producto.precio * cantidad;
                    }
                });
                // Mostrar total del pedido calculado
                const totalDiv = document.getElementById("totalPedido");
                totalDiv.textContent = `${totalCalculado.toFixed(2)}`;
            }
        }
    });
});