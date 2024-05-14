<?php
$token = $_COOKIE["token"];

$db = new SQLite3("data.db");

if(isset($token)) {
    $req = $db->prepare("SELECT * FROM users WHERE token=?");
    $req->bindValue(1, $token, SQLITE3_TEXT);
    $user = $req->execute();
    $dbarr = $user->fetchArray();
    $dbtoken = $dbarr["token"];

    if($token != $dbtoken) {
        die(header("Location: login.php"));
    }
}
?>

<head>
    <title>KuDash</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="tabs.js"></script>
    <script>
        function logout()
        {
            document.cookie = 'token=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/';
            window.open('index.php', '_self')
        }
    </script>
</head>

<body>
    <div class="groups">
        <div class="group-btn" onclick="window.open('addgroup.php', '_self')"><img src="add.png"></div>

        <?php
        $groups = $db->query("SELECT * FROM groups WHERE public=1");
        while($group = $groups->fetchArray()) {
            if(isset($token) && $dbarr["name"] == substr($group["id"], 0, strlen($dbarr["name"])))
            {
                continue;
            }
            $groupname = str_replace($uname . "_", "", $group["id"]);
            echo "<div id=\"btn-" . $groupname . "\" class=\"group-btn\">";
            echo "<a onclick=\"opengroup('" . $groupname . "')\">" . $group["display"] . "</a>";
            echo "</div>";
        }

        if(isset($token)) {
            $uname = $dbarr["name"];
            $req = $db->prepare("SELECT * FROM groups WHERE id LIKE ?");
            $req->bindValue(1, $uname . "%", SQLITE3_TEXT);
            $groups = $req->execute();
            while($group = $groups->fetchArray()) {
                $groupname = str_replace($uname . "_", "", $group["id"]);
                echo "<div id=\"btn-" . $groupname . "\" class=\"group-btn\">";
                echo "<a onclick=\"opengroup('" . $groupname . "')\">" . $group["display"] . "</a>";
                echo "<img class=\"remove\" src=\"remove.png\" onclick=\"window.open('api/removegroup.php?id=" . $group["id"] . "', '_self')\">";
                echo "</div>";
            }

            echo "<div class=\"group-btn\" style=\"float: right\"><a onclick=\"logout();\">logout</a></div>";
            echo "<div class=\"group-btn\" style=\"float: right\"><a onclick=\"window.open('account.php', '_self')\">account</a></div>";
        }
        else
        {
            echo "<div class=\"group-btn\" style=\"float: right\"><a onclick=\"window.open('login.php', '_self')\">login</a></div>";
        }
        ?>
    </div>

    <div class="group-div div-active">
        <center><h1 style="color:#fff">Please open or create a tab.</h1></center>
    </div>

    <?php
    $groups = $db->query("SELECT * FROM groups WHERE public=1");
    while($group = $groups->fetchArray()) {
        if(isset($token) && $dbarr["name"] == substr($group["id"], 0, strlen($dbarr["name"])))
        {
            continue;
        }
        $groupname = str_replace($uname . "_", "", $group["id"]);

        $req = $db->prepare("SELECT * FROM links WHERE \"group\"=?");
        $req->bindValue(1, $group["id"], SQLITE3_TEXT);
        $links = $req->execute();

        echo "<div id=\"grp-" . $groupname . "\" class=\"group-div\">";

        while($link = $links->fetchArray())
        {
            echo "<div>";
            echo "<a href=\"" . $link["url"] . "\"><img src=\"https://www.google.com/s2/favicons?domain=" . $link["url"] . "&sz=64\">";
            echo $link["name"] . "</a>";
            echo "</div>";
        }

        echo "</div>";
    }

    if(isset($token)) {
        $uname = $dbarr["name"];
        $req = $db->prepare("SELECT * FROM groups WHERE id LIKE ?");
        $req->bindValue(1, $uname . "%", SQLITE3_TEXT);
        $groups = $req->execute();
        while($group = $groups->fetchArray()) {
            $groupname = str_replace($uname . "_", "", $group["id"]);

            $req = $db->prepare("SELECT * FROM links WHERE \"group\"=?");
            $req->bindValue(1, $group["id"], SQLITE3_TEXT);
            $links = $req->execute();

            echo "<div id=\"grp-" . $groupname . "\" class=\"group-div\">";

            echo "<div><a href=\"addlink.php?group=" . $group["id"] . "\"><img src=\"add.png\">Add</a></div>";

            while($link = $links->fetchArray())
            {
                echo "<div>";
                echo "<img class=\"remove\" src=\"remove.png\" onclick=\"window.open('api/removelink.php?name=" . $link["name"] . "&group=" . $group["id"] . "', '_self')\">";
                echo "<a href=\"" . $link["url"] . "\"><img src=\"https://www.google.com/s2/favicons?domain=" . $link["url"] . "&sz=64\">";
                echo $link["name"] . "</a>";
                echo "</div>";
            }

            echo "</div>";
        }
    }

    $req = $db->prepare("SELECT COUNT(*) as count FROM groups WHERE id LIKE ? AND public=0");
    $req->bindValue(1, $uname . "%", SQLITE3_TEXT);
    $countArr = $req->execute();
    $countRow = $countArr->fetchArray();
    $count = $countRow["count"];

    $req = $db->query("SELECT COUNT(*) as count FROM groups WHERE public=1");
    $countRow = $req->fetchArray();
    $count = $count + $countRow["count"];

    if($count == 1) {
        $group = $groups->fetchArray();
        $groupname = str_replace($uname . "_", "", $group["id"]);
        echo "<script>opengroup('" . $groupname . "')</script>";
    }
    ?>
</body>