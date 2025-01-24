<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converter</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <h1>CONVERTER TO WEBP</h1>
    </header>
    <main>
        <form action="converter.php" method="post" enctype="multipart/form-data">
            <input type="file" name="img[]" id="img" accept="image/*" multiple required> <br>
            <input type="submit" name="submit" value="Convert">
        </form>
        <div class="result">
            <?php include "./converter.php" ?>
        </div>
    </main>
</body>

</html>