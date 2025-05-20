<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
                    <h2 class="mb-1 fw-bold">Manipulación de Usuarios</h2>
                    <p class="mb-0 fs-6">¡Bienvenido a la aplicación para el registro, modificación y eliminación de usuario!</p>
                </div>
                <div class="card-body p-5">
                    <form id="FormUsuarios">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control rounded-pill shadow-sm" id="usuario_nombres" name="usuario_nombres">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control rounded-pill shadow-sm" id="usuario_apellidos" name="usuario_apellidos">
                            </div>
                            <div class="col-md-6">
                                <label for="nit" class="form-label">NIT</label>
                                <input type="number" class="form-control rounded-pill shadow-sm" id="usuario_nit" name="usuario_nit">
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="number" class="form-control rounded-pill shadow-sm" id="usuario_telefono" name="usuario_telefono">
                            </div>
                            <div class="col-md-12">
                                <label for="correo" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control rounded-pill shadow-sm" id="usuario_correo" name="usuario_correo">
                            </div>
                            <div class="col-md-6">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select rounded-pill shadow-sm" id="usuario_estado" name="usuario_estado">
                                    <option value="P">Presente</option>
                                    <option value="F">Faltando</option>
                                    <option value="C">Comisión</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="situacion" class="form-label">Situación</label>
                                <select class="form-select rounded-pill shadow-sm" id="usuario_situacion" name="usuario_situacion">
                                    <option value="1" selected>Habilitado</option>
                                    <option value="0">Deshabilitado</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm" id="BtnGuardar">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                            <button type="submit" class="btn btn-warning px-4 rounded-pill shadow-sm d-none" id="BtnModificar">
                                <i class="bi bi-pencil-square"></i> Modificar
                            </button>
                            <button type="reset" class="btn btn-secondary px-4 rounded-pill shadow-sm" id="BtnLimpiar">
                                <i class="bi bi-eraser"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/usuarios/index.js') ?>"></script>