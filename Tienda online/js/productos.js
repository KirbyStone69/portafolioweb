// Se importan las clases de base y producto
import { Base } from '../class/Base.js';
import { Producto } from '../class/Producto.js';

// Se iniciliza la pseudo base de datos con la claase de Base y la varibale de dataTable (esta ultima esta vacia hasta que se cargue el DOM)
let BD = Base.cargar();
let dataTable;

let idAEditar = null;

function agregarProducto(nombre, categoria, precio, stock, urlImagen) {
    // Se crea una instancia del producto
    const nuevoProducto = new Producto(nombre, categoria, precio, stock, urlImagen);
    // Se agrega el producto a la pseudo base de datos
    BD.agregarProducto(nuevoProducto);
    // Se actualiza la dataTable
    dataTable.row.add(nuevoProducto).draw();
    // Se cierra el modal
    const agregarProductoModal = bootstrap.Modal.getInstance(document.getElementById('agregarProductoModal'));
    agregarProductoModal.hide();
    // Se reinicia el formulario
    agregarProductoForm.reset();
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Producto agregado correctamente.',
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: 'btn btn-sweetalert'
        }
    });
}

function editarProducto(nombre, categoria, precio, stock, urlImagen) {//esta funcion es llamada en la linea 213
    if (idAEditar != null) {
        let idx = idAEditar;
        BD.productos[idx].setNombre(nombre);
        BD.productos[idx].setCategoria(categoria);
        BD.productos[idx].setPrecio(precio);
        BD.productos[idx].setStock(stock);
        BD.productos[idx].setUrlImagen(urlImagen);
        BD.guardar();
        dataTable.clear().rows.add(BD.productos).draw();
        const e = bootstrap.Modal.getInstance(document.getElementById('editarProductoModal'));
        e.hide();
        idAEditar = null;
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'Producto editado correctamente.',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-sweetalert'
            }
        });
    }else{
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'La operacion fallo con exito.',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-sweetalert'
            }
        });
        idAEditar = null;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Se inicializa la dataTable con los productos existentes en la pseudo base de datos
    dataTable = new DataTable('#productosTabla', {
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
            zeroRecords: "No se encontraron productos"
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'categoria' },
            { 
                data: 'precio',
                render: function(data) {
                    return '$' + data.toFixed(2);
                }
            },
            { data: 'stock' },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        <button class="btn me-2 btn-eliminar" onclick="fkthis(${row.id})" title="Eliminar">
                            <i class="bi bi-trash-fill"></i>
                        </button>

                        <button class="btn btn-editar" onclick="modalEdicion(${row.id})" title="Editar">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    `;
                }
            }
        ],
        data: BD.productos
    });

    function modalEdicion(aidi) {
        if (BD.BuscarProductoID(aidi) != null) {
            let idx = BD.BuscarProductoID(aidi);
            document.getElementById('renombreProducto').value = BD.productos[idx].nombre;
            document.getElementById('recategoriaProducto').value = BD.productos[idx].categoria;
            document.getElementById('reprecioProducto').value = BD.productos[idx].precio;
            document.getElementById('restockProducto').value = BD.productos[idx].stock;
            document.getElementById('reurlImgProducto').value = BD.productos[idx].urlImagen;
            idAEditar = idx;
            const editarProductoModal = new bootstrap.Modal(document.getElementById('editarProductoModal'));
            editarProductoModal.show();
        }
    }
    function fkthis(aidi) {
        const idx = BD.BuscarProductoID(aidi);
        if (idx === null) {
            Swal.fire('Error', 'El producto no se encontró.', 'error');
            return;
        }

        const producto = BD.productos[idx];

        Swal.fire({
            title: 'Confirmar eliminación',
            html: `
                <p>Para confirmar, escribe el nombre del producto:</p>
                <strong>${producto.nombre}</strong>
                <input id="confirmInput" class="swal2-input" placeholder="Escribe el nombre aquí">
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const inputValue = document.getElementById('confirmInput').value.trim();
                if (inputValue !== producto.nombre) {
                    Swal.showValidationMessage('El nombre no coincide');
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (BD.eliminarProducto(aidi)) {
                    dataTable.clear().rows.add(BD.productos).draw();
                    Swal.fire('¡Eliminado!', 'El producto ha sido eliminado.', 'success');
                } else {
                    Swal.fire('Error', 'El producto no se encontró.', 'error');
                }
            }
        });
    }

    // Se muestra el modal para agregar un producto
    const agregarProductoBtn = document.getElementById('agregarProductoBtn');
    agregarProductoBtn.addEventListener('click', function() {
        const agregarProductoModal = new bootstrap.Modal(document.getElementById('agregarProductoModal'));
        agregarProductoModal.show();
    });

    // Se obtiene lo del formulrio y si no esta vacio se manda a llamar la funcion agregarProducto
    const agregarProductoForm = document.getElementById('agregarProductoForm');
    agregarProductoForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombreProducto').value.trim();
        const categoria = document.getElementById('categoriaProducto').value.trim();
        const precio = parseFloat(document.getElementById('precioProducto').value);
        const stock = parseInt(document.getElementById('stockProducto').value);
        const urlImagen = document.getElementById('urlImgProducto').value.trim();

        if(!nombre || !categoria || isNaN(precio) || isNaN(stock) || precio < 0 || stock < 0 || !urlImagen) {
            Swal.fire({
                icon: 'warning',
                title: '¡Atención!',
                text: 'Por favor, complete todos los campos correctamente.',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-sweetalert'
                }
            });
            return;
        }
        agregarProducto(nombre, categoria, precio, stock, urlImagen);
    });

    const editarProductoForm = document.getElementById('reagregarProductoForm');
    editarProductoForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const nombre = document.getElementById('renombreProducto').value.trim();
        const categoria = document.getElementById('recategoriaProducto').value.trim();
        const precio = parseFloat(document.getElementById('reprecioProducto').value);
        const stock = parseInt(document.getElementById('restockProducto').value);
        const urlImagen = document.getElementById('reurlImgProducto').value.trim();

        if(!nombre || !categoria || isNaN(precio) || isNaN(stock) || precio < 0 || stock < 0 || !urlImagen) {
            Swal.fire({
                icon: 'warning',
                title: '¡Atención!',
                text: 'Por favor, complete todos los campos correctamente.',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-sweetalert'
                }
            });
            return;
        }
        editarProducto(nombre, categoria, precio, stock, urlImagen);
    });
    window.fkthis = fkthis;
    window.modalEdicion = modalEdicion;
    window.editarProducto = editarProducto;
});
