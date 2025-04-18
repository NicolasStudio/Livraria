<?php
// Verifica se os dados foram enviados por POST
include_once('config.php');

// Verifica se os dados foram enviados por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $senha1 = mysqli_real_escape_string($conexao, $_POST['senha1']);
    $senha2 = mysqli_real_escape_string($conexao, $_POST['senha2']);

    // Verifica se o email já está em uso
    $result_email_existente = mysqli_query($conexao, "SELECT email FROM `login` WHERE email = '$email'");
    if(mysqli_num_rows($result_email_existente) > 0) {
        echo "Este email já está em uso. Por favor, escolha outro.";
    } else {
        // Verifica se as senhas são iguais
        if ($senha1 != $senha2 || $senha1 == '') {
            echo "As senhas não correspondem ou estão vazias.";
        } else {

            session_start();

            // Recupera os dados do formulário da página anterior
            $email = $_POST['email'];
            $senha1 = $_POST['senha1'];
            $senha2 = $_POST['senha2'];
            
            // Armazena os dados na sessão
            $_SESSION['email'] = $email;
            $_SESSION['senha1'] = $senha1;
            $_SESSION['senha2'] = $senha2;
            
            // Redireciona para a página cadastrar2.php
            header('Location: cadastrar2.php');

        }
    }
}
?>
<!-- Parei aqui -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16"  href="Img/IconBook.png">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Links do carrossel -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
       
    <!-- Estilos de cada parte -->
    <link rel="stylesheet" href="Styles/StylePadrao.css">
    <link rel="stylesheet" href="Styles/StyleEntrarCadastrar.css">
    <link rel="stylesheet" href="Js/Script.js">
    <script src="Js/Script.js"></script>

    <title>W3-Books</title>

</head>
<body>
    <header class="cabecalho">
        <div class="logo" onclick="home()">
            <img src="Img/IconBook5.jpg" alt="Livro aberto" class="imgIcon">
            <h2 class="textIcon">W3-Books</h2>
        </div>
        <nav class="opcoesUsuarios">
            <div class="logar">
                <button class="btnEntrar" onclick="entrar()">Entrar</button>
            </div>

            <div class="usuarioLogado">
                <h3 class="saudacao">Olá,
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn"> <span class="usuario">Francisco </span><img src="Img/Icones/Seta.png" alt="" style="width: 20px; height: 20px;"></a>
                    <div class="dropdown-content">
                      <a href="minhaConta.html" class="linhaMenuTopo">Minha Conta</a>
                      <a href="meusPedidos.html" class="linhaMenuTopo">Meus Pedidos</a>
                      <a href="#" class="linhaMenuTopo">Suporte</a>
                      <a href="#" class="linhaMenuTopo">Sair</a>
                    </div>
                </h3>
            </div>
    </header>

    <div class="boxEntrarCadastrar">
        <h4>Cadastrar</h4>

        <form method="post" action="">
            <div class="primeirosDados">
                <label for="email" class="lblTitle3">Email: </label>
                <input type="email" name="email" id="email" class="emailCad"><br>

                <label for="senha1" class="lblTitle3">Crie uma Senha: </label>
                <input type="password" name="senha1" id="senha1" class="senhaCad"><br>

                <label for="senha2" class="lblTitle3">Repita a Senha: </label>
                <input type="password" name="senha2" id="senha2" class="senhaCad2"><br>

                <button type="submit" name="avancar" class="avancarCad">Avançar</button>
            </div>
        </form>
</div>
</html>