<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$dsn = "mysql:host=127.0.0.1; dbname=burger; charset=utf8";
$pdo = new PDO($dsn , 'mysql' , 'mysql');
$pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);


?>

<html>
<head>
    <title>Admin page</title>
</head>

<body>

<table width='100%' border=0>
    <tr bgcolor='#6495ed'>
        <td>ИД</td>
        <td>Имя</td>
        <td>E-MAIL</td>
        <td>Кол-во заказов</td>
        <td>Последний заказ</td>
    </tr>

    <?php
    foreach($pdo->query('SELECT * FROM users') as $orders) {
        echo "<tr>";
        echo "<tr style='border: solid 1px black;'>";
        echo "<td>{$orders['user_id']}</td>";
        echo "<td>{$orders['name']}</td>";
        echo "<td>{$orders['email']}</td>";
        echo "<td>{$orders['orders']}</td>";
        echo "<td>{$orders['last_order']}</td>";
        echo "</tr>";
    }
    ?>
</table>

<table width='100%' border=0>
    <tr bgcolor='#6495ed'>
        <td>ИД</td>
        <td>Имя</td>
        <td>Телефон</td>
        <td>Адрес</td>
        <td>Перезвон ?</td>
        <td>Оплата ?</td>
        <td>Комментарий</td>
        <td>Дата</td>
    </tr>

    <?php
    foreach($pdo->query('SELECT * FROM orders') as $orders) {
        echo "<tr>";
        echo "<tr style='border: solid 1px black;'>";
        echo "<td>{$orders['id']}</td>";
        echo "<td>{$orders['name']}</td>";
        echo "<td>{$orders['phone']}</td>";
        echo "<td>{$orders['address']}</td>";
        echo "<td>{$orders['callback']}</td>";
        echo "<td>{$orders['payment']}</td>";
        echo "<td>{$orders['comment']}</td>";
        echo "<td>{$orders['date']}</td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>