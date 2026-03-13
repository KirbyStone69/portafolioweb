// Variables globales
var todasLasBitacoras = [];
var bitacorasFiltradas = [];
var paginaActual = 1;
var registrosPorPagina = 10;
var busqueda = "";

// Funcion para cargar datos desde el servidor
function cargarDatos() {
    fetch('php/bitacoras/listar.php')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (datos) {
            todasLasBitacoras = datos;
            bitacorasFiltradas = datos;
            paginaActual = 1;
            mostrarTabla();
        })
        .catch(function (error) {
            console.log('Error:', error);
            document.getElementById('tabla-bitacoras').innerHTML =
                '<tr><td colspan="5">Error al cargar datos</td></tr>';
        });
}

// Funcion para filtrar datos
function filtrarDatos() {
    var textoBusqueda = busqueda.toLowerCase();

    bitacorasFiltradas = todasLasBitacoras.filter(function (bitacora) {
        var nombre = bitacora.NombreCompleto.toLowerCase();
        var rol = bitacora.Rol.toLowerCase();
        var accion = bitacora.AccionRealizada.toLowerCase();
        var modulo = bitacora.Modulo.toLowerCase();
        return nombre.includes(textoBusqueda) ||
            rol.includes(textoBusqueda) ||
            accion.includes(textoBusqueda) ||
            modulo.includes(textoBusqueda);
    });

    paginaActual = 1;
    mostrarTabla();
}

// Funcion para mostrar la tabla
function mostrarTabla() {
    var tbody = document.getElementById('tabla-bitacoras');
    var total = bitacorasFiltradas.length;
    var totalPaginas = Math.ceil(total / registrosPorPagina);

    if (totalPaginas === 0) {
        totalPaginas = 1;
    }

    if (paginaActual > totalPaginas) {
        paginaActual = totalPaginas;
    }

    var inicio = (paginaActual - 1) * registrosPorPagina;
    var fin = inicio + registrosPorPagina;
    var registrosMostrar = bitacorasFiltradas.slice(inicio, fin);

    var html = '';

    if (registrosMostrar.length === 0) {
        html = '<tr><td colspan="5">No hay registros de bitácora</td></tr>';
    } else {
        for (var i = 0; i < registrosMostrar.length; i++) {
            var bitacora = registrosMostrar[i];
            html += '<tr>';
            html += '<td>';
            html += '<div class="fw-semibold">' + bitacora.NombreCompleto + '</div>';
            html += '<div class="small text-muted">Usuario ID: ' + bitacora.IdUsuario + '</div>';
            html += '</td>';
            html += '<td>' + bitacora.Rol + '</td>';
            html += '<td>' + bitacora.FechaAcceso + '</td>';
            html += '<td>' + bitacora.AccionRealizada + '</td>';
            html += '<td class="text-end"><span class="badge text-bg-light">' + bitacora.Modulo + '</span></td>';
            html += '</tr>';
        }
    }

    tbody.innerHTML = html;

    var inicioMostrar = total === 0 ? 0 : inicio + 1;
    var finMostrar = total === 0 ? 0 : Math.min(fin, total);

    document.getElementById('info-registros').textContent =
        'Mostrando ' + inicioMostrar + ' a ' + finMostrar + ' de ' + total + ' registros';

    document.getElementById('page-info').textContent = paginaActual + '/' + totalPaginas;

    document.getElementById('btn-prev').disabled = (paginaActual === 1);
    document.getElementById('btn-next').disabled = (paginaActual === totalPaginas);
}

// Cuando carga la pagina
document.addEventListener('DOMContentLoaded', function () {
    cargarDatos();

    // Evento para cambiar registros por pagina
    document.getElementById('select-mostrar').addEventListener('change', function () {
        registrosPorPagina = parseInt(this.value);
        paginaActual = 1;
        mostrarTabla();
    });

    // Evento para el buscador
    var inputBuscar = document.querySelector('input[placeholder="Buscar"]');
    inputBuscar.addEventListener('input', function () {
        busqueda = this.value;
        filtrarDatos();
    });

    // Evento boton anterior
    document.getElementById('btn-prev').addEventListener('click', function () {
        if (paginaActual > 1) {
            paginaActual--;
            mostrarTabla();
        }
    });

    // Evento boton siguiente
    document.getElementById('btn-next').addEventListener('click', function () {
        var totalPaginas = Math.ceil(bitacorasFiltradas.length / registrosPorPagina);
        if (paginaActual < totalPaginas) {
            paginaActual++;
            mostrarTabla();
        }
    });
});
