import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormProductos = document.getElementById('FormProductos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

const GuardarProducto = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    const nombre = document.getElementById('producto_nombre').value.trim();
    const precio = document.getElementById('producto_precio').value.trim();
    const cantidad = document.getElementById('producto_cantidad').value.trim();

    if (!nombre) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Campo obligatorio",
            text: "El nombre del producto es obligatorio",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (!precio || parseFloat(precio) <= 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Precio inválido",
            text: "El precio debe ser mayor a 0",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (!cantidad || parseInt(cantidad) < 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Cantidad inválida",
            text: "La cantidad no puede ser negativa",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormProductos);
    body.append('producto_stock_minimo', '1');
    body.append('producto_estado', 'D');
    body.append('producto_descripcion', ''); 

    const url = '/app02_macs/productos/guardarAPI';
    const config = {
        method: 'POST',
        body
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos);
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarProductos();
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
    BtnGuardar.disabled = false;
};

const BuscarProductos = async () => {
    const url = '/app02_macs/productos/buscarAPI';
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            if (datatable) {
                datatable.clear().draw();
                if (data && data.length > 0) {
                    datatable.rows.add(data).draw();
                }
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
            text: "No se pudieron cargar los productos",
            showConfirmButton: true,
        });
    }
};

const datatable = new DataTable('#TableProductos', {
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
            data: 'producto_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Producto', 
            data: 'producto_nombre' 
        },
        { 
            title: 'Precio', 
            data: 'producto_precio',
            render: (data) => `Q${parseFloat(data || 0).toFixed(2)}`
        },
        { 
            title: 'Cantidad', 
            data: 'producto_cantidad' 
        },
        { 
            title: 'Stock Mínimo', 
            data: 'producto_stock_minimo',
            render: (data) => data || '1'
        },
        {
            title: 'Estado',
            data: 'producto_estado',
            render: (data, type, row) => {
                const estado = row.producto_estado;
                const cantidad = parseInt(row.producto_cantidad || 0);
                
                if (estado === "D" && cantidad > 0) {
                    return `<span class="badge bg-success">DISPONIBLE</span>`;
                } else if (estado === "D" && cantidad === 0) {
                    return `<span class="badge bg-warning">SIN STOCK</span>`;
                } else {
                    return `<span class="badge bg-secondary">NO DISPONIBLE</span>`;
                }
            }
        },
        { 
            title: 'Fecha Creación', 
            data: 'fecha_creacion',
            render: (data) => {
                if (data) {
                    return new Date(data).toLocaleDateString();
                }
                return '-';
            }
        },
        {
            title: 'Acciones',
            data: 'producto_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nombre="${row.producto_nombre}"  
                         data-precio="${row.producto_precio}"  
                         data-cantidad="${row.producto_cantidad}"  
                         data-stock-minimo="${row.producto_stock_minimo || '1'}">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}"
                         data-cantidad="${row.producto_cantidad}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('producto_id').value = datos.id;
    document.getElementById('producto_nombre').value = datos.nombre;
    document.getElementById('producto_precio').value = datos.precio;
    document.getElementById('producto_cantidad').value = datos.cantidad;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

const limpiarTodo = () => {
    FormProductos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    document.querySelectorAll('.is-valid, .is-invalid').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
    });
};

const ModificarProducto = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const nombre = document.getElementById('producto_nombre').value.trim();
    const precio = document.getElementById('producto_precio').value.trim();
    const cantidad = document.getElementById('producto_cantidad').value.trim();

    if (!nombre) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Campo obligatorio",
            text: "El nombre del producto es obligatorio",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    if (!precio || parseFloat(precio) <= 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Precio inválido",
            text: "El precio debe ser mayor a 0",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    if (!cantidad || parseInt(cantidad) < 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Cantidad inválida",
            text: "La cantidad no puede ser negativa",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormProductos);
    body.append('producto_stock_minimo', '1');
    body.append('producto_estado', 'D');
    body.append('producto_descripcion', '');

    const url = '/app02_macs/productos/modificarAPI';
    const config = {
        method: 'POST',
        body
    };

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

            limpiarTodo();
            BuscarProductos();
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
    BtnModificar.disabled = false;
};

const EliminarProducto = async (e) => {
    const idProducto = e.currentTarget.dataset.id;
    const cantidad = e.currentTarget.dataset.cantidad;

    if (parseInt(cantidad) > 0) {
        await Swal.fire({
            position: "center",
            icon: "warning",
            title: "No se puede eliminar",
            text: "El producto tiene cantidad disponible. No se puede eliminar.",
            showConfirmButton: true,
        });
        return;
    }

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea ejecutar esta acción?",
        text: '¿Está completamente seguro que desea eliminar este producto?',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/app02_macs/productos/eliminar?id=${idProducto}`;
        const config = {
            method: 'GET'
        };

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });

                BuscarProductos();
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
                text: "No se pudo conectar con el servidor",
                showConfirmButton: true,
            });
        }
    }
};

BuscarProductos();
datatable.on('click', '.eliminar', EliminarProducto);
datatable.on('click', '.modificar', llenarFormulario);
FormProductos.addEventListener('submit', GuardarProducto);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarProducto);