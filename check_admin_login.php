<?php 
// check admin login
include_once("database_connection.php");
session_start();
$admin_user_name = "";
$admin_password = "";
$error_admin_user_name = "";
$error_admin_password = "";
$error =0;

if(empty($_POST['admin_user_name'])) {
    $error_admin_user_name = "User is required";
    $error++;
}
else{
    $admin_user_name = $_POST['admin_user_name'];
}
if(empty($_POST['admin_password'])) {
    $error_admin_password = "Password is required";
    $error++;
}
else{
    $admin_password = $_POST['admin_password'];
}

if($error=0) {
    $query = "
    SELECT *FORM `tbl_admin`
    WHERE admin_user_name = '".$admin_user_name."'    
    ";
    $statement = $connect-> prepare($query);

    if($statement->execute()) {
        $total_row = $statement->rowCount();
        if($total_row > 0) {
            $result = $statement->fetchAll();
            foreach($result as $row){
                if(password_verify($admin_user_name, $row['admin_password'])){
                    $_SESSION['admin_id'] = $row['admin_id'];
                }
                else {
                    $error_admin_password = "wrong password";
                     $error++;
        }
                }               
            }
            
        }
        else {
            $error_admin_user_name = "wrong user name";
            $error++;
    }
}

if($error >0) {
    $output = array(
        'error'                 => true,
        'error_admin_user_name' => $error_admin_user_name,
        'error_admin_password' => $error_admin_user_name

    );
}
else {
    $output = array(
        'success'  => true

    );
}

echo json_encode($output);
?> 
