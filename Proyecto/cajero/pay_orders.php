<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php'); // Asegúrate de que este archivo exista y tenga las funciones para generar los códigos
check_login();

$customer_id = $_GET['customer_id'];
$order_status = 'Pendiente';

// Obtén todos los pedidos pendientes del cliente
$query = "SELECT * FROM rpos_orders WHERE customer_id = ? AND order_status = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss', $customer_id, $order_status);
$stmt->execute();
$res = $stmt->get_result();

$total = 0;
while ($order = $res->fetch_object()) {
    // Suma el valor de todos los pedidos pendientes
    $total += $order->prod_price * $order->prod_qty; // Multiplica el valor del pedido por la cantidad del producto
}

if (isset($_POST['payment_method'])) {
    $order_status = 'Pagado';
    $payment_method = $_POST['payment_method'];

    $res->data_seek(0); // reset the result set to the beginning
    while ($order = $res->fetch_object()) {
        // Genera un nuevo pay_id para cada pago
        $pay_id = bin2hex(random_bytes('3'));

        // Usa las variables $payid y $mpesaCode directamente
        $pay_code = $mpesaCode;

        // Inserta el nuevo pago en la tabla rpos_payments
        $payment_query = "INSERT INTO rpos_payments (pay_id, pay_code, order_code, customer_id, pay_amt, pay_method) VALUES (?, ?, ?, ?, ?, ?)";
        $payment_stmt = $mysqli->prepare($payment_query);
        $payment_stmt->bind_param('ssssss', $pay_id, $pay_code, $order->order_code, $customer_id, $total, $payment_method);
        $payment_stmt->execute();

        // Actualiza el estado del pedido a "Pagado"
        $update_query = "UPDATE rpos_orders SET order_status = ? WHERE order_code = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param('ss', $order_status, $order->order_code);
        $update_stmt->execute();
    }

    if ($update_stmt && $payment_stmt) {
        $success = "Todos los pedidos han sido pagados" && header("refresh:1; url=payments.php");
    } else {
        $err = "Por favor, inténtalo de nuevo más tarde";
    }
}

require_once('partials/_head.php');
?>

<!-- El resto del código HTML sigue aquí -->



<!-- El resto del código HTML sigue aquí -->



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
              <form method="post" action="">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Valor total - productos $</label>
                    <input type="text" name="total" readonly value="<?php echo $total;?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Método de pago</label>
                    <select class="form-control" name="payment_method">
                        <option selected>Efectivo - En Caja</option>
                        <option>Nequi - Daviplata - QR</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" value="Pagar" class="btn btn-success">
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

