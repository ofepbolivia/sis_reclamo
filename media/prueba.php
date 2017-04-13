<?php
//echo phpinfo();
//var_dump('Conectar sqlserver');exit;
$conexion = mssql_connect('172.17.45.133', 'Test', 'Boa.2017');

if (!$conexion) {
    die('Algo fue mal mientras se conectaba a MSSQL');
}else{
    echo ('Conectado Correctamente');
    $p_cadena = 'Ejecucion Diaria ERP';
    mssql_select_db('msdb', $conexion);

    $stmt = mssql_init('dbo.sp_start_job', $conexion);
    mssql_bind($stmt, '@job_name', $p_cadena, SQLVARCHAR, false, false, 50);

    mssql_execute($stmt);
    //mssql_free_statement($stmt);

    echo 'Mensaje:: ';

}

mssql_close($conexion);



?>