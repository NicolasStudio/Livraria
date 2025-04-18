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
                <button class="btnCadastrar" onclick="cadastrar()">Cadastrar</button>
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
    <form action="testeLogin.php" method="POST">
        <h4>Login</h4><br>

        <label for="" class="lblTitle">Email: </label>
        <input type="email" name="email" class="email"><br>

        <label for="" class="lblTitle">Senha: </label>
        <input type="password" name="senha" class="senha"><br>

        <a href="#">Esqueci minha senha</a><br>

        <button type="submit" class="entrar" name="submit">Entrar</button>
    </form>
</div>

</html>