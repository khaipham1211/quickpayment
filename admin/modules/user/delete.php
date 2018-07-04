<?php
	if (!defined('IN_SITE')) die ('The request not found');
	 
	// Thiết lập font chữ UTF8 để khỏi bị lõi font
	header('Content-Type: text/html; charset=utf-8');
	 
	// Kiểm tra quyền, nếu không có quyền thì chuyển nó về trang logout
	if (!is_admin()){
	    redirect(create_link(base_url('admin'), array('m' => 'common', 'a' => 'logout')));
	}
	 
	// Nếu người dùng submit delete user
	if (is_submit('delete_user'))
	{
	    // Lấy id_member và ép kiểu
	    $id_member = input_post('id_member');
	    if ($id_member)
	    {
	    	//Lấy thông tin card
	    	$card = db_get_row(db_create_sql('SELECT * FROM cards {where}', array(
	            'id_member' => $id_member)));
	        // Lấy thông tin người dùng
	        $user = db_get_row(db_create_sql('SELECT * FROM members {where}', array(
	            'id_member' => $id_member
	        )));
	        
	        $id_card = $card['id_card'];
	        $data = array('id_card'=>$id_card);
	        // Kiểm tra có phải xóa admin hay không
	        if ($user['ID_UG'] == '1'){
	            ?>
	            <script language="javascript">
	                alert('Bạn không thể xóa Supper Admin được!');
	                window.location = '<?php echo input_post('redirect'); ?>';
	            </script>
	            <?php
	        }
	        else
	        {
	            $sql = db_create_sql('DELETE FROM members {where}', array(
	                'id_member' => $id_member
	            ));
	 
	            if (db_execute($sql)&&(db_insert('cards',$data))){
	                ?>
	                <script language="javascript">
	                    alert('Xóa thành công!');
	                    window.location = '<?php echo input_post('redirect'); ?>';
	                </script>
	                <?php
	            }
	            else{
	                ?>
	                <script language="javascript">
	                    alert('Xóa thất bại!');
	                    window.location = '<?php echo input_post('redirect'); ?>';
	                </script>
	                <?php
	            }
	        }
	    }
	}
	else{
	    // Nếu không phải submit delete user thì chuyển về trang chủ
	    redirect(base_url('admin'));
	}
?>