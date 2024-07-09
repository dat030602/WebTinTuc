<?php include_once('components/header.php') ?>
<?php 
$errors = []; // biến để lưu tất cả các lỗi ở server thực hiện và trả về cho người dùng (1 mảng)
$success = ""; // là 1 chuỗi thông báo thành công (1 chuỗi)

require("./config/connect.php");
require("./config/method.php");

$sqlstring = "SELECT 
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
    JOIN conferences c ON c.conference_id = p.paper_id
    WHERE YEAR(c.start_date) = YEAR(CURDATE())
) ranked
WHERE row_num <= 5;
";
$result = execute($conn,"special", $sqlstring,"", [], "", []);
?>
<div class="container">

      <?php
      $pre_topic = "";
      $cur_topic = "";
      $iq = 1;
      while ($row = mysqli_fetch_array($result)) {
        $cur_topic = $row['topic_name'];
        $topic_name = $row['topic_name'];
        $title = $row['title'];
        $paper_id = $row['paper_id'];
        $author_username = $row['author_username'];
        $user_id = $row['user_id'];
      ?>
        <?php 
        if($cur_topic != $pre_topic){
          $str_echo = "<div class='row'><h1 style='font-size: 20px;'>Chủ đề: $topic_name</h1>";
          if($iq!=1){
            $str_echo = "</div>" . $str_echo;
          }
          else{
            $iq=2;
          }
        }
        echo $str_echo;
        $str_echo = ''
        ?>
            <?php echo "
            <div class='col-4 mb-2'>
                <div class='card'>
                    <div class='card-body'>
                        <h3 class='card-title' style='font-size: 16px;'>Tên bài báo: <a href='/pages/paper.php?id=$paper_id'>$title></a></h3>
                        <p class='card-text' style='font-size: 16px;'>Tác giả: <a href='/pages/profile.php?id=$user_id>'>$author_username></a></p>
                    </div>
                </div>
            </div>
            ";
            ?>
      <?php 
        $pre_topic = $cur_topic;
        $cur_topic = "";
    } ?>

    
</div>
<?php include_once('components/footer.php') ?>
