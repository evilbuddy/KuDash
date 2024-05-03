<?php
$token = $_COOKIE["token"];

if(!isset($token)) {
    die(header("Location: ../login.php"));
}

$db = new SQLite3("../data.db");
$req = $db->prepare("SELECT * FROM users WHERE token=?");
$req->bindValue(1, $token, SQLITE3_TEXT);
$user = $req->execute();
$dbtoken = $user->fetchArray()["token"];
$admin = $user->fetchArray()["isAdmin"];

if($token != $dbtoken) {
    die(header("Location: ../login.php"));
}

if($admin != 1) {
    die(header("Location: ../index.php"));
}

$newtoken = $_GET["token"];
$newname = $_GET["name"];

if(!isset($newtoken) | !isset($newname)) {
    die(header("Location: ../login.php"));
}

$req = $db->prepare("INSERT INTO users (name, token) VALUES(?, ?)");
$req->bindValue(1, $newname, SQLITE3_TEXT);
$req->bindValue(2, $newtoken, SQLITE3_TEXT);
$newuser = $req->execute();

die(header("Location: ../index.php"));
?>