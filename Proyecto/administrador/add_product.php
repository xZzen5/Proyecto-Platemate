<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['addProduct'])) {
  //Prevenir la publicación de valores en blanco
  if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price'])) {
    $err = "No se aceptan valores en blanco";
  } else {
    $prod_id = $_POST['prod_id'];
    $prod_code  = $_POST['prod_code'];
    $prod_name = $_POST['prod_name'];
    $prod_img = $_FILES['prod_img']['name'];
    move_uploaded_file($_FILES["prod_img"]["tmp_name"], "assets/img/products/" . $_FILES["prod_img"]["name"]);
    $prod_desc = $_POST['prod_desc'];
    $prod_price = $_POST['prod_price'];

    //Insertar la información capturada en una tabla de base de datos
    $postQuery = "INSERT INTO rpos_products (prod_id, prod_code, prod_name, prod_img, prod_desc, prod_price ) VALUES(?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('ssssss', $prod_id, $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price);
    $postStmt->execute();
    //declarar una variable que se pasará a la función de alerta
    if ($postStmt) {
      $success = "Producto añadido" && header("refresh:1; url=add_product.php");
    } else {
      $err = "Por favor, inténtalo de nuevo o más tarde";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
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
    <div style="background-image: url(assets/img/theme/pagina.png); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
    <span class="mask bg-gradient-dark opacity-4"></span>
      <div class="container-fluid">
        <div class="header-body">
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
              <h3>Por favor, rellena todos los campos</h3>
            </div>
            <div class="card-body">
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Nombre del producto</label>
                    <input type="text" name="prod_name" class="form-control">
                    <input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Código del producto</label>
                    <input type="text" name="prod_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Imagen del producto</label>
                    <input type="file" name="prod_img" class="btn btn-outline-success form-control" value="">
                  </div>
                  <div class="col-md-6">
                    <label>Precio del producto</label>
                    <input type="text" name="prod_price" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-12">
                    <label>Descripción del producto</label>
                    <textarea rows="5" name="prod_desc" class="form-control" value=""></textarea>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addProduct" value="Añadir producto" class="btn btn-success" value="">
                  </div>
                </div>
              </form>
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
</body>

</html>
