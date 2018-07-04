<?php if (!defined('IN_SITE')) die ('The request not found'); ?>
<?php 
// Biến chứa lỗi
$error = array();

// require file xử lý database cho user
require_once('database/user.php');

// Nếu người dùng submit form
if (is_submit('update_pass'))
{   
    $id_member = get_current_id();
    // Lấy danh sách dữ liệu từ form
    $data = array(
        'id_member'       => get_current_id(),
        'passwordold'     => input_post('passwordold'), 
        'password'        => input_post('passwordnew'),
        're_password'     => input_post('re_password'),
    );
    // Thực hiện validate
    $error = db_updatepass_validate($data);
    // Nếu validate không có lỗi
    if (!$error)
    {
        // Xóa key re-password ra khoi $data
        unset($data['re_password']);
        $passwordnew = md5($data['password']);
        // Nếu insert thành công thì thông báo
        // và chuyển hướng về trang danh sách user

        if (db_execute(db_create_sql("UPDATE  members SET password ='$passwordnew' {where}", array('id_member' => $id_member)))){
            ?>
            <script language="javascript">
                alert('Sửa người dùng thành công!');
                 window.location = '<?php echo create_link(base_url('admin'), array('m' => 'common', 'a' => 'dashboard')); ?>';
            </script>
            <?php
            die();
        }
    }
}
?>
 
<?php include_once('widgets/header.php'); ?>
<div class="container">
<center><h1>Change Password</h1></center>

<form id="main-form" method="post" action="">
    <input type="hidden" name="request_name" value="update_pass"/>
    <center><table cellspacing="0" cellpadding="0" class="form">
        <tr>
            <th><sup style="color: red">(*)</sup>Old Password </th>
            <td>
                <input type="password" class="form-control" placeholder="Old Password" name="passwordold" value="<?php echo input_post('passwordold'); ?>" />
                <?php show_error($error, 'passwordold'); ?>
            </td>
        </tr>
        <tr>
            <th><sup style="color: red">(*)</sup>New Password </th>
            <td>
                <input type="password" class="form-control" placeholder="Password" name="passwordnew" value="<?php echo input_post('passwordnew'); ?>" />
                <?php show_error($error, 'password'); ?>
            </td>
        </tr>
        <tr>
            <th><sup style="color: red">(*)</sup>Re-New Password </th>
            <td>
                <input type="password" class="form-control" placeholder="Re-Password" name="re_password" value="<?php echo input_post('re_password'); ?>" />
                <?php show_error($error, 're_password'); ?>
            </td>
        </tr>
    </table></center>
</form>
<br>
<center>
<div class="controls">
    <a class="btn btn-primary btn-sm" role="button" onclick="$('#main-form').submit()" href="#">Save</a>
    <a class="btn btn-primary btn-sm" role="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Cancel</a></div>
</center>
</div>
 </div>
<?php include_once('widgets/footer.php'); ?>
