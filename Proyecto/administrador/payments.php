<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

//Cancelar pedido
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM rpos_orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Eliminado" && header("refresh:1; url=payments.php");
    } else {
        $err = "Intenta de nuevo más tarde";
    }
}

// Obtener todos los clientes únicos
$clientes = $mysqli->query("SELECT DISTINCT customer_id, customer_name FROM rpos_orders WHERE order_status !='Pagado'");

require_once('partials/_head.php');
?>

<style>
/* Estilo para centrar los botones y agregar espacio entre ellos */
.cliente {
    text-align: center;
    margin-bottom: 20px; /* Espacio entre los botones de cada cliente */
}
.cliente button {
    margin: 0 auto; /* Centrar botón */
}
</style>

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
                            Pedidos en curso
                        </div>
                        <div class="table-responsive">
                            <?php while ($cliente = $clientes->fetch_assoc()) { ?>
                                <div class="cliente">
                                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $cliente['customer_id']; ?>" aria-expanded="false" aria-controls="collapseExample">
                                        Pedidos de <?php echo $cliente['customer_name']; ?>
                                    </button>

                                    <a href="pay_orders.php?customer_id=<?php echo $cliente['customer_id']; ?>&order_status=Pagado&pay_all=Pagado">
    <button class="btn btn-sm btn-success">
        <i class="fas fa-handshake"></i>
        Pagar todos los pedidos
    </button>
</a>


                                    <div class="collapse" id="collapse<?php echo $cliente['customer_id']; ?>">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Descripción producto</th>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col">Precio total</th>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Mesa Cliente</th> <!-- Nueva columna -->
                                                    <th scope="col">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = "SELECT * FROM rpos_orders WHERE customer_id = ? AND order_status !='Pagado' ORDER BY `created_at` DESC";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->bind_param('s', $cliente['customer_id']);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                while ($order = $res->fetch_object()) {
                                                    $total = ($order->prod_price * $order->prod_qty);

                                                    // Consulta para obtener la descripción del producto
                                                    $prod_desc_query = "SELECT prod_desc FROM rpos_products WHERE prod_id = ?";
                                                    $prod_desc_stmt = $mysqli->prepare($prod_desc_query);
                                                    $prod_desc_stmt->bind_param('s', $order->prod_id);
                                                    $prod_desc_stmt->execute();
                                                    $prod_desc_res = $prod_desc_stmt->get_result();
                                                    $prod_desc_row = $prod_desc_res->fetch_object();
                                                    $prod_desc = wordwrap($prod_desc_row->prod_desc, 50, "<br />\n"); // Agrega saltos de línea cada 50 caracteres
                                                ?>

<tr>
    <td><?php echo $prod_desc; ?></td>
    <td><?php echo $order->prod_qty; ?></td>
    <td>$ <?php echo $total; ?></td>
    <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
    <td><?php echo $order->mesas; ?></td> <!-- Muestra el número de mesa -->
    <td>
        <a href="pay_order.php?order_code=<?php echo $order->order_code;?>&customer_id=<?php echo $order->customer_id;?>&order_status=Pagado">
            <button class="btn btn-sm btn-success">
                <i class="fas fa-handshake"></i>
                Pagar pedido
            </button>
        </a>

        <a href="payments.php?cancel=<?php echo $order->order_id; ?>">
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-window-close"></i>
                Cancelar pedido
            </button>
        </a>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
<?php } ?>
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

