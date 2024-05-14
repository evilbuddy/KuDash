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
$id = $dbarr["name"] . "_" . strtolower(str_replace(" ", "_", $name));

$public = $_GET["public"];

$req = $db->prepare("SELECT COUNT(*) as count FROM groups WHERE id=?");
$req->bindValue(1, $id, SQLITE3_TEXT);
$countArr = $req->execute();
$countRow = $countArr->fetchArray();
$count = $countRow["count"];

if($count != 0)
{
    echo "<h1>The group " . $name . " already exists !</h1>";
    echo "<a href=\"../index.php\">home</a>";
    die();
}

$req = $db->prepare("INSERT INTO groups (id, display, public) VALUES(?, ?, ?)");
$req->bindValue(1, $id, SQLITE3_TEXT);
$req->bindValue(2, $name, SQLITE3_TEXT);
$req->bindValue(3, $public, SQLITE3_TEXT);
$newgroup = $req->execute();

die(header("Location: ../index.php"));
?>