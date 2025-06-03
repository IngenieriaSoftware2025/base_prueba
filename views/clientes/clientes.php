<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a la Aplicación para el registro, modificación y eliminación de clientes!</h5>
                        <h3 class="fw-bold text-primary mb-0">GESTIÓN DE CLIENTES</h3>
                    </div>
                    <form id="FormClientes" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="cliente_id" name="cliente_id">
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="cliente_nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_nombres" name="cliente_nombres" placeholder="Ingrese los nombres del cliente" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_apellidos" name="cliente_apellidos" placeholder="Ingrese los apellidos del cliente" required>
                            </div>
                        </div>
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="cliente_email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control form-control-lg" id="cliente_email" name="cliente_email" placeholder="ejemplo@ejemplo.com">
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_telefono" class="form-label">Teléfono</label>
                                <input type="number" class="form-control form-control-lg" id="cliente_telefono" name="cliente_telefono" placeholder="Ingrese el número de teléfono" required>
                            </div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="cliente_nit" class="form-label">NIT</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_nit" name="cliente_nit" placeholder="Ingrese el NIT del cliente">
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_direccion" name="cliente_direccion" placeholder="Ingrese la dirección completa">
                            </div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="cliente_estado" class="form-label">Estado del cliente</label>
                                <select name="cliente_estado" class="form-select form-select-lg" id="cliente_estado" required>
                                    <option value="">Seleccione el estado</option>
                                    <option value="A">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
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
                    <h3 class="text-center text-primary mb-4">Clientes registrados en la base de datos</h3>

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
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden" id="TableClientes">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre Completo</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>NIT</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
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
<script src="<?= asset('build/js/clientes/index.js') ?>"></script>