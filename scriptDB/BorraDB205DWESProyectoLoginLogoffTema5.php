<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="../webroot/css/style.css">
        <title>Borrado BBDD</title>
    </head>

    <body>
        <header>
            <h1>Tema 5</h1>
            <h2>Borrado de la base de datos</h2>
        </header>
        <main>
            <section>
                <?php
//@author Josue Martinez Fernandez
//@version 1.0
//ultima actualizacion 13/12/2022
//Introduzca aquí la información de su base de datos y el nombre del archivo de copia de seguridad.
                if ($_SERVER['HTTP_HOST'] == 'daw205.ieslossauces.es') {
                    $mysqlDatabaseName = 'dbs9174047';
                    $mysqlUserName = 'dbu2082397';
                    $mysqlPassword = 'daw2_Sauces';
                    $mysqlHostName = 'db5010845872.hosting-data.io';
                    $mysqlImportFilename = './BorraDB205DWESProyectoLoginLogoffTema5EE.sql';
                } else if ($_SERVER['SERVER_ADDR'] == '192.168.3.212') {
                    $mysqlDatabaseName = 'mysql';
                    $mysqlUserName = 'adminsql';
                    $mysqlPassword = 'paso';
                    $mysqlHostName = '192.168.3.212';
                    $mysqlImportFilename = './BorraDB205DWESProyectoLoginLogoffTema5ED.sql';
                } else if ($_SERVER['SERVER_ADDR'] == '192.168.20.19') {
                    $mysqlDatabaseName = 'mysql';
                    $mysqlUserName = 'adminsql';
                    $mysqlPassword = 'paso';
                    $mysqlHostName = '192.168.20.19';
                    $mysqlImportFilename = './BorraDB205DWESProyectoLoginLogoffTema5ED.sql';
                }

//Por favor, no haga ningún cambio en los siguientes puntos
//Exportación de la base de datos y salida del status
                $command = 'mysql -h' . $mysqlHostName . ' -u' . $mysqlUserName . ' --password="' . $mysqlPassword . '" ' . $mysqlDatabaseName . ' < ' . $mysqlImportFilename;
                exec($command, $output, $worked);
                switch ($worked) {
                    case 0:
                        echo 'Los datos del archivo <b>' . $mysqlImportFilename . '</b> se han importado correctamente a la base de datos <b>' . $mysqlDatabaseName . '</b>';
                        break;
                    case 1:
                        echo 'Se ha producido un error durante la importación. Por favor, compruebe si el archivo está en la misma carpeta que este script. Compruebe también los siguientes datos de nuevo: <br/><br/><table><tr><td>Nombre de la base de datos MySQL:</td><td><b>' . $mysqlDatabaseName . '</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' . $mysqlUserName . '</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' . $mysqlHostName . '</b></td></tr><tr><td>Nombre de archivo de la importación de MySQL:</td><td><b>' . $mysqlImportFilename . '</b></td></tr></table>';
                        break;
                }
                ?>
            </section>
            <div class="return">
                <a href="../indexProyectoTema5.php"><img src="../webroot/images/back.png" alt="Imagen back"></a>
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
                <a href="../../index.html"><img src="../webroot/images/home.png" alt="Imagen home"></a>
                <p>Josué martínez Fernández</p>
            </div>
        </footer>
    </body>

</html>