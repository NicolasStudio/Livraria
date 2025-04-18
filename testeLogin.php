<?php
session_start();

if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])){
    include_once('config.php');

    // Valores pegos quando forem digitados.
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Use declarações preparadas para prevenir injeção de SQL
    $stmt = $conexao->prepare("SELECT * FROM `login` WHERE email = '$email' and senha = '$senha'");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($resultado->num_rows < 1){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header("Location: entrar.php");
        exit();
    }else{
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
        header("Location: index.php");
        exit();
    }
}else{
    // Não acessa e retorna. 
    header("Location: entrar.php");
    exit();
}
?>
