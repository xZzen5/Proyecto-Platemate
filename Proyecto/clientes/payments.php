<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

//Cancelar pedido
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM  rpos_orders  WHERE  order_id = ?";
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
                            <a href="orders.php" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> <i class="fas fa-utensils"></i>
                                Hacer un nuevo pedido
                            </a>
                            <!-- Botón Pagar Todo -->
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Menú</th>
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
                                    $total_sum = 0; // Variable para almacenar la suma total
                                    $customer_id = $_SESSION['customer_id'];
                                    $ret = "SELECT * FROM  rpos_orders WHERE order_status ='Pendiente' AND customer_id = '$customer_id'  ORDER BY `rpos_orders`.`created_at` DESC  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        $total = ($order->prod_price * $order->prod_qty);
                                        $total_sum += $total; // Suma el total al total acumulado

                                        // Obtén la imagen del producto de la base de datos
                                        $prod_id = $order->prod_id;
                                        $ret_prod = "SELECT prod_img, prod_desc FROM rpos_products WHERE prod_id = '$prod_id'";
                                        $stmt_prod = $mysqli->prepare($ret_prod);
                                        $stmt_prod->execute();
                                        $res_prod = $stmt_prod->get_result();
                                        $prod = $res_prod->fetch_object();
                                        $prod_desc = wordwrap($prod->prod_desc, 50, "<br />\n"); // Agrega saltos de línea cada 50 caracteres
                                    ?>
                                        <tr>
                                            <th scope="row">
                                                <img src="../administrador/assets/img/products/<?php echo $prod->prod_img; ?>" height="140" width="140" class="img-thumbnail">
                                            </th>
                                            <td><?php echo $prod_desc; ?></td>
                                            <td><?php echo $order->prod_qty; ?></td>
                                            <td>$ <?php echo $total; ?></td>
                                            <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                            <td><?php echo $order->mesas; ?></td> <!-- Muestra el número de mesa -->
                                            <td>

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
