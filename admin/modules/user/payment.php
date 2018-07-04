<?php if (!defined('IN_SITE')) die ('The request not found');
 
// Kiểm tra quyền, nếu không có quyền thì chuyển nó về trang logout
//if (!is_deposit()){
//    redirect(create_link(base_url('admin'), array('m' => 'common', 'a' => 'logout')));
//}
?>
 
<?php include_once('widgets/header.php'); ?>

<?php
    if(is_submit('search')){
        // Gán hàm addslashes để chống sql injection
        $search = addslashes($_POST['search']);
        set_search($search);
    if (!$search=='')
    {
        // Xóa key re-password ra khoi $data
        //unset($data['re-password']);
         
        // Nếu insert thành công thì thông báo
        // và chuyển hướng về trang danh sách user
        if(is_admin()){
            ?>
            <script language="javascript">
                window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'searchpay')); ?>';
            </script>
            <?php
        }
        if(is_deposit()){
            ?>
            <script language="javascript">
                window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'search')); ?>';
            </script>
            <?php
        }
    }
        else{
            echo "Enter data into searchbar!";
            ?>
            <div class="controls">
                <a class="btn btn-primary btn-sm" role="btn" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'payment')); ?>">Back</a>
            </div>
            <?php
            return $is_search = (is_search());

        }
    }
?>

 
<?php 
        //  CODE XỬ LÝ PHÂN TRANG
        $id = get_current_id(); 
        // Tìm tổng số records
        if(is_deposit()){
            $sql = "SELECT count(date_time) as counter from payments where id_collect_member ='$id'";
        }
        if(is_admin()){
            $sql = "SELECT count(date_time) as counter from payments";
        }
        $result = db_get_row($sql);
        $total_records = $result['counter'];
         
        // Lấy trang hiện tại
        $current_page = input_get('page');
         
        // Lấy limit
        $limit = 10;
         
        // Lấy link
        $link = create_link(base_url('admin'), array(
            'm' => 'user',
            'a' => 'payment',
            'page' => '{page}'
        ));
        // Thực hiện phân trang
        $paging = paging($link, $total_records, $current_page, $limit);
        if(is_deposit()){
            $query = "select * from payments where id_collect_member = '$id' ORDER BY date_time DESC LIMIT {$paging['start']}, {$paging['limit']} ";
        }
        if(is_admin()){
            $query = "select * from payments ORDER BY date_time DESC LIMIT {$paging['start']}, {$paging['limit']} ";
        }
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
        <h1 align="center">List of newest transactions</h1>
        <div class="controls">
            <form id="main-form" method="post" action="">
                <div class="col-xs-6 col-md-4">
                <?php if(is_admin()){?>
                 <input type="text" name="search" class="form-control" placeholder="ID Deposit staff" />
                 <?php }?>
                <?php if(is_deposit()){?>
                 <input type="text" name="search" class="form-control" placeholder="ID Member" />
                 <?php }?>
                </div>
                    <td>
                        <input type="hidden" name="request_name" value="search" class="button" onclick="$('#main-form').submit()"/>
                    </td>
                    <td>
                        <input type="submit" name="login-btn" value="Search" class="btn btn-default" />
                    </td>
                </tr>
            </form>
        <br>
            <a  href="<?php echo create_link(base_url('admin'), array('m' => 'common', 'a' => 'dashboard')); ?>" class="btn btn-primary btn-sm" role="button" >Back</a>
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
                <?php foreach ($users as $item) { ?>
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
            </tbody>
        </table>
<div class="pagination">
    <?php //  CODE HIỂN THỊ CÁC NÚT PHÂN TRANG 
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
</body>
</html>
 
<?php include_once('widgets/footer.php'); ?>