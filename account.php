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
    <link rel="stylesheet" href="style.css">
    <style>
        .group
        {
            width: calc(50% - 5px);
            border: solid 1px #7E57C2;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 5px;
        }

        a
        {
            margin: 0 !important;
        }
    </style>
    <script>
        function changepass()
        {
            let newpass = document.getElementById("pass_new").value;
            window.open('api/changepass.php?new=' + newpass, "_self");
        }
    </script>
</head>

<body>
    <?php
    if($dbarr["isAdmin"] == 1)
    {
        echo "<div class=\"group\">";
        echo "<a class=\"form-button\" onclick=\"window.open('register.php', '_self')\">new user</a>";
        echo "</div>";
    }
    ?>

    <div class="group">
        <input id="pass_new" type="password" placeholder="New password"></input>
        <a class="form-button" onclick="changepass();">Change password</a>
    </div>
</body>