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
                            Registros de pedidos
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Producto</th>
                                        <th scope="col">Cliente</th>
                                        <th class="text-success" scope="col">Producto</th>
                                        <th scope="col">Precio unitario</th>
                                        <th class="text-success" scope="col">Cantidad</th>
                                        <th scope="col">Precio total</th>
                                        <th scop="col">Estado</th>
                                        <th scope="col">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM  rpos_orders ORDER BY `created_at` DESC LIMIT $empieza, $por_pagina";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        // Verifica si el producto todavía existe en la base de datos
                                        $prod_id = $order->prod_id;
                                        $ret_prod = "SELECT * FROM rpos_products WHERE prod_id = '$prod_id'";
                                        $stmt_prod = $mysqli->prepare($ret_prod);
                                        $stmt_prod->execute();
                                        $res_prod = $stmt_prod->get_result();
                                        if($res_prod->num_rows > 0) {
                                            $prod = $res_prod->fetch_object();
                                            $total = ($order->prod_price * $order->prod_qty);
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row">
                                                <img src="assets/img/products/<?php echo $prod->prod_img; ?>" height="140" width="140" class="img-thumbnail">
                                            </th>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td class="text-success"><?php echo $order->prod_name; ?></td>
                                            <td>$ <?php echo $order->prod_price; ?></td>
                                            <td class="text-success"><?php echo $order->prod_qty; ?></td>
                                            <td>$ <?php echo $total; ?></td>
                                            <td><?php if ($order->order_status == 'pendiente') {
                                                    echo "<span class='badge badge-danger'>$order->order_status</span>";
                                                } else {
                                                    echo "<span class='badge badge-success'>$order->order_status</span>";
                                                } ?></td>
                                            <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                        </tr>
                                    <?php 
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        $pag_query = "SELECT * FROM rpos_orders";
                        $pag_result = mysqli_query($mysqli, $pag_query);
                        $total_registros = mysqli_num_rows($pag_result);
                        $total_paginas = ceil($total_registros / $por_pagina);

                        echo '<nav aria-label="Page navigation example">';
                        echo '<ul class="pagination justify-content-center">';
                        for ($i=1; $i<=$total_paginas; $i++) {
                            echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?pagina='.$i.'">'.$i.'</a></li>';
                        }
                        echo '</ul>';
                        echo '</nav>';
                        ?>
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

