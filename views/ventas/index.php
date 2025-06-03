<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido al Sistema de Ventas y Carrito de Compras!</h5>
                        <h3 class="fw-bold text-primary mb-0">CLIENTES REGISTRADOS</h3>
                    </div>
                    <form id="FormVentas" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="ventas_descuento" name="descuento" value="0">
                        <input type="hidden" id="ventas_observaciones" name="observaciones" value="">
                        
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <label for="ventas_cliente" class="form-label">Cliente</label>
                                <select class="form-select form-select-lg" id="ventas_cliente" name="id_cliente" required>
                                    <option value="">-- Seleccione un cliente --</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row" id="info_cliente_seleccionado" style="display: none;">
                            <div class="col-md-4">
                                <p><strong>Email:</strong> <span id="cliente_email_info">-</span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Teléfono:</strong> <span id="cliente_telefono_info">-</span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>NIT:</strong> <span id="cliente_nit_info">-</span></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Productos Disponibles</h3>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden" id="TableProductosDisponibles">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="seleccionar_todos_productos" class="form-check-input">
                                    </th>
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th width="15%">Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando</span>
                                        </div>
                                        <p class="mt-2">Cargando productos disponibles</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-11">
            <div class="card shadow-lg border-warning rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #fff9c4 60%, #fef3c7 100%);">
                    <h3 class="text-center text-warning-emphasis mb-4">Descripción de compra seleccionada</h3>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="p-3 bg-white rounded-3 shadow-sm">
                                <h5>Productos Seleccionados: <span id="total_productos_badge" class="badge bg-primary">0</span></h5>
                                <div id="lista_productos_carrito">
                                    <p class="text-muted">No hay productos seleccionados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button class="btn btn-success btn-lg px-4 shadow" type="button" id="BtnProcesarFactura" disabled>
                            Procesar Factura
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/ventas/index.js') ?>"></script>