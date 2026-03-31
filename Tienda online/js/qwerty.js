// Se importan las clases de base y producto
import { Base } from '../class/Base.js';
import { Producto } from '../class/Producto.js';

let BD = Base.cargar();
//constructor(nombre, categoria, precio, stock, urlImagen)
if (BD.productos.length == 0) {
    let u1 = new Producto("Trapeador", "Limpieza",25.4,60,"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTaRRRcdrM0ov77yQQqDSTaLzvgRinYoZmBgg&s");
    let u2 = new Producto("Cloralex", "Limpieza",25.4,60,"https://cloralex.com.mx/wp-content/uploads/2017/06/rendidor-covid.png");
    let u3 = new Producto("Xbox Series S", "Electrónicos",10500.0,20,"https://m.media-amazon.com/images/I/61wgsQ6bGtL._AC_UF1000,1000_QL80_.jpg");
    let u4 = new Producto("Xbox Series X", "Electrónicos",12000.0,15,"https://i5.walmartimages.com/asr/94ccb89c-e87d-424c-8826-22bd8babf2e1.1439d801aa283a8256ed5a6e80004efd.jpeg");
    let u5 = new Producto("Playera de Chivas", "Vestimenta",2000.0,10,"https://www.tudnfanshop.com/cdn/shop/files/VIuVK6u3_UMR.jpg");
    let u6 = new Producto("Laptop Gamer ASUS", "Electrónicos",35000.0,6,"https://imggraficos.gruporeforma.com/2022/05/Laptops-gamer-asus-hot-sale-2022-2.png");
    let u7 = new Producto("Cámara Digital Canon", "Electrónicos",25000.0,13,"https://i5.walmartimages.com/asr/c874a829-e0d3-4e2b-836e-e04ae92493e5.ace36ad11460c7f483b00175d96acd71.jpeg");
    let u8 = new Producto("Camisa verde", "Vestimenta",10000.0,3,"https://soulandblues.com/cdn/shop/products/A04008Q_a0ab4a98-ceef-43bb-8849-98460b4aaeb2.jpg");
    BD.agregarProducto(u1);
    BD.agregarProducto(u2);
    BD.agregarProducto(u3);
    BD.agregarProducto(u4);
    BD.agregarProducto(u5);
    BD.agregarProducto(u6);
    BD.agregarProducto(u7);
    BD.agregarProducto(u8);
    BD.guardar();
}