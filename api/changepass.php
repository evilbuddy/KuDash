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

$pass = $_GET["new"];
$userHash = hash("sha256", $dbarr["name"]);
$passHash = hash("sha256", $pass);
$token = $userHash . $passHash;

$req = $db->prepare("UPDATE users SET token=? WHERE token=?");
$req->bindValue(1, $token, SQLITE3_TEXT);
$req->bindValue(2, $dbtoken, SQLITE3_TEXT);
$newpass = $req->execute();

die(header("Location: ../index.php"));
?>