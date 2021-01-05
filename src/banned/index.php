<?php
include "../config.php";
session_start();
$IP = $_SERVER["REMOTE_ADDR"];
$result = $conn->query("SELECT * FROM bans WHERE ip = '$IP'");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $banReason = $row["reason"];
    $banID = $row["id"];
} else {
    header("Location: ../");
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
        </div>
        <h1>Banned<img src="../img/trollface.png" style="height: 2rem"></h1>
        <?php
        echo "<p>You were banned for $banReason (ID $banID)</p>"
        ?>
    </body>
</html>