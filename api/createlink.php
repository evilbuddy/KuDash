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
$link = $_GET["link"];
$group = $_GET["group"];

$req = $db->prepare("INSERT INTO links (name, url, \"group\") VALUES(?, ?, ?)");
$req->bindValue(1, $name, SQLITE3_TEXT);
$req->bindValue(2, $link, SQLITE3_TEXT);
$req->bindValue(3, $group, SQLITE3_TEXT);
$newgroup = $req->execute();

die(header("Location: ../index.php"));
?>