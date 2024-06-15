<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

require_once('partials/_head.php');
?>

<body>
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
    <div style="background-image: url(../administrador/assets/img/theme/pagina.png); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
    <span class="mask bg-gradient-dark opacity-3"></span>
      <div class="container-fluid">
        <div class="header-body">
          <!-- Aquí es donde agregamos los botones de filtro -->
          <div class="row justify-content-center">
            <div class="col-auto">
              <button class="btn btn-danger" onclick="filterProducts('Hamburguesa')">Hamburguesa</button>
              <button class="btn btn-danger" onclick="filterProducts('Perro Caliente')">Perros Calientes</button>
              <button class="btn btn-danger" onclick="filterProducts('Salchipapa')">Salchipapas</button>
              <button class="btn btn-danger" onclick="filterProducts('Arepa')">Arepas</button>
              <button class="btn btn-danger" onclick="filterProducts('Pincho')">Pinchos</button>
              <button class="btn btn-danger" onclick="filterProducts('Bebida')">Bebidas</button>
              <button class="btn btn-danger" onclick="filterProducts('Adiciones')">Adiciones</button>
              <button class="btn btn-danger" onclick="filterProducts('')">Todo el menú</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Contenido de la página -->
    <div class="container-fluid mt--8">
      <!-- Tabla -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              Selecciona cualquier producto para hacer un pedido
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Menú</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Acción</th>
                  </tr>
                </thead>
                <tbody id="productTable">
                  <?php
                  $ret = "SELECT * FROM  rpos_products  ORDER BY `rpos_products`.`created_at` DESC ";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prod = $res->fetch_object()) {
                  ?>
                    <tr id="<?php echo $prod->prod_name; ?>">
                      <td>
                        <?php
                        if ($prod->prod_img) {
                          echo "<img src='../administrador/assets/img/products/$prod->prod_img' height='140' width='140 class='img-thumbnail'>";
                        } else {
                          echo "<img src='../administrador/assets/img/products/default.jpg' height='60' width='140 class='img-thumbnail'>";
                        }

                        ?>
                      </td>
                      <td><?php echo wordwrap($prod->prod_desc, 50, "<br />\n"); ?></td>
                      <td><?php echo $prod->prod_name; ?></td>
                      <td>$ <?php echo $prod->prod_price; ?></td>
                      <td>
                        <a href="make_oder.php?prod_id=<?php echo $prod->prod_id; ?>&prod_name=<?php echo $prod->prod_name; ?>&prod_price=<?php echo $prod->prod_price; ?>">
                          <button class="btn btn-sm btn-warning">
                            <i class="fas fa-cart-plus"></i>
                            Hacer pedido
                          </button>
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Pie de página -->
      <?php
      require_once('partials/_footer.php');
      ?>
    </div>
  </div>
  <!-- Scripts de Argon -->
  <?php
  require_once('partials/_scripts.php');
  ?>
  <!-- Aquí es donde agregamos el script de filtrado -->
  <script>
    function filterProducts(productName) {
      var productRows = document.querySelectorAll('#productTable tr');
      productRows.forEach(function(row) {
        if (productName === '' || row.id === productName) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }
  </script>
</body>

</html>
