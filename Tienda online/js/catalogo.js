import { Base } from "../class/Base.js";
// Se carga la base de datos del localStorage
let BD = Base.cargar();

document.addEventListener("DOMContentLoaded", function() {
    const contenedorProductos = document.getElementById("contenedorProductos");
    const busquedaInput = document.getElementById("busquedaInput");
    const filtroCategoria = document.getElementById("filtroCategoria");
    const ordenarPor = document.getElementById("ordenarPor");
    window.addEventListener("actualizarCatalogo", () => {
        BD = Base.cargar(); // recargar la BD actualizada
        aplicarFiltrosYOrden(); // volver a renderizar productos con stock actualizado
    });


    // Función principal que aplica todos los filtros o busquedas y ordena los productos
    function aplicarFiltrosYOrden() {
        const terminoBusqueda = busquedaInput.value.toLowerCase();
        const categoriaSeleccionada = filtroCategoria.value;
        const criterioOrden = ordenarPor.value;

        // Se filtran los productos según la búsqueda y categoría ingresadas
        let productosFiltrados = BD.productos.filter((producto) => {
            const coincideBusqueda = producto.nombre.toLowerCase().includes(terminoBusqueda);
            const coincideCategoria = categoriaSeleccionada === "" || producto.categoria === categoriaSeleccionada;
            const tieneStock = producto.stock > 0 ? true : false;
            return coincideBusqueda && coincideCategoria && tieneStock;
        });

        // Se ordenan los productos según el criterio seleccionado
        if (criterioOrden === "precioDesc") {
            productosFiltrados.sort((a, b) => b.precio - a.precio);
        } else if (criterioOrden === "precioAsc") {
            productosFiltrados.sort((a, b) => a.precio - b.precio);
        } else if (criterioOrden === "stockDesc") {
            productosFiltrados.sort((a, b) => b.stock - a.stock);
        } else if (criterioOrden === "stockAsc") {
            productosFiltrados.sort((a, b) => a.stock - b.stock);
        }
        // Limpiar el contenedor antes de mostrar los productos
        contenedorProductos.innerHTML = "";
        // Si no hay productos que mostrar, se muestra un mensaje
        if (productosFiltrados.length === 0) {
            contenedorProductos.innerHTML = `<p class="text-center">No se encontraron productos que coincidan con los filtros aplicados o que cuenten con stock.</p>`;
        } else {
            productosFiltrados.forEach((producto) => {
                const tarjeta = document.createElement("div");
                tarjeta.classList.add("col-md-4", "mb-4");
                tarjeta.innerHTML = `
                    <div class="card h-100">
                        <img src="${producto.urlImagen || 'https://eod-grss-ieee.com/uploads/science/1655561736_noimg_-_Copy.png'}" 
                            class="card-img-top" 
                            alt="${producto.nombre}"
                            style="height: 250px; object-fit: contain; object-position: center;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${producto.nombre}</h5>
                            <p class="card-text">Categoría: ${producto.categoria}</p>
                            <p class="card-text">Precio: $${producto.precio.toFixed(2)}</p>
                            <p class="card-text text-muted">Stock: ${producto.stock}</p>
                            <button class="btn btn-primary w-25 mt-auto btn-agregar-carrito" data-id="${producto.id}">
                                <i class="bi bi-cart-plus-fill"></i>
                            </button>
                        </div>
                    </div>
                `;
                contenedorProductos.appendChild(tarjeta);
            });

            // Se agregan los eventos a los botones de agregar al carrito
            contenedorProductos.querySelectorAll(".btn-agregar-carrito").forEach((boton) => {
                boton.addEventListener("click", function() {
                    const idProducto = parseInt(this.getAttribute("data-id"));
                    const agregado = BD.agregarAlCarrito(idProducto);
                    if (agregado) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Producto agregado al carrito!',
                            text: 'El producto ha sido añadido exitosamente a tu carrito de compras.',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-sweetalert'
                            }
                        });
                        // Se re-renderiza para actualizar el stock visible
                        aplicarFiltrosYOrden();
                    }
                });
            });
        }
    }

    // Eventos que llaman a la función principal
    busquedaInput.addEventListener("input", aplicarFiltrosYOrden);
    filtroCategoria.addEventListener("change", aplicarFiltrosYOrden);
    ordenarPor.addEventListener("change", aplicarFiltrosYOrden);

    // Mostrar todos los productos al inicio
    aplicarFiltrosYOrden();
});

