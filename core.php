<?php
    session_start();
    $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    if ($isAjaxRequest) {
        $host = "localhost";
        $log = "root";
        $password_sql = "";
        $database = "bd";
        $conn = mysqli_connect($host, $log, $password_sql, $database);
        
        if(!$conn){
            echo("Connection error.");
            exit();
        }
        $login = $_SESSION['user'];
        $sql = "SELECT * FROM medicine WHERE login = $login";
        $result = mysqli_query($conn, $sql);
        $med_data = mysqli_fetch_assoc($result);
        $med_id = $med_data['id'];

        $action = $_POST['action'];
        switch ($action) {
            case "add_data":
                $sugar = $_POST['sugar'];
                $photo = $_POST['photo'];
                $davl = $_POST['davl'];
                $pulse = $_POST['pulse'];

                if($sugar == "") {
                    $sugar = 4;
                }
                if ($photo == "") {
                    $photo = NULL;
                }
                if ($davl == "") {
                    $davl = 120/80;
                }
                if ($pulse == "") {
                    $pulse = 65;
                }

                if (exif_imagetype($sugar) !== false) {
                    //Отправка нейросети по API.
                }

                $sql = "INSERT INTO user_data (id_data, id_user, sugar, davl, pulse) VALUES (NULL, NULL, '$sugar', '$davl', '$pulse')";
                if (mysqli_query($conn, $sql)) {
                    echo "Данные успешно отправленны!";
                } else {
                    echo "Ошибка: " . $sql . "<br>" . mysqli_error($conn);
                }
                break;
            case "add_patient":
                $name = $_POST['name'];
                $diagnos = $_POST['diagnos'];

                $sql = "INSERT INTO patient (tg_id, username, id_pharmaceft, diagnose, date) VALUES (NULL, '$name', $med_id, '$diagnos', NOW()";
                if (mysqli_query($conn, $sql)) {
                    echo "Успешно зарегистрирован. Для приглашения используйте следующую команду: /start?id=$med_id";
                } else {
                    echo "Ошибка: " . $sql . "<br>" . mysqli_error($conn);
                }
                break;
            default:
                echo "Неизвестная команда";
                break;
        }
    }
    mysqli_close($conn);