<?php

if (isset($_FILES)) {

    if (isset($_FILES['upload']['name'][0])) {

        $files = $_FILES['upload'];
        $allowedFormats = array('jpg', 'png', 'gif');
        $uploadError = array();
        $uploaded = array();


        foreach ($files['name'] as $index => $fileName) {

            $fileTmp = $files['tmp_name'][$index];
            $fileSize = $files['size'][$index];
            $fileError = $files['error'][$index];

            $fileExtension = explode('.', $fileName);
            $fileExtension = strtolower(end($fileExtension));

            if (in_array($fileExtension, $allowedFormats)) {

                if ($fileError === 0) {

                    if ($fileSize <= 1048576) {

                        $fileNewName = uniqid('image', true) . '.' . $fileExtension;
                        $filesDestination = "uploads/" . $fileNewName;

                        if (move_uploaded_file($fileTmp, $filesDestination)) {
                            $uploaded[$index] = $filesDestination;
                        } else {
                            $uploadError[$index] = "Upload non-exécuté, veuillez ré-essayer.";
                        }

                    } else {

                        $uploadError[$index] = "[{$fileName}] : La taille du fichier dépasse 1Mo.";

                    }

                } else {

                    $uploadError[$index] = "[{$fileName}] : Echec du téléchargement.";

                }

            } else {

                $uploadError[$index] = "[{$fileName}] : extension non autorisée.";

            }

        }


    }
    
}

$filesDestination = "uploads/";

if (isset($_POST['delete-img'])) {

    $fileToDelete = $_POST['fileToDelete'];
    unlink($filesDestination . $fileToDelete);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Upload files</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .img-container {
            margin-top: 50px;
        }

        h6 {
            overflow-wrap: break-word;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Envoi de fichiers</h1>

    <form method="POST" enctype="multipart/form-data" action="index.php">

        <div class="form-group">
            <label for="exampleInputFile">Sélection des fichiers</label>
            <input id='upload' name="upload[]" type="file" multiple="multiple"/>
            <input type="hidden" name="MAX_fileSize" value="1048576">
            <p class="help-block">Taille maximum: 1Mo. Formats acceptés : jpg, png, gif.</p>
        </div>

        <button type="submit" name="submit" class="btn btn-default">Envoyer</button>

    </form>
</div>

<div class="container img-container">

    <?php

    $scannedFiles = array_diff(scandir($filesDestination), array('.', '..'));

    foreach ($scannedFiles as $scannedFile) {

        if (file_exists($filesDestination . $scannedFile)) {
            ?>

            <div class="col-lg-3">
                <form action="index.php" method="post">
                    <input type="hidden" name="fileToDelete" value="<?php echo $scannedFile; ?>">
                    <img src="<?php echo $filesDestination . $scannedFile ?>" class="img-thumbnail img-responsive"
                         alt="<?php echo $scannedFile ?>" width="304" height="236">
                    <h6><?php echo $scannedFile ?></h6>
                    <button class="btn btn-danger" name="delete-img">Delete</button>
                </form>
            </div>

            <?php

        }

    }


    ?>

</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
