<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a la Aplicación para el registro, modificación y eliminación de productos!</h5>
                        <h3 class="fw-bold text-primary mb-0">MANIPULACIÓN DE PRODUCTOS</h3>
                    </div>
                    <form id="FormProductos" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="producto_id" name="producto_id">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="producto_nombre" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control form-control-lg" id="producto_nombre" name="producto_nombre" placeholder="Ingrese el nombre del producto" required>
                            </div>
                            <div class="col-md-6">
                                <label for="producto_precio" class="form-label">Precio</label>
                                <input type="number" step="0.01" class="form-control form-control-lg" id="producto_precio" name="producto_precio" placeholder="0.00" required>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="producto_cantidad" class="form-label">Cantidad Disponible</label>
                                <input type="number" class="form-control form-control-lg" id="producto_cantidad" name="producto_cantidad" placeholder="Cantidad disponible" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center mt-5">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Productos registrados en la base de datos</h3>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                            <input type="date" id="fecha_inicio" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_fin" class="form-label">Fecha de fin</label>
                            <input type="date" id="fecha_fin" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary btn-lg w-100 shadow" id="btn_filtrar_fecha">
                                <i class="bi bi-funnel-fill me-2"></i>Buscar por fecha
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden" id="TableProductos">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Stock Mínimo</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/productos/index.js') ?>"></script>