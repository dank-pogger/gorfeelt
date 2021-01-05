<?php
include "../config.php";
session_start();
$IP = $_SERVER["REMOTE_ADDR"];
$query1 = "SELECT * FROM bans WHERE ip = '$IP'";
$result1 = $conn->query($query1);
if ($result1->num_rows > 0) {
    header("Location: ../banned");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-GB3YDT4EXS"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date())
            gtag('config', 'G-GB3YDT4EXS');
        </script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../favicon.ico">
        <link rel="stylesheet" href="../style.css?bruh=<?php echo rand(1, 100000); ?>">
        <title>gorfeelt</title>
    </head>
    <body>
        <div class="header">
            <span class="title">gorfeelt</span>
            <div class="nav">
                <a class="nav-link" href="../rules">Rules</a>
                <a class="nav-link" href="../">Home</a>
            </div>
        </div>
        <h1>Start a thread on gorfeelt</h1>
        <form method="post" action="." enctype="multipart/form-data">
            <label>Image:</label>
            <input type="file" name="image"><br><br>
            <label>Content:</label><br>
            <textarea name="content" style="height:200px;max-width:400px;resize:none;" placeholder="Le content goes here"></textarea><br><br>
            <input type="submit" name="submit" value="Submit"><br>
        </form>
        <?php
        function startsWith(string $string, string $startString): bool { 
            $len = strlen($startString); 
            return (substr($string, 0, $len) === $startString); 
        }
        function format(string $string): string {
            $string = htmlspecialchars($string);
            $lines = explode("\n", $string);
            for ($i = 0; $i < count($lines); $i++) {
                if (startsWith($lines[$i], "&gt;")) {
                    $lines[$i] = "<greentext>" . $lines[$i] . "</greentext>";
                }
            }
            return addslashes(implode("<br>", $lines));
        }
        if (isset($_POST["submit"])) {
            if (strlen($_POST["content"]) > 0) {
                if (strlen($_FILES["image"]["tmp_name"]) > 0) {
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check) {
                        $mimeType = $check["mime"];
                        $typeFormat = explode("/", $mimeType);
                        $format = $typeFormat[1];
                        $fileData = file_get_contents($_FILES["image"]["tmp_name"]);
                        $result2 = $conn->query("SELECT id FROM posts ORDER BY id DESC LIMIT 1");
                        $id = $result2->fetch_assoc()["id"] + 1;
                        $content = format($_POST["content"]);
                        $file = fopen("../uploads/$id.$format", "wb");
                        fwrite($file, $fileData);
                            $query3 = "INSERT INTO posts (content, ip, image_format) VALUES ('$content', '$IP', '$format')";
                            if ($conn->query($query3)) {
                                header("Location: ../");
                            } else {
                                unlink("../uploads/$id.$format");
                                echo "<p>Le unepic thing happened and thread could not be started</p>";
                            } 
                        } else {
                            echo "<p>That's not an image bruh</p>";
                        }
                    } else {
                        echo "<p>This is an IMAGEboard, add an image</p>";
                    }
                } else {
                    echo "<p>Blank text is bad</p>";
                }
            }
        ?>
    </body>
</html>