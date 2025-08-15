<?php
session_start();

if(!isset($_SESSION['liked'])){
    $_SESSION['liked'] = [];
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if($id){
    if(in_array($id, $_SESSION['liked'])){
        $_SESSION['liked'] = array_diff($_SESSION['liked'], [$id]);
        echo json_encode(['status' => 'unliked']);
    } else {
        $_SESSION['liked'][] = $id;
        echo json_encode(['status' => 'liked']);
    }
}
