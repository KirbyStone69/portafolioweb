export class Producto {
    constructor(nombre, categoria, precio, stock, urlImagen) {
        this.id = 0;
        this.nombre = nombre;
        this.categoria = categoria;
        this.precio = precio;
        this.stock = stock;
        this.urlImagen = urlImagen;
    }

    //edicion del producto
    setNombre(rename) {
        if (rename.trim() !== '') {
            this.nombre = rename.trim();//trim quita los espacios en blanco que estan a los lados
            return true;
        }else{return false}
    }
    setCategoria(categoria){
        if (categoria.trim() !== '') {
            this.categoria = categoria.trim();//trim quita los espacios en blanco que estan a los lados
            return true;
        }else{return false}
    }
    setPrecio(newcosto) {
        if (typeof newcosto === 'number' && newcosto >= 0) {
            this.precio = newcosto;
            return true;
        }else{return false}
    }
    setStock(estoc) {
        if (typeof estoc === 'number' && estoc >= 0) {
            this.stock = estoc;
            return true;
        }else{return false}
    }
    setUrlImagen(urlImg) {
        if (urlImg.trim() !== '') {
            this.urlImagen = urlImg.trim();//trim quita los espacios en blanco que estan a los lados
            return true;
        }else{return false}
    }
}