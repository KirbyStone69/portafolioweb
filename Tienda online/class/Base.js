import { Producto } from './Producto.js';
//objetote
export class Base {
    constructor() {
        //lo mero bueno el almacenaje de arreglo de objetos :p
        this.productos = []; 
        this.usuarios = [];
        this.carrito = [];
        this.pedidos = [];
        this.usuarioActivo = null;
        //
        this.ultimoIdProducto = 0;
        this.ultimoIdUsuario = 0;
    }
    VaciarAll(){
        this.productos = [];
        this.carrito = [];
        this.pedidos = [];
        this.ultimoIdProducto = 0;
        this.ultimoIdUsuario = 0;
        this.guardar();
    }
    agregarPedido(pedido) {
        this.pedidos.push(pedido);
        this.carrito = [];
        this.guardar();
    }
    agregarProducto(producto) {
        this.ultimoIdProducto++;
        producto.id = this.ultimoIdProducto;
        //
        this.productos.push(producto);
        this.guardar();
    }
    agregarUsuario(usuario) {
        this.ultimoIdUsuario++;
        usuario.id = this.ultimoIdUsuario;
        //
        this.usuarios.push(usuario);
        this.guardar();
    }
    eliminarProducto(id){
        for (let i = 0; i < this.productos.length; i++) {
            if(this.productos[i].id === id){
                this.productos.splice(i,1);
                this.guardar();
                return true;
            }
        }
        return false;
    }
    // Busca un usuario por email y password, si lo encuentra lo establece como usuarioActivo y retorna el usuario, si no lo encuentra retorna null
    verificarCredenciales(email, password){
        for (let i = 0; i < this.usuarios.length; i++) {
            if(this.usuarios[i].email === email && this.usuarios[i].password === password){
                this.usuarioActivo = this.usuarios[i];
                this.guardar();
                return this.usuarios[i];
            }
        }
        return null;
    }
    // Busca un usuario por email, si lo encuentra retorna el usuario, si no lo encuentra retorna null
    buscarUsuarioMismoCorreo(email){
        for (let i = 0; i < this.usuarios.length; i++) {
            if(this.usuarios[i].email === email){
                return this.usuarios[i];
            }
        }
        return null;
    }

    BorrarCantidadCarrito(idProducto) {
        const itemExistente = this.carrito.find(item => item.idProducto === idProducto);
        const producto = this.productos.find(p => p.id === idProducto);
        if (!itemExistente || !producto) {
            return false;
        }
        // devolvemos al stock la cantidad del carrito
        producto.stock += itemExistente.cantidad;
        // quitamos el producto del carrito
        this.carrito = this.carrito.filter(item => item.idProducto !== idProducto);
        // guardamos los cambios
        this.guardar();
        return true;
    }
    BorrarCarrito(idProducto) {
        const itemExistente = this.carrito.find(item => item.idProducto === idProducto);
        const producto = this.productos.find(p => p.id === idProducto);
        if (!itemExistente || !producto) {
            return false;
        }
        // quitamos el producto del carrito
        this.carrito = this.carrito.filter(item => item.idProducto !== idProducto);
        // guardamos los cambios
        this.guardar();
        return true;
    }

    agregarAlCarrito(idProducto) {
        const itemExistente = this.carrito.find(item => item.idProducto === idProducto);
        const producto = this.productos.find(p => p.id === idProducto);

        if (!producto) {
            // producto no encontrado
            return false;
        }
        if (producto.stock <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Sin stock',
                text: 'No hay más stock disponible para este producto.'
            });
            return false;
        }
        // Siempre decrementamos el stock cuando se agrega exitosamente
        if (itemExistente) {
            itemExistente.cantidad++;
        } else {
            this.carrito.push({
                idProducto: idProducto,
                cantidad: 1
            });
        }
        producto.stock--;
        this.guardar();
        return true;
    }

    reducirCantidadCarrito(idProducto) {
        const itemExistente = this.carrito.find(item => item.idProducto === idProducto);
        const producto = this.productos.find(p => p.id === idProducto);
        // Verifica si el producto existe en el carrito
        if (itemExistente) {
            // Se reduce la cantidad en el carrito y se incrementa el stock del producto
            itemExistente.cantidad--;
            producto.stock++;
            // Si la cantidad llega a cero, se elimina el producto del carrito
            if (itemExistente.cantidad <= 0) {
                this.carrito = this.carrito.filter(item => item.idProducto !== idProducto);
            }
            this.guardar();
            return true;
        }
        return false;
    }

    //aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    BuscarProductoID(id){
        for (let i = 0; i < this.productos.length; i++) {
            if (this.productos[i].id === id) {
                return i;
            }
        }
        return null;
    }

    guardar() {
        localStorage.setItem("pseudoBase", JSON.stringify(this));
    }
static cargar() {
        const lamadreguardada = localStorage.getItem("pseudoBase");
        if (lamadreguardada) {
            const objetito = JSON.parse(lamadreguardada); //lo convierto a un objeto
            //lo mareo
            const bd = new Base();//le creo una base para almacenar lo del localstorage que al final mandaderemos al this
            bd.usuarios = objetito.usuarios || [];//cargamos los usuarios si no hay nda el array amanece vacio
            bd.usuarioActivo = objetito.usuarioActivo || null; //almacena el usuario que hizo login valido
            bd.ultimoIdProducto = objetito.ultimoIdProducto || 0;//lo mismo del usuario
            bd.ultimoIdUsuario = objetito.ultimoIdUsuario || 0; //lo mismo de arriba lol
            bd.pedidos = objetito.pedidos || []; //lo mismo de arriba lol
            
            if (objetito.carrito && Array.isArray(objetito.carrito)) {
                bd.carrito = objetito.carrito.map(item => {
                    return {
                        idProducto: item.idProducto,
                        cantidad: item.cantidad
                    };
                });
            } else {
                bd.carrito = [];
            }

            if (objetito.productos && Array.isArray(objetito.productos)) {
                //Pasamos cada dato de productos del localstorage a nuevos objetos
                //esto para poder usar sus funciones
                bd.productos = objetito.productos.map(p => {
                    const productoReal = new Producto(p.nombre, p.categoria, p.precio, p.stock, p.urlImagen);
                    productoReal.id = p.id;
                    return productoReal; //esto se lo avienta a bd.productospara llenar todo el array
                });
            } else {
                bd.productos = [];
            }
            return bd;
        }
        return new Base();
    }
}

//Esto en el main.js
//let BD = Base.cargar();