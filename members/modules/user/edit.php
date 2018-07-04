<?php if (!defined('IN_SITE')) die ('The request not found'); ?>
 
<?php
// Kiểm tra quyền, nếu không có quyền thì chuyển nó về trang logout
if (!is_admin()){
    redirect(base_url('admin'), array('m' => 'common', 'a' => 'logout'));
}
?>

<?php
    $UG = db_get_list('select * from usergroups');
?>

<?php
        $id_member = input_get('id_member');
        if ($id_member)
        {
            // Lấy thông tin người dùng
            $user = db_get_row(db_create_sql('SELECT * FROM members {where}', array(
                'id_member' => $id_member
            )));
        }
?>
<?php 
// Biến chứa lỗi
$error = array();

// require file xử lý database cho user
require_once('database/user.php');

// Nếu người dùng submit form
if (is_submit('update_user'))
{
    // Lấy danh sách dữ liệu từ form
    $data = array(
        $id_member  	= input_post('id_member'),
        $name 		    = input_post('name'),
        $sex     		= input_post('sex'),
        $dayofbirth  	= input_post('dayofbirth'),
        $phone      	= input_post('phone'),
        $ID_UG     	    = input_post('ID_UG'),
        $ID_WP      	= input_post('ID_WP'),
    );
    // Thực hiện validate
    $error = db_updateuser_validate($data);
     
    // Nếu validate không có lỗi
    if (!$error)
    {
        // Xóa key re-password ra khoi $data
        //unset($data['re-password']);
         
        // Nếu insert thành công thì thông báo
        // và chuyển hướng về trang danh sách user

        if (db_execute(db_create_sql("UPDATE  members SET id_member='$id_member', name='$name',sex='$sex',dayofbirth='$dayofbirth',phone='$phone', ID_UG = '$ID_UG', ID_WP='$ID_WP' {where}", array('id_member' => $id_member)))){
            ?>
            <script language="javascript">
                alert('Sửa người dùng thành công!');
                window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>';
            </script>
            <?php
            die();
        }
    }
}
?>
 
<?php include_once('widgets/header.php'); ?>

<h1 align="center">Chỉnh Sửa thành viên</h1>
 
<div class="controls">
    <a class="button" onclick="$('#main-form').submit()" href="#">Lưu</a>
    <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Trở về</a>
</div>
 
<form id="main-form" method="post" action="">
    <input type="hidden" name="request_name" value="update_user"/>
    <table cellspacing="0" cellpadding="0" class="form">
        <tr>
            <td width="200px">Tên đăng nhập</td>
            <td>
                <?php
                //echo var_dump(db_get_list(db_create_sql("select * from members {where}", array('id_member' => $id_member))));
                    ?>
                    <input name = "id_member" type = "text" value="<?php echo $user['id_member'] ?>" readonly>
                <?php show_error($error, 'id_member'); ?>

            </td>
        </tr>
        <!--<tr>
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
        </tr>-->
        <tr>
            <td width="200px">Họ Tên</td>
            <td>
                <input name = "name" type = "text" value="<?php echo $user['name'] ?>">
                <?php show_error($error, 'name'); ?>
            </td>
        </tr>
        <tr>
            <td>Giới Tính </td>
            <td>
                <select name="sex">
                    <option value="">-- Chọn Giới Tính --</option>
                    <option value ="nam"<?php   if($user['sex'] == "nam")   echo 'selected="selected"'?>>Nam</option>
                    <option value ="nu"<?php    if($user['sex'] == "nu")    echo 'selected="selected"'?>>Nữ</option>
                    <option value ="khac"<?php  if($user['sex'] == "khac")  echo 'selected="selected"'?>>Khác</option>
                </select>
                <?php show_error($error, 'sex'); ?>
            </td>
        </tr>
        <tr>
            <td>Ngày Sinh </td>
            <td>
                <input name = "dayofbirth" type = "text" value="<?php echo $user['dayofbirth'] ?>">
                <?php show_error($error, 'dayofbirth'); ?>
            </td>
        </tr>
        <tr>
            <td>Số Điện Thoại</td>
            <td>
                <input name = "phone" type = "text" value="<?php echo $user['phone'] ?>">
                <?php show_error($error, 'phone'); ?>
            </td>
        </tr>
        <tr>
            <td>Word Group</td>
            <td>
                <select name="ID_UG">
                    <option value="">-- Chọn Word Groups --</option>
                    <option value ="2"<?php if($user['ID_UG']== 2) echo 'selected="selected"'?>>Admin</option>
                    <option value ="3"<?php if($user['ID_UG']== 3) echo 'selected="selected"'?>>Deposit staff</option>
                    <option value ="4"<?php if($user['ID_UG']== 4) echo 'selected="selected"'?>>service staff</option>
                    <option value ="5"<?php if($user['ID_UG']== 5) echo 'selected="selected"'?>>Student / Lecturer</option>
                </select>
                <?php show_error($error, 'ID_UG'); ?>
            </td>
        </tr>
        <tr>
            <td>Word Plances</td>
            <td>
                <select name="ID_WP">
                    <option value="">-- Chọn Word Places --</option>
                    <option value="KH" 	<?php if($user['ID_WP']== 'KH')     echo 'selected="selected"' ?>>Khoa KHTN</option>
                    <option value="KT" 	<?php if($user['ID_WP']== 'KT')     echo 'selected="selected"' ?>>Khoa KT-QTKD</option>
                    <option value="MT" 	<?php if($user['ID_WP']== 'MT')     echo 'selected="selected"' ?>>Khoa KHCT</option>
                    <option value="XH" 	<?php if($user['ID_WP']== 'XH')     echo 'selected="selected"' ?>>Khoa KHXHNV</option>
                    <option value="DB" 	<?php if($user['ID_WP']== 'DB')     echo 'selected="selected"' ?>>Khoa DBDT</option>
                    <option value="DI" 	<?php if($user['ID_WP']== 'DI')     echo 'selected="selected"' ?>>Khoa CNTT&TT</option>
                    <option value="CN" 	<?php if($user['ID_WP']== 'CN')     echo 'selected="selected"' ?>>Khoa CN</option>
                    <option value="TS" 	<?php if($user['ID_WP']== 'TS')     echo 'selected="selected"' ?>>Khoa TS</option>
                    <option value="NN" 	<?php if($user['ID_WP']== 'NN')     echo 'selected="selected"' ?>>Khoa NN&SHUD</option>
                    <option value="SH" 	<?php if($user['ID_WP']== 'SH')     echo 'selected="selected"' ?>>Vien NC&PTCNSH</option>
                    <option value="MTN" <?php if($user['ID_WP']== 'MTN')    echo 'selected="selected"' ?>>Khoa MT&TNTN</option>
                    <option value="HA"	<?php if($user['ID_WP']== 'HA')     echo 'selected="selected"' ?>>Khoa PTNT</option>
                    <option value="HL" 	<?php if($user['ID_WP']== 'HL')     echo 'selected="selected"' ?>>TTHL</option>
                    <option value="NDH" <?php if($user['ID_WP']== 'NDH')    echo 'selected="selected"' ?>>Nha Dieu Hanh</option>
                    <option value="A1" 	<?php if($user['ID_WP']== 'A1')     echo 'selected="selected"' ?>>Nha hoc A1</option>
                    <option value="A3" 	<?php if($user['ID_WP']== 'A3')     echo 'selected="selected"' ?>>Nha hoc A3</option>
                    <option value="B1" 	<?php if($user['ID_WP']== 'B1')     echo 'selected="selected"' ?>>Nha hoc B1</option>
                    <option value="C1" 	<?php if($user['ID_WP']== 'C1')     echo 'selected="selected"' ?>>Nha hoc C1</option>
                    <option value="C2" 	<?php if($user['ID_WP']== 'C2')     echo 'selected="selected"' ?>>Nha hoc C2</option>
                </select>
                <?php show_error($error, 'ID_WP'); ?>
            </td>
        </tr>
    </table>
</form>
<?php include_once('widgets/footer.php'); ?>