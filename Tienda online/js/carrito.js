import { Base } from "../class/Base.js";
import { Pedido } from "../class/Pedido.js";

document.addEventListener("DOMContentLoaded", function () {
    const abrirCarrito = document.getElementById("abrirCarrito");
    abrirCarrito.addEventListener("click", function (event) {
        event.preventDefault();
        const modalCarrito = new bootstrap.Modal(document.getElementById("modalCarrito"));
        modalCarrito.show();
        cargarCarrito();
    });

    const contenidoCarrito = document.getElementById("contenidoCarrito");
    const totalCarrito = document.getElementById("totalCarrito");
    const totalCarritoiva = document.getElementById("totalCarritoiva");
    const btnVaciarCarrito = document.getElementById("VaciarCarrito");
    const btnaceptarcompra = document.getElementById("Aceptar_compra");

    btnaceptarcompra.addEventListener("click", function () {
        const BD = Base.cargar();
        if (!BD.usuarioActivo) {
            Swal.fire({
                icon: 'error',
                title: 'No hay usuario activo',
                text: 'Debes iniciar sesión para hacer una compra.'
            });
            return;
        }

        if (BD.carrito.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Carrito vacío',
                text: 'No hay productos para comprar.'
            });
            return;
        }
        // Calcular total del carrito
        let total = 0;
        BD.carrito.forEach(item => {
            const producto = BD.productos.find(p => p.id === item.idProducto);
            if (producto) total += producto.precio * item.cantidad;
        });
        total *= 1.16; // Aplicar IVA del 16%
        const pedido = new Pedido(BD.usuarioActivo.id, total);
        BD.carrito.forEach(item => {
            const producto = BD.productos.find(p => p.id === item.idProducto);
            if (producto) {
                producto.stock -= item.cantidad;
                if (producto.stock < 0) producto.stock = 0;
            }
            for (let i = 0; i < item.cantidad; i++) {
                pedido.addidproductos(item.idProducto);
            }
        });

        BD.agregarPedido(pedido);

        // me lo actualiza
        window.dispatchEvent(new CustomEvent("actualizarCatalogo"));
        cargarCarrito();

        Swal.fire({
            icon: 'success',
            title: 'Compra realizada',
            html: `Tu pedido <strong>${pedido.folio}</strong> se ha registrado correctamente.<br>Total: $${total.toFixed(2)}`
        });
    });



    btnVaciarCarrito.addEventListener("click", function () {
        const BD = Base.cargar();
        if (BD.carrito.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Carrito vacío',
                text: 'No hay productos para eliminar.'
            });
            return;
        }
        Swal.fire({
            title: '¿Vaciar carrito?',
            text: 'Se eliminarán todos los productos y se restaurará el stock.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, vaciar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // ciclo que borra todo el carrito
                // usamos slice() para clonar el arreglo antes de modificarlo
                BD.carrito.slice().forEach(item => {
                    BD.BorrarCantidadCarrito(item.idProducto);
                });
                BD.guardar();
                window.dispatchEvent(new CustomEvent("actualizarCatalogo"));
                cargarCarrito();
                Swal.fire({
                    icon: 'success',
                    title: 'Carrito vaciado',
                    text: 'Todos los productos han sido eliminados.'
                });
            }
        });
    });

    function cargarCarrito() {
        // Recargar los datos del carrito desde el localStorage
        const BD = Base.cargar();
        // Limpiar el contenido actual
        contenidoCarrito.innerHTML = "";
        // Mensaje si el carrito está vacío
        if (BD.carrito.length === 0) {
            contenidoCarrito.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                    <p class="fs-5 mt-3">El carrito está vacío.</p>
                </div>
            `;
            totalCarrito.textContent = "0.00";
            totalCarritoiva.textContent = "0.00";
        } else {
            let total = 0;
            let totaliva = 0;
            // Recorrer cada item del carrito (que tiene idProducto y cantidad)
            BD.carrito.forEach((item) => {
                const producto = BD.productos.find(p => p.id === item.idProducto);
                // Si el producto existe, se muestra en el carrito
                if (producto) {
                    const subtotal = producto.precio * item.cantidad;
                    total += subtotal;
                    totaliva += subtotal * 1.16;
                    // Crear el HTML del producto en el carrito
                    const productoHTML = `
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <img src="${producto.urlImagen}" alt="${producto.nombre}" 
                                            class="img-fluid rounded" style="max-height: 80px; object-fit: contain;">
                                    </div>
                                    <div class="col-6">
                                        <h6 class="mb-1 fw-bold">${producto.nombre}</h6>
                                        <small class="text-muted">Precio unitario: $${producto.precio.toFixed(2)}</small><br>
                                        <small class="text-muted">IVA: $${(producto.precio * 0.16).toFixed(2)}</small><br>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-danger" id="btnReducir" data-id="${producto.id}">-</button>
                                            <span class="mx-2">${item.cantidad}</span>
                                            <button class="btn btn-sm btn-outline-success"  id="btnAumentar" data-id="${producto.id}">+</button>

                                            <button class="btn me-2" id="btnBorrar" data-id="${producto.id}"><i class="bi bi-trash-fill"></i></button>
                                        
                                            </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <p class="mb-0 fw-bold text-success">$${subtotal.toFixed(2)}</p>
                                        <p class="mb-0 fw-bold text-success">IVA(16%) $${ (subtotal+(subtotal * 0.16)).toFixed(2)}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    // Agregar el producto al contenido del carrito
                    contenidoCarrito.innerHTML += productoHTML;
                }
            });
            // Actualizar el total del carrito
            totalCarrito.textContent = total.toFixed(2);
            totalCarritoiva.textContent = totaliva.toFixed(2);
        }
    }
    // Manejar los clicks en los botones de aumentar/reducir cantidad
    contenidoCarrito.addEventListener("click", function (event) {
        const BD = Base.cargar();
        // Aumento de cantidad  
        if (event.target.id === "btnAumentar") {
            const idProducto = parseInt(event.target.getAttribute("data-id"));
            BD.agregarAlCarrito(idProducto);
            // Reducción de cantidad
        } else if (event.target.id === "btnReducir") {
            const idProducto = parseInt(event.target.getAttribute("data-id"));
            BD.reducirCantidadCarrito(idProducto);
        }  else if (event.target.id === "btnBorrar" || event.target.closest("#btnBorrar")) {
            const btn = event.target.closest("#btnBorrar");
            Swal.fire({
                title: '¿Eliminar producto?',
                text: 'Se eliminará el producto del carrito y se restaurará el stock.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const idProducto = parseInt(btn.getAttribute("data-id"));
                    BD.BorrarCantidadCarrito(idProducto);
                    BD.guardar();
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto eliminado del carrito',
                        text: 'El producto ha sido eliminado y el stock restaurado.'
                    });
                    cargarCarrito();
                }
            });
        }
        window.dispatchEvent(new CustomEvent("actualizarCatalogo"));
        cargarCarrito();
    }); 

    // Cargar el carrito al iniciar
    cargarCarrito();
});
