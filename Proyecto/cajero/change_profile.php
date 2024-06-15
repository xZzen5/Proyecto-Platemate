<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
//Actualizar perfil
if (isset($_POST['ChangeProfile'])) {
    $staff_id = $_SESSION['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $Qry = "UPDATE rpos_staff SET staff_name =?, staff_email =? WHERE staff_id =?";
    $postStmt = $mysqli->prepare($Qry);
    //vincular parámetros
    $rc = $postStmt->bind_param('ssi', $staff_name, $staff_email, $staff_id);
    $postStmt->execute();
    //declarar una variable que se pasará a la función de alerta
    if ($postStmt) {
        $success = "Cuenta actualizada" && header("refresh:1; url=dashboard.php");
    } else {
        $err = "Por favor, inténtalo de nuevo o más tarde";
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
        $staff_id = $_SESSION['staff_id'];
        if ($new_password != $confirm_password) {
            $err = "La contraseña de confirmación no coincide";
        } else {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            //Insertar la información capturada en una tabla de base de datos
            $query = "UPDATE rpos_staff SET staff_password = ? WHERE staff_id = ?";
            $stmt = $mysqli->prepare($query);
            //bind parameters
            $rc = $stmt->bind_param('ss', $new_password_hashed, $staff_id); // Modificación aquí
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
        $staff_id = $_SESSION['staff_id'];
        //$login_id = $_SESSION['login_id'];
        $ret = "SELECT * FROM  rpos_staff  WHERE staff_id = '$staff_id'";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($staff = $res->fetch_object()) {
        ?>
            <!-- Encabezado -->
            <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(../administrador/assets/img/theme/pagina.png); background-size: cover; background-position: center top;">
                <!-- Máscara -->
                <span class="mask bg-gradient-default opacity-3"></span>
                <!-- Contenedor del encabezado -->
                <div class="container-fluid d-flex align-items-center">
                    <div class="row">
                        <div class="col-lg-7 col-md-10">
                            <h1 class="display-2 text-white">Hola <?php echo $staff->staff_name; ?></h1>
                            <p class="text-white mt-0 mb-5">Esta es la página de tu perfil. Puedes personalizar tu perfil como quieras y también cambiar la contraseña</p>
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
                            <?php echo $staff->staff_name; ?></span>
                        </h3>
                        <div class="h5 font-weight-300">
                            <i class="ni location_pin mr-2"></i><?php echo $staff->staff_email; ?>
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
                                        <label class="form-control-label" for="input-username">Nombre de usuario</label>
                                        <input type="text" name="staff_name" value="<?php echo $staff->staff_name; ?>" id="input-username" class="form-control form-control-alternative" ">
                                        </div>
                                        </div>
                                        <div class=" col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Dirección de correo electrónico</label>
                                            <input type="email" id="input-email" value="<?php echo $staff->staff_email; ?>" name="staff_email" class="form-control form-control-alternative">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="submit" id="input-email" name="ChangeProfile" class="btn btn-success form-control-alternative" value="Enviar"">
                                            </div>
                                            </div>
                                        </div>
                                        </div>
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
                <!-- Footer -->
            <?php
            require_once('partials/_footer.php');
        }
            ?>
            </div>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
</body>

</html>