<?php
session_start();

include_once('config.php');

// Verifica se o usuário está logado
if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header('Location: entrar.php');
    exit();
}

// Pega o email do usuário logado
$email_logado = $_SESSION['email'];

// Consulta para obter o ID do usuário logado
$stmt = $conexao->prepare("SELECT id_usuario FROM login WHERE email = ?");
$stmt->bind_param("s", $email_logado);
$stmt->execute();
$resultado_login = $stmt->get_result();

// Verifica se o resultado da consulta não está vazio
if ($resultado_login->num_rows > 0) {
    // Extrai o ID do usuário logado
    $row_login = $resultado_login->fetch_assoc();
    $id_usuario = $row_login['id_usuario'];

    // Consulta para obter o nome do usuário com base no ID do usuário logado
    $stmt = $conexao->prepare("SELECT nome, admin FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado_usuario = $stmt->get_result();

    // Verifica se o resultado da consulta não está vazio
    if ($resultado_usuario->num_rows > 0) {
        // Extrai o nome do usuário
        $row_usuario = $resultado_usuario->fetch_assoc();
        $nome_usuario = $row_usuario['nome'];
        $admin_usuario = $row_usuario['admin'];
        
            //Exibe o nome do usuário
            if($admin_usuario === 1){
                header("Location: minhaConta2.php");
            }else{
                
            }
        
    } else {
        echo "Nome de usuário não encontrado.";
    }
} else {
    echo "ID de usuário não encontrado.";
}

?>

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
    <link rel="stylesheet" href="Styles/StylePerfil.css">
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
            <div class="logar" >
                <button class="btnEntrar" onclick="entrar()">Entrar</button>
                <button class="btnCadastrar" onclick="cadastrar()">Cadastrar</button>
                <script>
                    // Verifica se o usuário está logado
                    if (<?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>) {
                        // Se estiver logado, altere o estilo para display: none
                        document.querySelector('.logar').style.display = 'none';
                        document.querySelector('.usuarioLogado').style.display = 'block';

                    }
                </script>
            </div>

            <div class="usuarioLogado">
            <script>
                // Verifica se o usuário está logado
                if (<?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>) {
                    // Se estiver logado, altere o estilo para display: none
                    document.querySelector('.usuarioLogado').style.display = 'block';

                }
            </script>
                <h3 class="saudacao">Olá,
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn"> 
                        <span class="usuario"><?php echo $nome_usuario , " "?></span><img src="Img/Icones/Seta.png" alt="" style="width: 20px; height: 20px;"></a>
                    <div class="dropdown-content">
                        <a href="alterarProduto.php" class="linhaMenuTopo">Alterar Produto</a>
                        <a href="cadastrarProduto.php" class="linhaMenuTopo">Cadastrar Produto</a>
                        <a href="excluirProduto.php" class="linhaMenuTopo">Excluir Produto</a>
                        <a href="minhaConta.php" class="linhaMenuTopo">Minha Conta</a>
                        <a href="#" class="linhaMenuTopo">Suporte</a>
                        <a href="sair.php"  class="linhaMenuTopo">Sair</a>
                    </div>
                </li>
                </h3>
            </div>
        </nav>
    </header>

    <div class="historicoPedidos">

        <h2>Histórico de Pedidos</h2>
        <div class="boxPedidos">

        <details>
                <summary><b>Número do pedido:</b> #12345</summary>
            <div class="itensCompra">
                <div class="produtoComprado">
                    <img src="Img/Livros/HP Prisioneiro.jpg" alt="" class="imgLivro">
                </div>
                <div class="infoProduto">
                    <h4 class="titleLivro"><a href="#">Harry Potter e o Prisioneiro de Azkaban</a></h4>
                    <p><b>Qtd.:</b> 1</p>
                    <p><b>Preço:</b> R$ 29,90</p>
                </div>
            </div>
            
            <div class="itensCompra">
                <div class="produtoComprado">
                    <img src="Img/Livros/HP Filosofal.jpg" alt="" class="imgLivro">
                </div>
                <div class="infoProduto">
                    <h4 class="titleLivro"><a href="#">Harry Potter e a Pedra Filosofal</a></h4>
                    <p><b>Qtd.:</b> 2</p>
                    <p><b>Preço:</b> R$ 24,90</p>
                </div>
            </div>
        </details>

        <hr>

        <details>
            <summary><b>Número do pedido:</b> #5555</summary>
        <div class="itensCompra">
            <div class="produtoComprado">
                <img src="Img/Livros/Hp Enigma.jpg" alt="" class="imgLivro">
            </div>
            <div class="infoProduto">
                <h4 class="titleLivro"><a href="#">Harry Potter e o Enigma do Príncipe</a></h4>
                <p><b>Qtd.:</b> 1</p>
                <p><b>Preço:</b> R$ 29,90</p>
            </div>
        </div>
        
        <div class="itensCompra">
            <div class="produtoComprado">
                <img src="Img/Livros/HP Filosofal.jpg" alt="" class="imgLivro">
            </div>
            <div class="infoProduto">
                <h4 class="titleLivro"><a href="#">Harry Potter e a Pedra Filosofal</a></h4>
                <p><b>Qtd.:</b> 2</p>
                <p><b>Preço:</b> R$ 24,90</p>
            </div>
        </div>
        </details>

            <!-- Adicione mais blocos .itensCompra conforme necessário para outros itens na compra -->
        </div>
    </div>
    
    




</html>