export class Pedido {
    constructor(usuarioId, total) {
        this.folio = this.addfolio();
        this.fecha = new Date();
        this.usuarioId = usuarioId;
        this.idproductos = [];
        this.total = total;
    }
    addidproductos(productoId) {
        this.idproductos.push(productoId);
    }
    addfolio() {
        const f = new Date();
        const dia = String(f.getDate()).padStart(2, '0');
        const mes = String(f.getMonth() + 1).padStart(2, '0');
        const anio = f.getFullYear();
        const segundosDesde1970 = Math.floor(f.getTime() / 1000);
        return `MP${dia}${mes}${anio}${segundosDesde1970}`;
    }
}