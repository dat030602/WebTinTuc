<?php
require("../config/connect.php");
require("../config/method.php");
$id = $_GET['paper_id'];
$result = execute($conn, "insert", '',[
    'author_id'=> $_GET['user_id'],
    'paper_id'=> $_GET['paper_id'],
    'role'=> $_GET['role'],
    'date_added'=> date("Y-m-d H:i:s"),
    'status'=> 'show',

], [], "participation", []);
header("Location: ../pages/paper.php?id=$id");
?>

<?php include('../components/footer.php') ?>
