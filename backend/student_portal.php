<?php
    $host = "localhost";
    $user_name = "root";
    $password = "Niraj@9876";
    $db = "tpc";
    $conn = mysqli_connect($host, $user_name, $password, $db);
    session_start();
    if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'job_apply'){
      $jobs_id = $_POST['jobs_id'];
      $roll = $_SESSION['roll'];
      $sql = "insert into job_apply (jobs_id, roll) values ($jobs_id, \"$roll\");";
      $result = $conn->query($sql);
      $response = "";
      if($result){
          http_response_code(201);
          $response = array('status' => "Success", 'message' => "Successfully Applied");
          echo json_encode($response);
      }else{
          http_response_code(401);
          $response = array('status' => "Failed", 'message' => "Failed Application");
          echo json_encode($response);
      }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] == 'selection'){
        $offer = $_POST['offer'];
        $jobs_id = $_POST['jobs_id'];
        $roll = $_SESSION['roll'];
        $emailQuery = "select email from jobs where jobs_id = $jobs_id";
        $emailResult = $conn->query($emailQuery);
        $email = mysqli_fetch_assoc($emailResult)['email'];
        $companyQuery = "select distinct(name) from company where email = \"$email\";";
        $companyResult = $conn->query($companyQuery);
        $company = mysqli_fetch_assoc($companyResult)['name'];
        $sql = "update students set current_offer = $offer, company_name = \"$company\" where roll = \"$roll\";";
        $res=  $conn->query($sql);
        $sql = "insert into selection (jobs_id, roll, offer) values ($jobs_id, \"$roll\", $offer);";
        $result = $conn->query($sql);
        if($result){
            http_response_code(201);
            echo json_encode(array('status' => "Success", "message" => "Your selection has been recorded"));
        }else{
            http_response_code(401);
            echo json_encode(array('status' => "failed", "message" => "Could not be marked"));
        }
    }
    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['applied'])){
        $roll = $_SESSION['roll'];
        $sql = "select * from job_apply join jobs join company where jobs.jobs_id = job_apply.jobs_id and jobs.email = company.email and roll = \"$roll\";";
        $result = $conn->query($sql);
        $response = "";
        if($result){
            http_response_code(201);
            $response = array('status' => 'success', 'message'=>"Data Retrived", 'entries' => mysqli_num_rows($result));
            while($row = mysqli_fetch_assoc($result)){
                $data = array();
                $email = $row['email'];
                $logo = base64_encode(file_get_contents("C:/xampp/htdocs/second/backend/company/$email/logo.jpg"));
                $jobs_id = $row['jobs_id'];
                $data['name'] = $row['name'];
                $data['cpiReq'] = $row['cpiReq'];
                $data['post'] = $row['post'];
                $data['forWhom'] = $row['forWhom'];
                $data['cpiReq'] = $row['cpiReq'];
                $data['jobas'] = $row['jobas'];
                // $data['logo'] = base64_encode(file_get_contents("C:/xampp/htdocs/second/backend/company/$email/logo.jpg"));
                $data['logo'] = "hello";
                $data['deadline'] = $row['deadline'];
                $data['email'] = $row['email'];
                $response['data'][$jobs_id] = $data;
            }
        }else{
            http_response_code(401);
            $responsee = array('status' => 'failed', "message" => "Something went wrong");
        }
        echo json_encode($response);
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['vacancies'])){
        $roll = $_SESSION['roll'];
         //If selected or all 
        $sql = "select * from jobs natural join company where jobs_id not in 
                (select jobs_id from job_apply where roll = \"$roll\") and deadline >= curdate();";
        $result = $conn->query($sql);
        $success = true;
        $response = array('status' => 'success', 'data' => array());
        if($result){
            http_response_code(201);
            $row;
            $size  = mysqli_num_rows($result);
            $response['entries'] = $size;
            while($row = mysqli_fetch_assoc($result)){
                $data = array();
                $email = $row['email'];
                $jobs_id = $row['jobs_id'];
                $data['verified'] = $row['verified'];
                $logo = base64_encode(file_get_contents("C:/xampp/htdocs/second/backend/company/$email/logo.jpg"));
                $data['minsal'] = $row['minsal'];
                $data['email'] = $row['email'];
                $data['maxsal'] = $row['maxsal'];
                $data['name'] = $row['name'];
                $data['post'] = $row['post'];
                $data['forWhom'] = $row['forWhom'];
                $data['cpiReq'] = $row['cpiReq'];
                $data['jobas'] = $row['jobas'];
                $data['logo'] = "Hello";
                $data['deadline'] = $row['deadline'];
                $response['data'][$jobs_id] = $data;
            }
            echo json_encode($response);
        }else{
            http_response_code(401);
            $response = array('status' => 'failed', 'message' => "Something went wrong");
            echo json_encode($response);
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['application_remove'])){
        $jobs_ids = $_GET['jobs_ids'];
        $roll = $_SESSION['roll'];
        $count = count($jobs_ids);
        $q = "";
        if($jobs_ids[0] != 'all'){
            for($i = 0;$i < $count; $i++){
                if($i != $count-1){
                    $q = $q . $jobs_ids[$i] . ", ";
                }else{
                    $q = $q . $jobs_ids[$i];
                }
            }
        }
        if($jobs_ids[0] === 'all'){
            $q = 'all';
        }
        $sql = "";
        if($q === 'all'){
            $sql = "delete from job_apply where roll =\"$roll\";";    
        }else{
            $sql = "delete from job_apply where roll =\"$roll\" and jobs_id in ($q);";
        }
        $result = $conn->query($sql);
        $response =  "";
        echo mysqli_error($conn);
        if($result){
            http_response_code(201);
            $response = array('status' => 'success', 'message' => "Application Withdrawn");
        }else{
            http_response_code(401);
            $response = array('status' => 'failed', 'message' => "Something went wrong");
        }
        echo json_encode($response);
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'update'){
        $roll = $_SESSION['roll'];
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $cpi = $_POST['cpi'];
        $department = $_POST['department'];
        $current_offer = $_POST['current_offer'];
        $course = $_POST['course'];
        $gender = $_POST['gender'];
        $sql= "update students set name = \"$name\", dob = \"$dob\", cpi = $cpi, department = \"$department\", 
            current_offer = $current_offer, gender = \"$gender\" where  roll = \"$roll\";";
        if($conn->query($sql)){
            http_response_code(201);
            echo json_encode(array('status' => "Success", 'message' => "Data updated"));
        }else{
            echo json_encode(array('status' => 'Failed', "message" => "Something went wrong"));
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'account_delete'){
        $password = $_POST['password'];
        $roll = $_SESSION['roll'];
        $result = $conn->query("select password from students where roll = \"$roll\";");
        $orig = mysqli_fetch_assoc($result)['password'];
        if($password === $orig){
            $del = "delete from students where roll = \"$roll\";";
            $res = $conn->query($del);
            if($res){
                http_response_code(201);
                echo json_encode(array('status' => "success", 'message' => "Successfully Removed"));
            }else{
                http_response_code(401);
                echo json_encode(array('satus' => "failed", 'message'=> "Something went wrong"));
            }
        }else{
            http_response_code(201);
            echo json_encode(array('status' => 'failed', 'message' => "Wrong password"));
        }

    }
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['brochure'])){
            $email = $_GET['email'];
            $link = ("C:/xampp/htdocs/second/backend/company/$email/brochure.pdf");
            $response = "";
            if(file_exists($link)){
                $brochure = base64_encode(file_get_contents($link));
                $response = array('status' => 'success', 'message' =>"Brochure fetched successfully", 'brochure' => $brochure);
            }else{
                $response = array('status' => 'failed', 'message' => "No Brochure Available");
            }
            echo json_encode($response);
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['alumini'] != null){
        $roll = $_GET['roll'];
        $sql = "select * from students where roll = '$roll'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $roll = $row['roll'];
        $password = $row['password'];
        $dob = $row['dob'];
        $cpi = $row['cpi'];
        $course = $row['course'];
        $department = $row['department'];
        $current_offer = $row['current_offer'];
        $gender = $row['gender'];
        $company_name = $row['company_name'];
        $email = $row['email'];
        $year = $row['register_year'] + 4;
        $contact_number = $row['contact_number'];

        
        $sql = "insert into alumini (name, roll, password, dob, cpi, course, department, current_offer, gender, company_name, email, contact_number, year) values ('$name', '$roll', '$password', '$dob', '$cpi', '$course', '$department', $current_offer, '$gender', '$company_name', '$email', '$contact_number', $year);";
        $result = $conn->query($sql);
        echo mysqli_error($conn);
        $sql = "delete from students where roll = '$roll';";
        $res = $conn->query($sql);
        if($result){
            http_response_code(201);
            echo json_encode(array('status' => 'success'));
        }
    }
?>