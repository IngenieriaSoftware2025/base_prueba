import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

let facturaActual = null;
let productosDisponibles = [];
let productosFactura = [];

const BuscarFacturas = async () => {
    const url = `/app02_macs/facturas/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            if (datatable) {
                datatable.clear().draw();
                datatable.rows.add(data).draw();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
}

const ModificarFactura = async (event) => {
    const id = event.currentTarget.dataset.id;
    
    const url = `/app02_macs/ventas/buscarFacturaPorIdAPI?id=${id}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            await CargarProductosDisponibles();
            MostrarModalModificar(data);
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error al cargar los datos de la factura",
            showConfirmButton: true,
        });
    }
}

const CargarProductosDisponibles = async () => {
    const url = `/app02_macs/ventas/buscarProductosDisponiblesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        if (codigo == 1) {
            productosDisponibles = data;
        }
    } catch (error) {
        console.log(error);
    }
}

const MostrarModalModificar = (facturaData) => {
    facturaActual = facturaData.informacion_factura;
    productosFactura = [...(facturaData.productos_factura || [])];

    const productosHTML = GenerarTablaProductosEditable();
    const selectorProductos = GenerarSelectorProductos();
    const resumenCompra = GenerarResumenCompra();

    const modalContent = `
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-primary rounded-4">
                        <div class="card-body">
                            <h6 class="text-primary">Información de Factura</h6>
                            <p><strong>Número:</strong> ${facturaActual.factura_numero}</p>
                            <p><strong>Cliente:</strong> ${facturaData.datos_cliente.cliente_nombres} ${facturaData.datos_cliente.cliente_apellidos}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-primary rounded-4 mb-3">
                <div class="card-body">
                    <h6 class="text-primary text-center mb-3"><i class="bi bi-cart3"></i> Productos de la Factura</h6>
                    
                    <div class="mb-3">
                        <h6 class="text-secondary">Productos Actuales</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio Unitario</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProductosModificar">
                                    ${productosHTML}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div>
                        <h6 class="text-secondary">Agregar Productos Adicionales</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Seleccionar Producto</label>
                                <select class="form-select" id="selectorProductoNuevo">
                                    <option value="">Seleccione un producto para agregar</option>
                                    ${selectorProductos}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidadProductoNuevo" value="1" min="1">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-success w-100" onclick="AgregarProductoNuevo()">
                                    <i class="bi bi-plus-circle"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-primary rounded-4">
                <div class="card-body">
                    <h5 class="text-primary text-center mb-3">Resumen de Compra</h5>
                    <div id="resumenCompraModificar">
                        ${resumenCompra}
                    </div>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        title: `Modificar Factura ${facturaActual.factura_numero}`,
        html: modalContent,
        width: '1000px',
        showConfirmButton: true,
        confirmButtonText: 'Guardar Cambios',
        confirmButtonColor: '#28a745',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        cancelButtonColor: '#6c757d',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            GuardarCambiosFactura();
        }
    });
}

const GenerarTablaProductosEditable = () => {
    if (!productosFactura || productosFactura.length === 0) {
        return '<tr><td colspan="5" class="text-center text-muted">No hay productos en esta factura</td></tr>';
    }
    
    return productosFactura.map((producto, index) => `
        <tr data-index="${index}">
            <td>${producto.producto_nombre}</td>
            <td class="text-end">Q${parseFloat(producto.detalle_precio_unitario).toFixed(2)}</td>
            <td>
                <input type="number" class="form-control form-control-sm text-center" 
                       value="${producto.detalle_cantidad}" 
                       min="1" 
                       data-index="${index}"
                       onchange="ActualizarCantidadProducto(${index}, this.value)">
            </td>
            <td class="text-end">Q${parseFloat(producto.detalle_total).toFixed(2)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-info" onclick="EliminarProductoFactura(${index})" title="Eliminar producto">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

const GenerarSelectorProductos = () => {
    return productosDisponibles.map(producto => `
        <option value="${producto.producto_id}" data-precio="${producto.producto_precio}">
            ${producto.producto_nombre} - Q${parseFloat(producto.producto_precio).toFixed(2)}
        </option>
    `).join('');
}

const GenerarResumenCompra = () => {
    if (!productosFactura || productosFactura.length === 0) {
        return '<p class="text-center text-muted">No hay productos seleccionados</p>';
    }

    const productosResumen = productosFactura.map((producto, index) => `
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <span class="badge bg-primary rounded-circle me-2">${index + 1}</span>
            <div class="flex-grow-1">
                <strong>${producto.producto_nombre}</strong> - Q${parseFloat(producto.detalle_precio_unitario).toFixed(2)} c/u = Q${parseFloat(producto.detalle_total).toFixed(2)}
            </div>
        </div>
    `).join('');

    const totalGeneral = productosFactura.reduce((sum, producto) => sum + parseFloat(producto.detalle_total), 0);

    return `
        <div>
            <p><strong>Productos Seleccionados:</strong> <span class="badge bg-primary">${productosFactura.length}</span></p>
            ${productosResumen}
            <div class="text-end mt-3">
                <h5><strong>Total: Q${totalGeneral.toFixed(2)}</strong></h5>
            </div>
        </div>
    `;
}

const ActualizarCantidadProducto = (index, nuevaCantidad) => {
    const cantidad = parseInt(nuevaCantidad);
    if (cantidad > 0) {
        productosFactura[index].detalle_cantidad = cantidad;
        productosFactura[index].detalle_subtotal = cantidad * parseFloat(productosFactura[index].detalle_precio_unitario);
        productosFactura[index].detalle_total = productosFactura[index].detalle_subtotal;
        
        document.querySelector(`tr[data-index="${index}"] td:nth-child(4)`).textContent = 
            `Q${parseFloat(productosFactura[index].detalle_total).toFixed(2)}`;
        
        RecalcularTotales();
    }
}

const EliminarProductoFactura = (index) => {
    productosFactura.splice(index, 1);
    document.getElementById('tablaProductosModificar').innerHTML = GenerarTablaProductosEditable();
    RecalcularTotales();
}

const AgregarProductoNuevo = () => {
    const selector = document.getElementById('selectorProductoNuevo');
    const cantidad = parseInt(document.getElementById('cantidadProductoNuevo').value);
    
    if (selector.value && cantidad > 0) {
        const productoSeleccionado = productosDisponibles.find(p => p.producto_id == selector.value);
        const precio = parseFloat(productoSeleccionado.producto_precio);
        
        const nuevoProducto = {
            producto_id: productoSeleccionado.producto_id,
            producto_nombre: productoSeleccionado.producto_nombre,
            detalle_cantidad: cantidad,
            detalle_precio_unitario: precio,
            detalle_subtotal: cantidad * precio,
            detalle_total: cantidad * precio
        };
        
        productosFactura.push(nuevoProducto);
        document.getElementById('tablaProductosModificar').innerHTML = GenerarTablaProductosEditable();
        
        selector.value = '';
        document.getElementById('cantidadProductoNuevo').value = '1';
        
        RecalcularTotales();
    }
}

const RecalcularTotales = () => {
    document.getElementById('resumenCompraModificar').innerHTML = GenerarResumenCompra();
}

const GuardarCambiosFactura = async () => {
    if (productosFactura.length === 0) {
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe tener al menos un producto en la factura'
        });
        return;
    }

    const productosParaAPI = productosFactura.map(producto => ({
        id: producto.producto_id,
        cantidad: producto.detalle_cantidad,
        precio: producto.detalle_precio_unitario
    }));

    const formData = new FormData();
    formData.append('factura_id', facturaActual.factura_id);
    formData.append('productos_actualizados', JSON.stringify(productosParaAPI));
    formData.append('descuento_actualizado', '0');
    formData.append('observaciones_actualizadas', '');

    const url = `/app02_macs/facturas/modificarAPI`;
    const config = {
        method: 'POST',
        body: formData
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });
            BuscarFacturas();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error al guardar los cambios",
            showConfirmButton: true,
        });
    }
}

const VerDetallesFactura = async (event) => {
    const id = event.currentTarget.dataset.id;
    
    const url = `/app02_macs/ventas/buscarFacturaPorIdAPI?id=${id}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            MostrarModalDetalles(data);
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error al cargar los detalles de la factura",
            showConfirmButton: true,
        });
    }
}

const MostrarModalDetalles = (facturaData) => {
    const factura = facturaData.informacion_factura;
    const cliente = facturaData.datos_cliente;
    const detalles = facturaData.productos_factura;

    let productosHTML = '';
    if (detalles && detalles.length > 0) {
        productosHTML = detalles.map(detalle => `
            <tr>
                <td>${detalle.producto_nombre}</td>
                <td class="text-center">${detalle.detalle_cantidad}</td>
                <td class="text-end">Q${parseFloat(detalle.detalle_precio_unitario).toFixed(2)}</td>
                <td class="text-end">Q${parseFloat(detalle.detalle_total).toFixed(2)}</td>
            </tr>
        `).join('');
    }

    const modalContent = `
        <div class="row">
            <div class="col-md-6">
                <h6>Información de Factura</h6>
                <p><strong>Número:</strong> ${factura.factura_numero}</p>
                <p><strong>Fecha:</strong> ${new Date(factura.factura_fecha).toLocaleDateString()}</p>
                <p><strong>Estado:</strong> <span class="badge bg-primary">${factura.factura_estado}</span></p>
            </div>
            <div class="col-md-6">
                <h6>Cliente</h6>
                <p><strong>Nombre:</strong> ${cliente.cliente_nombres} ${cliente.cliente_apellidos}</p>
                <p><strong>NIT:</strong> ${cliente.cliente_nit || '-'}</p>
                <p><strong>Email:</strong> ${cliente.cliente_email || '-'}</p>
            </div>
        </div>
        <hr>
        <h6>Productos</h6>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                ${productosHTML}
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Subtotal:</strong></td>
                        <td class="text-end">Q${parseFloat(factura.factura_subtotal).toFixed(2)}</td>
                    </tr>
                    <tr>
                        <td><strong>IVA:</strong></td>
                        <td class="text-end">Q${parseFloat(factura.factura_iva).toFixed(2)}</td>
                    </tr>
                    <tr>
                        <td><strong>Descuento:</strong></td>
                        <td class="text-end">Q${parseFloat(factura.factura_descuento || 0).toFixed(2)}</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>TOTAL:</strong></td>
                        <td class="text-end"><strong>Q${parseFloat(factura.factura_total).toFixed(2)}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    `;

    Swal.fire({
        title: `Detalles de Factura ${factura.factura_numero}`,
        html: modalContent,
        width: '800px',
        showConfirmButton: true,
        confirmButtonText: 'Cerrar',
        showCancelButton: false
    });
}

const datatable = new DataTable('#TableFacturas', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'factura_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Número Factura', 
            data: 'factura_numero'
        },
        { 
            title: 'Cliente', 
            data: 'cliente_nombres',
            render: (data, type, row) => `${row.cliente_nombres} ${row.cliente_apellidos}`
        },
        { 
            title: 'NIT', 
            data: 'cliente_nit'
        },
        { 
            title: 'Fecha', 
            data: 'factura_fecha',
            render: (data) => new Date(data).toLocaleDateString()
        },
        { 
            title: 'Total', 
            data: 'factura_total',
            render: (data) => `Q${parseFloat(data).toFixed(2)}`
        },
        {
            title: 'Estado',
            data: 'factura_estado',
            render: (data) => {
                let badgeClass = 'bg-secondary';
                switch (data) {
                    case 'PROCESADA':
                        badgeClass = 'bg-primary';
                        break;
                    case 'PAGADA':
                        badgeClass = 'bg-success';
                        break;
                    case 'ANULADA':
                        badgeClass = 'bg-danger';
                        break;
                }
                return `<span class="badge ${badgeClass}">${data}</span>`;
            }
        },
        {
            title: 'Acciones',
            data: 'factura_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                    <div class='d-flex justify-content-center'>
                        <button class='btn btn-warning modificar mx-1' 
                            data-id="${data}" 
                            data-numero="${row.factura_numero}"
                            title="Modificar factura">
                            <i class='bi bi-pencil-square me-1'></i> Modificar
                        </button>
                        <button class='btn btn-info ver-detalles mx-1' 
                            data-id="${data}" 
                            data-numero="${row.factura_numero}"
                            title="Ver detalles">
                            <i class="bi bi-eye me-1"></i> Ver
                        </button>
                    </div>
                `;
            }
        }
    ]
});

window.AgregarProductoNuevo = AgregarProductoNuevo;
window.EliminarProductoFactura = EliminarProductoFactura;
window.ActualizarCantidadProducto = ActualizarCantidadProducto;

BuscarFacturas();
datatable.on('click', '.modificar', ModificarFactura);
datatable.on('click', '.ver-detalles', VerDetallesFactura);