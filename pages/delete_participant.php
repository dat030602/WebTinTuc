<?php
require("../config/connect.php");
require("../config/method.php");
$id = $_GET['paper_id'];
$result = execute($conn, "delete", '',[], [], "participation", ['paper_id' => $_GET['paper_id'],'author_id' => $_GET['user_id']]);
header("Location: ../pages/paper.php?id=$id");
?>

<?php include('../components/footer.php') ?>
