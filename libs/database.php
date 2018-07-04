<?php
	//if (!defined('IN_SITE')) die ('The request not found');
	//biến lưu trữ kết nối
	$conn = null;

	// hàm kết nối
	function db_connect(){
		global $conn;
		if (!$conn) {
			$conn = mysqli_connect('localhost','root','','quickpayment2')
					or die ('Không thể kết nối CSDL');
			mysqli_set_charset($conn, 'UTF-8');
		}
	}

	//Hàm ngắt kết nối
	function db_close(){
		global $conn;
		if ($conn) {
			mysqli_close($conn);
		}
	}
	// Hàm lấy danh sách, kq danh sách các record trong một mảng
	function db_get_list($sql){
		db_connect();
		global $conn;
		$data 		= array();
		$result		= mysqli_query($conn, $sql);
		while($row = mysqli_fetch_assoc($result)){
			$data[]	= $row;
		}
		return $data;
		//var_dump(db_get_list('select * from members'));

	}
	// hàm lấy chi tiết, dùng để select theo ID vì nó trả về 1 record
	function db_get_row($sql){
		db_connect();
		global $conn;
		$result	= mysqli_query($conn, $sql);
		$row 	= array();
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
		}
		return $row;
	}
	// hàm thực thi câu truy vấn insert, update, delete
	function db_execute($sql){
		db_connect();
		global $conn;
		return mysqli_query($conn, $sql);
	}

	// Hàm tạo câu truy vấn có thêm điều kiện Where
	function db_create_sql($sql, $filter = array())
	{    
	    // Chuỗi where
	    $where = '';
	     
	    // Lặp qua biến $filter và bổ sung vào $where
	    foreach ($filter as $field => $value){
	        if ($value != ''){
	            $value = addslashes($value);
	            $where .= "AND $field = '$value', ";
	        }
	    }
	     
	    // Remove chữ AND ở đầu
	    $where = trim($where, 'AND');
	    // Remove ký tự , ở cuối
	    $where = trim($where, ', ');
	     
	    // Nếu có điều kiện where thì nối chuỗi
	    if ($where){
	        $where = ' WHERE '.$where;
	    }
	     
	    // Return về câu truy vấn
	    return str_replace('{where}', $where, $sql);
	}
	//echo db_create_sql("select * from user {where}", array('id' => 1));
	//echo db_create_sql("UPDATE  members SET id_member='1', name='1',sex='1',dayofbirth='1',phone='1', ID_UG = '1', ID_WP='1' {where}", array('id_member' => 1));

	// Hàm insert dữ liệu vào table
	function db_insert($table, $data = array())
	{
	    // Hai biến danh sách fields và values
	    $fields = '';
	    $values = '';
	     
	    // Lặp mảng dữ liệu để nối chuỗi
	    foreach ($data as $field => $value){
	        $fields .= $field .',';
	        $values .= "'".addslashes($value)."',";
	    }
	     
	    // Xóa ký từ , ở cuối chuỗi
	    $fields = trim($fields, ',');
	    $values = trim($values, ',');
	     
	    // Tạo câu SQL
	    $sql = "INSERT INTO {$table}($fields) VALUES ({$values})";
	     
	    // Thực hiện INSERT
	    return db_execute($sql);
	}

	//hàm update
	function db_update($table, $data = array())
	{
	    // Hai biến danh sách fields và values
	    $fields = '';
	    $values = '';
	     
	    // Lặp mảng dữ liệu để nối chuỗi
	    foreach ($data as $field => $value){
	        $fields .= $field .',';
	        $values .= "'".addslashes($value)."',";
	    }
	     
	    // Xóa ký từ , ở cuối chuỗi
	    $fields = trim($fields, ',');
	    $values = trim($values, ',');
	     
	    // Tạo câu SQL
	    $sql = "UPDATE {$table}($fields) SET ({$values}) ";
	     
	    // Thực hiện INSERT
	    return db_execute($sql);
	}
	//sử dụng hàm!!!
	//echo var_dump((db_get_list(db_create_sql("select * from members {where}", array('id_member' => 'iadmin')))));
	//db_get_list(db_create_sql("select * from members {where}", array('id_member' => input_post('id_member'))));
?>
