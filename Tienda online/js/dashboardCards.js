import { Base } from "../class/Base.js";

let BD = Base.cargar();

document.addEventListener("DOMContentLoaded", function () {
    const ingresosTotalesCont = document.getElementById("ingresosTotalesCont");
    const pedidosTotalesCont = document.getElementById("pedidosTotalesCont");
    const productosEnInventarioCont = document.getElementById("productosEnInventarioCont");
    const productosBajoStockCont = document.getElementById("productosBajoStockCont");

    let ingresosTotales = 0;

    BD.pedidos.forEach(p => {
        ingresosTotales += p.total;
    });

    ingresosTotalesCont.textContent = `$${ingresosTotales.toFixed(2)}`;

    pedidosTotalesCont.textContent = BD.pedidos.length;
    productosEnInventarioCont.textContent = BD.productos.length;

    let productosBajoStock = BD.productos.filter(p => p.stock < 10).length;
    productosBajoStockCont.textContent = productosBajoStock;
});