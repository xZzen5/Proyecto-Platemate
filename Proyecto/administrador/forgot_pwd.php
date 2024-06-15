<?php
session_start();
include('config/config.php');
require_once('config/code-generator.php');

if (isset($_POST['reset_pwd'])) {
  if (!filter_var($_POST['reset_email'], FILTER_VALIDATE_EMAIL)) {
    $err = 'Correo electrónico no válido';
  }
  $checkEmail = mysqli_query($mysqli, "SELECT `admin_email` FROM `rpos_admin` WHERE `admin_email` = '" . $_POST['reset_email'] . "'") or exit(mysqli_error($mysqli));
  if (mysqli_num_rows($checkEmail) > 0) {
    //exit('Este correo electrónico ya está siendo utilizado');
    //Restablecer contraseña
    $reset_code = $_POST['reset_code'];
    $reset_token = sha1(md5($_POST['reset_token']));
    $reset_status = $_POST['reset_status'];
    $reset_email = $_POST['reset_email'];
    $query = "INSERT INTO rpos_pass_resets (reset_email, reset_code, reset_token, reset_status) VALUES (?,?,?,?)";
    $reset = $mysqli->prepare($query);
    $rc = $reset->bind_param('ssss', $reset_email, $reset_code, $reset_token, $reset_status);
    $reset->execute();
    if ($reset) {
      $success = "Las instrucciones para restablecer la contraseña se han enviado a tu correo electrónico";
      // && header("refresh:1; url=index.php");
    } else {
      $err = "Por favor, inténtalo de nuevo más tarde";
    }
  } else {
    $err = "No existe una cuenta con ese correo electrónico";
  }
}
require_once('partials/_head.php');
?>

<body class="bg-dark">
  <div>
    <div class="main-content">
      <div class="header bg-gradient-primar py-7">
        <div class="container">
          <div class="header-body text-center mb-7">
            <div class="row justify-content-center">
              <div class="col-lg-5 col-md-6">
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Contenido de la página -->
      <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
          <div class="col-lg-5 col-md-7">
            <div class="card">
              <div class="card-body px-lg-5 py-lg-5">
                <form method="post" role="form">
                  <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                      </div>
                      <input class="form-control" required name="reset_email" placeholder="Correo electrónico" type="email">
                    </div>
                  </div>
                  <div style="display:none">
                    <input type="text" value="<?php echo $tk; ?>" name="reset_token">
                    <input type="text" value="<?php echo $rc; ?>" name="reset_code">
                    <input type="text" value="Pendiente" name="reset_status">
                  </div>
                  <div class="text-center">
                    <button type="submit" name="reset_pwd" class="btn btn-primary my-4">Restablecer contraseña</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-6">
                <a href="index.php" class="text-light"><small>¿Iniciar sesión?</small></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Pie de página -->
    <?php
    require_once('partials/_footer.php');
    ?>
    <!-- Scripts de Argon -->
    <?php
    require_once('partials/_scripts.php');
    ?>
  </div>
</body>

</html>
