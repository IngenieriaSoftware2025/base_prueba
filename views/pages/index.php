<style>
    body {
        background: linear-gradient(135deg,rgb(109, 109, 109) 0%,rgb(179, 188, 204) 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    .header {
        padding: 2rem;
        text-align: center;
        border-radius: 15px;
        margin-top: 2rem;
        margin-bottom: 2rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .logo {
        font-size: 3rem;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 1rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .container {
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .product-img {
      border-radius: 10px;
      width: 100%;
      height: 100%;
      max-height: 300px;
      object-fit: cover;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      margin-bottom: 1rem;
      background-color: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 1.2rem;
      text-align: center;
    }

    .product-img:hover {
      transform: scale(1.05) rotate(-2deg);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      cursor: pointer;
    }
    
</style>
<body>
    <div class="header">
        <div class="logo">¡Bienvenido a tu Sistema de Facturación!</div>
    </div>
    
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <p class="lead">
                    "Gestiona de manera eficiente tus clientes, productos y facturas, controla tu inventario, procesa ventas y genera documentos profesionales de forma rápida y sencilla."
                </p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12 text-center mb-4">
                <h2 class="text-uppercase fw-bold">Módulos del Sistema</h2>
                <p class="text-muted">Administra todos los aspectos de tu negocio desde una sola plataforma.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://www.questionpro.com/blog/wp-content/uploads/2016/08/Portada-gestion-de-clientes.jpg" alt="Gestión de Clientes" style="max-width:100%; max-height:100%; border-radius:10px;">
                      </a>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Gestión de Clientes</h5>
                        <p class="card-text text-muted">Administra tu base de clientes de forma organizada y eficiente.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://www.mrpeasy.com/blog/wp-content/uploads/2024/01/production-control.jpg" alt="productos" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Control de Productos</h5>
                        <p class="card-text text-muted">Gestiona tu inventario con control de stock y precios actualizados.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://blog.qupos.com/hs-fs/hubfs/que-es-un-sistema-punto-de-venta-4.png?width=1600&name=que-es-un-sistema-punto-de-venta-4.png" alt="ventas" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Sistema de Ventas</h5>
                        <p class="card-text text-muted">Procesa ventas y genera facturas profesionales con PDF incluido.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-6 mx-auto text-center">
                <h2 class="mb-4 text-uppercase fw-bold">Características Principales</h2><br>
                <p><strong>Organiza:</strong> mantén ordenados todos tus datos de clientes y productos.</p>
                <p><strong>Controla:</strong> supervisa tu inventario y valida transacciones en tiempo real.</p>
                <p><strong>Documenta:</strong> genera facturas profesionales con numeración automática y PDF.</p>
                <a href="/app02_macs/clientes" class="btn btn-primary mt-3">Comenzar a usar el sistema</a>
            </div>
        </div>
    </div>
    <script src="<?= asset('build/js/inicio.js') ?>"></script>
</body>