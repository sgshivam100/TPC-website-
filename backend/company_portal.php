<?php
    $host = "localhost";
    $user = "root";
    $password = "Niraj@9876";
    $db = "tpc";
    $conn = mysqli_connect($host, $user, $password, $db);
    //Job Offer variable will held the Data 
    session_start();
    if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'update_job'){
        if($_FORM_TYPE = "update_job"){
            $jobs_id = $_POST['jobs_id'];
            $post = $_POST['post'];
            $industry=  $_POST['industry'];
            $minsal = $_POST['minsalary'];
            $maxsal = $_POST['maxsalary'];
            $forWhom = $_POST['forWhom']; 
            $skills = $_POST['skills'];
            $interview = $_POST['interview'];
            $deadline = $_POST['deadline'];
            $year = $_POST['year'];
            $cpiReq = $_POST['cpi'];
            $jobas = $_POST['jobas'];          
            $sql = "update jobs set post = \"$post\", industry = \"$industry\", minsal = \"$minsal\", maxsal = \"$maxsal\", 
            forWhom = \"$forWhom\", interview = \"$interview\", deadline=\"$deadline\", year = \"$year\", cpiReq = $cpiReq
            , jobas = \"$jobas\" where jobs_id = $jobs_id;";
            $result = $conn->query($sql);
            $sql = "delete from skillreq where jobs_id = $jobs_id;";
            $del = $conn->query($sql);
            $skills = $_POST['skills'];
            foreach($skills as $value){ //value is skill Id 
                $insert = "insert into skillReq (jobs_id, skill) values ($jobs_id, \"$value\");";
                $temp = $conn->query($insert);
           }
            if($result){
                http_response_code(201);
                echo json_encode(array('status' => "Success", 'message' => "data updated"));
            }else{
                http_response_code(401);
                echo json_encode(array('status' => "failed", 'message' => "Something went wrong"));
            }

        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'job_offer'){
        if($_POST['form_type'] === 'job_offer'){
            $post = $_POST['post'];
            $industry=  $_POST['industry'];
            $minsal = $_POST['minsalary'];
            $maxsal = $_POST['maxsalary'];
            $forWhom = $_POST['forWhom']; 
            $skills = $_POST['skills'];
            $email = $_SESSION['email'];
            $interview = $_POST['interview'];
            $deadline = $_POST['deadline'];
            $year = $_POST['year'];
            $cpiReq = $_POST['cpi'];
            $jobas = $_POST['jobas']; //Intern Full Time , Part Time, Contract
            $sql = "insert into jobs(year, deadline, email, post, industry, minsal, maxsal, forWhom, interview, 
             cpiReq, jobas) values ($year, \"$deadline\", \"$email\", \"$post\", \"$industry\", $minsal, $maxsal, \"$forWhom\", \"$interview\", $cpiReq, \"$jobas\");";
            $result = $conn->query($sql);
            echo mysqli_error($conn);
            $last = $conn->query("select last_insert_id() as jobs_id");
            $lastrow = mysqli_fetch_assoc($last);
            $jobs_id = $lastrow['jobs_id'];
            $value = "";
            $success = true;
            if(!$result){
                echo mysqli_error($conn);
                $success = false;
            }
            foreach($skills as $value){ //value is skill Id 
                 $insert = "insert into skillReq (jobs_id, skill) values ($jobs_id, \"$value\");";
                 $temp = $conn->query($insert);
                 if(!$temp){
                     $success = false;
                 }
            }
            $respose = "";
            $json_response = "";
            if($success){
                 http_response_code(201);
                 $response = array('status' => "Success", "message" => "Offer Posted Successfully");
                 $json_response = json_encode($response);
                 echo $json_response; 
            }else{
                 http_response_code(401);
                 $response = array('status' => "Failed", "message" => "Data Couldn't be written");
                 $json_response = json_encode($response);
                 echo $json_response;
            }
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['vacancies'])){
        $email = $_SESSION['email'];
        $sql = "select * from jobs where email = \"$email\" and deadline >= curdate();";
        $result = $conn->query($sql);
        if($result){
            $response = array('status' => 'success', 'entries'=> mysqli_num_rows($result), 'data' => array());
            while($row = mysqli_fetch_assoc($result)){
                $entry = array();
                $entry['post'] = $row['post'];
                $entry['interview']= $row['interview'];
                $entry['cpiReq'] = $row['cpiReq'];
                $entry['jobas'] = $row['jobas'];
                $entry['minsal'] = $row['minsal'];
                $entry['maxsal'] = $row['maxsal'];
                $entry['forWhom'] = $row['forWhom'];
                $response['data'][$row['jobs_id']] = $entry;
            }
            echo json_encode($response);
        }else{
            $response = array('status' => 'failed', 'message' => "Something went wrong");
            echo json_encode($response);
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['applied_students'])){
        $email = $_SESSION['email'];
        $jobs_id = $_GET['jobs_id'];
        $sql = "select * from job_apply natural join students where (jobs_id = \"$jobs_id\");";
        $result = $conn->query($sql);
        if($result){
            $response = array('status' => 'success', 'entries' => mysqli_num_rows($result), 'data'=>array());
            while($row = mysqli_fetch_assoc($result)){
                $data = array();
                $data['name'] = $row['name'];
                $data['cpi'] = $row['cpi'];
                $data['roll'] = $row['roll'];
                $data['department'] = $row['department'];
                $data['course'] = $row['course'];
                $data['email'] = $row['email'];
                $data['contact_number'] = $row['contact_number'];
                $data['gender'] = $row['gender'];
                $course = $row['course'];
                $roll = $row['roll'];
                // $data['profile'] = base64_encode(file_get_contents("C:/xampp/htdocs/second/backend/student/$course/$roll/profile.jpg"));
                $data['profile'] = "hello";
                $response['data'][$row['id']] = $data;
            }
            echo json_encode($response);
        }
    }

?>