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

    <script>
        function addlink()
        {
            let name = document.getElementById("name").value;
            let public = 0;

            if(document.getElementById("public").checked == true)
            {
                public = 1;
            }

            window.open("api/creategroup.php?name=" + name + "&public=" + public, "_self");
        }
    </script>
</head>

<body>
    <center><input id="name" type="text" placeholder="name">
    <input id="public" type="checkbox"><label for="public">Public</label>
    <a class="form-button" onclick="addlink()">add group</a></center>
</body>