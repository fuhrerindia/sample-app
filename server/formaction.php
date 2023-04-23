<?php
    echo json_encode([
        "status"=>200,
        "message"=>$_POST['name']
    ]);
?>