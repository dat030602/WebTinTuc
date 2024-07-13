<?php include('../components/header.php') ?>
<?php 
$errors = []; // biến để lưu tất cả các lỗi ở server thực hiện và trả về cho người dùng (1 mảng)
$success = ""; // là 1 chuỗi thông báo thành công (1 chuỗi)

require("../config/connect.php");
require("../config/method.php");
$user_id = intval($_GET['id']);
$result = execute($conn, "select", '','', ['*'], "AUTHORS", ['user_id' => $user_id]);
$data = $result->fetch_array(MYSQLI_ASSOC);
if (isset($_POST['submit'])) {
    $username = trim($_POST['name']);
    $website = trim($_POST['website']);
    $data_update = ['full_name'=>$username,'website'=>$website,'profile_json_text'=>$profile_json_text];
    if (isset($_FILES["avatar"])) {
        $targetDir = "D:/code/PHP/WebTinTuc/uploads/";
        $targetFile = $targetDir . basename($_FILES["avatar"]["name"]);
        echo $_FILES["avatar"]["tmp_name"];
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
            $uploadedFilePath = $targetFile;
            $hash = hash_file('sha256', $uploadedFilePath);
            $data_update['image_path'] = $hash;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    
    $profile_json_text = trim($_POST['profile_json_text']);
    $result = execute($conn, "update", '',$data_update, [], "AUTHORS", ['user_id' => $user_id]);
    header("Location: ./profile.php?id=$user_id");
}
?>
<div class="container">
    <form method="post" action="" onsubmit="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Tên</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Tên" value="<?php echo $data['full_name']?>">
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="text" class="form-control" name="website" id="website" placeholder="Website" value="<?php echo $data['website']?>">
        </div>
        <div class="form-group">
            <label for="avatar">Avatar</label>
            <input type="file" class="form-control" name="avatar" id="avatar" placeholder="Avatar">
        </div>
        <div class="form-group">
            <label for="website">Profile</label>
            <textarea type="text" class="form-control" style="height: 200px;" name="profile_json_text" id="profile_json_text" placeholder="Profile"><?php echo $data['profile_json_text']?></textarea>
        </div>
        <div class='d-flex justify-content-center mt-4'>
            <button type="submit" class="btn btn-primary mt-4 p-4 pt-2 pb-2" name='submit'>Lưu</button>
        </div>
    </form>
</div>
<?php include('../components/footer.php') ?>
