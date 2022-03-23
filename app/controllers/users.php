<?php
require_once 'controller.php';
class Users extends Controller
{
    public function __construct()
    {

        echo "<h1>inside users controller construct</h1>";
    }
    function index()
    {

        echo "<h1>index of users</h1>";
    }
    function show($id)
    {


        $user = $this->model('user');
        $userName = $user->select($id);
        $this->view('user_view', $userName);
    }
    function add()
    {

        echo "<h1>add of users</h1>";
    }

   private function check_password($password,$min,$max){
        if(strlen($password) >= $min && strlen($password) <= $max)
        return true;
        else
        return false;
            
    }


    function add_user()
    {

        print_r($_POST);
        if(isset($_POST['submit']))
        {
            $userName=$_POST['name'];
            $password=$_POST['password'];
            $email=$_POST['email'];
            $retype_password = $_POST['retype_password'];

            
           if(!empty($userName) && !empty($password) && !empty($email)  && !empty($retype_password))
           {
               $user_data =array(
                   'name'=>$userName,
                   'password'=>md5($password),
                   'email'=>$email,
                   'retype_password'=>$retype_password
                   
               );
               if($this->check_password($password,5,15))
               {
                   if($password===$retype_password)
                   {

                   
                $u=$this->model('user');
                $message="";
                if($u->insert($user_data)){
                    $type='success';
                     $message="user created successful";
                     $this->view('feedback',array('type'=>$type,'message'=>$message));
 
                 }
                else {
                    $type='danger';
                    $message="can not create user please check your data ";
                
                    $this->view('register',array('type'=>$type,'message'=>$message,'form_values'=>$_POST));
 
                 }
                }
                else
                {
                    $type='danger';
                $message="Passwords Must be the same";
                $this->view('register',array('type'=>$type,'message'=>$message,'form_values'=>$_POST));
                }
               }
               else {
                $type='danger';
                $message="Password Must be <= 5 && >= 15";
                $this->view('register',array('type'=>$type,'message'=>$message,'form_values'=>$_POST));

             }

               
           } 
           else {
            $type='danger';
            $message="Fill Required Fields ";
            $this->view('register',array('type'=>$type,'message'=>$message,'form_values'=>$_POST));

         }

        }
        
        
        
    }
    function register()
    {
        $this->view('register');
    }

    function list_all()
    { $users=$this->model("user");
        $result=$users->select();
        $this->view('users_table',$result);

    }
    function status($id){
    $user=$this->model("user");
        $user->changeStatus($id);
        $this->list_all();

//        header('location:users/list_all');


        
    }
}
