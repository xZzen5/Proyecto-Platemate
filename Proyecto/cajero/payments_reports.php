<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

$por_pagina = 10;

if(isset($_GET['pagina'])){
   $pagina = $_GET['pagina'];
}else{
   $pagina = 1;
}

$empieza = ($pagina-1) * $por_pagina;

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
                            Informes de pago
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Código de pago</th>
                                        <th scope="col">Método de pago</th>
                                        <th class="text-success" scope="col">Código de pedido</th>
                                        <th scope="col">Monto pagado</th>
                                        <th class="text-success" scope="col">Fecha de pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM  rpos_payments ORDER BY `created_at` DESC LIMIT $empieza, $por_pagina";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($payment = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row">
                                                <?php echo $payment->pay_code; ?>
                                            </th>
                                            <th scope="row">
                                                <?php echo $payment->pay_method; ?>
                                            </th>
                                            <td class="text-success">
                                                <?php echo $payment->order_code; ?>
                                            </td>
                                            <td>
                                                $ <?php echo $payment->pay_amt; ?>
                                            </td>
                                            <td class="text-success">
                                                <?php echo date('d/M/Y g:i', strtotime($payment->created_at)) ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        $pag_query = "SELECT * FROM rpos_payments";
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
