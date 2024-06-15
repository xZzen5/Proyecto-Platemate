<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Actualizar personal
if (isset($_POST['UpdateStaff'])) {
  //Prevenir la publicación de valores en blanco
  if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
    $err = "No se aceptan valores en blanco";
  } else {
    $staff_number = $_POST['staff_number'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $staff_password = $_POST['staff_password'];
    $update = $_GET['update'];

    //Insertar la información capturada en una tabla de la base de datos
    $postQuery = "UPDATE rpos_staff SET  staff_number =?, staff_name =?, staff_email =?, staff_password =? WHERE staff_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    //vincular parámetros
    $rc = $postStmt->bind_param('ssssi', $staff_number, $staff_name, $staff_email, $staff_password, $update);
    $postStmt->execute();
    //declarar una variable que se pasará a la función de alerta
    if ($postStmt) {
      $success = "Personal actualizado" && header("refresh:1; url=hrm.php");
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
    $ret = "SELECT * FROM  rpos_staff WHERE staff_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($staff = $res->fetch_object()) {
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
                      <label>Número de personal</label>
                      <input type="text" name="staff_number" class="form-control" value="<?php echo $staff->staff_number; ?>">
                    </div>
                    <div class="col-md-6">
                      <label>Nombre del personal</label>
                      <input type="text" name="staff_name" class="form-control" value="<?php echo $staff->staff_name; ?>">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Correo electrónico del personal</label>
                      <input type="email" name="staff_email" class="form-control" value="<?php echo $staff->staff_email; ?>">
                    </div>
                    <div class="col-md-6">
                      <label>Contraseña del personal</label>
                      <input type="password" name="staff_password" class="form-control" value="">
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="UpdateStaff" value="Actualizar personal" class="btn btn-success" value="">
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
