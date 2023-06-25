<?php
    session_start();
    $host = "localhost";
    $user = "root";
    $pass = "Niraj@9876";
    $db = "tpc";
    $conn = mysqli_connect($host, $user, $pass, $db);
    $who = $_SESSION['who'];
    if($who === 'admin'){
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['regstud'])){
            $lyear = $_GET['lyear'];
            echo "I'm here";
            $uyear= $_GET['uyear'];
            $sql = "select name, roll, dob, cpi, course, department, current_offer, gender, company_name, email, contact_number from students where register_year >= $lyear and register_year <= $uyear";
            $result = $conn->query($sql);
            if(!$result){
                echo json_encode(array('status' => 'failed', 'message' => 'data could not be fetched'));
                echo mysqli_error($conn);
            }else{
                $response = array();
                $response['entries'] = mysqli_num_rows($result);
                while($row = mysqli_fetch_assoc($result)){
                    $data = array();
                    $data['name'] = $row['name'];
                    $roll = $row['roll'];
                    $course= $row['course'];
                    $data['dob'] = $row['dob'];
                    $data['cpi'] = $row['cpi'];
                    $data['course'] = $row['course'];
                    $data['department'] = $row['department'];
                    $data['current_offer'] = $row['current_offer'];
                    $data['gender'] = $row['gender'];
                    $data['company_name'] = $row['company_name'];
                    $data['email'] = $row['email'];
                    $data['contact_number'] = $row['contact_number'];
                    $profile = base64_encode(file_get_contents("C:/xampp/htdocs/second/backend/student/$course/$roll/profile.jpg"));
                    $data['profile'] = $profile;
                    $response['data'][$roll] = $data;
                }
                echo json_encode($response);
            }
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['transcript'])){
            $roll = $_GET['roll'];
            $course = $_GET['course'];
            $addr = "C:/xampp/htdocs/second/backend/student/$course/$roll/transcript.pdf";
            $transcript = base64_encode(file_get_contents($addr));
            $response = array();
            if(!file_exists($addr)){
                http_response_code(401);
                echo json_encode(array('status' => 'failed', 'message' => "No Transcript"));
            }else{
                http_response_code(201);
                echo json_encode(array('status' => 'success', 'message'=>"Transcript fetched", 'transcript' => $transcript));
            }          
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['removestud'])){
            $roll = $_GET['roll'];
            $sql = "delete from job_apply where roll = '$roll';";
            $r2 = $conn->query($sql);
            $sql = "delete from selection where roll = '$roll';";
            $r3 = $conn->query($sql);
            $sql = "delete from students where roll = '$roll';";
            $r1 = $conn->query($sql);
            if($r1 && $r2 && $r3){
                http_response_code(201);
                echo json_encode(array('status' => 'succes', 'mesage'=> "Student Removed"));
            }else{
                http_response_code(401);
                echo json_encode(array('status' =>"failed", 'message' => "Something went wronge"));
            }
        }


        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['company'])){
            $sql = "select * from company order by verified desc;";
            $result = $conn->query($sql);
            $response = array();
            while($row = mysqli_fetch_assoc($result)){
                $data = array();
                $data['name'] = $row['name'];
                $data['location'] = $row['location'];
                $data['verified'] = $row['verified'];
                $email = $row['email'];
                $data['logo'] = base64_encode("C:/xampp/htdocs/second/backend/company/$email/logo.jpg");
                $response[$row['email']] = $data;
            }   
            if($result){
                echo json_encode(array('status' => "success", "message" => "Data fetched", "entries" => mysqli_num_rows($result), "data" => $response));
            }else{
                echo json_encode(array('status' => "failed", "message" => "Something went wrong"));
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form_type'] === 'appoint'){
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['name'];
            $year= $_POST['year'];
            $sql = "insert into officer (name, email, password, year) values ('$name', '$email', '$pass', $year);";
            $result = $conn->query($sql);
            if($result){
                http_response_code(201);
                echo json_encode(array('status' => "Success", 'message' => "Appointed"));
            }else{
                http_response_code(401);
                echo json_encode(array('status' => 'Failed', 'message' => "Something went wrong"));
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'query'){
            echo "I'm Here";
            $query = $_POST['query'];
            $result = $conn->query($query);
            $response;
            if($result){
                echo json_encode(array('status' => "success", "message" => "Query Ran Successfully"));
            }else{
                http_response_code(401);
                echo json_encode(array('status' => "failed", "message" => mysqli_error($conn)));
            }
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['verify'])){
            $email = $_GET['email'];
            $sql = "update company set verified = true where email = '$email';";
            $result = $conn->query($sql);
            if($result){
                http_response_code(201);
                echo json_encode(array('staus' => "success", "message" => "Comapny marked verified"));
            }else{
                http_response_code(401);
                echo json_encode(array('status' => "failed", 'message' => "Something went wrong"));
            }
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['remove_company'])){
            $email = $_GET['email'];
            $sql = "delete from jobs where email = '$email';";
            $r1  = $conn->query($sql);
            echo mysqli_error($conn);
            $sql = "delete from company where  email = '$email';";
            $r2 = $conn->query($sql);
            if($r1 && $r2){
                http_response_code(201);
                echo json_encode(array('status' => 'success', 'message'=> "Company Removed"));
            }else{
                http_response_code(401);
                echo json_encode(array('status' => "failed" , 'message' => "Something went wrong"));
            }
        }

    }
?>