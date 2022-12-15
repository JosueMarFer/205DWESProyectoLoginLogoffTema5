<?php
//@author Josue Martinez Fernandez
//@version 1.0
//ultima actualizacion 30/11/2022
//Si la sesion no tiene guardado el array referente al login no ha sido correctamente logueado y te redirige el login
session_start();
if (!isset($_SESSION['205DWESProyectoLoginLogoffTema5']) || is_null($_SESSION['205DWESProyectoLoginLogoffTema5'])) {
    header('Location: ./login.php');
    exit;
}
//Si se pulsa salir se destruye la sesion y se redirige al login
if (isset($_REQUEST['salir'])) {
    $_SESSION['205DWESProyectoLoginLogoffTema5'] = null;
    session_destroy();
    header('Location: ./login.php');
    exit;
}
//Si se pulsa detalle la pagina redirige a la pagina de detalle
if (isset($_REQUEST['detalle'])) {
    header('Location: ./detalle.php');
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
                <?php
//bienvenida dependiendo del dioma
                if (isset($_COOKIE['idioma'])) {
                    switch ($_COOKIE['idioma']) {
                        case "espanol":
                            echo '<h3>Bienvenido ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_CodUsuario) . '</h3>';
                            echo '<h3>Se ha conectado: ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones) . ' veces</h3>';
                            if ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones > 1) {
                                echo '<h3>Su ultima conexion ha sido: ' . $_SESSION['fechaHoraConexionAnterior'] . '</h3>';
                            }
                            break;
                        case "portugues":
                            echo '<h3>Bem-vindo ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_CodUsuario) . '</h3>';
                            echo '<h3>conectou: ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones) . ' vezes</h3>';
                            if ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones > 1) {
                                echo '<h3>Sua última conexão foi:: ' . $_SESSION['fechaHoraConexionAnterior'] . '</h3>';
                            }
                            break;
                        case "ingles":
                            echo '<h3>Wellcome ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_CodUsuario) . '</h3>';
                            echo '<h3>Has conected: ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones) . ' times</h3>';
                            if ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones > 1) {
                                echo '<h3>Your last connection has been: ' . $_SESSION['fechaHoraConexionAnterior'] . '</h3>';
                            }
                            break;
                    }
                } else {
                    echo '<h3>Bienvenido ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_CodUsuario) . '</h3>';
                    echo '<h3>Se ha conectado: ' . ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones) . ' veces</h3>';
                    if ($_SESSION['205DWESProyectoLoginLogoffTema5']->T01_NumConexiones > 1) {
                        echo '<h3>Su ultima conexion ha sido: ' . $_SESSION['fechaHoraConexionAnterior'] . '</h3>';
                    }
                }
                ?>
                <form name="ProyectoLoginLogoffTema5Programa" action="./<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="formElement">
                        <input type="submit" value="Salir" name="salir" />
                    </div>
                    <div class="formElement">
                        <input type="submit" value="Detalle" name="detalle" />
                    </div>
                </form>
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