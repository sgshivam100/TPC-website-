<?php
    $host = "localhost";
    $database = "tpc";
    $username = "root";
    $password = "Niraj@9876";
    $conn = mysqli_connect($host, $username, $password, $database);
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])){
        $who = $_POST['form_type'];
        $response = "";
        if($who == 'student'){
            $roll = $_POST['roll'];
            $pass = $_POST['password'];
            $sql = "select * from students where roll = \"$roll\";";
            $result = $conn->query($sql);
            echo mysqli_error($conn);
            if(mysqli_num_rows($result) === 0){
                //401 Failed because user doesn't exist
                http_response_code(401);
                $response = array('status' => 'failed', 'message' => "User doesn't exist");
            }else{
                $row = mysqli_fetch_assoc($result);
                $orig_pass = $row['password'];
                $course = $row['course'];
                if($pass != $orig_pass){
                    http_response_code(402);
                    $response = array('status' => 'failed', 'message' => "Wrong password");
                }else{
                    http_response_code(201);
                    $profile = base64_encode(file_get_contents("C:/xampp/htdocs/second/backend/student/$course/$roll/profile.jpg"));
                    session_start();
                    $_SESSION['roll'] = $row['roll'];
                    $_SESSION['who'] = "student";
                    $response = array('status' => 'success', 'message' => "You're logged in", 
                    'data' => array('name' => $row['name'], 'dob' => $row['dob'], 'cpi' => $row['cpi'], 'course' => $row['course'], 'department' => $row['department'], 'roll' => $row['roll'], 'profile' => $profile));
                }
            }
            $json_response = json_encode($response);
            echo $json_response;
        }else if($who === 'company'){
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $sql = "select * from company where email = \"$email\";";
            $result = $conn->query($sql);
            $response = "";
            if(mysqli_num_rows($result) === 0){
                http_response_code(401);
                $response = array('status' => "Failed", 'message' => 'No Company with this ID');
            }else{
                $row=  mysqli_fetch_assoc($result);
                $orig = $row['password'];
                if($pass != $orig){
                    http_response_code(401);
                    $response = array('status' => "Failed", 'message' => "Wrong Password");
                }else{
                    http_response_code(201);
                    $logo = base64_encode(file_get_contents("C:/xampp/htdocs/second/backend/company/$email/logo.jpg"));
                    session_start();
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['who'] = "company";
                    $response = array('status' => "Success", 'message' => "Successful login", 'logo' => $logo);
                }
            }
            $json_response = json_encode($response);
            echo $json_response;
        }else if($who === 'admin'){
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sql = "select password from admin where email = '$email';";
            $result = $conn->query($sql);
            if(mysqli_num_rows($result) === 0){
                http_response_code(401);
                echo json_encode(array('status' => "failed", 'message' => "No such admin"));
            }else{
                $orig = mysqli_fetch_assoc($result)['password'];
                if($orig === $password){
                    http_response_code(201);
                    echo json_encode(array('status' => 'success', 'message' => "Succcess Login"));
                    session_start();
                    $_SESSION['email'] = $email;
                    $_SESSION['who'] = "admin";
                }else{
                    http_response_code(401);
                    echo json_encode(array('status' => 'failed', "message" => "Wrong Credentials"));
                }
            }
        }else if($who === 'officer'){
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $sql = "select password from officer where email = '$email'";
            $result = $conn->query($sql);
            if($result){
                $row = mysqli_fetch_assoc($result);
                $orig = $row['password'];
                $year= $row['year'];
                if($orig === $pass){
                    echo json_encode(array('status'=> "Success", "message" =>"Success logged in"));
                    session_start();
                    $_SESSION['email'] = $email;
                    $_SESSION['who'] = "officer";
                    $_SESSION['year'] = $year;
                }else{
                    echo json_encode(array('status' => "failed", "message" => "Wrong Password"));
                }
            }else{
                http_response_code(401);
                echo json_encode(array('status' => "Failed", 'message' => "Something went wrong"));
            }
        }else if($who === 'alumini'){
            $roll = $_POST['roll'];
            $pass = $_POST['password'];
            $sql = "select password from alumini where roll = '$roll';";
            $result = $conn->query($sql);
            $orig = mysqli_fetch_assoc($result)['password'];
            if($orig === $pass){
                http_response_code(201);
                echo json_encode(array('status' => "Success", "message" => "Logged in"));
                session_start();
                $_SESSION['who'] = "alumini";
                $_SESSION['roll'] = $roll;
            }else{
                http_response_code(401);
                echo json_encode(array('status' => "Failed"));
            }
        }
    }
?>