<?php
session_start();
include('config/config.php');

// Iniciar sesión
if (isset($_POST['login'])) {
  $customer_email = $_POST['customer_email'];
  $password = $_POST['customer_password']; // No encriptes la contraseña aquí

  // Si el checkbox "Recordarme" está marcado, guarda el correo electrónico en una cookie
  if (isset($_POST['customCheckLogin'])) {
    setcookie('customer_email', $customer_email, time() + (86400 * 30), "/"); // 86400 = 1 día
  } else {
    // Si el checkbox "Recordarme" no está marcado, elimina la cookie
    if (isset($_COOKIE['customer_email'])) {
      setcookie('customer_email', '', time() - 3600, "/"); // 3600 = 1 hora
    }
  }

  // Obtén el hash de la contraseña del cliente de la base de datos
  $stmt = $mysqli->prepare("SELECT customer_password, customer_id FROM rpos_customers WHERE customer_email = ?");
  $stmt->bind_param('s', $customer_email);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  // Verifica si se encontró un usuario
  if ($row) {
    $hashed_password = $row['customer_password'];
    // Verifica la contraseña
    if (password_verify($password, $hashed_password)) {
      // La contraseña es correcta, inicia la sesión
      $_SESSION['customer_id'] = $row['customer_id'];
      header("location:dashboard.php");
    } else {
      // La contraseña es incorrecta
      $err = "Credenciales de autenticación incorrectas ";
    }
  } else {
    // No se encontró un usuario con ese correo electrónico
    $err = "No se encontró un usuario con ese correo electrónico";
  }
}

require_once('partials/_head.php');
?>
<!-- El resto del código HTML sigue aquí -->


<body style="background-image: url('../administrador/assets/img/theme/indice-usuario.jpg'); background-size: cover;" >
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
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center mb-4">
                <img src="../administrador/assets/img/theme/Logo-.png" alt="Icono" style="width: 140px;">
                <h2>¡Bienvenido de nuevo!</h2>
              </div>
                            <form method="post" role="form">
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" required name="customer_email" placeholder="Correo electrónico" type="email" value="<?php echo isset($_COOKIE['customer_email']) ? $_COOKIE['customer_email'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" required name="customer_password" placeholder="Contraseña" type="password">
                                    </div>
                                </div>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id="customCheckLogin" name="customCheckLogin" type="checkbox" <?php echo isset($_COOKIE['customer_email']) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="customCheckLogin">
                                        <span class="text-muted">Recuérdame</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="text-left">
                                        <button type="submit" name="login" class="btn btn-primary my-4">Iniciar sesión</button>
                                        <a href="create_account.php" class=" btn btn-success pull-right">Crear cuenta</a>
                                    </div>
                                </div>
                                <div class="col-6">
                        <!-- <a href="../admin/forgot_pwd.php" target="_blank" class="text-light"><small>¿Olvidaste la contraseña?</small></a> -->
                        </div>
                            </form>

                        </div>
                    </div>
                    <div class="row mt-3">
                        
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
</body>

</html>
