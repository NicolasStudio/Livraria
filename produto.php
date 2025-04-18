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
    <link rel="stylesheet" href="Styles/StyleCarrinho.css">
    <link rel="stylesheet" href="Styles/StyleCarrossel.css">
    <link rel="stylesheet" href="Styles/StyleFooter.css">
    <link rel="stylesheet" href="Styles/StyleProduto.css">
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

    <h1>Bem vindo(a), ao novo e maior site de livros digitais do Brasil!</h1>

        <!-- Carrinho -->
        <div class="carrinho">
            <div class="carrinhoBola" id="imgKart" onclick="abrirfechar()">
                <div class="notificacao"></div>
                <img src="Img/Icones/Carrinho.png" alt="carrinho de compras" class="imgCarrinho">
            </div>
        
            <div class="carrinhoProdutos" >
            <div class="fecharcarrinho" onclick="abrirfechar2()"> X </div>
                    <h2 class="titleCarrinho">Carrinho de Compras</h2>
                <div class="containerCarrinho">
                </div>
        
                <div class="finalizarCarrinho">
                    <p class="preçoTotal">Total: R$ <span class="price"> 0,00</span></p>
                    <button class="finalizar" onclick="comprarProdutos()"> Comprar</button>
                </div>
            </div>
        </div>

        <?php
            // Verifica se o parâmetro 'nome' foi passado na URL
            if (isset($_GET['nome'])) {
                // Obtém o nome do produto da URL
                $nomeProduto = urldecode($_GET['nome']);

                // Inclua seu arquivo de configuração do banco de dados
                include_once('config.php');

                // Consulta o banco de dados para obter as informações do produto com base no nome
                $query = "SELECT * FROM produtos WHERE nome = '$nomeProduto'";
                $result = mysqli_query($conexao, $query);

                // Verifica se a consulta retornou algum resultado
                if ($result && mysqli_num_rows($result) > 0) {
                    // Obtém os dados do produto
                    $produto = mysqli_fetch_assoc($result);

                    // Exibe as informações do produto na página
        ?>
                    <div class="boxProduct">
                        <div class="boxImgProduto">
                            <img src="<?php echo 'Img/Livros/' . $produto['img']; ?>" alt="<?php echo $produto['nome']; ?>" class="">
                            <div class="boxInfoProduto">
                                <h4 class="titleLivro"><?php echo $produto['nome']; ?></h4>
                                <p class="autorLivro"><b>Autor(a): </b><?php echo $produto['autor']; ?></p>
                                <p><b>Tema: </b><?php echo $produto['tema']; ?></p>
                                <p><b>Preço: </b><?php 
                                                        $valorFormatado = number_format($produto['preco'], 2, ',', '.');
                                                        echo $valorFormatado;
                                ?></p>
                                <p><b>Páginas: </b><?php echo $produto['paginas']; ?></p>
                                <!-- Adicione outros campos conforme necessário -->
                            </div>
                        </div>
                        <details class="descricao">
                            <summary class="summaryDesc">Descrição:</summary>
                            <p><?php echo $produto['descricao']; ?></p>
                        </details>
                    </div>
        <?php
                } else {
                    // Se não houver nenhum produto com o nome especificado, exibe uma mensagem de erro
                    echo "<p>Produto não encontrado.</p>";
                }
            } else {
                // Se o parâmetro 'nome' não foi passado na URL, exibe uma mensagem de erro
                echo "<p>Nome do produto não especificado.</p>";
            }
        ?>

        <section class="sectionUm">
            <?php
                // Inclua seu arquivo de configuração do banco de dados e realize a consulta para obter os produtos de aventura
                include_once('config.php');
                $consultaMaisVendidos = mysqli_query($conexao, "SELECT * FROM produtos ORDER BY vendas DESC LIMIT 12");
            ?>

            <div class="container-xl">
                <div class="row">
                    <div class="col-md-12">

                        <h6>Livros <b>Mais vendidos</b></h6>
                        <div id="carrosselMaisVendidos" class="carousel slide" data-ride="carousel" data-interval="0">

                            <!-- Indicadores de carrossel-->
                            <ol class="carousel-indicators">
                                <?php
                                // Contar o número total de itens
                                $totalItens = mysqli_num_rows($consultaMaisVendidos);
                                // Calcular o número total de indicadores necessários
                                $totalIndicadores = ceil($totalItens / 4);
                                // Gerar os indicadores
                                for ($i = 0; $i < $totalIndicadores; $i++) {
                                    $active = ($i == 0) ? 'active' : '';
                                    echo '<li data-target="#carrosselMaisVendidos data-slide-to="' . $i . '" class="' . $active . '"></li>';
                                }
                                ?>
                            </ol>

                            <!-- Embrulho para itens do carrossel -->
                            <div class="carousel-inner">
                                <?php
                                // Loop para preencher os itens do carrossel
                                $indiceItem = 0;
                                while ($produto = mysqli_fetch_assoc($consultaMaisVendidos)) {
                                    // Verificar se é o primeiro item do grupo
                                    if ($indiceItem % 4 == 0) {
                                        $active = ($indiceItem == 0) ? ' active' : '';
                                        echo '<div class="item carousel-item' . $active . '"><div class="row">';
                                    }
                                    ?>
                                    <!-- Item -->
                                    <div class="col-sm-3">
                                        <div class="thumb-wrapper">
                                            <div class="img-box">
                                                <img src="<?php echo 'Img/Livros/' . $produto['img']; ?>" class="img-fluid" alt="<?php echo $produto['nome']; ?>">
                                            </div>
                                            <div class="thumb-content">
                                            <h4><a href="produto.php?nome=<?php echo urlencode($produto['nome']); ?>"><?php echo $produto['nome']; ?></a></h4>
                                                <p class="item-price">
                                                <strike>  
                                                    <?php
                                                        $valor = ($produto['preco'] / 12) + $produto['preco'];
                                                        $valorFormatado = number_format($valor, 2, ',', '.');
                                                        echo $valorFormatado;
                                                    ?> 
                                                </strike> 
                                                    <b>
                                                        R$ <?php
                                                            $preco = $produto['preco']; 
                                                            $precoFormatado = number_format($preco, 2, ',', '.');
                                                            echo $precoFormatado;
                                                        ?>
                                                    </b></p>
                                                <a href="#" class="btn btn-primary adicionar-carrinho"
                                                data-nome="<?php echo $produto['nome']; ?>"
                                                data-preco="<?php echo $produto['preco']; ?>"
                                                data-img="<?php echo 'Img/Livros/' . $produto['img']; ?>">Adicionar ao Carrinho</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    // Verificar se é o último item do grupo
                                    if ($indiceItem % 4 == 3 || $indiceItem == $totalItens - 1) {
                                        echo '</div></div>';
                                    }
                                    $indiceItem++;
                                }
                                ?>
                            </div>

                            <!-- Controle do Carrossel -->
                            <a class="carousel-control-prev" href="#carrosselMaisVendidos" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="carousel-control-next" href="#carrosselMaisVendidos" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <br><br><br>

            <br><br><br>

            
        </section>

        <script>
            // Adicionar um ouvinte de evento de clique para todos os links "Adicionar ao Carrinho"
            var botoesAdicionar = document.querySelectorAll('.adicionar-carrinho');
            botoesAdicionar.forEach(function(botao) {
                botao.addEventListener('click', function(evento) {
                    evento.preventDefault(); // Impede o comportamento padrão do link

                    // Recupere o nome, o preço e a imagem do livro associados a este link
                    var nomeLivro = this.getAttribute('data-nome');
                    var precoLivro = parseFloat(this.getAttribute('data-preco'));
                    var imgLivro = this.getAttribute('data-img');

                    // Chame a função adicionarAoCarrinho com as informações do livro
                    adicionarAoCarrinho(nomeLivro, precoLivro, imgLivro);
                });
            });
        </script>
</body>

<!-- Footer -->
<section id="contatos">
    <footer class="rodape">
        <h1 class="titleRodape"> Alguma dúvida?</h1>
        <p class="txtRodape"> Obrigado pela sua atenção, aqui vai alguns dos meus contatos:</p>
        <p class="txtRodape"> Email: nicolasalmeida1@yahoo.com</p>
        <p class="txtRodape"> WhatsApp: (11) 94137-7733</p>
        <div class="boxRodapeImg">
            <a href="https://www.linkedin.com/in/nicolas-almeida-376284244/"> <img class="redeImg" src="Img/Icones/linkedin2.png" alt="ERRO"> </a>
            <a href="https://github.com/NicolasStudio"> <img class="redeImg" src="Img/Icones/github2.png" alt="ERRO"> </a>
            <a href="https://www.instagram.com/nicolas29sousa/"> <img class="redeImg" src="Img/Icones/instagram2.png" alt="ERRO"> </a>
            <a href="https://www.facebook.com/nicolas.almeida.1042/"> <img class="redeImg" src="Img/Icones/facebook2.png" alt="ERRO"> </a>
            <a href="https://api.whatsapp.com/send?phone=11941377733"> <img class="redeImg" src="Img/Icones/whatsapp.png" alt="ERRO"> </a>
        </div>
        <p class="txtRodape">  ©Nicolas de Almeida Sousa. Todos os direitos Reservados.</p>   
          
            <center class="linkDireitosAutorais">
                <a href="https://www.flaticon.com/br/icones-gratis/linkedin" title="linkedin ícones">Linkedin ícones criados por IconsBox - Flaticon</a> //
                <a href="https://www.flaticon.com/br/icones-gratis/facebook" title="facebook ícones">Facebook ícones criados por justicon - Flaticon</a> //
                <a href="https://www.flaticon.com/br/icones-gratis/logotipo-do-instagram" title="logotipo do instagram ícones">Logotipo do instagram ícones criados por justicon - Flaticon</a> //
                <a href="https://www.flaticon.com/br/icones-gratis/github" title="github ícones">Github ícones criados por Freepik - Flaticon</a> //
                <a href="https://www.flaticon.com/br/icones-gratis/compra" title="compra ícones">Compra ícones criados por adrianadam - Flaticon</a> //
                <a href="https://www.flaticon.com/br/icones-gratis/seta" title="seta ícones">Seta ícones criados por Freepik - Flaticon</a> // 
                <a href="https://www.flaticon.com/br/icones-gratis/olho" title="olho ícones">Olho ícones criados por Creatype - Flaticon</a> //
                <a href="https://www.flaticon.com/br/icones-gratis/olho" title="olho ícones">Olho ícones criados por Gregor Cresnar - Flaticon</a>
            </center>
    </footer>
</section>

</html>