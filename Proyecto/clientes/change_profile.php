<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['ChangeProfile'])) {
  //Prevenir la publicación de valores en blanco
  if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email'])) {
    $err = "No se aceptan valores en blanco";
  } else {
    $customer_name = $_POST['customer_name'];
    $customer_phoneno = $_POST['customer_phoneno'];
    $customer_email = $_POST['customer_email'];
    $customer_id = $_SESSION['customer_id'];

    //Insertar la información capturada en una tabla de base de datos
    $postQuery = "UPDATE rpos_customers SET customer_name =?, customer_phoneno =?, customer_email =? WHERE  customer_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('ssss', $customer_name, $customer_phoneno, $customer_email, $customer_id);
    $postStmt->execute();
    //declarar una variable que se pasará a la función de alerta
    if ($postStmt) {
      $success = "Perfil actualizado" && header("refresh:1; url=dashboard.php");
    } else {
      $err = "Por favor, inténtalo de nuevo o más tarde";
    }
  }
}
if (isset($_POST['changePassword'])) {
    //Cambiar contraseña
    $error = 0;
    
    if (isset($_POST['old_password']) && !empty($_POST['old_password'])) {
        $old_password = mysqli_real_escape_string($mysqli, trim($_POST['old_password']));
        // Aquí puedes agregar la lógica para verificar la contraseña antigua
    } else {
        $error = 1;
        $err = "La contraseña antigua no puede estar vacía";
    }

    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim($_POST['new_password']));
    } else {
        $error = 1;
        $err = "La nueva contraseña no puede estar vacía";
    }
    
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim($_POST['confirm_password']));
    } else {
        $error = 1;
        $err = "La contraseña de confirmación no puede estar vacía";
    }

    if (!$error) {
        $customer_id = $_SESSION['customer_id'];
        if ($new_password != $confirm_password) {
            $err = "La contraseña de confirmación no coincide";
        } else {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            //Insertar la información capturada en una tabla de base de datos
            $query = "UPDATE rpos_customers SET customer_password = ? WHERE customer_id = ?";
            $stmt = $mysqli->prepare($query);
            //bind parameters
            $rc = $stmt->bind_param('ss', $new_password_hashed, $customer_id); // Modificación aquí
            $stmt->execute();

            // Verificar errores de MySQL
            if ($stmt->error) {
                echo "Error de MySQL: " . $stmt->error;
            }

            //declarar una variable que se pasará a la función de alerta
            if ($stmt) {
                $_SESSION['success'] = "Contraseña cambiada";
                header("location: dashboard.php");
                exit();
            } else {
                $err = "Por favor, inténtalo de nuevo o más tarde";
            }
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
        $customer_id = $_SESSION['customer_id'];
        //$login_id = $_SESSION['login_id'];
        $ret = "SELECT * FROM  rpos_customers  WHERE customer_id = '$customer_id'";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($customer = $res->fetch_object()) {
        ?>
            <!-- Encabezado -->
            <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(../administrador/assets/img/theme/pagina.png); background-size: cover; background-position: center top;">
                <!-- Máscara -->
                <span class="mask bg-gradient-default opacity-3"></span>
                <!-- Contenedor del encabezado -->
                <div class="container-fluid d-flex align-items-center">
                    <div class="row">
                        <div class="col-lg-7 col-md-10">
                            <h1 class="display-2 text-white">Hola <?php echo $customer->customer_name; ?></h1>
                            <p class="text-white mt-0 mb-5">Esta es tu página de perfil. Puedes personalizar tu perfil como quieras y también cambiar la contraseña</p>
                        </div>
                    </div>
                </div>
                </div>
            <!-- Contenido de la página -->
            <div class="container-fluid mt--8">
                <div class="row">
                    <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                        <div class="card card-profile shadow">
                            <div class="row justify-content-center">
                                <div class="col-lg-3 order-lg-2">
                                    <div class="card-profile-image">
                                        <a href="#">
                                            <img src="../administrador/assets/img/theme/usuario.jpg" class="rounded-circle">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                                <div class="d-flex justify-content-between">
                                </div>
                            </div>
                            <div class="card-body pt-0 pt-md-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                            <div>
                                            </div>
                                            <div>
                                            </div>
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3>
                                        <?php echo $customer->customer_name; ?></span>
                                    </h3>
                                    <div class="h5 font-weight-300">
                                        <i class="fas fa-envelope mr-2"></i><?php echo $customer->customer_email; ?>
                                    </div>
                                    <div class="h5 font-weight-300">
                                        <i class="fas fa-phone mr-2"></i><?php echo $customer->customer_phoneno; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 order-xl-1">
                        <div class="card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">Mi cuenta</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <h6 class="heading-small text-muted mb-4">Información del usuario</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-username">Nombre completo</label>
                                                    <input type="text" name="customer_name" value="<?php echo $customer->customer_name; ?>" id="input-username" class="form-control form-control-alternative" ">
                                                </div>
                                            </div>
                                            <div class=" col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-email">Número de teléfono</label>
                                                    <input type="text" id="input-email" value="<?php echo $customer->customer_phoneno; ?>" name="customer_phoneno" class="form-control form-control-alternative">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-email">Dirección de correo electrónico</label>
                                                    <input type="email" id="input-email" value="<?php echo $customer->customer_email; ?>" name="customer_email" class="form-control form-control-alternative">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="submit" id="input-email" name="ChangeProfile" class="btn btn-success form-control-alternative" value="Enviar"">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <form method="post">
    <h6 class="heading-small text-muted mb-4">Cambiar contraseña</h6>
    <div class="pl-lg-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-control-label" for="input-old-password">Contraseña antigua</label>
                    <input type="password" name="old_password" id="input-old-password" class="form-control form-control-alternative">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-control-label" for="input-new-password">Nueva contraseña</label>
                    <input type="password" name="new_password" id="input-new-password" class="form-control form-control-alternative">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-control-label" for="input-confirm-password">Confirmar nueva contraseña</label>
                    <input type="password" name="confirm_password" id="input-confirm-password" class="form-control form-control-alternative">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <input type="submit" id="input-submit" name="changePassword" class="btn btn-success form-control-alternative" value="Cambiar contraseña">
                </div>
            </div>
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
require_once('partials/_sidebar.php');
?>
</body>

</html>
