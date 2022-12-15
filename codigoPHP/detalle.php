<?php
//@author Josue Martinez Fernandez
//@version 1.0
//ultima actualizacion 30/11/2022
//Si la sesion no tiene guardado el array referente al login no ha sido correctamente logueado y te redirige al login
session_start();
if (!isset($_SESSION['205DWESProyectoLoginLogoffTema5']) || is_null($_SESSION['205DWESProyectoLoginLogoffTema5'])) {
    header('Location: ./login.php');
    exit;
}
//Si se pulsa volver te redirige a la pagina de programa
if (isset($_REQUEST['volver'])) {
    header('Location: ./programa.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="../webroot/css/style.css">
        <title>Login 205ProyectoLoginLogoff</title>
    </head>
    <body>
        <header>
            <h1>&lt;/DWES&gt;</h1>
            <h2>Proyecto Tema 5 LoginLogoff Login</h2>
        </header>
        <main>
            <section>
                <form name="ProyectoLoginLogoffTema5Detalle" action="./<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="formElement">
                        <input type="submit" value="Volver" name="volver" />
                    </div>
                </form>
                <?php
//Obtiene la clave del array y el dato almacenado en el mismo (SUPERGLOBALES)  
                echo '<h2>$_SESSION</h2><table>';
                foreach ($_SESSION as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td>';
                    if (is_object($valorCampo)) {
                        echo '<td><table>';
                        foreach ($valorCampo as $campo2 => $valorCampo2) {
                            echo '<tr><td>' . $campo2 . '</td><td>' . $valorCampo2 . '</td></tr>';
                        }
                        echo '</td></table>';
                    } else {
                        echo '<td>' . $valorCampo . '</td>';
                    }
                }
                echo '</table>';
                echo '<h2>$_COOKIE</h2><table>';
                foreach ($_COOKIE as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td><td>' . $valorCampo . '</td></tr>';
                }
                echo '</table>';
                echo '<h2>$_ENV</h2><table>';
                foreach ($_ENV as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td><td>' . $valorCampo . '</td></tr>';
                }
                echo '</table>';
                echo '<h2>$_FILES</h2><table>';
                foreach ($_FILES as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td><td>' . $valorCampo . '</td></tr>';
                }
                echo '</table>';
                echo '<h2>$_GET</h2><table>';
                foreach ($_GET as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td><td>' . $valorCampo . '</td></tr>';
                }
                echo '</table>';
                echo '<h2>$_POST</h2><table>';
                foreach ($_POST as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td><td>' . $valorCampo . '</td></tr>';
                }
                echo '</table>';
                echo '<h2>$_REQUEST</h2><table>';
                foreach ($_REQUEST as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td><td>' . $valorCampo . '</td></tr>';
                }
                echo '</table>';
                echo '<h2>$_SERVER</h2><table>';
                foreach ($_SERVER as $campo => $valorCampo) {
                    echo '<tr><td>' . $campo . '</td><td>' . $valorCampo . '</td></tr>';
                }
                echo '</table>';
//Almacenar en el buffer la salida de phpinfo para poder a traves de 
//una expresion regular tan solo recoger la tabla (sin formato) 
                ob_start();
                phpinfo();
                $pinfo = ob_get_contents();
                ob_end_clean();
                $pinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
                echo $pinfo;
                ?>
            </section>
        </main>
        <footer>
            <div class="footerIcons">
                <a href="../doc/curriculum.pdf" target="_blank"><img src="../webroot/images/curriculum.png"
                                                                     alt="Imagen curriculum"></a>
                <a href="https://github.com/JosueMarFer/205DWESProyectoLoginLogoffTema5" target="_blank"><img
                        src="../webroot/images/github.png" alt="Imagen github"></a>
            </div>
            <div class="home">
                <a href="../../index.html"><img src="../webroot/images/home.png" alt="Imagen home"></a>
                <p>Josué martínez Fernández</p>
            </div>
        </footer>
    </body>
</html>