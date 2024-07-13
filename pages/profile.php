<?php include('../components/header.php') ?>
<style>
    <?php include('../assets/css/profile.css') ?>
</style>
<?php 
$errors = []; // biến để lưu tất cả các lỗi ở server thực hiện và trả về cho người dùng (1 mảng)
$success = ""; // là 1 chuỗi thông báo thành công (1 chuỗi)

require("../config/connect.php");
require("../config/method.php");
$user_id = intval($_GET['id']);
$result = execute($conn, "select", '','', ['*'], "AUTHORS", ['user_id' => $user_id]);
$data = $result->fetch_array(MYSQLI_ASSOC);
$profile_json_text = $data['profile_json_text'];
?>
<div class="container">
    <div class="d-flex align-item-center">
        <div class="p-3">
            <img class="avatar" src="<?php echo $data['image_path']?>" alt="Avatar"/>
        </div>
        <div>
            <h2><?php echo $data['full_name']?></h2>
            <p>Website: <strong><?php echo $data['website']?></strong></p>
        </div>
    </div>
    <?php
        if($profile_json_text != '') {
            $data = json_decode($profile_json_text, true);
            echo '<p>Thông tin thêm:</p>';
            echo '<p><strong>Bio:</strong> ' . $data['bio'] . '</p>';
            echo '<p><strong>Interests:</strong> ';
            echo '<ul>';
            foreach ($data['interests'] as $interest) {
                echo '<li>' . $interest . '</li>';
            }
            echo '</ul>';
            echo '</p>';
        }
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
            conference_id
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
                ROW_NUMBER() OVER (PARTITION BY t.topic_id ORDER BY c.start_date DESC) AS row_num
            FROM PAPERS p
            JOIN TOPICS t ON p.topic_id = t.topic_id
            JOIN USERS u ON p.user_id = u.user_id
            JOIN conferences c ON c.conference_id = p.conference_id
            WHERE p.user_id = $user_id
        ) ranked
        WHERE row_num <= 5;
    ";
    $result_paper = execute($conn,"special", $sqlstring,"", [], "", []);
    ?>
    <div class="row">
    <div class="ind">
      <?php
      while ($row = mysqli_fetch_array($result_paper)) {
        $title = $row['title'];
        $paper_id = $row['paper_id'];
        $author_username = $row['author_username'];
        $user_id = $row['user_id'];
      ?>
        <div class="col">
            <?php echo "
            <div class='col-4 mb-2'>
                <div class='card'>
                    <div class='card-body'>
                        <h3 class='card-title' style='font-size: 16px;'>Tên bài báo: <a href='/pages/paper.php?id=$paper_id'>$title></a></h3>
                    </div>
                </div>
            </div>
            ";
            ?>
        </div>
      <?php } ?>

    </div>
  </div>
</div>
</div>
<?php
if (isset($_SESSION['account'])) 
if ($_SESSION['account']['user_id'] == $user_id || $_SESSION['account']['user_type'] =='admin')
    echo "
    <div class='d-flex justify-content-center mt-4'>
        <a href='/pages/update_profile.php?id=$user_id' class='btn btn-success p-4 pt-2 pb-2'>Cập nhật</a>
    </div>
"
?>
</div>
<?php include('../components/footer.php') ?>
