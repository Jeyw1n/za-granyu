<?php
    session_start();

    if (!isset($_SESSION["user"])) {
        header("Location: ./reg.html");
    }
    $host = "localhost";
    $log = "root";
    $password_sql = "";
    $database = "bd";
    $conn = mysqli_connect($host, $log, $password_sql, $database);
    
    if(!$conn){
        echo("Connection error.");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="headr">
             <div class="texthdr">
                <p><?php echo $_SESSION['user']; ?>, добро пожаловать!</p>
             </div>
    </div> 
    <div class="nav">
        <div class="b1">
            1
        </div>
        <div class="b2">
            2
        </div>
        <div class="b3">
            3
        </div>
    </div>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="./js/jquery-3.7.1.min.js"></script>
</body>
</html>