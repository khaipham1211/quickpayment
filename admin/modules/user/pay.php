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
if (is_submit('add_pay'))
{   

    $id_member = input_post('id_pay_member');
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
            'id_member'  	=> input_post('id_pay_member'),
            $id_member      = input_post('id_pay_member'),
            'balance' 		=> input_post('balance'),
            $balance        = (input_post('balance')+$user['balance']),
        );
        //lay ngay h cua VN
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        //Lấy danh sách dữ liệu từ form để inset
        $is_data = array(
            'date_time'         => date('Y-m-d H:i:s'),
            'id_pay_member'     => input_post('id_pay_member'),
            'id_collect_member' => input_post('id_collect_member'),
            'amountofmoney'     => input_post('balance'),
            'type_payment'      => '+',

        );

        // Thực hiện validate
        $error = db_userpay_validate($data);
        // Nếu validate không có lỗi
        if (!$error)
        {
            // Xóa key re-password ra khoi $data
            //unset($data['re-password']);
             
            // Nếu insert thành công thì thông báo
            // và chuyển hướng về trang danh sách user

            if (db_insert('payments', $is_data) && db_execute(db_create_sql("UPDATE  members SET id_member='$id_member', balance = '$balance' {where}", array('id_member' => $id_member)))){
                ?>
                <script language="javascript">
                    alert('Nạp thành công !');
                    window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'pay')); ?>';
                </script>
                <?php
                die();
            
        }
    }
}
?>
 
<?php include_once('widgets/header.php'); ?>

<div class="container">
<h1 align="center">Deposit</h1>
 

<center><form id="main-form" method="post" action="">
    <input type="hidden" name="request_name" value="add_pay"/>
    <table cellspacing="0" cellpadding="0" class="form">
        <tr>
            <td width="200px"><sup style="color: red">(*)</sup>ID Deposit Staff</td>
            <td>
                <?php
                //echo var_dump(db_get_list(db_create_sql("select * from members {where}", array('id_member' => $id_member))));
                    ?>
                    <input type="text" name="id_collect_member" class="form-control"  value="<?php echo get_current_id();  ?>" readonly/>
                <?php show_error($error, 'id_collect_member'); ?>

            </td>
        </tr>

            <td width="200px"><sup style="color: red">(*)</sup>ID Member</td>
            <td>
                <input type="text" name="id_pay_member" class="form-control" placeholder="ID Member" value="<?php echo input_post('id_member'); ?>" />
                <?php show_error($error, 'id_member'); ?>
            </td>
        </tr>
        <tr>
            <td><sup style="color: red">(*)</sup>Amount of Money </td>
            <td>
                <select name="balance" class="form-control">
                    <option value="">-- Chọn Mệnh Giá --</option>
                    <option value="10000"     <?php echo (input_post('balance') == '10000')  ? 'selected' : ''; ?>>10,000 VND</option>
                    <option value="20000"     <?php echo (input_post('balance') == '20000')  ? 'selected' : ''; ?>>20,000 VND</option>
                    <option value="50000"     <?php echo (input_post('balance') == '50000')  ? 'selected' : ''; ?>>50,000 VND</option>
                    <option value="100000"    <?php echo (input_post('balance') == '100000') ? 'selected' : ''; ?>>100,000 VND</option>
                    <option value="200000"    <?php echo (input_post('balance') == '200000') ? 'selected' : ''; ?>>200,000 VND</option>
                </select>
                <?php show_error($error, 'balance'); ?>
            </td>
        </tr>
    </table>
</form>
<br>
<div class="controls">
    <a class="btn btn-primary btn-sm" onclick="$('#main-form').submit()" href="#">OK</a>
    <a class="btn btn-primary btn-sm" href="<?php echo create_link(base_url('admin'), array('m' => 'common', 'a' => 'dashboard')); ?>">Back</a>
</div>
</center>
</div>
<br>
<br>
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