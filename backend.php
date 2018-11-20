<?php

    try {
        $dsn = "mysql:host=127.0.0.1; dbname=burger; charset=utf8";
        $pdo = new PDO($dsn , 'mysql' , 'mysql');
        $pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        $phone = $_GET['phone'];
        $email = $_GET['email'];
        $name = $_GET['name'];
        $street = $_GET['street'];
        $home = $_GET['home'];
        $part = $_GET['part'];
        $appt = $_GET['appt'];
        $floor = $_GET['floor'];
        $comment = $_GET['comment'];
        $payment = $_GET['payment'];
        $callback = $_GET['callback'];
        if ($payment == 1) {
            $payment = "Потребуется сдача";
        } elseif ($payment == 2) {
            $payment = "Оплата по карте";
        } else {
            $payment = "Уточнить у клиента";
        }

        if (isset($callback)) {
            $callback = "Перезвонить";
        } else {
            $callback = "Не звонить";

        }
        $address = $street . " " . $home . " Корпус: {$part} " . "Квартира: {$appt} " . "Этаж: {$floor}";

        If (empty($phone) or empty($email) or empty($name)) {
            echo "Укажите обязательно Телефон, Почтовый адресс и Ваше Имя";
        } else {
            $query = "select * from users where email = '{$email}'";

            $select_from_users = $pdo->prepare($query);
            $select_from_users->execute();

            $result = $select_from_users->fetchAll();
            $first_order = false;
            // Проверяем есть ли такой email уже заказывал, то прибавляем 1 к его заказам и меняем дату
            // если нет, тогда создаем его запись
            if ($result) {
                $user = $result[0];
                $user_id = $user['user_id'];
                $order = $user['orders'] + 1;

                $query = "UPDATE users SET orders = '$order' WHERE user_id = '$user_id'";
                $add_order = $pdo->prepare($query);
                $add_order->execute();

                $query = "UPDATE users SET last_order = NOW() WHERE user_id = '$user_id'";
                $add_order = $pdo->prepare($query);
                $add_order->execute();
            } else {
                $orders = 1;
                $query = "insert into users (name, email, orders, last_order) values ('$name', '$email', '$orders', NOW() )";
                $insert_user = $pdo->prepare($query);
                $insert_user->execute();
                global $first_order;
                $first_order = true;
            }
            $insert_order = $pdo->prepare("insert into orders (name, phone, address, callback, payment, comment, date) values ('$name', '$phone', '$address', '$callback', '$payment', '$comment', NOW())");

            if ($insert_order->execute()) {
                $order_id = $pdo->prepare("SELECT MAX(id) FROM orders WHERE name = '$name' AND phone='$phone' AND address='$address'");
                $order_id->execute();
                $order_id = $order_id->fetchColumn();

                $order_number = $pdo->prepare("SELECT orders FROM users WHERE email = '$email'");
                $order_number->execute();
                $order_number = $order_number->fetchColumn();


                // Проверочный текст

                echo "Ваш заказ успешно размещен." . "</br>";
                echo "Уважаемый " . $name . ", Ваш заказ №" . $order_id . " успешно размещен." . "</br>";
                echo "Заказ будет доставлен по адресу: {$address}" . "</br>";
                if ($first_order) {
                    $firstOrder = "Спасибо за ваш первый заказ! ";
                    echo $firstOrder;
                } else {

                    $firstOrder = "Спасибо, это ваш {$order_number} заказ! ";
                    echo $firstOrder;
                }

                $fileName = 'file.txt';
                $content = "Уважаемый " . $name . ", Ваш заказ №" . $order_id . " успешно размещен. Доставка по адресу: {$address}" . $firstOrder;
                $length = file_put_contents($fileName , $content . "\r\n" , FILE_APPEND);

            }
        }
    }

    catch
        (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }


