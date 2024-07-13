<?php
require("../config/connect.php");
require("../config/method.php");
$id = $_GET['paper_id'];
$result = execute($conn, "update", '',['author_string_list'=> $_GET['user']], [], "papers", ['paper_id' => $_GET['paper_id']]);
header("Location: ../pages/paper.php?id=$id");
?>

<?php include('../components/footer.php') ?>
