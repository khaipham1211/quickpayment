<?php if (!defined('IN_SITE')) die ('The request not found');
 
	function db_user_get_by_id_member($id_member){
	    $id_member = addslashes($id_member);
	    $sql = "SELECT * FROM members where id_member = '$id_member'";
	    return db_get_row($sql);
	}
	//Ham random password
  	function rand_string( $length=6 ) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
	    $size = strlen( $chars );
	    $str="";
	      for( $i = 0; $i < $length; $i++ ) {
	        $str .= $chars[ rand( 0, $size - 1 ) ];
	      }
	return $str;
  	}

// Hàm validate dữ liệu bảng User
function db_user_validate($data){
    // Biến chứa lỗi
    $error = array();
     
    /* VALIDATE CĂN BẢN */
    // id_member
    if (isset($data['id_member']) && $data['id_member'] == ''){
        $error['id_member'] = 'Bạn chưa nhập tên đăng nhập';
    }

    //Full Name
    if (isset($data['name']) && $data['name'] == ''){
        $error['name'] = 'Bạn chưa nhập Họ Tên';
    }
    //Sex
    if (isset($data['sex']) && $data['sex'] == ''){
        $error['sex'] = 'Bạn chưa nhập giới tính';
    }

    //Day Of Birth
    if (isset($data['dayofbirth']) && $data['dayofbirth'] == ''){
        $error['dayofbirth'] = 'Bạn chưa nhập ngày tháng năm sinh';
    }

    //Phone
    if (isset($data['phone']) && $data['phone'] == ''){
        $error['phone'] = 'Bạn chưa nhập Số Điện Thoại';
    }
     
    // Email
    if (isset($data['email']) && $data['email'] == ''){
        $error['email'] = 'Bạn chưa nhập email';
    }
    if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false){
        $error['email'] = 'Email không hợp lệ';
    }
     
    /* Password
    if (isset($data['password']) && $data['password'] == ''){
        $error['password'] = 'Bạn chưa nhập mật khẩu';
    }
     
    // Re-password
    if (isset($data['password']) && isset($data['re-password']) && $data['password'] != $data['re-password']){
        $error['re-password'] = 'Mật khẩu nhập lại không đúng';
    }*/
     
    // Level
    if (isset($data['ID_UG']) && !in_array($data['ID_UG'], array('1', '2','3','4','5'))){
        $error['ID_UG'] = 'User Group bạn chọn không tồn tại';
    }

    //WP
     if (isset($data['ID_WP']) && !in_array($data['ID_WP'], array('KH' ,'KT' ,'MT' ,'XH' ,'DB' ,'DI' ,'CN' ,'TS' ,'NN' ,'SH' ,'MTN' ,'HA' ,'HL' ,'NDH','A1' ,'A3' ,'B1' ,'C1' ,'C2'))){
        $error['ID_WP'] = 'Word Places bạn chọn không tồn tại';
    }
     
    /* VALIDATE LIÊN QUAN CSDL */
    // Chúng ta nên kiểm tra các thao tác trước có bị lỗi không, nếu không bị lỗi thì mới
    // tiếp tục kiểm tra bằng truy vấn CSDL
    // id_member
    if (!($error) && isset($data['id_member']) && $data['id_member']){
        $sql = "SELECT count(id_member) as counter FROM members WHERE id_member='".addslashes($data['id_member'])."'";
        $row = db_get_row($sql);
        if ($row['counter'] > 0){
            $error['id_member'] = 'Tên đăng nhập này đã tồn tại';
        }
    }
     
    /* Email
    if (!($error) && isset($data['email']) && $data['email']){
        $sql = "SELECT count(id) as counter FROM tb_user WHERE email='".addslashes($data['email'])."'";
        $row = db_get_row($sql);
        if ($row['counter'] > 0){
            $error['email'] = 'Email này đã tồn tại';
        }
    }*/
     
    return $error;

}
function db_updateuser_validate($data){
    // Biến chứa lỗi
    $error = array();
     
    /* VALIDATE CĂN BẢN */
    // id_member
    if (isset($data['id_member']) && $data['id_member'] == ''){
        $error['id_member'] = 'Bạn chưa nhập tên đăng nhập';
    }

    //Full Name
    if (isset($data['name']) && $data['name'] == ''){
        $error['name'] = 'Bạn chưa nhập Họ Tên';
    }
    //Sex
    if (isset($data['sex']) && $data['sex'] == ''){
        $error['sex'] = 'Bạn chưa nhập giới tính';
    }

    //Day Of Birth
    if (isset($data['dayofbirth']) && $data['dayofbirth'] == ''){
        $error['dayofbirth'] = 'Bạn chưa nhập ngày tháng năm sinh';
    }

    //Phone
    if (isset($data['phone']) && $data['phone'] == ''){
        $error['phone'] = 'Bạn chưa nhập Số Điện Thoại';
    }
     
    // Email
    if (isset($data['email']) && $data['email'] == ''){
        $error['email'] = 'Bạn chưa nhập email';
    }
    if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false){
        $error['email'] = 'Email không hợp lệ';
    }
     
    /* Password
    if (isset($data['password']) && $data['password'] == ''){
        $error['password'] = 'Bạn chưa nhập mật khẩu';
    }
     
    // Re-password
    if (isset($data['password']) && isset($data['re-password']) && $data['password'] != $data['re-password']){
        $error['re-password'] = 'Mật khẩu nhập lại không đúng';
    }*/
     
    // Level
    if (isset($data['ID_UG']) && !in_array($data['ID_UG'], array('1', '2','3','4','5'))){
        $error['ID_UG'] = 'User Group bạn chọn không tồn tại';
    }

    //WP
     if (isset($data['ID_WP']) && !in_array($data['ID_WP'], array('KH' ,'KT' ,'MT' ,'XH' ,'DB' ,'DI' ,'CN' ,'TS' ,'NN' ,'SH' ,'MTN' ,'HA' ,'HL' ,'NDH','A1' ,'A3' ,'B1' ,'C1' ,'C2'))){
        $error['ID_WP'] = 'Word Places bạn chọn không tồn tại';
    }
     
    /* VALIDATE LIÊN QUAN CSDL */
    // Chúng ta nên kiểm tra các thao tác trước có bị lỗi không, nếu không bị lỗi thì mới
    // tiếp tục kiểm tra bằng truy vấn CSDL
    // id_member
    /*if (!($error) && isset($data['id_member']) && $data['id_member']){
        $sql = "SELECT count(id_member) as counter FROM members WHERE id_member='".addslashes($data['id_member'])."'";
        $row = db_get_row($sql);
        if ($row['counter'] > 0){
            $error['id_member'] = 'Tên đăng nhập này đã tồn tại';
        }
    }
     
    /* Email
    if (!($error) && isset($data['email']) && $data['email']){
        $sql = "SELECT count(id) as counter FROM tb_user WHERE email='".addslashes($data['email'])."'";
        $row = db_get_row($sql);
        if ($row['counter'] > 0){
            $error['email'] = 'Email này đã tồn tại';
        }
    }*/
     
    return $error;

}

function db_userpay_validate($data){
    // Biến chứa lỗi
    $error = array();
     
    /* VALIDATE CĂN BẢN */
    // id_member
    if (isset($data['id_member']) && $data['id_member'] == ''){
        $error['id_member'] = 'Bạn chưa nhập tên đăng nhập';
    }
    if (isset($data['balance']) && $data['balance'] == ''){
        $error['balance'] = 'Bạn chưa nhập số tiền cần nạp';
    }  
    /* VALIDATE LIÊN QUAN CSDL */
    // Chúng ta nên kiểm tra các thao tác trước có bị lỗi không, nếu không bị lỗi thì mới
    // tiếp tục kiểm tra bằng truy vấn CSDL
    // id_member
    if (!($error) && isset($data['id_member']) && $data['id_member']){
        $sql = "SELECT count(id_member) as counter FROM members WHERE id_member='".addslashes($data['id_member'])."'";
        $row = db_get_row($sql);
        if ($row['counter'] == 0){
            $error['id_member'] = 'Tài khoản cần nạp không tồn tại';
        }
    }
    return $error;

}

function db_updatepass_validate($data){
    $error = array();
    // Passwordold
    
    if (isset($data['passwordold']) && $data['passwordold'] == ''){
        $error['passwordold'] = 'Bạn chưa nhập mật khẩu cũ';
    }

    if (isset($data['password']) && $data['password'] == ''){
        $error['password'] = 'Bạn chưa nhập mật khẩu mới';
    }
    
    // Re-password
    if (isset($data['password']) && isset($data['re_password']) && $data['password'] != $data['re_password']){
        $error['re_password'] = 'Mật khẩu nhập lại không đúng';
    }
    if(!$error){
    $user = db_user_get_by_id_member($data['id_member']);

    if ($user['password'] != md5($data['passwordold'])) {
            $error['passwordold'] = 'Mật khẩu cũ bạn nhập không đúng';
        }
    }
    return $error;
}
