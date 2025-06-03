import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormVentas = document.getElementById('FormVentas');
const SelectCliente = document.getElementById('ventas_cliente');
const InfoClienteSeleccionado = document.getElementById('info_cliente_seleccionado');
const BtnProcesarFactura = document.getElementById('BtnProcesarFactura');
const TablaProductosDisponibles = document.getElementById('TableProductosDisponibles');
const SeleccionarTodosProductosElement = document.getElementById('seleccionar_todos_productos');
const VentasDescuento = document.getElementById('ventas_descuento');
const VentasObservaciones = document.getElementById('ventas_observaciones');

let productosDisponibles = [];
let productosSeleccionados = [];

const BuscarClientes = async () => {
    const url = '/app02_macs/ventas/buscarClientesAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectCliente.innerHTML = '<option value="">-- Seleccione un cliente --</option>';
            data.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.cliente_id;
                option.textContent = `${cliente.cliente_nombres} ${cliente.cliente_apellidos}`;
                option.dataset.email = cliente.cliente_email || '';
                option.dataset.telefono = cliente.cliente_telefono || '';
                option.dataset.nit = cliente.cliente_nit || '';
                SelectCliente.appendChild(option);
            });
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
            title: "Error de conexión",
            text: "No se pudieron cargar los clientes",
            showConfirmButton: true,
        });
    }
}

const BuscarProductosDisponibles = async () => {
    const url = '/app02_macs/ventas/buscarProductosDisponiblesAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            productosDisponibles = data;
            RenderizarTablaProductos();
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
            title: "Error de conexión",
            text: "No se pudieron cargar los productos",
            showConfirmButton: true,
        });
    }
}

const RenderizarTablaProductos = () => {
    const cuerpoTabla = TablaProductosDisponibles.querySelector('tbody');
    
    if (productosDisponibles.length === 0) {
        cuerpoTabla.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">
                    <p class="text-muted">No hay productos disponibles</p>
                </td>
            </tr>
        `;
        return;
    }

    cuerpoTabla.innerHTML = '';
    productosDisponibles.forEach(producto => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>
                <input type="checkbox" class="form-check-input producto-checkbox" 
                       data-id="${producto.producto_id}" 
                       data-nombre="${producto.producto_nombre}"
                       data-precio="${producto.producto_precio}"
                       data-stock="${producto.producto_cantidad}">
            </td>
            <td>${producto.producto_nombre}</td>
            <td>${producto.producto_descripcion || ''}</td>
            <td>Q${parseFloat(producto.producto_precio).toFixed(2)}</td>
            <td>${producto.producto_cantidad}</td>
            <td>
                <input type="number" class="form-control cantidad-input" 
                       data-id="${producto.producto_id}"
                       min="1" max="${producto.producto_cantidad}" 
                       value="1" disabled>
            </td>
            <td class="subtotal-celda" data-id="${producto.producto_id}">Q0.00</td>
        `;
        cuerpoTabla.appendChild(fila);
    });
}

const MostrarInfoCliente = (event) => {
    const opcionSeleccionada = event.target.selectedOptions[0];
    
    if (opcionSeleccionada && opcionSeleccionada.value) {
        document.getElementById('cliente_email_info').textContent = opcionSeleccionada.dataset.email || '-';
        document.getElementById('cliente_telefono_info').textContent = opcionSeleccionada.dataset.telefono || '-';
        document.getElementById('cliente_nit_info').textContent = opcionSeleccionada.dataset.nit || '-';
        InfoClienteSeleccionado.style.display = 'block';
    } else {
        InfoClienteSeleccionado.style.display = 'none';
    }
    
    ValidarFormularioVenta();
}

const SeleccionarTodosProductos = (event) => {
    const checkboxes = document.querySelectorAll('.producto-checkbox');
    const cantidadInputs = document.querySelectorAll('.cantidad-input');
    
    checkboxes.forEach((checkbox, index) => {
        checkbox.checked = event.target.checked;
        cantidadInputs[index].disabled = !event.target.checked;
    });
    
    ActualizarCalculos();
}

const ManejarCambiosProductos = (event) => {
    if (event.target.classList.contains('producto-checkbox')) {
        const cantidadInput = document.querySelector(`.cantidad-input[data-id="${event.target.dataset.id}"]`);
        cantidadInput.disabled = !event.target.checked;
        
        if (!event.target.checked) {
            cantidadInput.value = 1;
        }
    }
    
    ActualizarCalculos();
}

const ActualizarCalculos = () => {
    productosSeleccionados = [];
    let totalProductos = 0;
    let total = 0;
    
    const checkboxes = document.querySelectorAll('.producto-checkbox:checked');
    
    checkboxes.forEach(checkbox => {
        const id = checkbox.dataset.id;
        const cantidadInput = document.querySelector(`.cantidad-input[data-id="${id}"]`);
        const cantidad = parseInt(cantidadInput.value) || 1;
        const precio = parseFloat(checkbox.dataset.precio);
        const subtotalProducto = cantidad * precio;
        
        productosSeleccionados.push({
            id: id,
            nombre: checkbox.dataset.nombre,
            cantidad: cantidad,
            precio: precio,
            subtotal: subtotalProducto
        });
        
        total += subtotalProducto;
        totalProductos++;
        
        const subtotalCelda = document.querySelector(`.subtotal-celda[data-id="${id}"]`);
        subtotalCelda.textContent = `Q${subtotalProducto.toFixed(2)}`;
    });
    
    const todasLasCeldas = document.querySelectorAll('.subtotal-celda');
    todasLasCeldas.forEach(celda => {
        const id = celda.dataset.id;
        const checkbox = document.querySelector(`.producto-checkbox[data-id="${id}"]`);
        if (!checkbox.checked) {
            celda.textContent = 'Q0.00';
        }
    });

    document.getElementById('total_productos_badge').textContent = totalProductos;
    
    const totalVentaElement = document.getElementById('total_venta');
    if (totalVentaElement) {
        totalVentaElement.textContent = total.toFixed(2);
    }

    ActualizarListaProductosSeleccionados();
    ValidarFormularioVenta();
}

const ActualizarListaProductosSeleccionados = () => {
    const lista = document.getElementById('lista_productos_carrito');
    
    if (productosSeleccionados.length === 0) {
        lista.innerHTML = '<p class="text-muted">No hay productos seleccionados</p>';
        return;
    }
    
    let html = '';
    productosSeleccionados.forEach(producto => {
        html += `
            <div class="mb-2">
                <span class="badge bg-primary me-2">${producto.cantidad}</span>
                <strong>${producto.nombre}</strong> - 
                Q${producto.precio.toFixed(2)} c/u = 
                <span class="text-success">Q${producto.subtotal.toFixed(2)}</span>
            </div>
        `;
    });
    
    lista.innerHTML = html;
}

const ValidarFormularioVenta = () => {
    const clienteSeleccionado = SelectCliente.value;
    const productosSeleccionadosValidos = productosSeleccionados.length > 0;
    
    BtnProcesarFactura.disabled = !(clienteSeleccionado && productosSeleccionadosValidos);
}

const GuardarVenta = async (event) => {
    event.preventDefault();
    BtnProcesarFactura.disabled = true;

    if (!SelectCliente.value) {
        await Swal.fire({
            position: "center",
            icon: "warning",
            title: "Cliente requerido",
            text: "Debe seleccionar un cliente",
            showConfirmButton: true,
        });
        BtnProcesarFactura.disabled = false;
        return;
    }
    
    if (productosSeleccionados.length === 0) {
        await Swal.fire({
            position: "center",
            icon: "warning",
            title: "Productos requeridos",
            text: "Debe seleccionar al menos un producto",
            showConfirmButton: true,
        });
        BtnProcesarFactura.disabled = false;
        return;
    }

    const body = new FormData(FormVentas);
    body.append('cliente_id', SelectCliente.value);
    body.append('descuento_aplicado', VentasDescuento?.value || 0);
    body.append('observaciones_factura', VentasObservaciones?.value || '');
    
    body.append('productos_seleccionados', JSON.stringify(productosSeleccionados));

    const url = '/app02_macs/ventas/guardarFacturaAPI';
    const config = {
        method: 'POST',
        body
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
                text: `${mensaje}. Factura ${datos.numero_factura} creada exitosamente`,
                showConfirmButton: true,
            });

            LimpiarTodo();
            BuscarProductosDisponibles();

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
            title: "Error",
            text: "Error al procesar la venta",
            showConfirmButton: true,
        });
    }
    BtnProcesarFactura.disabled = false;
}

const LimpiarTodo = () => {
    FormVentas.reset();
    SelectCliente.value = '';
    InfoClienteSeleccionado.style.display = 'none';
    SeleccionarTodosProductosElement.checked = false;
    productosSeleccionados = [];
    
    const checkboxes = document.querySelectorAll('.producto-checkbox');
    const cantidadInputs = document.querySelectorAll('.cantidad-input');
    
    checkboxes.forEach((checkbox, index) => {
        checkbox.checked = false;
        cantidadInputs[index].disabled = true;
        cantidadInputs[index].value = 1;
    });
    
    const inputs = FormVentas.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
    
    ActualizarCalculos();
}

BuscarClientes();
BuscarProductosDisponibles();
SelectCliente.addEventListener('change', MostrarInfoCliente);
SeleccionarTodosProductosElement.addEventListener('change', SeleccionarTodosProductos);
TablaProductosDisponibles.addEventListener('change', ManejarCambiosProductos);
FormVentas.addEventListener('submit', GuardarVenta);
BtnProcesarFactura.addEventListener('click', GuardarVenta);