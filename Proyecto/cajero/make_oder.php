<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['make'])) {
  //Prevenir la publicación de valores en blanco
  if (empty($_POST["order_code"]) || empty($_POST["customer_name"]) || empty($_GET['prod_price']) || empty($_POST["mesas"])) {
    $err = "No se aceptan valores en blanco";
  } else {
    $order_id = $_POST['order_id'];
    $order_code  = $_POST['order_code'];
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $prod_id  = $_GET['prod_id'];
    $prod_name = $_GET['prod_name'];
    $prod_price = $_GET['prod_price'];
    $prod_qty = $_POST['prod_qty'];
    $mesas = strval($_POST['mesas']); // Convertir a cadena
    $order_status = 'Pendiente'; // Añade aquí el valor predeterminado para 'order_status'

    //Insertar la información capturada en una tabla de la base de datos
    $postQuery = "INSERT INTO rpos_orders (prod_qty, order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price, order_status, mesas) VALUES(?,?,?,?,?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    //vincular parámetros
    $rc = $postStmt->bind_param('ssssssssss', $prod_qty, $order_id, $order_code, $customer_id, $customer_name, $prod_id, $prod_name, $prod_price, $order_status, $mesas);
    $postStmt->execute();
    //declarar una variable que se pasará a la función de alerta
    if ($postStmt) {
      $success = "Pedido enviado" && header("refresh:1; url=payments.php");
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
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">

                  <div class="col-md-4">
                    <label>Nombre del cliente</label>
                    <select class="form-control" name="customer_name" id="custName" onChange="getCustomer(this.value)">
                      <option value="">Seleccionar nombre del cliente</option>
                      <?php
                      //Cargar todos los clientes
                      $ret = "SELECT * FROM  rpos_customers ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      while ($cust = $res->fetch_object()) {
                      ?>
                        <option><?php echo $cust->customer_name; ?></option>
                      <?php } ?>
                    </select>
                    <input type="hidden" name="order_id" value="<?php echo $orderid; ?>" class="form-control">
                  </div>

                  <div class="col-md-4">
                    <label>ID del cliente</label>
                    <input type="text" name="customer_id" readonly id="customerID" class="form-control">
                  </div>

                  <div class="col-md-4">
                    <label>Código de pedido</label>
                    <input type="text" name="order_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <?php
                $prod_id = $_GET['prod_id'];
                $ret = "SELECT * FROM  rpos_products WHERE prod_id = '$prod_id'";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($prod = $res->fetch_object()) {
                ?>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Precio del producto ($)</label>
                      <input type="text" readonly name="prod_price" value="$ <?php echo $prod->prod_price; ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Cantidad de producto</label>
                      <input type="text" name="prod_qty" class="form-control" value="">
                    </div>
                    <div class="col-md-6">
                      <label>Escribe la Mesa en la que estas</label>
                      <input type="text" name="mesas" class="form-control" value="">
                    </div>
                  </div>
                <?php } ?>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="make" value="Hacer pedido" class="btn btn-success" value="">
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
