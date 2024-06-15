<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Comienza tu desarrollo con un Panel de control para Bootstrap 4.">
    <meta name="author" content="MartDevelopers Inc">
    <title>PlateMate - Personal ➤</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../administrador/assets/img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../administrador/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../administrador/assets/img/icons/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Iconos -->
    <link href="assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- CSS de Argon -->
    <link type="text/css" href="assets/css/argon.css?v=1.0.0" rel="stylesheet">
    <script src="assets/js/swal.js"></script>
    <!--Cargar Swal-->
    <?php if (isset($success)) { ?>
        <!--Este código para inyectar alerta de éxito-->
        <script>
            setTimeout(function() {
                    swal("Éxito", "<?php echo $success; ?>", "success");
                },
                100);
        </script>

    <?php } ?>
    <?php if (isset($err)) { ?>
        <!--Este código para inyectar alerta de error-->
        <script>
            setTimeout(function() {
                    swal("Falló", "<?php echo $err; ?>", "error");
                },
                100);
        </script>

    <?php } ?>
    <?php if (isset($info)) { ?>
        <!--Este código para inyectar alerta de información-->
        <script>
            setTimeout(function() {
                    swal("Éxito", "<?php echo $info; ?>", "info");
                },
                100);
        </script>

    <?php } ?>
    <script>
        function getCustomer(val) {
            $.ajax({

                type: "POST",
                url: "customer_ajax.php",
                data: 'custName=' + val,
                success: function(data) {
                    //alert(data);
                    $('#customerID').val(data);
                }
            });

        }
    </script>
</head>
