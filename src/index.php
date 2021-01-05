<?php
include "./config.php";
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
        <link rel="icon" href="./favicon.ico">
        <link rel="stylesheet" href="./style.css?bruh=<?php echo rand(1, 100000); ?>">
        <meta name="description" content="A cool imageboard for cool people, you can do anything here if it's not illegal">
        <title>gorfeelt</title>
    </head>
    <body>
        <div class="header">
            <span class="title">gorfeelt</span>
            <div class="nav">
                <a class="nav-link" href="./create_thread">New Thread</a>  
                <a class="nav-link" href="./rules">Rules</a>
            </div>
        </div>
        <?php
        $query2 = "SELECT * FROM posts WHERE replying_to IS NULL ORDER BY id DESC";
        $result2 = $conn->query($query2) or die($conn->error);
        if ($result2->num_rows > 0) {
            while ($row = $result2->fetch_assoc()) {
                $content = $row["content"];
                $id = $row["id"];
                $date = date($row["posted_on"]);
                $user = $row["user"];
                $imgFormat = $row["image_format"];
                $anonID = substr(md5($row["ip"]), 0, 8);
                echo "<div class=\"post-wrapper\">";
                echo "<div class=\"post\">";
                echo "<div class=\"post-header\">&gt;&gt;$id | $date | ";
                if ($user === NULL) {
                    echo "Anonymous";
                } else {
                    echo $user;
                }
                echo "&ensp;<span class=\"id-badge\">ID:&nbsp;$anonID</span>";
                echo "<br></div>";
                echo "<div class=\"post-body\">";
                echo "<img class=\"post-image\" width=\"150\" src=\"./uploads/$id.$imgFormat\">";
                echo "<p class=\"post-text\">$content</p>";
                echo "</div>";
                echo "<br class=\"break-float\"";
                echo "<strong><a href=\"./thread?id=$id\">View Thread</a></strong>";
                echo "</div><br>";
                echo "<div class=\"reply-wrapper\">";
                $query3 = "SELECT * FROM posts WHERE replying_to = $id ORDER BY id ASC";
                $result3 = $conn->query($query3);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result3->fetch_assoc()) {
                        $replyContent = $row2["content"];
                        $replyID = $row2["id"];
                        $replyDate = date($row2["posted_on"]);
                        $replyUser = $row2["user"];
                        $replyImgFormat = $row2["image_format"];
                        $replyAnonID = substr(md5($row2["ip"]), 0, 8);
                        $replyNoImg = $row2["noimage"];
                        echo "<div class=\"post\">";
                        echo "<div class=\"post-header\">&gt;&gt;$replyID | $replyDate | ";
                        if ($replyUser === null) {
                            echo "Anonymous";
                        } else {
                            echo $replyUser;
                        }
                        echo "&ensp;<span class=\"id-badge\">ID:&nbsp;$replyAnonID</span>";
                        echo "<br></div>";
                        echo "<div class=\"post-body\">";
                        if ($replyNoImg == 0) {
                            echo "<img class=\"post-image\" width=\"150\" src=\"./uploads/$replyID.$replyImgFormat\">";
                        }
                        echo "<p class=\"post-text\">$replyContent</p>";
                        echo "<br class=\"break-float\">";
                        echo "</div>";
                        echo "</div>";
                        echo "<br>";
                    }
                }
                echo "</div></div></div>";
            }
        }
        ?>
    </body>
</html>