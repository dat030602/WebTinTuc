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
    $user_add = $data['author_string_list'] . ',' . $data['full_name'];
    if (isset($_POST['submit'])) {
        $title = trim($_POST['title']);
        $abstract = trim($_POST['abstract']);
        $result = execute($conn, "update", '',['title'=>$title,'abstract'=>$abstract], [], "papers", ['paper_id' => $id]);
        header("Location: ./paper.php?id=$user_id");
    }
    ?>

<div class="container">
    <form method="post" action="" onsubmit="">
        <div class="form-group">
            <label for="name">Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Tên" value="<?php echo $data['title']?>">
        </div>
        <div class="form-group">
            <label for="website">Abstract</label>
            <textarea type="text" class="form-control" style="height: 200px;" name="abstract" id="abstract" placeholder="Abstract"><?php echo $data['abstract']?></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-4" name='submit'>Lưu</button>
    </form>
</div>
