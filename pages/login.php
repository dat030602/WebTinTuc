<?php include_once('../components/header.php') ?>
<?php 
$errors = []; // biến để lưu tất cả các lỗi ở server thực hiện và trả về cho người dùng (1 mảng)
$success = ""; // là 1 chuỗi thông báo thành công (1 chuỗi)

require("../config/connect.php");
require("../config/method.php");
if (isset($_SESSION['account'])) {
    header('Location: ../index.php');
}
function set_account_session(&$session, $account) {
    $session['account'] = $account;
}
if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $result = execute($conn, "select", '','', ['*'], "users", ['username' => $username, 'password' => $password]);
    if (mysqli_num_rows($result) > 0) {
        $account = $result->fetch_array(MYSQLI_ASSOC);
        set_account_session($_SESSION, $account);
        $result = execute($conn, "update", '',['status'=>'active'], [], "users", ['username' => $username, 'password' => $password]);
        header('Location: ../index.php');
    } else {
        // Đăng nhập thất bại
        $errors[] = "Thông tin đăng nhập chưa đúng. Vui lòng đăng nhập lại";
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col col-md-6 col-md-offset-3">
            <div class="panel panel-defaul">
                <div class="panel-heading">
                    Đăng nhập
                </div>
                <div class="panel-body">
                    <?php if (count($errors) > 0) : ?>
                        <?php for ($i = 0; $i < count($errors); $i++) : ?>
                            <p class="errors" style="color: red;"> <?php echo $errors[$i]; ?> </p>
                        <?php endfor; ?>
                    <?php endif; ?>
                    <?php if ($success) : ?>
                        <p class="success" style="color: green;"> <?php echo $success; ?> </p>
                    <?php endif; ?>
                    <form method="post" action="" onsubmit="return handeFormSubmit();">
                        <div class="form-group">
                            <label for="username">Email hoặc tên đăng nhập</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Nhập Email hoặc tên đăng nhập">
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu">
                        </div>

                        <button type="submit" class="btn btn-primary mt-4" name='submit'>Đăng nhập</button>
                        <button type="button" class="btn btn-primary mt-4">
                            <a href="forget-password.php">Quên mật khẩu</a>
                            <br>
                        <button type="button" class="btn btn-white mt-4">
                        <a href="regisin.php">Tạo tài khoản</a></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('../components/footer.php') ?>
