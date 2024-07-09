<?php 

require("./config/connect.php");
require("./config/method.php");

if (isset($_GET['q'])) {
  $query = $_GET['q'];
  $sql = "SELECT paper_id FROM papers WHERE title LIKE '%$query%'";
  $result = execute($conn,"special", $sql,"", [], "", []);

  if ($result->num_rows > 0) {
    $paper_ids = [];
    while ($row = $result->fetch_assoc()) {
        $paper_ids[] = $row['paper_id'];
    }
    $paper_id_string = implode(',', $paper_ids);
    $response = [
        'status' => 'success',
        'link' => "/pages/search.php?q=$paper_id_string",
        'html' => "<div></div>",
    ];
  
    // Send JSON response back to the JavaScript code
    header('Content-Type: application/json');
    echo json_encode($response);
  }
  else {
    // Handle case where no query parameter is provided
    $response = [
        'status' => 'error',
        'message' => 'No search query provided'
    ];
    echo json_encode($response);
  }
} 
?>