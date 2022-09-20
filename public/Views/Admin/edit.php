<?php 
    print_r($_POST);
    
    if(!isset($_POST['id'])){
        header('Location: crud.php?message=error');
        exit();
    }

    include_once "../../Models/new-connection.php";

    $id = $_POST["id"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $birthYear = $_POST["birthYear"];
    $rol = $_POST["rol"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $edit_query = $bd -> prepare("UPDATE persona SET ID_Persona = ?, nombres = ?, apellidos = ?, fecha_nacimiento = ?, rol = ?, correo_electronico = ?, telefono = ? WHERE ID_Persona = ?;");
    $query_result = $edit_query -> execute([$id, $firstName, $lastName, $birthYear, $rol, $email, $phone, $id]);

    if($query_result){
        header('Location: crud.php?message=modified');
        exit();
    }

?>