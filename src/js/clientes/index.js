import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { data } from "jquery";

const FormClientes = document.getElementById('FormClientes');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

const GuardarCliente = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    const nombres = document.getElementById('cliente_nombres').value.trim();
    const apellidos = document.getElementById('cliente_apellidos').value.trim();

    if (!nombres) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Campo obligatorio",
            text: "Los nombres del cliente son obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (!apellidos) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Campo obligatorio",
            text: "Los apellidos del cliente son obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormClientes);
    const url = '/app02_macs/clientes/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos)
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarClientes();
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
        console.log(error)
    }
    BtnGuardar.disabled = false;
}

const BuscarClientes = async () => {
    const url = `/app02_macs/clientes/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

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
        console.log(error)
    }
}

const datatable = new DataTable('#TableClientes', {
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
            data: 'cliente_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Nombre Completo', 
            data: 'cliente_nombres',
            render: (data, type, row) => {
                return `${row.cliente_nombres} ${row.cliente_apellidos}`;
            }
        },
        { title: 'Correo', data: 'cliente_email' },
        { title: 'Telefono', data: 'cliente_telefono' },
        { title: 'NIT', data: 'cliente_nit' },
        { title: 'Direccion', data: 'cliente_direccion' },
        {
            title: 'Estado',
            data: 'cliente_estado',
            render: (data, type, row) => {
                const estado = row.cliente_estado
                if (estado == "A") {
                    return `<span class="badge bg-success">ACTIVO</span>`
                } else if (estado == "I") {
                    return `<span class="badge bg-secondary">INACTIVO</span>`
                }
            }
        },
        { title: 'Fecha Registro', data: 'fecha_registro'},
        {
            title: 'Acciones',
            data: 'cliente_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nombres="${row.cliente_nombres}"  
                         data-apellidos="${row.cliente_apellidos}"  
                         data-email="${row.cliente_email || ''}"  
                         data-telefono="${row.cliente_telefono || ''}"  
                         data-direccion="${row.cliente_direccion || ''}"  
                         data-nit="${row.cliente_nit || ''}"  
                         data-estado="${row.cliente_estado}">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('cliente_id').value = datos.id
    document.getElementById('cliente_nombres').value = datos.nombres
    document.getElementById('cliente_apellidos').value = datos.apellidos
    document.getElementById('cliente_email').value = datos.email
    document.getElementById('cliente_telefono').value = datos.telefono
    document.getElementById('cliente_direccion').value = datos.direccion
    document.getElementById('cliente_nit').value = datos.nit
    document.getElementById('cliente_estado').value = datos.estado

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    })
}

const limpiarTodo = () => {
    FormClientes.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    document.querySelectorAll('.is-valid, .is-invalid').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
    });
}

const ModificarCliente = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const nombres = document.getElementById('cliente_nombres').value.trim();
    const apellidos = document.getElementById('cliente_apellidos').value.trim();

    if (!nombres) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Campo obligatorio",
            text: "Los nombres del cliente son obligatorios",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    if (!apellidos) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Campo obligatorio",
            text: "Los apellidos del cliente son obligatorios",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormClientes);
    const url = '/app02_macs/clientes/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarClientes();
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
        console.log(error)
    }
    BtnModificar.disabled = false;
}

const EliminarCliente = async (e) => {
    const idCliente = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este cliente',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url =`/app02_macs/clientes/eliminar?id=${idCliente}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarClientes();
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
            console.log(error)
        }
    }
}

BuscarClientes();
datatable.on('click', '.eliminar', EliminarCliente);
datatable.on('click', '.modificar', llenarFormulario);
FormClientes.addEventListener('submit', GuardarCliente);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarCliente);