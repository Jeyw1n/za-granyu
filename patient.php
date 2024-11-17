<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header('Location: reg.html');
        exit();
    }
    $host = "localhost";
    $log = "admin";
    $password_sql = "_WC,?@y&W&N;'|sh0<Q]";
    $database = "db";
    $conn = mysqli_connect($host, $log, $password_sql, $database);
    if(!$conn){
        echo("Ошибка соединения.");
        exit();
    }

    $login = $_SESSION['user'];
    $sql = "SELECT * FROM medicine WHERE login = $login";
    $result = mysqli_query($conn, $sql);
    $med_data = mysqli_fetch_assoc($result);
    $med_id = $med_data['id'];
    $sql = "SELECT * FROM patient WHERE id_pharmaceft = $med_id";
    $result = mysqli_query($conn, $sql);

    $patient_table = "<table id='patient_table' border='1'>";
    $patient_table .= "<thead>";
    $patient_table .= "<tr>";
    $patient_table .= "<th>ФИО пациента</th>";
    $patient_table .= "<th>Телеграмм id</th>";
    $patient_table .= "<th>Диагноз</th>";
    $patient_table .= "<th>Дата следующего приёма</th>";
    $patient_table .= "</tr>";
    $patient_table .= "</thead>";
    $patient_table .= "<tbody>";
    foreach($result as $row){
        $patient_table .= "<tr>";
        $patient_table .= "<td>".$row['username']."</td>";
        $patient_table .= "<td>".$row['id_tg']."</td>";
        $patient_table .= "<td>".$row['diagnose']."</td>";
        $patient_table .= "<td>".$row['date']."</td>";
        $patient_table .= "</tr>";
    }
    $patient_table .= "</tbody>";
    $patient_table .= "</table>";

    echo $patient_table;