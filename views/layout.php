<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>APP02_MACS - Sistema de Carrito</title>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/app02_macs/">
                <img src="<?= asset('images/cit.png') ?>" alt="Logo" width="32" height="32" class="me-2">
                APP02_MACS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/app02_macs/clientes">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/app02_macs/productos">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/app02_macs/ventas">Carrito de Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/app02_macs/facturas">Facturas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <main class="container py-5 my-4 rounded shadow bg-white" style="min-height: 75vh;">
        <?php echo $contenido; ?>
    </main>

    <footer class="bg-primary text-white-50 py-3 mt-auto shadow-sm">
        <div class="container text-center">
            <small>
                <i class="bi bi-cpu me-2"></i>
                Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
            </small>
        </div>
    </footer>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>