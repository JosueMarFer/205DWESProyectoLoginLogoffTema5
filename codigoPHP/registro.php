<?php
//@author Josue Martinez Fernandez
//@version 1.0
//ultima actualizacion 09/01/2023
//Importacion del fichero de conexion
//El fichero se selecciona en base al host en el que se ejecute el programa
if ($_SERVER['HTTP_HOST'] == 'daw205.ieslossauces.es') {
    require_once '../conf/confConexionEE.php';
} else if ($_SERVER['SERVER_ADDR'] == '192.168.3.212') {
    require_once '../conf/confConexionEDH.php';
} else if ($_SERVER['SERVER_ADDR'] == '192.168.20.19') {
    require_once '../conf/confConexionED.php';
}
//Importa la libreria de validación    
require_once '../core/221120ValidacionFormularios.php';
//Define las instrucciones sql en variables    
$sqlBuscaLogin = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :codUsuario AND T01_Password = :password;";
$sqlActualizarConexiones = "UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1, T01_FechaHoraUltimaConexion=NOW() WHERE T01_CodUsuario = :codUsuario;";
$sqlCrearUsuario = "INSERT INTO T01_Usuario VALUES(:codUsuario, SHA2(concat(:codUsuario, :password),256), :descripcionUsuario, 0, NOW(), 'usuario', null);";
//Define e inicializa el array de errores
$aErrores = [
    'codUsuario' => '',
    'password' => '',
    'repetirPassword' => '',
    'descripcionUsuario' => '',
];
//Si se ha pulsado el boton de cancelar redirige al login
if (isset($_REQUEST['cancelar'])) {
    header('Location: ./login.php');
}
//Define e inicializa la variable encargada de comprobar si los datos estan validados
$entradaOK = true;
//Si se ha pulsado el boton de enviar valida los campos (tambien en la BBDD)
//en caso de devolver algun error almacena el mismo en el array de errores (en su campo correspondiente)
if (isset($_REQUEST['enviar'])) {
    $aErrores['codUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['codUsuario'], 8, 4, 1);
    $aErrores['password'] = validacionFormularios::validarPassword($_REQUEST['password'], 8, 4, 1, 1);
    $aErrores['repetirPassword'] = validacionFormularios::validarPassword($_REQUEST['repetirPassword'], 8, 4, 1, 1);
    $aErrores['descripcionUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['descripcionUsuario'], 255, 1, 1);

    if ($_REQUEST['repetirPassword'] != $_REQUEST['password']) {
        $aErrores['repetirPassword'] = 'Las contraseñas no coinciden';
    }
//Recorre el array de errores y en caso de tener alguno la variable que comprueba la entrada pasa a ser false
    foreach ($aErrores as $errorIndex => $errorValue) {
        if (isset($errorValue)) {
            $entradaOK = false;
            $_REQUEST[$errorIndex] = null;
        }
    }
    if ($entradaOK) {
        try {
            $miDB = new PDO(HOSTPDO, USER, PASSWD);
            $passwordCifrada = (hash('sha256', ($_REQUEST['codUsuario'] . $_REQUEST['password'])));
            $buscaLogin = $miDB->prepare($sqlBuscaLogin);
            $buscaLogin->bindParam(':codUsuario', $_REQUEST['codUsuario']);
            $buscaLogin->bindParam(':password', $passwordCifrada);
            $buscaLogin->execute();
            if ($buscaLogin->rowCount() != 0) {
                $entradaOK = false;
                $aErrores['codUsuario'] = 'El usuario ya existe';
                $_REQUEST['codUsuario'] = null;
            } else {
                $buscaLogin->execute();
                $oUsuario = $buscaLogin->fetchObject();
            }
        } catch (Exception $e) {
//Si la conexion da error...
            ?>
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <link rel="stylesheet" href="../webroot/css/style.css">
                    <title>Registro 205ProyectoLoginLogoff</title>
                </head>
                <body>
                    <header>
                        <h1>&lt;/DWES&gt;</h1>
                        <h2>Proyecto Tema 5 LoginLogoff Registro</h2>
                    </header>
                    <main>
                        <section>
                            <?php echo 'Error ' . $e->getCode() . ' : ' . $e->getMessage() . '<br>'; ?>  
                        </section>
                        <div class="return">
                            <a href="../../205DWESProyectoDWES/indexProyectoDWES.php"><img src="../webroot/images/back.png" alt="Imagen back"></a>
                        </div>
                    </main>
                    <footer>
                        <div class="footerIcons">
                            <a href="../doc/curriculum.pdf" target="_blank"><img src="../webroot/images/curriculum.png"
                                                                                 alt="Imagen curriculum"></a>
                            <a href="https://github.com/JosueMarFer/205DWESProyectoLoginLogoffTema5" target="_blank"><img
                                    src="../webroot/images/github.png" alt="Imagen github"></a>
                        </div>
                        <div class="home">
                            <a href="../../../index.html"><img src="../webroot/images/home.png" alt="Imagen home"></a>
                            <p>Josué martínez Fernández</p>
                        </div>
                    </footer>
                    <?php
                    exit();
                } finally {
                    unset($miDB);
                }
            }
        } else {
            $entradaOK = false;
        }
//Comprueba si la entrada es valida
//En caso de serlo crea en "_SERVER un campo con el array de respuestas
        if ($entradaOK) {
            try {
                $miDB = new PDO(HOSTPDO, USER, PASSWD);
                $crearUsuario = $miDB->prepare($sqlCrearUsuario);
                $crearUsuario->bindParam(':codUsuario', $_REQUEST['codUsuario']);
                $crearUsuario->bindParam(':password', $_REQUEST['password']);
                $crearUsuario->bindParam(':descripcionUsuario', $_REQUEST['descripcionUsuario']);
                $crearUsuario->execute();
                session_start();
                $miDB = new PDO(HOSTPDO, USER, PASSWD);
                $actualizaConexiones = $miDB->prepare($sqlActualizarConexiones);
                $actualizaConexiones->bindParam(':codUsuario', $_REQUEST['codUsuario']);
                $actualizaConexiones->execute();
                $buscaLogin->execute();
                $oUsuario = $buscaLogin->fetchObject();
                $_SESSION['fechaHoraConexionAnterior'] = $oUsuario->T01_FechaHoraUltimaConexion;
                $_SESSION['205DWESProyectoLoginLogoffTema5'] = $oUsuario;
//Establecemos una cookie de idioma y por parametro añadimos la fecha actual mas los segundos que queremos que tarde en caducar (5min) (Formato unix)
                setcookie('idioma', $_REQUEST['idioma'], time() + 300);
                header('Location: ./programa.php');
                exit();
            } catch (Exception $e) {
//Si la conexion da error...
                ?>
                <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <link rel="stylesheet" href="../webroot/css/style.css">
                    <title>Registro 205ProyectoLoginLogoff</title>
                </head>
                <body>
                    <header>
                        <h1>&lt;/DWES&gt;</h1>
                        <h2>Proyecto Tema 5 LoginLogoff Registro</h2>
                    </header>
                    <main>
                        <section>
                            <?php echo 'Error ' . $e->getCode() . ' : ' . $e->getMessage() . '<br>'; ?>  
                        </section>
                        <div class="return">
                            <a href="../../205DWESProyectoDWES/indexProyectoDWES.php"><img src="../webroot/images/back.png" alt="Imagen back"></a>
                        </div>
                    </main>
                    <footer>
                        <div class="footerIcons">
                            <a href="../doc/curriculum.pdf" target="_blank"><img src="../webroot/images/curriculum.png"
                                                                                 alt="Imagen curriculum"></a>
                            <a href="https://github.com/JosueMarFer/205DWESProyectoLoginLogoffTema5" target="_blank"><img
                                    src="../webroot/images/github.png" alt="Imagen github"></a>
                        </div>
                        <div class="home">
                            <a href="../../../index.html"><img src="../webroot/images/home.png" alt="Imagen home"></a>
                            <p>Josué martínez Fernández</p>
                        </div>
                    </footer>
                    <?php
                    exit();
                } finally {
                    unset($miDB);
                }
            } else {
                ?>
                <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <link rel="stylesheet" href="../webroot/css/style.css">
                    <title>Registro 205ProyectoLoginLogoff</title>
                </head>
                <body>
                    <header>
                        <h1>&lt;/DWES&gt;</h1>
                        <h2>Proyecto Tema 5 LoginLogoff Registro</h2>
                    </header>
                    <main>
                        <section>
                            <form name="ProyectoLoginLogoffTema5Registro" action="./<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post">
                                <fieldset>
                                    <legend>Registro</legend>
                                    <div class="formElement">
                                        <label for="codUsuario">Codigo de usuario:</label>
                                        <input type="text" id="codUsuario" name="codUsuario" value="<?php echo $_REQUEST['codUsuario'] ?? '' ?>"/>
                                        <?php (!is_null($aErrores['codUsuario'])) ? print '<p style="color: red; display: inline;">' . $aErrores['codUsuario'] . '</p>' : ''; ?>
                                    </div>
                                    <div class="formElement">
                                        <label for="password">Contraseña:</label>
                                        <input type="password" id="password" name="password"/>
                                        <?php (!is_null($aErrores['password'])) ? print '<p style="color: red; display: inline;">' . $aErrores['password'] . '</p>' : ''; ?>
                                    </div>
                                    <div class="formElement">
                                        <label for="repetirPassword">Repetir Contraseña:</label>
                                        <input type="password" id="repetirPassword" name="repetirPassword"/>
                                        <?php (!is_null($aErrores['repetirPassword'])) ? print '<p style="color: red; display: inline;">' . $aErrores['repetirPassword'] . '</p>' : ''; ?>
                                    </div>
                                    <div class="formElement">
                                        <label for="descripcionUsuario">Descripcion de usuario:</label>
                                        <input type="text" id="descripcionUsuario" name="descripcionUsuario" value="<?php echo $_REQUEST['descripcionUsuario'] ?? '' ?>"/>
                                        <?php (!is_null($aErrores['descripcionUsuario'])) ? print '<p style="color: red; display: inline;">' . $aErrores['descripcionUsuario'] . '</p>' : ''; ?>
                                    </div>
                                    <div class="formElement">
                                        <label for="idioma">Selecionar idioma:</label>
                                        <select id="idioma" name="idioma">
                                            <option value="espanol">Español</option>
                                            <option value="portugues">Portugués</option>
                                            <option value="ingles">Ingles</option>
                                        </select>
                                    </div>
                                    <div class="formElement">
                                        <input type="submit" value="Enviar" name="enviar" />
                                        <input type="submit" value="Cancelar" name="cancelar" />
                                    </div>
                                </fieldset>
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
                            <a href="../../../index.html"><img src="../webroot/images/home.png" alt="Imagen home"></a>
                            <p>Josué martínez Fernández</p>
                        </div>
                    </footer>
                </body>

            </html>
            <?php
        }
        ?>
                    