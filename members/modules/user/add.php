<!-- <?php if (!defined('IN_SITE')) die ('The request not found'); ?>
 
<?php
// Kiểm tra quyền, nếu không có quyền thì chuyển nó về trang logout
if (!is_admin()){
    redirect(base_url('admin'), array('m' => 'common', 'a' => 'logout'));
}
?> -->
 
<?php 
// Biến chứa lỗi
$error = array();
require_once('database/user.php');
 
// Nếu người dùng submit form
if (is_submit('add_user'))
{
    // Lấy danh sách dữ liệu từ form
    $data = array(
        'id_member'  	=> input_post('id_member'),
        'password'  	=> md5(input_post('password')),
        're-password'   => input_post('re-password'),
        'name'  		=> input_post('name'),
        'sex'     		=> input_post('sex'),
        'dayofbirth'  	=> input_post('dayofbirth'),
        'phone'     	=> input_post('phone'),
        'ID_UG'     	=> input_post('ID_UG'),
        'ID_WP'     	=> input_post('ID_WP'),
        'balance'      	=> 0,
    );
     
    // require file xử lý database cho user
    require_once('database/user.php');
     
    // Thực hiện validate
    $error = db_user_validate($data);
     
    // Nếu validate không có lỗi
    if (!$error)
    {
        // Xóa key re-password ra khoi $data
        //unset($data['re-password']);
         
        // Nếu insert thành công thì thông báo
        // và chuyển hướng về trang danh sách user
        if (db_insert('members', $data)){
            ?>
            <script language="javascript">
                alert('Thêm người dùng thành công!');
                window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>';
            </script>
            <?php
            die();
        }
    }
}
?>
 
<?php include_once('widgets/header.php'); ?>
 
<h1 align="center">Thêm thành viên</h1>
 
<div class="controls">
    <a class="button" onclick="$('#main-form').submit()" href="#">Lưu</a>
    <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Trở về</a>
</div>
 
<form id="main-form" method="post" action="">
    <input type="hidden" name="request_name" value="add_user"/>
    <table cellspacing="0" cellpadding="0" class="form">
        <tr>
            <td width="200px">Tên đăng nhập</td>
            <td>
                <input type="text" name="id_member" value="<?php echo input_post('id_member'); ?>" />
                <?php show_error($error, 'id_member'); ?>
            </td>
        </tr>
       <tr>
            <td>Mật khẩu</td>
            <td>
                <input type="password" name="password" value="<?php echo input_post('password'); ?>" />
                <?php show_error($error, 'password'); ?>
            </td>
        </tr>
        <tr>
            <td>Nhập lại mật khẩu</td>
            <td>
                <input type="password" name="re-password" value="<?php echo input_post('re-password'); ?>" />
                <?php show_error($error, 're-password'); ?>
            </td>
        </tr>
        <tr>
            <td>Họ Tên</td>
            <td>
                <input type="text" name="name" value="<?php echo input_post('name'); ?>" class="long" />
                <?php show_error($error, 'name'); ?>
            </td>
        </tr>
        <tr>
            <td>Giới Tính </td>
            <td>
                <select name="sex">
                    <option value="">-- Chọn Giới Tính --</option>
                    <option value="nam" 	<?php echo (input_post('sex') == 'nam') ? 'selected' : ''; ?>>Nam</option>
                    <option value="nu" 		<?php echo (input_post('sex') == 'nu') ? 'selected' : ''; ?>>Nữ</option>
                    <option value="khac" 	<?php echo (input_post('sex') == 'khac') ? 'selected' : ''; ?>>Khác</option>
                </select>
                <?php show_error($error, 'sex'); ?>
            </td>
        </tr>
        <tr>
            <td>Ngày Sinh </td>
            <td>
                <input type="text" name="dayofbirth" value="<?php echo input_post('dayofbirth'); ?>" class="long" />
                <?php show_error($error, 'dayofbirth'); ?>
            </td>
        </tr>
        <tr>
            <td>Số Điện Thoại</td>
            <td>
                <input type="text" name="phone" value="<?php echo input_post('phone'); ?>" class="long" />
                <?php show_error($error, 'phone'); ?>
            </td>
        </tr>
        <tr>
            <td>User Group</td>
            <td>
                <select name="ID_UG">
                    <option value="">-- Chọn User Groups --</option>
                    <option value="2" <?php echo (input_post('ID_UG') == 2) ? 'selected' : ''; ?>>Admin</option>
                    <option value="3" <?php echo (input_post('ID_UG') == 3) ? 'selected' : ''; ?>>Deposit staff</option>
                    <option value="4" <?php echo (input_post('ID_UG') == 3) ? 'selected' : ''; ?>>service staff</option>
                    <option value="5" <?php echo (input_post('ID_UG') == 3) ? 'selected' : ''; ?>>Student / Lecturer</option>
                </select>
                <?php show_error($error, 'ID_UG'); ?>
            </td>
        </tr>
        <tr>
            <td>Word Plances</td>
            <td>
                <select name="ID_WP">
                    <option value="">-- Chọn Word Places --</option>
                    <option value="KH" 	<?php echo (input_post('ID_WP') == 'KH') 	? 'selected' : ''; ?>>Khoa KHTN</option>
                    <option value="KT" 	<?php echo (input_post('ID_WP') == 'KT') 	? 'selected' : ''; ?>>Khoa KT-QTKD</option>
                    <option value="MT" 	<?php echo (input_post('ID_WP') == 'MT') 	? 'selected' : ''; ?>>Khoa KHCT</option>
                    <option value="XH" 	<?php echo (input_post('ID_WP') == 'XH') 	? 'selected' : ''; ?>>Khoa KHXHNV</option>
                    <option value="DB" 	<?php echo (input_post('ID_WP') == 'DB') 	? 'selected' : ''; ?>>Khoa DBDT</option>
                    <option value="DI" 	<?php echo (input_post('ID_WP') == 'DI') 	? 'selected' : ''; ?>>Khoa CNTT&TT</option>
                    <option value="CN" 	<?php echo (input_post('ID_WP') == 'CN') 	? 'selected' : ''; ?>>Khoa CN</option>
                    <option value="TS" 	<?php echo (input_post('ID_WP') == 'TS') 	? 'selected' : ''; ?>>Khoa TS</option>
                    <option value="NN" 	<?php echo (input_post('ID_WP') == 'NN') 	? 'selected' : ''; ?>>Khoa NN&SHUD</option>
                    <option value="SH" 	<?php echo (input_post('ID_WP') == 'SH') 	? 'selected' : ''; ?>>Vien NC&PTCNSH</option>
                    <option value="MTN" <?php echo (input_post('ID_WP') == 'MTN') 	? 'selected' : ''; ?>>Khoa MT&TNTN</option>
                    <option value="HA"	<?php echo (input_post('ID_WP') == 'HA') 	? 'selected' : ''; ?>>Khoa PTNT</option>
                    <option value="HL" 	<?php echo (input_post('ID_WP') == 'HL') 	? 'selected' : ''; ?>>TTHL</option>
                    <option value="NDH" <?php echo (input_post('ID_WP') == 'NDH') 	? 'selected' : ''; ?>>Nha dieu hanh</option>
                    <option value="A1" 	<?php echo (input_post('ID_WP') == 'A1') 	? 'selected' : ''; ?>>Nha hoc A1</option>
                    <option value="A3" 	<?php echo (input_post('ID_WP') == 'A3') 	? 'selected' : ''; ?>>Nha hoc A3</option>
                    <option value="B1" 	<?php echo (input_post('ID_WP') == 'B1') 	? 'selected' : ''; ?>>Nha hoc B1</option>
                    <option value="C1" 	<?php echo (input_post('ID_WP') == 'C1') 	? 'selected' : ''; ?>>Nha hoc C1</option>
                    <option value="C2" 	<?php echo (input_post('ID_WP') == 'C2') 	? 'selected' : ''; ?>>Nha hoc C2</option>
                </select>
                <?php show_error($error, 'ID_WP'); ?>
            </td>
        </tr>
    </table>
</form>
 
<?php include_once('widgets/footer.php'); ?>
?>