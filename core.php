<?php
session_start();
$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
if ($isAjaxRequest) {
    $host = "localhost";
    $log = "admin";
    $password_sql = "_WC,?@y&W&N;'|sh0<Q]";
    $database = "db";
    $conn = mysqli_connect($host, $log, $password_sql, $database);
    
    if (!$conn) {
        echo("Connection error.");
        exit();
    }

    $med_id = 5;
    $action = $_POST['action'];

    switch ($action) {
        case "add_data":
            $sugar = $_POST['sugar'] ?: 4; // Default to 4 if empty
            $davl = $_POST['davl'] ?: '120/80'; // Default to 120/80 if empty
            $pulse = $_POST['pulse'] ?: 65; // Default to 65 if empty
            $photo = $_FILES['photo'] ?? null; // Get the photo from the files

            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                // Path to the uploaded file
                $fileTmpPath = $photo['tmp_name'];
                $fileName = $photo['name'];

                // URL of your FastAPI server
                $url = 'http://localhost:8000/detect/';

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                    'file' => new CURLFile($fileTmpPath, $photo['type'], $fileName)
                ]);

                // Execute the request
                $response = curl_exec($ch);

                // Check for errors
                if (curl_errno($ch)) {
                    echo 'Ошибка: ' . curl_error($ch);
                } else {
                    $decodedResponse = json_decode($response, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        echo 'Ошибка декодирования JSON: ' . json_last_error_msg();
                    } else {
                        echo $decodedResponse; // Или обработайте ответ как вам нужно
                        $AIdata = $decodedResponse['labels'];
                    }
                }

                // Close cURL
                curl_close($ch);
            } else {
                echo "Ошибка загрузки файла";
            }

            $sql = "INSERT INTO user_data (id_data, id_patient, time, sugar, aisugar, pressure, pulse) VALUES (NULL, '5', NOW(), '$sugar', '$AIdata', '$davl', '$pulse')";
            if (mysqli_query($conn, $sql)) {
                echo "Данные успешно отправленны!";
            } else {
                echo "Ошибка: " . $sql . "<br>" . mysqli_error($conn);
            }
            break;

        case "add_patient":
            $name = $_POST['name'];
            $diagnos = $_POST['diagnos'];

            $sql = "INSERT INTO patient (tg_id, username, id_pharmaceft, diagnose, date) VALUES (NULL, '$name', $med_id, '$diagnos', NOW())"; // Added closing parenthesis
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
    mysqli_close($conn);
}
