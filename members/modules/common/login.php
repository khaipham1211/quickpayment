<?php include_once('widgets/header.php'); ?>
<?php
$error = array();
 
// BƯỚC 1: KIỂM TRA NẾU LÀ ADMIN THÌ REDIRECT

 
// BƯỚC 2: NẾU NGƯỜI DÙNG SUBMIT FORM
if (is_submit('login'))
{    
    // lấy tên đăng nhập và mật khẩu
    $id_member = input_post('id_member');
    $password = input_post('password');
     
    // Kiểm tra tên đăng nhập
    if (empty($id_member)){
        $error['id_member'] = 'Bạn chưa nhập tên đăng nhập';
    }
     
    // Kiểm tra mật khẩu
    if (empty($password)){
        $error['password'] = 'Bạn chưa nhập mật khẩu';
    }
     
    // Nếu không có lỗi
    if (!$error)
    {
        // include file xử lý database user
        include_once('database/user.php');
         
        // lấy thông tin user theo id_member
        $user = db_user_get_by_id_member($id_member);
         
        // Nếu không có kết quả
        if (empty($user)){
            $error['id_member'] = 'Tên đăng nhập không đúng';
        }
        // nếu có kết quả nhưng sai mật khẩu
        else if ($user['password'] != md5($password)) {
            $error['password'] = 'Mật khẩu bạn nhập không đúng';
        }
         
        // nếu mọi thứ ok thì tức là đăng nhập thành công 
        // nên thực hiện redirect sang trang chủ
        if (!$error){
            set_logged($user['id_member'], $user['ID_UG'],$user['name']);
            redirect(base_url('admin/?m=common&a=dashboard'));
        }
    }
}
 
?>
 
<?php include_once('widgets/header.php'); ?>
<h1 align="center">Trang đăng nhập!</h1>
<form method="post" action="<?php echo base_url('admin/?m=common&a=login'); ?>">
    <table align="center">
        <tr>
            <td>UserName</td>
            <td>
                <input type="text" name="id_member" value=""/>
                <?php show_error($error, 'id_member'); ?>
            </td>
        </tr>
        <tr>
            <td>Password</td>
            <td>
                <input type="password" name="password" value=""/>
                <?php show_error($error, 'password'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="hidden" name="request_name" value="login"/>
            </td>
            <td>
                <input type="submit" name="login-btn" value="Đăng nhập"/>
            </td>
        </tr>
    </table>
</form>
<?php include_once('widgets/footer.php'); ?>