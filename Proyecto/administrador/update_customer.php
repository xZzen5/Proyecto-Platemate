<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Agregar cliente
if (isset($_POST['updateCustomer'])) {
  //Prevenir la publicación de valores en blanco
  if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email']) || empty($_POST['customer_password'])) {
    $err = "No se aceptan valores en blanco";
  } else {
    $customer_name = $_POST['customer_name'];
    $customer_phoneno = $_POST['customer_phoneno'];
    $customer_email = $_POST['customer_email'];
    $customer_password = password_hash($_POST['customer_password'], PASSWORD_DEFAULT); //Encriptar esto
    $update = $_GET['update'];

    //Insertar la información capturada en una tabla de la base de datos
    $postQuery = "UPDATE rpos_customers SET customer_name =?, customer_phoneno =?, customer_email =?, customer_password =? WHERE  customer_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    //vincular parámetros
    $rc = $postStmt->bind_param('sssss', $customer_name, $customer_phoneno, $customer_email, $customer_password, $update);
    $postStmt->execute();
    //declarar una variable que se pasará a la función de alerta
    if ($postStmt) {
      $success = "Cliente agregado" && header("refresh:1; url=customes.php");
    } else {
      $err = "Por favor, inténtalo de nuevo más tarde";
    }
  }
}
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
    $update = $_GET['update'];
    $ret = "SELECT * FROM  rpos_customers WHERE customer_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($cust = $res->fetch_object()) {
    ?>
      <!-- Encabezado -->
      <div style="background-image: url(assets/img/theme/pagina.png); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-3"></span>
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
                <form method="POST">
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Nombre del cliente</label>
                      <input type="text" name="customer_name" value="<?php echo $cust->customer_name; ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Número de teléfono del cliente</label>
                      <input type="text" name="customer_phoneno" value="<?php echo $cust->customer_phoneno; ?>" class="form-control" value="">
                    </div>
                  </div>
                  <hr>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Correo electrónico del cliente</label>
                      <input type="email" name="customer_email" value="<?php echo $cust->customer_email; ?>" class="form-control" value="">
                    </div>
                    <div class="col-md-6">
                      <label>Contraseña del cliente</label>
                      <input type="password" name="customer_password" class="form-control" value="">
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="updateCustomer" value="Actualizar cliente" class="btn btn-success" value="">
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
    }
      ?>
      </div>
  </div>
  <!-- Scripts de Argon -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>
