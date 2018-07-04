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
                window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'search')); ?>';
            </script>
            <?php
        }
        else{
            echo "Enter data into searchbar";
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
        if(is_admin()){
        // Lấy danh sách User
        $is_search = (is_search());
        //  CODE XỬ LÝ PHÂN TRANG 
        // Tìm tổng số records
        $sql = "SELECT count(id_member) as counter from members where id_member like '%$is_search%'";
        $result = db_get_row($sql);
        $total_records = $result['counter'];
         
        // Lấy trang hiện tại
        $current_page = input_get('page');
         
        // Lấy limit
        $limit = 10;
         
        // Lấy link
        $link = create_link(base_url('admin'), array(
            'm' => 'user',
            'a' => 'search',
            'page' => '{page}'
        ));
         
        // Thực hiện phân trang
        $paging = paging($link, $total_records, $current_page, $limit);
        // Nếu $search rỗng thì báo lỗi, tức là người dùng chưa nhập liệu mà đã nhấn submit.
        if (empty($is_search)) {
            echo "Enter data into searchbar";
            ?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Back</a>
            </div>
            <?php
        }
        else{
            $query = "select * from members where id_member like '%$is_search%' LIMIT {$paging['start']}, {$paging['limit']} ";
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
<center><h1>List of members</h1></center>
        <div class="controls">
            <form id="main-form" method="post" action="">
                <div class="col-xs-6 col-md-4">
                 <input type="text" name="search" class="form-control" placeholder="ID member" />
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
    <a class="btn btn-primary btn-sm" role="button" style=" " href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Back</a>
</div>

<table  class="table table-hover">
    <thead>
        <tr  class="info">
            <th>ID Member</th>
            <th>PassWord</th>
            <th>FullName</th>
            <th>Sex</th>
            <th>DayofBirth</th>
            <th>Phone</th>
            <th>ID_UG</th>
            <th>ID_WP</th>
            <th>Balance</th>
            <?php if (is_admin()){ ?>
            <th>Action</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        //  CODE HIỂN THỊ NGƯỜI DÙNG 
        ?>
        <?php 
            if($total_records> 0 && $is_search != ""){
            echo " $total_records results about <b>'$is_search'</b>";
            foreach ($users as $item) {
        ?>
        <tr class="danger">
            <td ><?php echo $item['id_member']; ?></td>
            <td ><?php echo $item['password']; ?></td>
            <td ><?php echo $item['name']; ?></td>
            <td ><?php echo $item['sex']; ?></td>
            <?php 
                $datetime = date('d-m-Y',strtotime($item['dayofbirth']));?>
            <td><?php echo $datetime; ?></td>
            <td ><?php echo $item['phone']; ?></td>
            <td ><?php echo $item['ID_UG']; ?></td>
            <td ><?php echo $item['ID_WP']; ?></td>
            <td ><?php echo $item['balance']; ?></td>
            <?php if (is_admin()){ ?>
            <td>
                <form method="POST" class="form-delete" action="<?php echo create_link(base_url('admin/index.php'), array('m' => 'user', 'a' => 'delete')); ?>">
                    <a href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'edit', 'id_member' => $item['id_member'])); ?>">Edit</a>
                    <input type="hidden" name="id_member" value="<?php echo $item['id_member']; ?>"/>
                    <input type="hidden" name="request_name" value="delete_user"/>
                    <a href="#" class="btn-submit">Delete</a>
                </form>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>

</table>

<?php
        }
        else{
        echo"No result";
    }
    }
}
?>
<?php   if(is_deposit()){
        $is_search = (is_search());
        //  CODE XỬ LÝ PHÂN TRANG
        $id = get_current_id(); 
        // Tìm tổng số records
        $sql = "SELECT count(date_time) as counter from payments where id_collect_member ='$id' AND id_pay_member = '$is_search' ";
        $result = db_get_row($sql);
        $total_records = $result['counter'];
         
        // Lấy trang hiện tại
        $current_page = input_get('page');
         
        // Lấy limit
        $limit = 10;
         
        // Lấy link
        $link = create_link(base_url('admin'), array(
            'm' => 'user',
            'a' => 'search',
            'page' => '{page}'
        ));
        // Thực hiện phân trang
        $paging = paging($link, $total_records, $current_page, $limit);

        $query = "select * from payments where id_collect_member = '$id' AND id_pay_member = '$is_search' ORDER BY date_time DESC LIMIT {$paging['start']}, {$paging['limit']} ";
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
                 <input type="text" name="search" class="form-control" placeholder="Id Members" />
                </div>
                
                    <td>
                        <input type="hidden" name="request_name" value="search1" class="button" onclick="$('#main-form').submit()"/>
                    </td>
                    <td>
                        <input type="submit" name="login-btn" value="Search" class="btn btn-default" />
                    </td>
                </tr>
            </form>
        <br>
        <div class="controls">
<a class="btn btn-primary btn-sm" role="button" style=" " href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'payment')); ?>">Trở Về</a>
       </div>
        <table cellspacing="0" cellpadding="0" class="table table-hover">
            <thead>
                <tr class="info">
                    <th>ID Deposit</th>
                    <th>ID Member</th>
                    <th>Amount Of Money</th>
                    <th>Type Payment</th>
                    <th>Date Time</th>
                    <?php if (is_admin()){ ?>
                    <th>Action</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                //  CODE HIỂN THỊ NGƯỜI DÙNG 
                ?>
                <?php 
                if($total_records> 0 && $is_search != ""){
                    echo "$total_records results about <b>'$is_search'</b>";
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
                    <?php if (is_admin()){ ?>
                    <td>
                        <form method="POST" class="form-delete" action="<?php echo create_link(base_url('admin/index.php'), array('m' => 'user', 'a' => 'delete')); ?>">
                            <a href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'edit', 'id_member' => $item['id_member'])); ?>">Edit</a>
                            <input type="hidden" name="id_member" value="<?php echo $item['id_member']; ?>"/>
                            <input type="hidden" name="request_name" value="delete_user"/>
                            <a href="#" class="btn-submit">Delete</a>
                        </form>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
<?php
        }else{
            echo "No result <b>'$is_search'</b>";
        }
    }

?>
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
 
<?php include_once('widgets/footer.php'); ?>