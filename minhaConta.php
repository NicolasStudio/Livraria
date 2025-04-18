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
        $stmt = $conexao->prepare("SELECT nome, admin, telefone, dtNasc, cep, estado, cidade, bairro, rua, numero FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado_usuario = $stmt->get_result();

        // Verifica se o resultado da consulta não está vazio
        if ($resultado_usuario->num_rows > 0) {
            // Extrai o nome do usuário
            $row_usuario = $resultado_usuario->fetch_assoc();
            $nome_usuario = $row_usuario['nome'];
            $telefone_usuario = $row_usuario['telefone'];
            $dtNasc_usuario = $row_usuario['dtNasc'];
            $cep_usuario = $row_usuario['cep'];
            $estado_usuario = $row_usuario['estado'];
            $cidade_usuario = $row_usuario['cidade'];
            $bairro_usuario = $row_usuario['bairro'];
            $rua_usuario = $row_usuario['rua'];
            $numero_usuario = $row_usuario['numero'];

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

    // Verifica se o formulário de atualização foi enviado
    if(isset($_POST['atualizarDados'])){  

        // Obtém os dados atualizados do formulário
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $telefone = mysqli_real_escape_string($conexao, $_POST['telefone']);
        $dtNasc = mysqli_real_escape_string($conexao, $_POST['dtNasc']);
        $cep = mysqli_real_escape_string($conexao, $_POST['cep']);
        $rua = mysqli_real_escape_string($conexao, $_POST['rua']);
        $numero = mysqli_real_escape_string($conexao, $_POST['numero']);
        $bairro = mysqli_real_escape_string($conexao, $_POST['bairro']);
        $cidade = mysqli_real_escape_string($conexao, $_POST['cidade']);
        $estado = mysqli_real_escape_string($conexao, $_POST['estado']);

        // Obtém o ID do usuário logado
        $email_logado = $_SESSION['email'];
        $stmt = $conexao->prepare("SELECT id_usuario FROM login WHERE email = ?");
        $stmt->bind_param("s", $email_logado);
        $stmt->execute();
        $resultado_login = $stmt->get_result();

        // Verifica se o resultado da consulta não está vazio
        if ($resultado_login->num_rows > 0) {
            $row_login = $resultado_login->fetch_assoc();
            $id_usuario = $row_login['id_usuario'];

            // Atualiza os dados do usuário na tabela 'usuarios'
            $stmt = $conexao->prepare("UPDATE usuarios SET nome=?, telefone=?, dtNasc=?, cep=?, rua=?, numero=?, bairro=?, cidade=?, estado=? WHERE id=?");
            $stmt->bind_param("sssssssssi", $nome, $telefone, $dtNasc, $cep, $rua, $numero, $bairro, $cidade, $estado, $id_usuario);
            if ($stmt->execute()) {
                // Dados atualizados com sucesso
                echo "Dados atualizados com sucesso.";
                header("Refresh: 0");
            } else {
                // Erro ao atualizar os dados
                echo "Erro ao atualizar os dados.";
            }
        } else {
            echo "ID de usuário não encontrado.";
        }
    }

    if (isset($_POST['atualizarSenha'])) {
        // Obtém o email do usuário logado
        $email_logado = $_SESSION['email'];

        // Consulta para obter a senha do usuário logado
        $stmt = $conexao->prepare("SELECT senha FROM `login` WHERE email = ?");
        $stmt->bind_param("s", $email_logado);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verifica se o resultado da consulta não está vazio
        if ($resultado->num_rows > 0) {
            // Extrai a senha do usuário
            $row = $resultado->fetch_assoc();
            $senha_usuario = $row['senha'];

            // Obtém a senha antiga digitada pelo usuário
            $senha_antiga_digitada = mysqli_real_escape_string($conexao, $_POST['senhaAntiga']);
            
            // Verifica se a senha antiga digitada pelo usuário corresponde à senha armazenada
            if ($senha_usuario === $senha_antiga_digitada) {
                // Verifica se as novas senhas digitadas são iguais e não estão vazias
                $nova_senha = mysqli_real_escape_string($conexao, $_POST['senha']);
                $confirmacao_senha = mysqli_real_escape_string($conexao, $_POST['senha2']);
                if ($nova_senha !== '' && $nova_senha === $confirmacao_senha) {


                    $stmt = $conexao->prepare("UPDATE `login` SET senha = ? WHERE email = ?");
                    $stmt->bind_param("ss", $nova_senha, $email_logado);
                    if ($stmt->execute()) {
                        echo "Senha atualizada com sucesso.";
                        // Redirecionar ou fazer qualquer outra ação após a atualização da senha
                    } else {
                        echo "Erro ao atualizar a senha.";
                    }
                } else {
                    echo "As senhas não correspondem ou estão vazias.";
                }
            } else {
                echo "Senha antiga incorreta.";
            }
        } else {
            // Email do usuário não encontrado na tabela
            echo "Usuário não encontrado.";
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
                        <a href="meusPedidos.php" class="linhaMenuTopo">Meus Pedidos</a>
                        <a href="#" class="linhaMenuTopo">Suporte</a>
                        <a href="sair.php"  class="linhaMenuTopo">Sair</a>
                    </div>
                </li>
                </h3>
            </div>
        </nav>
    </header>

    <div class="boxDados">
        <h2>Minha Conta</h2>
        
        <form action="minhaConta.php" method="POST">
            <div class="dadosPerfil">
                <label for="" class="lblPerfil">Nome completo:</label>
                <input type="text" name="nome" class="nomeC" value="<?php echo $nome_usuario; ?>"><br>

                <label for="" class="lblPerfil">TELL:</label>
                <input type="text" class="tel" id="telefone" name="telefone" maxlength="15" oninput="mascararTelefone(this)" value="<?php echo $telefone_usuario; ?>"><br>

                <label for="" class="lblPerfil"> Data de nascimento:</label>
                <input type="date" class="dtnsc" name="dtNasc" value="<?php echo $dtNasc_usuario; ?>"><br>
            </div>

            <h4>Enderço: </h4>
            <div class="dadosPerfil">
                <label for="" class="lblPerfil">CEP:</label>
                <input type="text" id="cep" name="cep" maxlength="9" oninput="buscarCEP(this.value)"class="cep" value="<?php echo $cep_usuario; ?>"><i> *preencha primeiro o CEP</i><br>

                <label for="" class="lblPerfil">Longradouro/Rua:</label>
                <input type="text" id="logradouro" name="rua" class="logradouro" value="<?php echo $rua_usuario; ?>">

                <label for="" class="lblPerfil">Nº: </label>
                <input type="text" class="num" name="numero" value="<?php echo $numero_usuario; ?>"><br>

                <label for="" class="lblPerfil">Bairro: </label>
                <input type="text" id="bairro" name="bairro" class="bairro" value="<?php echo $bairro_usuario; ?>"><br>

                <label for="" class="lblPerfil">Cidade: </label>
                <input ttype="text" id="cidade" name="cidade" class="cidade" value="<?php echo $cidade_usuario; ?>">

                <label for="" class="lblPerfil">Estado: </label>
                <input type="text" id="estado" name="estado"  class="estado" value="<?php echo $estado_usuario;?>"><br>
            </div>

                    <button class="salvarAlteracoes" type="submit" name="atualizarDados">Salvar Alterações</button> <br>


        </form>

        <details class="detailsSenha">
                <summary>Alterar senha:</summary>

            <form action="minhaConta.php" method="POST">
                <div class="dadosPerfil">
                    <div class="campoSenha">
                        <label for="senhaAntiga" class="lblPerfil">Senha antiga:</label>
                        <div class="inputSenha">
                                                        <input type="password" id="senhaAntiga" value="" name="senhaAntiga" class="senhaAntiga">
                            <span onclick="togglePasswordVisibility(this.querySelector('img'))"><img src="Img/Icones/close.png" alt="" class="iconSenha"></span>
                        </div>
                    </div>
            
                    <div class="campoSenha">
                        <label for="senhaNova" class="lblPerfil">Senha Nova:</label>
                        <div class="inputSenha">
                            <input type="password" id="senhaNova" value="" class="senhaNova" name="senha">
                            <span onclick="togglePasswordVisibility(this.querySelector('img'))"><img src="Img/Icones/close.png" alt="" class="iconSenha"></span>
                        </div>
                    </div>

                    <div class="campoSenha">
                        <label for="senhaNova2" class="lblPerfil">Repita:</label>
                        <div class="inputSenha">
                            <input type="password" id="senhaNova2" value="" class="senhaNova2" name="senha2">
                            <span onclick="togglePasswordVisibility(this.querySelector('img'))"><img src="Img/Icones/close.png" alt="" class="iconSenha"></span>
                        </div>
                    </div>

                    <button class="alterarSenha" type="submit" name="atualizarSenha">Alterar Senha</button>
                </div>
            </form>
        </details>
        

    </div>



</html>