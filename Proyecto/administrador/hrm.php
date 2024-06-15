<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
//Eliminar personal
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $adn = "DELETE FROM  rpos_staff  WHERE  staff_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();
  if ($stmt) {
    $success = "Eliminado" && header("refresh:1; url=hrm.php");
  } else {
    $err = "Intenta de nuevo más tarde";
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
              <a href="add_staff.php" class="btn btn-outline-success"><i class="fas fa-user-plus"></i>Agregar nuevo personal</a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Número de personal</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo electrónico</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM  rpos_staff ";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($staff = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo $staff->staff_number; ?></td>
                      <td><?php echo $staff->staff_name; ?></td>
                      <td><?php echo $staff->staff_email; ?></td>
                      <td>
                        <a href="hrm.php?delete=<?php echo $staff->staff_id; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Eliminar
                          </button>
                        </a>

                        <a href="update_staff.php?update=<?php echo $staff->staff_id; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-user-edit"></i>
                            Actualizar
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
</body>

</html>
