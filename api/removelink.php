<?php
$token = $_COOKIE["token"];

if(!isset($token)) {
    die(header("Location: ../login.php"));
}

$db = new SQLite3("../data.db");
$req = $db->prepare("SELECT * FROM users WHERE token=?");
$req->bindValue(1, $token, SQLITE3_TEXT);
$user = $req->execute();
$dbarr = $user->fetchArray();
$dbtoken = $dbarr["token"];

if($token != $dbtoken) {
    die(header("Location: ../login.php"));
}

$name = $_GET["name"];
$group = $_GET["group"];

$req = $db->prepare("DELETE FROM links WHERE name=? AND \"group\"=?");
$req->bindValue(1, $name, SQLITE3_TEXT);
$req->bindValue(2, $group, SQLITE3_TEXT);
$newgroup = $req->execute();

die(header("Location: ../index.php"));
?>