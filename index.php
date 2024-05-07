<?php
$token = $_COOKIE["token"];

if(!isset($token)) {
    die(header("Location: login.php"));
}

$db = new SQLite3("data.db");
$req = $db->prepare("SELECT * FROM users WHERE token=?");
$req->bindValue(1, $token, SQLITE3_TEXT);
$user = $req->execute();
$dbarr = $user->fetchArray();
$dbtoken = $dbarr["token"];

if($token != $dbtoken) {
    die(header("Location: login.php"));
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
            window.open('login.php', '_self')
        }
    </script>
</head>

<body>
    <div class="groups">
        <div class="group-btn" onclick="window.open('addgroup.php', '_self')"><img src="add.png"></div>

    <?php
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

    if($dbarr["isAdmin"] == 1)
    {
        echo "<div class=\"group-btn\" style=\"float: right\"><a onclick=\"window.open('register.php', '_self')\">new user</a></div>";
    }
    ?>
        <div class="group-btn" style="float: right" onclick="logout();"><a onclick="logout();">logout</a></div>
    </div>

    <div class="group-div div-active">
        <center><h1 style="color:#fff">Please open or create a tab.</h1></center>
    </div>

    <?php
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

    $req = $db->prepare("SELECT COUNT(*) as count FROM groups WHERE id LIKE ?");
    $req->bindValue(1, $uname . "%", SQLITE3_TEXT);
    $countArr = $req->execute();
    $countRow = $countArr->fetchArray();
    $count = $countRow["count"];

    if($count == 1) {
        $group = $groups->fetchArray();
        $groupname = str_replace($uname . "_", "", $group["id"]);
        echo "<script>opengroup('" . $groupname . "')</script>";
    }
    ?>
</body>