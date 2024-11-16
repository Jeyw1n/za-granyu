<?php
    session_start();
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $host = "localhost";
        $log = "root";
        $password_sql = "";
        $database = "bd";

        $conn = mysqli_connect($host, $log, $password_sql, $database);
        if(!$conn){
            echo("Ошибка соединения.");
        } else {
            $login = $_POST['login'];
            $user_query = "SELECT * FROM users WHERE login = '$login'";
            $result = $conn->query($user_query);

            if($result -> num_rows > 0){
                $user = $result -> fetch_assoc();
                $password_bd = $user["password"];

                $password = $_POST['password'];

                if ($password == $password_bd) {
                    $_SESSION['great'] = "Вход успешен!";
                    $_SESSION['user'] = $user['login'];
                } else {
                    $_SESSION['error'] = "Неверный логин или пароль.";
                }
            } else {
                $_SESSION['error'] = "Неверный логин или пароль.";
            }

            mysqli_close($conn);
            header("Location: index.html");
        }
    }
?>
