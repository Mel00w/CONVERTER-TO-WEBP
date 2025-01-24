<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converter</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

</body>
</html>
<?php

if (isset($_FILES['img'])) {
    $files = $_FILES['img'];
    $path = './img_upload/';
    $resized_path = 'img_resize/';
    $zip_dir = 'img_zip/';
    $zip_name = $zip_dir . 'converted_images.zip';
    $zip = new ZipArchive;

    if (!is_dir($zip_dir)) {
        mkdir($zip_dir, 0755, true);
    }

    // Ouvrir ou créer le fichier ZIP
    if ($zip->open($zip_name, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        echo "<div class='messages'>";

        // Parcourir chaque fichier téléchargé
        for ($i = 0; $i < count($files['name']); $i++) {
            $name = $files['name'][$i];
            $tmp_name = $files['tmp_name'][$i];
            $type = $files['type'][$i];
            $upload_path = $path . $name;
            $converted_path = $resized_path . pathinfo($name, PATHINFO_FILENAME) . '.webp';

            // Déplacer le fichier téléchargé vers le chemin de destination
            if (move_uploaded_file($tmp_name, $upload_path)) {
                // Convertir l'image en WebP selon le type MIME
                switch ($type) {
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($upload_path);
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($upload_path);
                        break;
                    case 'image/gif':
                        $image = imagecreatefromgif($upload_path);
                        break;
                    default:
                        echo "<p class='error'>Type de fichier non pris en charge pour $name.</p>";
                        continue 2;
                }

                // Sauvegarder l'image convertie et ajouter au ZIP
                imagewebp($image, $converted_path);
                imagedestroy($image);

                $zip->addFile($converted_path, pathinfo($converted_path, PATHINFO_BASENAME));

                echo "<p class='success'>$name converti et ajouté au fichier ZIP.</p>";
            } else {
                echo "<p class='error'>Erreur lors du téléchargement de $name.</p>";
            }
        }

        // Fermer le fichier ZIP
        $zip->close();

        // Lien pour télécharger le fichier ZIP
        echo "<p class='success'>Téléchargez votre fichier ZIP : <a href='$zip_name' download onclick='deleteZip()'>Télécharger</a></p>";

        // Supprimer les fichiers temporaires
        foreach ($files['name'] as $name) {
            unlink($path . $name);
            unlink($resized_path . pathinfo($name, PATHINFO_FILENAME) . '.webp');
        }

        // Bouton pour convertir une nouvelle image
        echo "<button onclick='window.location.href=\"index.php\"'>Convertir une nouvelle image</button>";
        echo "</div>";

        // Générer le script JavaScript pour supprimer le fichier ZIP
        echo "<script>
        function deleteZip() {
            fetch('delete_zip.php')
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.error('Error:', error));
        }
        </script>";
    } else {
        echo "<p class='error'>Erreur lors de la création du fichier ZIP.</p>";
    }
} else {
    echo "<p class='error'>Veuillez sélectionner des fichiers.</p>";
}
?>