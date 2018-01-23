<?php
//создаем массив ошибок
$errors = array ();

if ( isset($_POST["doLogin"]) ) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        //создаем (открываем для чтения и записи) файл secrets.txt
        if (!file_exists("secrets.txt")) {
            $errors[] = "Вы не зарегистрированы";
        } else {
            $content = fopen("secrets.txt", "c+");
            while (!feof($content)) {
                //проверяем наличие такого email в базе
                //считываем файл построчно, пока не будет конец файла
                $buffer = fgets($content);
                //вытягиваем email из строки, обрезаем пробелы
                $bufEmail = trim(strstr($buffer, "|", true));
                if ( $email == $bufEmail ) {
                    //вытягиваем пароль (обрезаем символ |, обрезаем пробелы)
                    $buffPassword = trim(mb_substr(strstr($buffer, "|"), 1));
                        if ( password_verify($password, $buffPassword) ) {

                        } else {
                            $errors[] = "Неверный пароль";
                        }
                    break;
                } else {
                    //достигнут конец файла, e-mail не найден
                    if (feof($content)) {
                        $errors[] = "Пользователя с таким E-mail не существует. Пожалуйста зарегистрируйтесь";
                    }
                }
            }
            //закрываем файл secrets.txt
            fclose($content);
        }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Авторизация</title>
        <meta charset="utf-8">
    </head>
    <body>
    <?php
        if ( isset($_POST["doLogin"]) ) {
            //выдаем запись входа или ошибку
            if (empty($errors)) {
                echo "<div style='color: green'>Вход выполнен</div><hr>\n";
            } else {
                //показываем первый элемент массива ошибок
                echo "<div style='color: red'>" . array_shift($errors) . "</div><hr>\n";
            }
        }
    ?>
        <form method="POST">
            <p>
                <label>
                    Введите E-mail:
                    <input type="email" name="email" placeholder="Введите E-mail" required >
                </label>
            </p>
            <p>
                <label>
                    Введите пароль:
                    <input type="password" name="password" placeholder="Введите пароль" required>
                </label>
            </p>
            <p>
                <input type="submit" name="doLogin" value="Вход">
            </p>
        </form>
        <p>
            <a href="index.html">Главная</a>
        </p>
        <p>
            <a href="reg.php">Регистрация</a>
        </p>
    </body>
</html>