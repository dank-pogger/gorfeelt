<?php
include "../config.php";
session_start();
$IP = $_SERVER["REMOTE_ADDR"];
$query1 = "SELECT * FROM bans WHERE ip = '$IP'";
$result1 = $conn->query($query1);
if ($result1->num_rows > 0) {
    header("Location: ./banned");
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
        <meta name="description" content="A cool imageboard for cool people, you can do anything here if it's not illegal">
        <title>gorfeelt</title>
    </head>
    <body>
        <div class="header">
            <span class="title">gorfeelt</span>
            <div class="nav">
                <a class="nav-link" href="../create_thread">New Thread</a>
                <a class="nav-link" href="../">Home</a>
            </div>
        </div>
        <h1>Le rules of gorfeelt</h1>
        <ol>
            <li>Don't spam</li>
            <li>Don't post excessively dumb shit</li>
            <li>Don't post illegal shit</li>
        </ol>
    </body>
</html>