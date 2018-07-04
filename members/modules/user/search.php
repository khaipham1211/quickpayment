<?php if (!defined('IN_SITE')) die ('The request not found');
 
// Kiểm tra quyền, nếu không có quyền thì chuyển nó về trang logout
if (!is_admin()){
    redirect(base_url('admin'), array('m' => 'common', 'a' => 'logout'));
}
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
            echo "Yeu cau Nhap Du Lieu Vao O Tim Kiem";
            ?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Trở về</a>
            </div>
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
            echo "Yeu cau nhap du lieu vao o trong";
            ?>
            <div class="controls">
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Trở về</a>
            </div>
            <?php
        }
        else{
            $query = "select * from members where id_member like '%$is_search%' LIMIT {$paging['start']}, {$paging['limit']} ";
            // Thực thi câu truy vấn
            $users = mysqli_query($conn, $query);
            ?>
            <h1 align="center">Danh sách thành viên</h1>
            <div class="controls">
            <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'list')); ?>">Trở về</a>
                <form id="main-form" method="post" action="<?php echo base_url('admin/?m=user&a=search'); ?>">
                    <br>
                    Search: <input type="text" name="search" />
                        <td>
                            <input type="hidden" name="request_name" value="search1" class="button" onclick="$('#main-form').submit()"/>
                        </td>
                        <td>
                            <input type="submit" name="login-btn" value="Search"/>
                        </td>
                    </tr>
                </form>
            <br>
                <a class="button" href="<?php echo create_link(base_url('admin'), array('m' => 'user', 'a' => 'add')); ?>">Thêm </a>
            </div>
            <table cellspacing="0" cellpadding="0" class="form">
                <thead>
                    <tr>
                        <td>ID Member</td>
                        <td>PassWord</td>
                        <td>FullName</td>
                        <td>Sex</td>
                        <td>DayofBirth</td>
                        <td>Phone</td>
                        <td>ID_UG</td>
                        <td>ID_WP</td>
                        <td>Balance</td>
                        <?php if (is_admin()){ ?>
                        <td>Action</td>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php 
                //  CODE HIỂN THỊ NGƯỜI DÙNG 
                ?>
                <?php 
                if($total_records> 0 && $is_search != ""){
                    echo "Co $total_records ket qua tra ve voi tu khoa <b>'$is_search'</b>";
                    foreach ($users as $item) {
                      ?>
                <tr>
                        <td><?php echo $item['id_member']; ?></td>
                        <td><?php echo $item['password']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['sex']; ?></td>
                        <td><?php echo $item['dayofbirth']; ?></td>
                        <td><?php echo $item['phone']; ?></td>
                        <td><?php echo $item['ID_UG']; ?></td>
                        <td><?php echo $item['ID_WP']; ?></td>
                        <td><?php echo $item['balance']; ?></td>
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
                <?php }
                }else{
                        echo "khong tim thay ket qua";
                    }?>
            </tbody>
        </table>

<?php
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