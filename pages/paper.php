<?php include_once('../components/header.php') ?>
<?php 
$errors = []; // biến để lưu tất cả các lỗi ở server thực hiện và trả về cho người dùng (1 mảng)
$success = ""; // là 1 chuỗi thông báo thành công (1 chuỗi)

require("../config/connect.php");
require("../config/method.php");
$id = intval($_GET['id']);
if (isset($_SESSION['account'])) 
$user_id = intval($_SESSION['account']['user_id']);
?>
<?php 
    $sqlstring = "
        SELECT 
            topic_name,
            paper_id,
            title,
            abstract,
            author_string_list,
            author_username,
            user_id,
            topic_id,
            conference_id,
            full_name
        FROM (
            SELECT 
                t.topic_name,
                p.paper_id,
                p.title,
                p.abstract,
                p.author_string_list,
                u.username AS author_username,
                p.user_id,
                p.topic_id,
                p.conference_id,
                a.full_name,
                ROW_NUMBER() OVER (PARTITION BY t.topic_id ORDER BY c.start_date DESC) AS row_num
            FROM PAPERS p
            JOIN TOPICS t ON p.topic_id = t.topic_id
            JOIN USERS u ON p.user_id = u.user_id
            JOIN AUTHORS a ON a.user_id = u.user_id
            JOIN conferences c ON c.conference_id = p.conference_id
            WHERE p.paper_id = $id
        ) ranked
        WHERE row_num <= 5;
    ";
    $result_paper = execute($conn,"special", $sqlstring,"", [], "", []);
    $data = $result_paper->fetch_array(MYSQLI_ASSOC);;
    $user_add = $data['author_string_list'] . ',' . $data['full_name']
    ?>
<div>
    <h1><?php echo $data['title']?></h1>
    <p>Topic: <?php echo $data['topic_name']?></p>
    <p>User: <?php echo $data['full_name']?></p>
    <p>Participaint: <?php echo $data['author_string_list']?></p>
    
    <p><?php echo $data['abstract']?></p>
</div>
<?php
if (strpos($data['full_name'], $data['author_string_list']) !== false) {
echo "<a href='/pages/add_member_paper.php?paper_id=<?php echo $id?>&user=<?php echo $user_add?>' class='btn btn-success'>Trở thành thành viên</a>";
}
?>
<h5>Đồng tác giả</h5>
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Tên</th>
      <th scope="col">Role</th>
      <th scope="col">Hành động</th>
    </tr>
  </thead>
  <tbody>
        <?php
        $sqlstring = "select user_id,full_name,role from participation p join authors a on p.author_id=a.user_id where p.paper_id=$id";
        $result_par = execute($conn,"special", $sqlstring,"", [], "", []);
      while ($row = mysqli_fetch_array($result_par)) {
        $par_user_id = $row['user_id'];
        $par_name = $row['full_name'];
        $par_role = $row['role'];
        $par_link = "<a href='/pages/delete_participant.php?paper_id=$id&user_id=$par_user_id'>Xoá</a>";
        if (!isset($_SESSION['account'])) $par_link = ""
      ?>
        <div class="col">
            <?php echo "
            <tr>
                <th scope='row'>$par_user_id</th>
                <td>$par_name</td>
                <td>$par_role</td>
                <td>$par_link</td>
            </tr>
            ";
            ?>
        </div>
      <?php $user_ids[] = $par_user_id; } ?>
  </tbody>
</table>


<?php
  if (isset($_SESSION['account'])) 
  if ($_SESSION['account']['user_type'] =='admin') {
    echo "<h5>Thêm tác giả</h5>";
    echo "<table class='table table-hover'>";
    echo "<thead>
        <tr>
          <th scope='col'>ID</th>
          <th scope='col'>Tên</th>
          <th scope='col'>Thêm</th>
        </tr>
      </thead>";
    }
?>
  <tbody>
        <?php
        if (isset($_SESSION['account'])) 
        if ($_SESSION['account']['user_type'] =='admin') {
        $user_ids_str = implode(",", $user_ids);
        $sqlstring = "select user_id,full_name from authors where user_id NOT IN ($user_ids_str)";
        $result_not_par = execute($conn,"special", $sqlstring,"", [], "", []);
      while ($row = mysqli_fetch_array($result_not_par)) {
        $par_not_user_id = $row['user_id'];
        $par_not_name = $row['full_name'];
        $par_not_link = "
        <div><a href='/pages/add_participant.php?paper_id=$id&user_id=$par_not_user_id&role=first_author'>First member</a></div>
        <div><a href='/pages/add_participant.php?paper_id=$id&user_id=$par_not_user_id&role=member'>Member</a></div>
        ";
      ?>
        <div class="col">
            <?php echo "
            <tr>
                <th scope='row'>$par_not_user_id</th>
                <td>$par_not_name</td>
                <td>$par_not_link</td>
            </tr>
            ";
            ?>
        </div>
      <?php } }?>
  </tbody>
</table>
<?php
if (isset($_SESSION['account'])) 
if ($_SESSION['account']['user_id'] == $data['user_id'] || $_SESSION['account']['user_type'] =='admin') {
echo "<a href='/pages/update_paper.php?id=$id' class='btn btn-success'>Cập nhật</a>";
}
?>