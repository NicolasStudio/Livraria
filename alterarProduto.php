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
?>

<?php 
    // Verifica se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitAlterar'])) {
                
        include_once('config.php');
        
        // Verifica se a conexão foi bem-sucedida
        if (mysqli_connect_errno()) {
            echo "Falha ao conectar ao MySQL: " . mysqli_connect_error();
            exit();
        }
        
        // Recebe os dados do formulário
        $titulo = mysqli_real_escape_string($conexao, $_POST['titulo']);
        $tema = mysqli_real_escape_string($conexao, $_POST['tema']);
        $autor = mysqli_real_escape_string($conexao, $_POST['autor']);
        $preco = mysqli_real_escape_string($conexao, $_POST['preco']);
        $paginas = mysqli_real_escape_string($conexao, $_POST['paginas']);
        $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);

        // Verifica se uma nova imagem foi enviada
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            // Processa o upload da imagem
            $imagem_nome = $_FILES['imagem']['name'];
            $imagem_tmp = $_FILES['imagem']['tmp_name'];
            
            // Move a imagem para o diretório desejado (substitua "caminho/diretorio" pelo diretório desejado)
            $caminho_imagem = "C:/xampp/htdocs/Livraria/Img/Livros/" . $imagem_nome;
            move_uploaded_file($imagem_tmp, $caminho_imagem);
            
            // Atualiza o caminho da imagem no banco de dados
            $query_imagem = "UPDATE produtos SET img = '$caminho_imagem' WHERE nome = '$titulo'";
            mysqli_query($conexao, $query_imagem);
        }

        // Realiza a alteração no banco de dados
        $query = "UPDATE produtos SET tema = '$tema', autor = '$autor', preco = '$preco', paginas = '$paginas', descricao = '$descricao' WHERE nome = '$titulo'";
        $resultado = mysqli_query($conexao, $query);

        if ($resultado) {
            // Redireciona o usuário para a página de origem
            header("Location: alterarProduto.php");
        } else {
            echo "Erro ao alterar produto: " . mysqli_error($conexao);
        }

        // Fecha a conexão com o banco de dados
        mysqli_close($conexao);
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
    
    <!-- Link Search -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
       
    <!-- Estilos de cada parte -->
    <link rel="stylesheet" href="Styles/StylePadrao.css">
    <link rel="stylesheet" href="Styles/StylePerfil.css">
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
                        <a href="cadastrarProduto.php" class="linhaMenuTopo">Cadastrar Produto</a>
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
        <h6>Alterar Produtos</h6>

        <?php
            // Inclua o arquivo de configuração do banco de dados
            include_once('config.php');

            // Execute a consulta SQL para obter os nomes dos livros
            $query = "SELECT nome FROM produtos";
            $resultado = mysqli_query($conexao, $query);

            // Inicie um array para armazenar os nomes dos livros
            $livros = array();

            // Verifique se há resultados
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                // Itere sobre os resultados e adicione os nomes dos livros ao array
                while ($livro = mysqli_fetch_assoc($resultado)) {
                    $livros[] = $livro['nome'];
                }
            }

            // Converta o array em formato JSON
            $livros_json = json_encode($livros);
        ?>

        <form action="alterarProduto.php" method="POST" enctype="multipart/form-data">
            <div class="boxBusca" id="tags">
                <input type="search" name="pesquisarLivros" id="pesquisarLivros" placeholder="Pesquise o livro..." class="pesquisar" autocomplete="on">
                <button class="btnBuscarLupa" type="submit" name="submit"> <!-- Adicione o atributo name aqui -->
                    <img src="Img/Icones/Lupa.png" alt="Lupa" class="imgLupa">
                </button>
            </div>
        </form>

        <?php
            // Verifique se o formulário de pesquisa foi enviado
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                // Obtenha o título do livro pesquisado
                $titulo_pesquisado = $_POST['pesquisarLivros'];

                // Faça uma consulta ao banco de dados para encontrar o livro com base no título
                $query = "SELECT * FROM produtos WHERE nome LIKE '%$titulo_pesquisado%'";
                $resultado = mysqli_query($conexao, $query);

                // Verifique se algum livro foi encontrado
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    // Preencha os campos do formulário com os detalhes do primeiro livro encontrado
                    $livro_encontrado = mysqli_fetch_assoc($resultado);
                    $titulo = $livro_encontrado['nome'];
                    $tema = $livro_encontrado['tema'];
                    $autor = $livro_encontrado['autor'];
                    $preco = $livro_encontrado['preco'];
                    $paginas = $livro_encontrado['paginas'];
                    $img = $livro_encontrado['img'];
                    $descricao = $livro_encontrado['descricao'];
                } else {
                    // Se nenhum livro foi encontrado, defina os campos do formulário como vazios
                    $titulo = '';
                    $tema = '';
                    $autor = '';
                    $preco = '';
                    $paginas = '';
                    $descricao = '';
                    echo "Livro não encontrado.";
                }
            } else {
                // Se o formulário não foi enviado, defina os campos do formulário como vazios
                $titulo = '';
                $tema = '';
                $autor = '';
                $preco = '';
                $paginas = '';
                $descricao = '';
                $img = 'Insira.png';
            }
        ?>

        <form action="alterarProduto.php" method="POST" enctype="multipart/form-data">
            <div class="itensbox">
                <div class="bordaImg" value="">
                    <label class="txtImg">
                        <img src="Img/Livros/<?php echo $img; ?>" alt="" class="imagemProd" id="receberImg">
                        <input type="file" id="enviarImg" name="imagem" accept="image*/"/>
                    </label>
                </div>

                <div class="infoLivro">
                    <label for="titulo" class="lblTitleLivro">Título: </label>
                    <input type="text" id="titulo" name="titulo" class="titulo" value="<?php echo $titulo; ?>"><br>

                    <label for="tema" class="lblTema">Tema: </label>
                    <input type="text" id="tema" name="tema" class="tema" value="<?php echo $tema; ?>"><br>

                    <label for="autor" class="lblAutor">Autor(a): </label>
                    <input type="text" id="autor" name="autor" class="autor" value="<?php echo $autor; ?>"><br>

                    <label for="preco" class="lblPreco">Preço(a): </label>
                    <input type="number" step="0.01" id="preco" name="preco" class="preco" value="<?php echo $preco; ?>"><br>

                    <label for="paginas" class="lblPaginas">Nº de páginas: </label>
                    <input type="number" id="paginas" name="paginas" class="paginas" value="<?php echo $paginas; ?>">
                </div>
            </div>

            <div class="infoLivro2">
                <label for="descricao" class="lblDescricao">Descrição: </label><br>
                <textarea name="descricao" id="descricao" class="textDesc" value="" placeholder="Inclua aqui uma sinopse ou um trecho da obra."><?php echo $descricao; ?></textarea>
            </div>

            <button type="submit" name="submitAlterar" class="concluirCad">Alterar Produto</button>


        </form>

        <script>
            $(function() {
                // Use o array JSON de nomes de livros como fonte de dados para o autocompletar
                var availableTags = <?php echo $livros_json; ?>;
                $("#pesquisarLivros").autocomplete({
                    source: availableTags,
                    select: function(event, ui) {
                        var livroSelecionado = ui.item.value;
                        carregarInformacoesLivro(livroSelecionado);
                    }
                });
            });

            function carregarInformacoesLivro(nomeLivro) {
                // Fazer uma solicitação AJAX para obter as informações do livro com base no nome
                $.ajax({
                    url: 'obterInformacoesLivro.php',
                    type: 'GET',
                    data: { nome: nomeLivro },
                    success: function(response) {
                        // Preencher os campos do formulário com as informações do livro
                        var livro = JSON.parse(response);
                        $('#titulo').val(livro.nome);
                        $('#tema').val(livro.tema);
                        $('#autor').val(livro.autor);
                        $('#preco').val(livro.preco);
                        $('#paginas').val(livro.paginas);
                        $('#descricao').val(livro.descricao);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        </script>

        
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