<?php if (!defined('IN_SITE')) die ('The request not found'); ?>
 
<?php
// Kiểm tra quyền, nếu không có quyền thì chuyển nó về trang logout
if (!is_deposit()){
    redirect(create_link(base_url('admin'), array('m' => 'common', 'a' => 'logout')));
}
?>
<?php 
// Biến chứa lỗi
$error = array();
// require file xử lý database cho user
require_once('database/user.php');

// Nếu người dùng submit form
if (is_submit('add_card'))
{   

    $id_member = input_post('id_member');
    //$error = db_idmember_validate($id_member);
        if (!$error)
        {
            // Lấy thông tin người dùng
            $user = db_get_row(db_create_sql('SELECT * FROM members {where}', array(
                'id_member' => $id_member
            )));
        }
        error_reporting(0);
        // Lấy danh sách dữ liệu từ form để update
        $data = array(
            'id_card'  	    => input_post('id_card'),
            $id_card        = input_post('id_card'),
            'id_member' 	=> input_post('id_member'),
            $id_member      = input_post('id_member'),
        );
        var_dump($data);
        // Thực hiện validate
        //$error = db_userpay_validate($data);
        // Nếu validate không có lỗi
        if (!$error)
        {
            if (db_execute(db_create_sql("UPDATE cards SET id_card = '$id_card', id_member = '$id_member' {where}", array('id_card' => $id_card)))){
                ?>
                <script language="javascript">
                    alert('Nạp thành công !');
                    window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'addcard')); ?>';
                </script>
                <?php
                die();
            
        }
    }
}
?>
 
<?php include_once('widgets/header.php'); ?>

<div class="container">
<h1 align="center">Add Card</h1>
 

<center><form id="main-form" method="post" action="">
    <input type="hidden" name="request_name" value="add_card"/>
    <table cellspacing="0" cellpadding="0" class="form">
        <tr>
            <td width="200px"><sup style="color: red">(*)</sup>ID Card</td>
            <td>
                <?php
                //echo var_dump(db_get_list(db_create_sql("select * from members {where}", array('id_member' => $id_member))));
                    ?>
                    <input type="text" name="id_card" class="form-control"  placeholder="ID Card" value="<?php echo input_post('id_card');  ?>"/>
                <?php show_error($error, 'id_card'); ?>

            </td>
        </tr>

            <td width="200px"><sup style="color: red">(*)</sup>ID Member</td>
            <td>
                <input type="text" name="id_member" class="form-control" placeholder="ID Member" value="<?php echo input_post('id_member'); ?>" />
                <?php show_error($error, 'id_member'); ?>
            </td>
        </tr>
    </table>
</form>
<br>
<div class="controls">
    <a class="btn btn-primary btn-sm" onclick="$('#main-form').submit()" href="#">Add</a>
    <a class="btn btn-primary btn-sm" href="<?php echo create_link(base_url('admin'), array('m' => 'common', 'a' => 'dashboard')); ?>">Back</a>
</div>
</center>
<br>
</div>
<br><br>
<br>
<br>

<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php include_once('widgets/footer.php'); ?>