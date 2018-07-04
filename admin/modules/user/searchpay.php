<?php if (!defined('IN_SITE')) die ('The request not found');
 
// Kiểm tra quyền, nếu không có quyền thì chuyển nó về trang logout
?>
 
<?php include_once('widgets/header.php'); ?>

<?php
    if(is_submit('search1')){
        // Gán hàm addslashes để chống sql injection
        $search = addslashes($_POST['search']);
        set_search($search);
    if (!$search=='')
    {
        // Xóa key re-password ra khoi $data
        //unset($data['re-password']);
         
        // Nếu insert thành công thì thông báo
        // và chuyển hướng về trang danh sách user
            ?>
            <script language="javascript">
                window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'searchpay')); ?>';
            </script>
            <?php
        }
        else{
            echo "Enter data!";
            ?>
            <?php if(is_admin()){?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Back</a>
            </div>
            <?php }?>
            <?php if(is_deposit()){?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'payment')); ?>">Back</a>
            </div>
            <?php }?>
            <?php
             return $is_search = (is_search());
        }
    }
?>
 
<?php 
        // Lấy danh sách User
        $is_search = (is_search());
        //  CODE XỬ LÝ PHÂN TRANG 
        // Tìm tổng số records
        $sql = "SELECT count(date_time) as counter from payments where id_collect_member = '$is_search'";
        $result = db_get_row($sql);
        $total_records = $result['counter'];
         
        // Lấy trang hiện tại
        $current_page = input_get('page');
         
        // Lấy limit
        $limit = 10;
         
        // Lấy link
        $link = create_link(base_url('admin'), array(
            'm' => 'user',
            'a' => 'searchpay',
            'page' => '{page}'
        ));
         
        // Thực hiện phân trang
        $paging = paging($link, $total_records, $current_page, $limit);
        // Nếu $search rỗng thì báo lỗi, tức là người dùng chưa nhập liệu mà đã nhấn submit.
        if (empty($is_search)) {
            echo "Enter data!";
            ?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Back</a>
            </div>
            <?php
        }
        else{
        $query = "select * from payments where  id_collect_member = '$is_search' ORDER BY date_time DESC LIMIT {$paging['start']}, {$paging['limit']} ";
            // Thực thi câu truy vấn
            $users = mysqli_query($conn, $query);
            ?>
       <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>List user</title>
    </head>

   <body>

<div class="container">
<center><h1>List of newest transactions</h1></center>
        <div class="controls">
            <form id="main-form" method="post" action="">
                <div class="col-xs-6 col-md-4">
                 <input type="text" name="search" class="form-control" placeholder="ID Deposit" />
                 
                </div>
                
                    <td>

                        <input type="hidden" name="request_name" value="search1" class="button" onclick="$('#main-form').submit()"/>
                    </td>
                    <td>
                        <input type="submit" name="login-btn" value="Search" class="btn btn-default"/>
                    </td>
                </tr>
            </form>
        <br>
            <div class="controls">
    <a class="btn btn-primary btn-sm" role="button" style=" " href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'payment')); ?>">Back</a>
</div>

        <table cellspacing="0" cellpadding="0" class="table table-hover">
            <thead>
                <tr class="info">
                    <th>ID Deposit</th>
                    <th>ID Member</th>
                    <th>Amount Of Money</th>
                    <th>Type Payment</th>
                    <th>Date Time</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                //  CODE HIỂN THỊ NGƯỜI DÙNG 
                ?>
                <?php 
                if($total_records> 0 && $is_search != ""){
                    echo "$total_records results about <b>'$is_search'</b>" ;
                    $query1 = "select sum(amountofmoney) as tong from payments where  id_collect_member = '$is_search'";
                    // Thực thi câu truy vấn
                    $sum = mysqli_query($conn, $query1);
                    $row = mysqli_fetch_array($sum);
                    
                    echo "Tổng: " + $row['tong'];
                    foreach ($users as $item) {
                ?>
                <tr class="danger">
                    <td><?php echo $item['id_collect_member']; ?></td>
                    <td><?php echo $item['id_pay_member']; ?></td>
                    <td><?php echo $item['amountofmoney']; ?></td>
                    <td><?php echo $item['type_payment']; ?></td>
                    <?php 
                    $datetime = date('d-m-Y H:i:s',strtotime($item['date_time']));?>
                    <td><?php echo $datetime; ?></td>
                </tr>
                <?php } ?>
           
<?php
        }else{
            echo "No result!";
        }
    }

?>
 </tbody>
        </table>
        <div class="pagination pagination-lg">
            <?php //  CODE HIỂN THỊ CÁC NÚT PHÂNs TRANG 
            echo $paging['html'];
            ?>
        </div>
 
<script language="javascript">
    $(document).ready(function(){
        // Nếu người dùng click vào nút delete
        // Thì submit form
        $('.btn-submit').click(function(){
            $(this).parent().submit();
            return false;
        });
 
        // Nếu sự kiện submit form xảy ra thì hỏi người dùng có chắc không?
        $('.form-delete').submit(function(){
            if (!confirm('Bạn có chắc muốn xóa thành viên này không?')){
                return false;
            }
             
            // Nếu người dùng chắc chắn muốn xóa thì ta thêm vào trong form delete
            // một input hidden có giá trị là URL hiện tại, mục đích là giúp ở 
            // trang delete sẽ lấy url này để chuyển hướng trở lại sau khi xóa xong
            $(this).append('<input type="hidden" name="redirect" value="'+window.location.href+'"/>');
             
            // Thực hiện xóa
            return true;
        });
    });
</script>
 
 </div>
</div>
<?php include_once('widgets/footer.php'); ?>