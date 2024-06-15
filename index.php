<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<link rel="icon" type="image/png" sizes="32x32" href="../administrador/assets/img/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../administrador/assets/img/icons/favicon-16x16.png">

<head >
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PlateMate ★</title>

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:500,500" rel="stylesheet">

    <!-- Estilos -->
    <style>
        html,
        body {
            background-image: url('../platemate/Proyecto/administrador/assets/img/theme/indice.jpg');
            background-size: cover;
            color: black;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .logo {
            width: 300px;
            height: 300px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
    color: #636b6f;
    padding: 0 25px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .1rem;
    text-decoration: none;
    text-transform: uppercase;
    transition: background-color 0.5s ease, box-shadow 0.5s ease; /* Añadido box-shadow a la transición */
    border-radius: 10px;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1); /* Añadido box-shadow para dar profundidad */
}

.links a:hover {
    background-color: white;
    color: #000;
    box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.2); /* Añadido box-shadow más grande en hover para dar efecto de "levantamiento" */
}

.links a:active {
    background-color: white;
    color: #000;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); /* Añadido box-shadow más pequeño en active para dar efecto de "presionado" */
}


        .m-b-md {
            margin-bottom: 30px;
            color:#636b6f;
            }
            
    </style>
</head>
<body>

    <div class="flex-center position-ref full-height">
        <div class="content">
            <img src="../platemate/Proyecto/administrador/assets/img/theme/Logo-.png" alt="Logo" class="logo">
            <div class="title m-b-md" id="texto"></div>

            <div class="links">
                <a href="Proyecto/administrador/">Ingreso Administrador</a>
                <a href="Proyecto/cajero/">Ingreso Personal</a>
                <a href="Proyecto/clientes">Ingreso Cliente</a>
            </div>
        </div>
    </div>

    <script>
        var i = 0;
        var isTyping = true;
        var txt = '¡Ordena fácil, disfruta más!';
        var speed = 101; /* La velocidad/duración del efecto en milisegundos */

        function typeWriter() {
            if (isTyping && i < txt.length) {
                document.getElementById("texto").innerHTML += txt.charAt(i);
                i++;
                setTimeout(typeWriter, speed);
            } else if (!isTyping && i >= 0) {
                document.getElementById("texto").innerHTML = document.getElementById("texto").innerHTML.slice(0, -1);
                i--;
                setTimeout(typeWriter, speed);
            }

            if (isTyping && i == txt.length) {
                // Cuando se ha escrito todo el texto, espera 15 segundos y luego cambia a modo de borrado
                setTimeout(function() { isTyping = false; typeWriter(); }, 10000);
            } else if (!isTyping && i == 0) {
                // Cuando se ha borrado todo el texto, espera 15 segundos y luego cambia a modo de escritura
                setTimeout(function() { isTyping = true; typeWriter(); }, 2000);
            }
        }

        // Llama a la función typeWriter cuando la página se haya cargado completamente
        window.onload = typeWriter;
    </script>
</body>
</html>
