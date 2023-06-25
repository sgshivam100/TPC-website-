<?php
$servername = "localhost";
$username = "root";
$password = "Niraj@9876";
$dbname = "tpc";
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error) {
    echo "Connection failed";
    die("Connection failed: " . $conn->connect_error);
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])){
    $who = $_POST['form_type'];
    if($who === 'student'){
        $name = $_POST['name'];
        $roll = $_POST['roll'];
        $pass = $_POST['password'];
        $dob = $_POST['dob'];
        $cpi = $_POST['cpi'];
        $course = $_POST['course'];
        $department = $_POST['department'];
        $current_offer = $_POST['current_offer'];
        //Handling existing student and again registeratino 
        $sql = "select * from students where roll = \"$roll\";";
        $result = $conn->query($sql);
        $response = "";
        $success = true;
        http_response_code(200);
        header('Content-Type: application/json');
        if(mysqli_num_rows($result) > 0){
            http_response_code(401);
            $response = array('status' => 'failed', 
                'data' => array('message' => "User Already Registered"));
        }else{
            if(isset($_FILES['transcript']) && isset($_FILES['profile'])){
                $fname = $_FILES['transcript']['name'];
                $ftmp = $_FILES['transcript']['tmp_name'];
                $ftype = $_FILES['transcript']['type'];
                $fsize = $_FILES['transcript']['size'];
                $ftarget = "C:/xampp/htdocs/second/backend/student/$course/$roll/";
                if(!file_exists($ftarget)){
                    mkdir($ftarget, 0777, true);
                }
                $ftarget_file = $ftarget . basename("transcript.pdf");
                if(move_uploaded_file($ftmp, $ftarget_file)){
                }else{
                    $success = false;
                    http_response_code(401);
                    $response = array('status' => 'failed', 
                    'data' => array('message' => "Data couldn't be written"));
                }   
                $iname = $_FILES['profile']['name'];
                $itmp = $_FILES['profile']['tmp_name'];
                $itype = $_FILES['profile']['type'];
                $itarget = "C:/xampp/htdocs/second/backend/student/$course/$roll/";
                $itarget_file = $itarget . basename("profile.jpg");
                if(move_uploaded_file($itmp, $itarget_file)){
                }else{
                    $success = false;
                }
                if($success){
                    $sql = "insert into students (name, roll, dob, course, cpi, department, current_offer, password) values (\"$name\", \"$roll\", \"$dob\", \"$course\", $cpi, \"$department\", $current_offer, \"$pass\");";
                    if($conn->query($sql)){
                        http_response_code(201);
                        $response = array('status' => 'success', 
                        'data' => array('message' => "You've been registered"));
                    }else{
                        http_response_code(401);
                        $response = array('status' => 'failed', 
                        'data' => array('message' => "Data couldn't be written"));
                    }
                }else{
                    http_response_code(401);
                    $response = array('status' => 'failed', 
                    'data' => array('message' => "Data couldn't be written"));
                } 
            }
        }
        $json_response = json_encode($response);
        echo $json_response;
    }else if($who === 'company'){
         $name = $_POST['name'];
         $email = $_POST['email'];
         $location = $_POST['location'];
         $password = $_POST['password'];
         //Check if id is unique or not
         $sql = "select * from company where email = \"$email\";";
         $result = $conn->query($sql);
         $response = "";
         header('Content-Type: application/json');
         if(mysqli_num_rows($result) > 0){     
            http_response_code(401);
            $response = array('status' => 'failed', 'message' => 'ID is already registered');
            $json_response = json_encode($response);
            echo $json_response;
         }else{
            $success = true;
            if(isset($_FILES['brochure'])){
                $ftmp = $_FILES['brochure']['tmp_name'];
                $ftarget = "C:/xampp/htdocs/second/backend/company/$email/";
                if(!file_exists($ftarget)){
                    mkdir($ftarget, 0777, true);
                }else{
                }
                $target_file = $ftarget . basename("brochure.pdf");
                if(move_uploaded_file($ftmp, $target_file)){
                }else{
                    $success = false;
                }
            }
            if(isset($_FILES['logo'])){
                $itmp = $_FILES['logo']['tmp_name'];
                $itarget = "C:/xampp/htdocs/second/backend/company/$email/";
                if(!file_exists($itarget)){
                    mkdir($itarget, 0777, true);
                }
                $itarget_file = $itarget . basename("logo.jpg");
                if(move_uploaded_file($itmp, $itarget_file)){
                }else{
                    $success = false;
                }
            }
            if($success){
                $sql = "insert into company (name, email, location, password) values (\"$name\", \"$email\", \"$location\", \"$password\");";
                if($conn->query($sql)){
                    $response = array('status' => "Success", 'message' => "Comapny Registered Successfully");
                    $json_response = json_encode($response);
                    echo $json_response;
                }else{
                    echo mysqli_error($conn);
                    $success = false;
                }
            }else{
                http_response_code(401);
                $response = array('status' => "Failed", 'message' => "Registeration Failed");
                echo json_encode($response);
            }
        }
    }   
}
$conn->close();
?>
