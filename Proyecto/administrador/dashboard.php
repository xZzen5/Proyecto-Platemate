<?php
if(session_status() == PHP_SESSION_NONE){
  // Si no hay ninguna sesión activa, la iniciamos
  session_start();
}
include_once('config/config.php');
include_once('config/checklogin.php');
check_login();

require_once('partials/_head.php');
require_once('partials/_analytics.php');
?>

<body style="background-image: url(../administrador/assets/img/theme/pagina.png); background-size: cover;">
  <!-- Barra lateral -->
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <!-- Contenido principal -->
  <div class="main-content">
    <!-- Barra de navegación superior -->
    <?php
    require_once('partials/_topnav.php');
    ?>
    <!-- Encabezado -->
    <div class="header  pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-3"></span>
      <div class="container-fluid">
        <div class="header-body">
          <!-- Estadísticas de la tarjeta -->
          <div class="row">
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Clientes</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $customers; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Productos</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $products; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                        <i class="fas fa-utensils"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Pedidos</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $orders; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Ventas</h5>
                      <span class="h2 font-weight-bold mb-0">$<?php echo $sales; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                        <i class="fas fa-dollar-sign"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Contenido de la página -->
    <div class="container-fluid mt--7">
      <div class="row mt-5">
        <div class="col-xl-12">
          <div class="card shadow" style="background-color: rgba(128, 128, 128, 0.5);">
            <div class="card-body">
              <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active" data-interval="5000">
                    <img class="d-block w-100" src="../administrador/assets/img/theme/promocional.png" alt="Primera imagen promocional" style="width: 100%; height: auto;">
                  </div>
                  <div class="carousel-item" data-interval="5000">
                    <img class="d-block w-100" src="../administrador/assets/img/theme/promocional1.png" alt="Segunda imagen promocional" style="width: 100%; height: auto;">
                  </div>
                  <!-- Agrega más elementos de carrusel según sea necesario -->
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Siguiente</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Pie de página -->
      <?php require_once('partials/_footer.php'); ?>
    </div>
  </div>
  <!-- Scripts de Argon -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>
</html>
