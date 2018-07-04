<?php
$error = array();
 
if (is_submit('login'))
{    
    // lấy tên đăng nhập và mật khẩu
    $id_member = input_post('id_member');
    $password = input_post('password');
     
    // Kiểm tra tên đăng nhập
    if (empty($id_member)){
        $error['id_member'] = 'Bạn chưa nhập tên đăng nhập';
    }
     
    // Kiểm tra mật khẩu
    if (empty($password)){
        $error['password'] = 'Bạn chưa nhập mật khẩu';
    }
     
    // Nếu không có lỗi
    if (!$error)
    {
        // include file xử lý database user
        include_once('database/user.php');
         
        // lấy thông tin user theo id_member
        $user = db_user_get_by_id_member($id_member);
         
        // Nếu không có kết quả
        if (empty($user)){
            $error['id_member'] = 'Tên đăng nhập không đúng';
        }
        // nếu có kết quả nhưng sai mật khẩu
        else if ($user['password'] != md5($password)) {
            $error['password'] = 'Mật khẩu bạn nhập không đúng';
        }
         
        // nếu mọi thứ ok thì tức là đăng nhập thành công 
        // nên thực hiện redirect sang trang chủ
        if (!$error){
            set_logged($user['id_member'], $user['ID_UG'], $user['name'], $user['balance']);
            redirect(base_url('admin/?m=common&a=dashboard'));
        }
    }
}
 
?>
 

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>login</title>
   
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
    
    <script src="jquery/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet"> 
     <link rel="stylesheet" href="style/css/style.css" >
        </head>


        <body>
            <header></header>
            <div class="container-fluid"> 

         <div class="row-fluid"> 
          <div class="col-md-offset-4 col-md-4" id="box"> 
           <h2><span style=" color: #4267b2;">Login </span></h2> 
           <hr> 
           <form class="form-horizontal" action="<?php echo base_url('admin/?m=common&a=login'); ?>" method="post" id="login_form">
            <fieldset> 
             <div class="form-group"> 
              <div class="col-md-12"> 
               <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> <input name="id_member" placeholder="Username" class="form-control" type="text">
                      <?php show_error($error, 'id_member'); ?> 
               </div> 
              </div> 
             </div> 

             <div class="form-group"> 
              <div class="col-md-12"> 
               <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span> <input name="password" placeholder="Password" class="form-control" type="password">   <?php show_error($error, 'password'); ?>
               </div> 
              </div> 
             </div> 
<!--
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <div class="checkbox">
            <label>
              <input type="checkbox">  <span style="color:white">Nhớ tài khoản
            </label>
          </div>
        </div>
      </div>
-->
<input type="hidden" name="request_name" value="login"/>
             <div class="form-group"> 
              <div class="col-md-12"> 
               <button type="submit" class="btn btn-md btn btn-primary pull-right" name="login-btn" >Login </button> 
              </div> 
             </div> 
            </fieldset> 
           </form> 



          </div> 
         </div>
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
        </body>

    </html>

<?php include_once('widgets/footer.php'); ?>