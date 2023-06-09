<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container">
    <table class="table table-hover table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Способоность</th>
            <th scope="col">Количество пользователей</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $stmt = $db->prepare("SELECT t2.name as ability, count(r.id) as count FROM relations r JOIN abilities t2 ON t2.id = r.ability_id group by r.ability_id;");
        $stmt->execute();
        foreach ($stmt as $row) {
            printf('<tr>
            <td>%s</td>
            <th scope="row">%s</th>
        </tr>', $row['ability'], $row['count']);
        }
        ?>
        </tbody>
    </table>
    <br>
    <table class="table table-hover table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">ФИО</th>
            <th scope="col">Почта</th>
            <th scope="col">Год рождения</th>
            <th scope="col">Пол</th>
            <th scope="col">Кол-во конечностей</th>
            <th scope="col">Управление</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
            printf('<tr>
            <th scope="row">%s</th>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a class="delete" href="./edit/?id=%s">Изменить</a> | <a href="./delete/?id=%s">Удалить</a></td>
        </tr>', $row['id'], $row['name'], $row['email'], $row['year'], $row['gender'], $row['limbs'], $row['id'], $row['id']);
        }
        ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

