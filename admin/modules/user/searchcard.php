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
                window.location = '<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'searchcard')); ?>';
            </script>
            <?php
        }
        else{
            echo "Enter data into searchbar";
            ?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'listcard')); ?>">Back</a>
            </div>
            <?php
             return $is_search = (is_search());
        }
    }
?>
 
<?php 
        if(is_admin()|| is_deposit()){
        // Lấy danh sách User
        $is_search = (is_search());
        //  CODE XỬ LÝ PHÂN TRANG 
        // Tìm tổng số records
        $sql = "SELECT count(id_member) as counter from cards where id_member like '%$is_search%'";
        $result = db_get_row($sql);
        $total_records = $result['counter'];
         
        // Lấy trang hiện tại
        $current_page = input_get('page');
         
        // Lấy limit
        $limit = 10;
         
        // Lấy link
        $link = create_link(base_url('admin'), array(
            'm' => 'user',
            'a' => 'searchcard',
            'page' => '{page}'
        ));
         
        // Thực hiện phân trang
        $paging = paging($link, $total_records, $current_page, $limit);
        // Nếu $search rỗng thì báo lỗi, tức là người dùng chưa nhập liệu mà đã nhấn submit.
        if (empty($is_search)) {
            echo "Enter data into searchbar";
            ?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'listcard')); ?>">Back</a>
            </div>
            <?php
        }
        else{
            $query = "select * from cards where id_member like '%$is_search%' LIMIT {$paging['start']}, {$paging['limit']} ";
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
    <a class="btn btn-primary btn-sm" role="button" style=" " href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'listcard')); ?>">Back</a>
</div>

<table  class="table table-hover">
    <thead>
        <tr  class="info">
            <th>ID Card</th>
            <th>ID Member</th>
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
            <td ><?php echo $item['id_card']; ?></td>
            <td ><?php echo $item['id_member']; ?></td>
            <?php if (is_admin()){ ?>
            <td>
                <form method="POST" class="form-delete" action="<?php echo create_link(base_url('admin/index.php'), array('m' => 'user', 'a' => 'delete')); ?>">
                    <input type="hidden" name="id_card" value="<?php echo $item['id_card']; ?>"/>
                    <input type="hidden" name="request_name" value="delete_card"/>
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
            if (!confirm('Bạn có chắc muốn xóa card này không?')){
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