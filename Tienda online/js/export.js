/*
▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒░░░░░░░░░░▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
▒▒▒▒▒▒▒▒▒▒▒▒▒░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒▒▒▒▒▒
▒▒▒▒▒▒▒▒▒▒░░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒▒▒
▒▒▒▒▒▒▒▒░░░░░░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒
▒▒▒▒▒▒░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░▄░░▒▒▒▒▒
▒▒▒▒▒░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░██▌░░▒▒▒▒
▒▒▒▒░░░░░░░░░░░░░░░░░░░░░░░░░░░▄▄███▀░░░░▒▒▒
▒▒▒░░░░░░░░░░░░░░░░░░░░░░░░░░░█████░▄█░░░░▒▒
▒▒░░░░░░░░░░░░░░░░░░░░░░░░░░▄████████▀░░░░▒▒
▒▒░░░░░░░░░░░░░░░░░░░░░░░░▄█████████░░░░░░░▒
▒░░░░░░░░░░░░░░░░░░░░░░░░░░▄███████▌░░░░░░░▒
▒░░░░░░░░░░░░░░░░░░░░░░░░▄█████████░░░░░░░░▒
▒░░░░░░░░░░░░░░░░░░░░░▄███████████▌░░░░░░░░▒
▒░░░░░░░░░░░░░░░▄▄▄▄██████████████▌░░░░░░░░▒
▒░░░░░░░░░░░▄▄███████████████████▌░░░░░░░░░▒
▒░░░░░░░░░▄██████████████████████▌░░░░░░░░░▒
▒░░░░░░░░████████████████████████░░░░░░░░░░▒
▒█░░░░░▐██████████▌░▀▀███████████░░░░░░░░░░▒
▐██░░░▄██████████▌░░░░░░░░░▀██▐█▌░░░░░░░░░▒▒
▒██████░█████████░░░░░░░░░░░▐█▐█▌░░░░░░░░░▒▒
▒▒▀▀▀▀░░░██████▀░░░░░░░░░░░░▐█▐█▌░░░░░░░░▒▒▒
▒▒▒▒▒░░░░▐█████▌░░░░░░░░░░░░▐█▐█▌░░░░░░░▒▒▒▒
▒▒▒▒▒▒░░░░███▀██░░░░░░░░░░░░░█░█▌░░░░░░▒▒▒▒▒
▒▒▒▒▒▒▒▒░▐██░░░██░░░░░░░░▄▄████████▄▒▒▒▒▒▒▒▒
▒▒▒▒▒▒▒▒▒██▌░░░░█▄░░░░░░▄███████████████████
▒▒▒▒▒▒▒▒▒▐██▒▒░░░██▄▄███████████████████████
▒▒▒▒▒▒▒▒▒▒▐██▒▒▄████████████████████████████
▒▒▒▒▒▒▒▒▒▒▄▄████████████████████████████████
████████████████████████████████████████████
*/
import { Base } from "../class/Base.js";

const exportButton = document.getElementById('Export');

//al precionar el boton se ejecutan estas 3 funciones
exportButton.addEventListener('click', function () {
    // se hacen las filas con los datos
    const filas = construirFilasProductosYVentas();
    //esos datos se convierten a formato csv con puntitos y asi
    const csv = convertirFilasACSV(filas);
    //se descarga el archivo con un nombre que incluye la fecha y hora actual
    descargarCSV("productos_ventas_"+ ".csv", csv);
});


function construirFilasProductosYVentas() {
    const BD = Base.cargar();

    // se crea esta variable que sera un hashmap para contar ventas por id de producto
    let vendidasPorId = {}; 

    // Si hay pedidos los recorro
    if (BD && BD.pedidos) {
        //voy pedido por pedido
        for (let i = 0; i < BD.pedidos.length; i++) {
            let ped = BD.pedidos[i];

            // Si el pedido tiene lista de productos, la tomo, si no pus no
            let listaProd = [];
            if (ped && ped.idproductos) {
                listaProd = ped.idproductos;
            }

            //va todos los ides de productos
            for (let j = 0; j < listaProd.length; j++) {
                let idProductoVendido = listaProd[j];

                // Si es la primera vez que veo este id, lo inicio en 0
                if (!vendidasPorId[idProductoVendido]) {
                    vendidasPorId[idProductoVendido] = 0;
                }

                // Sumo una unidad vendida
                vendidasPorId[idProductoVendido] = vendidasPorId[idProductoVendido] + 1;
            }
        }
    }

    let filas = [];

    // Recorro los productos existentes
    if (BD && BD.productos) {
        for (let k = 0; k < BD.productos.length; k++) {
            let producto = BD.productos[k];
            // Unidades vendidas de este producto si no se vendio es 0 
            let unidadesVendidas = 0;
            if (vendidasPorId[producto.id]) {
                unidadesVendidas = vendidasPorId[producto.id];
            }
            // Precio actual del producto 
            let precioUnitario = Number(producto.precio);
            if (isNaN(precioUnitario)) {
                precioUnitario = 0; // por si viene algo raro osea que no sea numero
            }

            let ingresosSinIVA = precioUnitario * unidadesVendidas; //sin iva
            let iva16 = ingresosSinIVA * 0.16;// iva
            let ingresosConIVA = ingresosSinIVA + iva16;//total con iva

            let fila = {
                ProductoID: producto.id,
                Nombre: producto.nombre,
                Categoria: producto.categoria,
                PrecioUnitario: aDosDecimales(precioUnitario),
                UnidadesVendidas: unidadesVendidas,
                IngresosSinIVA: aDosDecimales(ingresosSinIVA),
                IVA_16: aDosDecimales(iva16),
                IngresosConIVA: aDosDecimales(ingresosConIVA),
                StockActual: producto.stock
            };

            // agrego la fila al arreglo general
            filas.push(fila);
        }
    }

    if (filas.length === 0) {
        filas.push({
            ProductoID: "",
            Nombre: "",
            Categoria: "",
            PrecioUnitario: "",
            UnidadesVendidas: "",
            IngresosSinIVA: "",
            IVA_16: "",
            IngresosConIVA: "",
            StockActual: ""
        });
    }

    //de aqui toca control de caracteres
    return filas;
}

function convertirFilasACSV(filas) {
    let headers = [
        "ProductoID",
        "Nombre",
        "Categoria",
        "PrecioUnitario",
        "UnidadesVendidas",
        "IngresosSinIVA",
        "IVA_16",
        "IngresosConIVA",
        "StockActual"
    ];

    // va encabezado por encabezado poniendo comas de separacion
    let lineaEncabezados = "";
    for (let i = 0; i < headers.length; i++) {
        lineaEncabezados = lineaEncabezados + ponerEntreComillas(headers[i]);
        if (i < headers.length - 1) {
            lineaEncabezados = lineaEncabezados + ",";
        }
    }

    let lineasCuerpo = "";
    for (let r = 0; r < filas.length; r++) {
        let fila = filas[r];

        for (let h = 0; h < headers.length; h++) {
            let nombreColumna = headers[h];
            let valor = "";

            // si la propiedad existe, la tomo, si no, dejo vacío
            if (fila && fila[nombreColumna] !== undefined && fila[nombreColumna] !== null) {
                valor = fila[nombreColumna];
            }

            // pongo el valor entre comillas dobles y escapo comillas internas
            lineasCuerpo = lineasCuerpo + ponerEntreComillas(valor);

            // separo por coma, excepto al final de la línea
            if (h < headers.length - 1) {
                lineasCuerpo = lineasCuerpo + ",";
            }
        }

        // salto de línea menos en la última fila 
        if (r < filas.length - 1) {
            lineasCuerpo = lineasCuerpo + "\n";
        }
    }

    // IMPORTANTE: agrego el BOM (\uFEFF) para que Excel respete UTF-8 osea los acentos pues
    let csvCompleto = "\uFEFF" + lineaEncabezados + "\n" + lineasCuerpo;

    // regreso el texto CSV ya se armo, cheves hoy
    return csvCompleto;
}


function descargarCSV(nombreArchivo, textoCSV) {
    // Creo un "blob" es un archivo en memoria con el texto del CSV
    let blob = new Blob([textoCSV], { type: "text/csv;charset=utf-8;" });

    // Creo una URL temporal para ese blob
    let url = URL.createObjectURL(blob);

    // Creo un <a> para la descarga
    let a = document.createElement("a");
    a.href = url;
    a.download = nombreArchivo;

    // Lo agrego, le doy click y lo quito
    document.body.appendChild(a);
    a.click();
    a.remove();

    // Libero la URL temporal
    URL.revokeObjectURL(url);

    /*
    aqui me la complique por que si hubiera usado un hiervinculo de primeras 
    no deberia de estar haciendo esto pero queria poner un boton de boostrap por que estan bonitos
    la verdad no se si alguien va a leer esto pero quiero aprocechar para saludar a mi a mi tio,
    a mi tia,
    a mi hermana,
    a mi abuelo,
    a mi tatarabuelo,
    a mi bisabuelo,
    a mi socio,
    a mis amigos,
    al bagabundo,
    al basurero,
    al cartonero,
    a cristiano ronaldo,
    a messi,
    a disney,
    al computador,
    a mi cama,
    a mi gato,
    a mi pez,
    a mi perro,
    a mi maestro,
    */
}

// Formatea un número a 2 decimales
function aDosDecimales(numero) {
    return Number(numero).toFixed(2);
}

// Pone un valor entre comillas y además duplica las comillas internas
// para que el CSV no se rompa si hay comas o comillas adentro del texto.
function ponerEntreComillas(valor) {
    let texto = String(valor);
    texto = texto.replace(/"/g, '""'); // " -> ""
    return '"' + texto + '"';
}