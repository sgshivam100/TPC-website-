<?php
    session_start();
    session_unset();
    session_destroy();
    $response = array('status' => 'success', 'message' => "Success logout");
    echo json_encode($response);
?>