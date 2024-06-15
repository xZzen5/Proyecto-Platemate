<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');

$por_pagina = 10;

if(isset($_GET['pagina'])){
   $pagina = $_GET['pagina'];
}else{
   $pagina = 1;
}

$empieza = ($pagina-1) * $por_pagina;
?>

<style>
/* Estilo para el panel desplegable y centrar los botones */
.cliente {
    margin-bottom: 20px; /* Espacio entre los botones de cada cliente */
    text-align: center; /* Centrar botones */
}
.collapse {
    margin-top: 10px;
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
                            Facturas 
                        </div>
                        <div class="table-responsive">
                            <?php
                            // Obtener todos los clientes únicos con pedidos pagados
                            $clientes = $mysqli->query("SELECT DISTINCT customer_id, customer_name FROM rpos_orders WHERE order_status = 'Pagado'");
                            while ($cliente = $clientes->fetch_assoc()) {
                                // Obtener la fecha y hora del último pedido del cliente
                                $last_order = $mysqli->query("SELECT created_at FROM rpos_orders WHERE customer_id = '{$cliente['customer_id']}' AND order_status = 'Pagado' ORDER BY created_at DESC LIMIT 1")->fetch_object();
                                $last_date = date('Y-m-d', strtotime($last_order->created_at));
                            ?>
                                <div class="cliente">
                                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $cliente['customer_id']; ?>" aria-expanded="false" aria-controls="collapseExample">
                                        Pedidos de <?php echo $cliente['customer_name']; ?>
                                    </button>
                                    <div class="collapse" id="collapse<?php echo $cliente['customer_id']; ?>">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Código</th>
                                                    <th scope="col">Producto</th>
                                                    <th scope="col">Precio unitario</th>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col">Precio total</th>
                                                    <th scope="col">Fecha</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Obtener todos los pedidos pagados del cliente actual en la última fecha
                                                $ret = "SELECT * FROM rpos_orders WHERE customer_id = ? AND order_status = 'Pagado' AND DATE(created_at) = ? ORDER BY `created_at` DESC LIMIT $empieza, $por_pagina";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->bind_param('ss', $cliente['customer_id'], $last_date);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                $total = 0;
                                                while ($order = $res->fetch_object()) {
                                                    $total += ($order->prod_price * $order->prod_qty);
                                                ?>
                                                    <tr>
                                                        <td><?php echo $order->order_code; ?></td>
                                                        <td><?php echo $order->prod_name; ?></td>
                                                        <td>$ <?php echo $order->prod_price; ?></td>
                                                        <td><?php echo $order->prod_qty; ?></td>
                                                        <td>$ <?php echo $order->prod_price * $order->prod_qty; ?></td>
                                                        <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4">Total del día</th>
                                                    <th>$ <?php echo $total; ?></th>
                                                    <th>
                                                        <a target="_blank" href="print_receipt.php?customer_id=<?php echo $cliente['customer_id']; ?>&fecha=<?php echo $last_date; ?>">
                                                            <button class="btn btn-sm btn-primary">
                                                                <i class="fas fa-print"></i>
                                                                Imprimir recibo
                                                            </button>
                                                        </a>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Código de paginación -->
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
