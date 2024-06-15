<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="MartDevelopers Inc">
    <title>PlateMate ★</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/img/icons/site.webmanifest">
    <link rel="mask-icon" href="assets/img/icons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="assets/css/bootstrap.css" rel="stylesheet" id="bootstrap-css">
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery.js"></script>
    <style>
        body {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php
    // Recuperar customer_id y fecha de los pedidos
    $customer_id = $_GET['customer_id'];
    $fecha = $_GET['fecha'];

    // Consulta para obtener todos los pedidos del cliente en la fecha especificada
    $ret = "SELECT * FROM rpos_orders WHERE customer_id = ? AND DATE(created_at) = ? AND order_status = 'Pagado'";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('ss', $customer_id, $fecha);
    $stmt->execute();
    $res = $stmt->get_result();
    $total = 0;
    ?>

    <div class="container">
        <div class="row">
            <div id="Receipt" class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <address>
                            <strong>PlateMate ★ </strong>
                            <br>
                            -------------
                            <br>
                            Punto de venta PlateMate
                            <br>
                            Tel: 3045661927
                        </address>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                        <p>
                            <em>Fecha: <?php echo date('d/M/Y', strtotime($fecha)); ?></em>
                        </p>
                        <p>
                            <em class="text-success">Recibo #: <?php echo $customer_id . $fecha; ?></em>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="text-center">
                        <h2>Recibo</h2>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th class="text-center">Precio unitario</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $res->fetch_object()) {
                                $subtotal = ($order->prod_price * $order->prod_qty);
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo $order->prod_name; ?></td>
                                    <td><?php echo $order->prod_qty; ?></td>
                                    <td class="text-center">$<?php echo $order->prod_price; ?></td>
                                    <td class="text-center">$<?php echo $subtotal; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>   </td>
                                <td>   </td>
                                <td class="text-right"><strong>Total: </strong></td>
                                <td class="text-center text-danger"><strong>$<?php echo $total; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                    <button id="print" onclick="printContent('Receipt');" class="btn btn-success btn-lg text-justify btn-block">
                        Imprimir <span class="fas fa-print"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printContent(el) {
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            $('body').html(restorepage);
        }
    </script>
</body>

</html>
<?php ?>
