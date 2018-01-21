<?php

if ( isset($_POST["doAuth"]) ) {

    if ($_POST["password"] !== $_POST["password_2"]) {
        echo "<div style='color: red'>Повторный пароль введен не верно</div><hr>";
    } else {
        $email = $_POST["email"];
        //создаем массив ошибок
        $errors = array ();
        //шифруем пароль
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        //формируем строку для записи
        $data = "$email | $password \r\n";

        //создаем (открываем для чтения и записи) файл secrets.txt
        if (!file_exists("secrets.txt")) {
            $content = fopen("secrets.txt", "w");
        } else {
            $content = fopen("secrets.txt", "c+");
            while (!feof($content)) {
                //проверяем наличие такого email в базе
                //считываем файл построчно, пока не будет конец файла
                $buffer = fgets($content);
                //вытягиваем email из строки, обрезаем пробелы
                $bufEmail = trim(strstr($buffer, "|", true));
                if ($email == $bufEmail) {
                    $errors[] = "Такой E-mail уже зарегистрирован";
                    break;
                }
            }
        }

        //регистрируем в базе если массив ошибок пуст
        if (empty($errors)) {
            //записываем данные в файл
            $write = fwrite($content, $data);
            if ($write === false) {
                echo "<div style='color: red'>Ошибка записи</div><hr>";
            } else {
                echo "<div style='color: green'>Вы зарегистрированы</div><hr>";
            }
        } else {
            //показываем первый элемент массива ошибок
            echo "<div style='color: red'>".array_shift($errors)."</div><hr>";
        }
        //закрываем файл secrets.txt
        fclose($content);
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Регистрация</title>
        <meta charset="utf-8">
    </head>
    <body>
        <form method="POST">
            <p>
                <label>
                    Введите E-mail:
                    <input type="email" name="email" placeholder="Введите E-mail"
                           required value="<?=@$_POST["email"]?>">
                </label>
            </p>
            <p>
                <label>
                    Введите пароль:
                    <input type="password" name="password" placeholder="Введите пароль"
                           required value="<?=@$_POST["password"]?>">
                </label>
            </p>
            <p>
                <label>
                    Введите пароль еще раз:
                    <input type="password" name="password_2" placeholder="Введите пароль"
                           required value="<?=@$_POST["password_2"]?>">
                </label>
            </p>
            <p>
                <button type="submit" name="doAuth">Зарегистрироваться</button>
            </p>
        </form>
        <p>
            <a href="index.html">Главная</a>
        </p>
        <p>
            <a href="auth.php">Авторизация</a>
        </p>
    </body>
</html>