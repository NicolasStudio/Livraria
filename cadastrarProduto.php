<?php
    session_start();

    include_once('config.php');

    // Verifica se o usuário está logado
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
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
                    
                }else{
                    header("Location: index.php");
                }
            
        } else {
            echo "Nome de usuário não encontrado.";
        }
    } else {
        echo "ID de usuário não encontrado.";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include_once('config.php');

        // Recupera os dados do formulário
        $titulo = mysqli_real_escape_string($conexao, $_POST['titulo']);
        $tema = mysqli_real_escape_string($conexao, $_POST['tema']);
        $autor = mysqli_real_escape_string($conexao, $_POST['autor']);
        $preco = mysqli_real_escape_string($conexao, $_POST['preco']);
        $paginas = mysqli_real_escape_string($conexao, $_POST['paginas']);
        $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);

        // Processamento da imagem
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];

        // Diretório de destino para salvar a imagem
        $diretorio_destino = "C:/xampp/htdocs/Livraria/Img/Livros/";

        // Verifica se o livro já existe no banco de dados
        $consulta_nome = mysqli_query($conexao, "SELECT nome FROM produtos WHERE nome = '$titulo'");
        if (mysqli_num_rows($consulta_nome) > 0) {
            // O livro já existe, não é necessário inserir novamente
            echo "O livro '$titulo' já está cadastrado.";
        } else {
            // O livro não existe, pode ser inserido
            // Move o arquivo temporário para o diretório de destino
            if (move_uploaded_file($imagem_tmp, $diretorio_destino . $imagem_nome)) {
                // Insere o novo livro no banco de dados
                $inserir_livro = mysqli_query($conexao, 
                    "INSERT INTO produtos (nome, tema, autor, preco, paginas, img, descricao) 
                    VALUES ('$titulo', '$tema', '$autor', '$preco', '$paginas', '$imagem_nome', '$descricao')");
                
                if ($inserir_livro) {
                    echo "Livro cadastrado com sucesso.";
                } else {
                    echo "Erro ao cadastrar o livro.";
                }
            } else {
                echo "Erro ao fazer upload da imagem.";
            }
        }
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
    <link rel="stylesheet" href="Styles/StyleCadProduct.css">
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
                        <a href="excluirProduto.php" class="linhaMenuTopo">Excluir Produto</a>
                        <a href="minhaConta.php" class="linhaMenuTopo">Minha Conta</a>
                        <a href="meusPedidos.php" class="linhaMenuTopo">Meus Pedidos</a>
                        <a href="#" class="linhaMenuTopo">Suporte</a>
                        <a href="sair.php"  class="linhaMenuTopo">Sair</a>
                    </div>
                </li>
                </h3>
            </div>
        </nav>
    </header>

    <div class="boxProdutos">
        <h6>Cadastrar Produtos</h6>
        
        <form action="cadastrarProduto.php" method="POST" enctype="multipart/form-data">
            <div class="itensbox">
            <div class="bordaImg" value="">
                <label class="txtImg">
                    <img src="Img/Livros/Insira.png" alt="" class="imagemProd" id="receberImg">
                    <input type="file" id="enviarImg" name="imagem" accept="image*/"/>
                </label>
            </div>
        
                <div class="infoLivro">
                    <label for="titulo" class="lblTitleLivro">Título: </label>
                    <input type="text" id="titulo" name="titulo" class="titulo" required placeholder="Digite o nome do livro..."><br>
        
                    <label for="tema" class="lblTema">Tema: </label>
                    <input type="text" id="tema" name="tema" class="tema"  required placeholder="Digite um único tema..."><br>
        
                    <label for="autor" class="lblAutor">Autor(a): </label>
                    <input type="text" id="autor" name="autor" class="autor"><br>
        
                    <label for="preco" class="lblPreco">Preço(a): </label>
                    <input type="number" step="0.01" id="preco" name="preco" class="preco" required ><br>
        
                    <label for="paginas" class="lblPaginas">Nº de páginas: </label>
                    <input type="number" id="paginas" name="paginas" class="paginas" required >
                </div>    
            </div>    
        
            <div class="infoLivro2">
                <label for="descricao" class="lblDescricao">Descrição: </label><br>
                <textarea name="descricao" id="descricao" class="textDesc"  required placeholder="Inclua aqui uma sinopse ou um trecho da obra."></textarea>
            </div>
                    
            <button type="submit" name="concluir" class="concluirCad">Cadastrar Produto</button>
        
        </form>
        
        <script>
            let recebendoImg = document.getElementById('receberImg');
            let enviandoImg = document.getElementById('enviarImg');
            let textoImg = document.querySelector('.txtImg');
        
            enviandoImg.addEventListener('change', (event) => {
                let reader = new FileReader();
                reader.onload = () => {
                    recebendoImg.src = reader.result;
                }
        
                reader.readAsDataURL(enviandoImg.files[0]);
        
                // Exibindo a imagem
                recebendoImg.style.display = 'block';
            });
        </script>
        
    </div>



</html>