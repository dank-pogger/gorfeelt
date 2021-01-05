<?php
include "../config.php";
session_start();
$IP = $_SERVER["REMOTE_ADDR"];
$urlID = $_GET["id"];
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
                <a class="nav-link" href="../">Home</a>  
                <a class="nav-link" href="../create_thread">New Thread</a>  
                <a class="nav-link" href="../rules">Rules</a>
            </div>
        </div>
        <?php
        $query2 = "SELECT * FROM posts WHERE id = $urlID";
        if (isset($urlID)) {
            $result2 = $conn->query($query2);
            if ($result2) {
                $row2 = $result2->fetch_assoc();
                if ($row2["replying_to"] !== NULL) {
                    $replyingTo = $row2["replying_to"];
                    header("Location: ./?id=$replyingTo");
                }
            }
        }
        $content = $row2["content"];
        $threadID = $row2["id"];
        $date = date($row2["posted_on"]);
        $user = $row2["user"];
        $imgFormat = $row2["image_format"];
        $anonID = substr(md5($row2["ip"]), 0, 8);
        echo "<div class=\"post-wrapper-full-width\">";
        echo "<div class=\"post\">";
        if (isset($urlID) and isset($row2)) {
            echo "<div class=\"post-header\">&gt;&gt;$threadID | $date | ";
            if ($user === NULL) {
                echo "Anonymous";
            } else {
                echo $user;
            }
            echo "&ensp;<span class=\"id-badge\">ID:&nbsp;$anonID</span>";
            echo "<br></div>";
            echo "<div class=\"post-body\">";
            echo "<img class=\"post-image\" width=\"150\" src=\"../uploads/$threadID.$imgFormat\">";
            echo "<p class=\"post-text\">$content</p>";
            echo "</div>";
            echo "<br class=\"break-float\"";
        } else {
            echo "<h2>Thread Not Found</h2>";
        }
        echo "</div>";
        echo "</div>";
        echo "<br>";
        ?>
        <br>
        <?php
        if (isset($urlID) and isset($row2)) {
            echo "<hr>";
            echo "<h2>Add a reply</h2>";
            echo "<form method=\"post\" action=\"./?id=$urlID\" enctype=\"multipart/form-data\">";
            echo "<label>Image (Optional):</label>";
            echo "<input type=\"file\" name=\"image\"><br><br>";
            echo "<label>Content:</label><br>";
            echo "<textarea name=\"content\" style=\"height:200px;max-width:400px;resize:none;\" placeholder=\"Le content goes here\"></textarea><br><br>";
            echo "<input type=\"submit\" name=\"submit\" value=\"Submit\"><br>";
            echo "</form>";
        }
        ?>
        <br>
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
                        $fileID = $result2->fetch_assoc()["id"] + 1;
                        $content = format($_POST["content"]);
                        $file = fopen("../uploads/$fileID.$format", "wb");
                        fwrite($file, $fileData);
                        $query3 = "INSERT INTO posts (content, ip, image_format, replying_to) VALUES ('$content', '$IP', '$format', $urlID)";
                        if ($conn->query($query3)) {
                            header("Location: ./?id=$urlID");
                        } else {
                            unlink("../uploads/$fileID.$format");
                            echo "<p>Le unepic thing happened and thread could not be started</p>";
                        }
                    } else {
                        echo "<p>That's not an image bruh</p>";
                    }
                } else {
                    $content = format($_POST["content"]);
                    $query3 = "INSERT INTO posts (content, ip, noimage, replying_to) VALUES ('$content', '$IP', 1, $urlID)";
                    if ($conn->query($query3)) {
                        header("Location: ./?id=$urlID");
                    } else {
                        echo "<p>Le unepic thing happened and thread could not be started</p>";
                        echo $conn->error;
                        echo $query3;
                    }
                }
            } else {
                echo "<p>Blank text is bad</p>";
            }
        }
        ?>
        <?php
        $query2 = "SELECT * FROM posts WHERE replying_to = $urlID ORDER BY id ASC";
        $result3 = $conn->query($query2);
            if ($result3) {
                echo "<hr>";
                echo "<h3>Replies</h3>";
                while ($row3 = $result3->fetch_assoc()) {
                    $replyContent = $row3["content"];
                    $replyID = $row3["id"];
                    $replyDate = date($row3["posted_on"]);
                    $replyUser = $row["user"];
                    $replyImgFormat = $row3["image_format"];
                    $replyNoImg = $row3["noimage"];
                    $replyAnonID = substr(md5($row3["ip"]), 0, 8);
                    echo "<div class=\"post-wrapper\">";
                    echo "<div class=\"post\">";
                    echo "<div class=\"post-header\">&gt;&gt;$replyID | $replyDate | ";
                    if ($replyUser === NULL) {
                        echo "Anonymous";
                    } else {
                        echo $replyUser;
                    }
                    echo "&ensp;<span class=\"id-badge\">ID:&nbsp;$replyAnonID</span>";
                    echo "<br></div>";
                    echo "<div class=\"post-body\">";
                    if ($replyNoImg == 0) {
                        echo "<img class=\"post-image\" width=\"150\" src=\"../uploads/$replyID.$replyImgFormat\">";
                    }
                    echo "<p class=\"post-text\">$replyContent</p>";
                    echo "</div>";
                    echo "<br class=\"break-float\">";
                    echo "</div>";
                    echo "</div>";
            }
        }
        ?>
    </body>
</html>