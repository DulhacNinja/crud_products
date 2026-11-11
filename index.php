<!DOCTYPE html>
<html>

<body>
    <h1>Recommended books</h1>
    <?php
    $books = [
        ["name" => "myBook",
        "author" => "Dulhac"],

        ["name" => "hisBook",
        "author" => "stefan"],

    ];
    ?>

    <ul>
        <?php foreach ($books as $book) : ?>
            <li> <?php echo "{$book["name"]} ". "{$book["author"]}"  ?> </li>
        <?php endforeach; ?>
        </ul>
</body>

</html>