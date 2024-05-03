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

$group = $_GET["id"];

$req = $db->prepare("DELETE FROM links WHERE \"group\"=?");
$req->bindValue(1, $group, SQLITE3_TEXT);
$newgroup = $req->execute();

$req = $db->prepare("DELETE FROM groups WHERE id=?");
$req->bindValue(1, $group, SQLITE3_TEXT);
$newgroup = $req->execute();

die(header("Location: ../index.php"));
?>