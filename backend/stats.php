<?php
    $host = "localhost";
    $user = "root";
    $pass = "Niraj@9876";
    $db = "tpc";
    $conn=  mysqli_connect($host, $user, $pass, $db);
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['companies_applied'])){
        $sql = "select name, email, location from company;";
        $result = $conn->query($sql);
        $response;
        if($result){
            $response = array('status' => "Success", "message" => "Success", 'data'=>array());
            while($row = mysqli_fetch_assoc($result)){
                $response['data'][$row['email']] = $row;
            }
            echo json_encode($response);
        }else{
            http_response_code(401);
            echo json_encode(array('status' => 'failed', 'message' => "Something is wrong"));
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['companies']) && isset($_GET['lyear']) && isset($_GET['uyear'])){
        $company = $_GET['companies'];
        $lyear = $_GET['lyear'];
        $uyear = $_GET['uyear'];
        $a = "";
        $b = "";
        if($lyear === 'all'){
            $a = "'all'";
            $b = "'all'";
        }else{
            $a = $lyear;
            $b = $uyear;
        }
        $sql = "select count(*) as total, year from job_apply join jobs join company where jobs.jobs_id = job_apply.jobs_id and jobs.email = company.email and (year >= $a or 'all' = $a) and (year <= $b or 'all' = $b) and (company.email = '$company' or 'all' = '$company') group by year;";
        $response = array();
        $applied = $conn->query($sql);
        $sql = "select count(*) as total, year from selection join jobs join company where jobs.jobs_id = selection.jobs_id and jobs.email = company.email and (year >= $a or 'all' = $a) and (year <= $b or 'all' = $b) and (company.email = '$company' or 'all' = '$company') group by year;";
        $selected = $conn->query($sql);
        $sql = "select offer, year, company.name from selection join jobs join company where jobs.jobs_id = selection.jobs_id and jobs.email = company.email and (year >= $a or 'all' = $a) and (year <= $b or 'all' = $b) and (company.email = '$company' or 'all' = '$company') order by offer limit 3;";
        $top = $conn->query($sql);
        for($i = 1;$i<=3;$i++){
            $row = mysqli_fetch_assoc($top);
            $response['top'][$i] = $row;
        }
        while($row = mysqli_fetch_assoc($applied)){
            $response[$row['year']]['applied'] = $row['total'];
            $response[$row['year']]['selected'] = 0;
        }
        while($row = mysqli_fetch_assoc($selected)){
            $response[$row['year']]['selected'] = $row['total'];
        }
        if($selected && $applied){
            http_response_code(201);
            echo json_encode($response);
        }else{
            http_response_code(401);
            echo json_encode(array('status' => 'failed', "messaage" => "Something went wrong"));
        }
    }
?>