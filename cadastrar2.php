<?php
// Verifica se os dados foram enviados por POST
session_start();

// Verifica se os dados foram enviados por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once('config.php');

    // Obtém os dados da sessão
    $email = $_SESSION['email'];
    $senha1 = $_SESSION['senha1'];
    $senha2 = $_SESSION['senha2'];

    // Verifica se o email já está em uso
    $result_email_existente = mysqli_query($conexao, "SELECT email FROM `login` WHERE email = '$email'");
    if(mysqli_num_rows($result_email_existente) == 0) {
        // Recupera os demais dados do formulário
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $cpf = mysqli_real_escape_string($conexao, $_POST['cpf']);
        $telefone = mysqli_real_escape_string($conexao, $_POST['telefone']);
        $dtNasc = mysqli_real_escape_string($conexao, $_POST['dtNasc']);
        $cep = mysqli_real_escape_string($conexao, $_POST['cep']);
        $estado = mysqli_real_escape_string($conexao, $_POST['estado']);
        $cidade = mysqli_real_escape_string($conexao, $_POST['cidade']);
        $bairro = mysqli_real_escape_string($conexao, $_POST['bairro']);
        $rua = mysqli_real_escape_string($conexao, $_POST['rua']);
        $numero = mysqli_real_escape_string($conexao, $_POST['numero']);

        // Insere os demais dados na tabela 'usuarios'
        $resultadoUsuarios = mysqli_query($conexao, 
            "INSERT INTO `usuarios` (nome, cpf, telefone, dtNasc, cep, estado, cidade, bairro, rua, numero) 
            VALUES ('$nome', '$cpf', '$telefone', '$dtNasc', '$cep', '$estado', '$cidade', '$bairro', '$rua', '$numero')");

        if ($resultadoUsuarios) {
            // Obtenha o ID do usuário recém-inserido
            $idUsuario = mysqli_insert_id($conexao);

            // Insere o email e a senha na tabela 'login', vinculando ao id_usuario
            $resultadoLogin = mysqli_query($conexao, 
                "INSERT INTO `login` (id_usuario, email, senha) 
                VALUES ('$idUsuario', '$email', '$senha1')");

            if ($resultadoLogin) {
                // Consulta se o usuário é administrador na tabela 'usuarios'
                $queryAdmin = "SELECT admin FROM usuarios WHERE id = $idUsuario";
                $resultAdmin = mysqli_query($conexao, $queryAdmin);

                if ($resultAdmin) {
                    $rowAdmin = mysqli_fetch_assoc($resultAdmin);
                    $isAdmin = $rowAdmin['admin'];

                    // Verifica se o usuário é um administrador
                    if ($isAdmin == 1) {
                        // Se o usuário é um administrador, redireciona para uma página específica
                        header("Location: pagina_administrador.php");
                    } else {
                        // Se não for um administrador, redireciona para a página inicial
                        header("Location: index.php");
                    }
                } else {
                    // Se houver um erro na consulta, redireciona para uma página de erro
                    header("Location: pagina_de_erro.php");
                }
                exit; // Certifique-se de sair após o redirecionamento
            } else {
                echo "Erro ao cadastrar usuário.";
            }
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    } else {
        echo "Este e-mail já está em uso. Por favor, cadastre-se primeiro.";
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

        <div class="segudosDados">
<form action="cadastrar2.php" method="POST" onsubmit="return validarFormulario()">
    <label for="nome" class="lblTitle2">Nome completo:</label>
    <input type="text" id="nome" name="nome" class="nomeCad" required><br>

    <label for="cpf" class="lblTitle3">CPF:</label>
    <input type="text" id="cpf" name="cpf" maxlength="14" oninput="mascararCPF(this)" class="cpfCad" required><br>

    <label for="telefone" class="lblTitle3">Telefone:</label>
    <input type="text" id="telefone" name="telefone" maxlength="15" oninput="mascararTelefone(this)" class="telCad" required><br>

    <label for="dtNasc" class="lblTitle2">Data de nasc.:</label>
    <input type="date" id="dtNasc" name="dtNasc" class="dtnscCad" required><br><br><br>

    <hr>
    <!-- ENDEREÇO -->
    <!-- Dentro do segundo formulário -->
    <label for="cep" class="lblTitle3">CEP:</label>
    <input type="text" id="cep" name="cep" maxlength="9" oninput="buscarCEP(this.value)" class="cep" required><i> *preencha primeiro o CEP</i><br>

    <label for="estado" class="lblTitle3">Estado:</label>
    <input type="text" id="estado" name="estado" class="estado" required><br>

    <label for="cidade" class="lblTitle3">Cidade:</label>
    <input type="text" id="cidade" name="cidade" class="cidade" required><br>

    <label for="bairro" class="lblTitle3">Bairro:</label>
    <input type="text" id="bairro" name="bairro" class="bairro" required><br>

    <label for="rua" class="lblTitle3">Rua:</label>
    <input type="text" id="rua" name="rua" class="logradouro" required><br>

    <label for="numero" class="lblTitle3">Nº:</label>
    <input type="text" id="numero" name="numero" class="num" required><br>

    <button type="submit" name="concluir" class="concluirCad">Concluir</button>
</form>

<script>
    function validarFormulario() {
        // Aqui você pode adicionar lógica de validação usando JavaScript
        // Por exemplo, validar se o CPF é válido, se todos os campos foram preenchidos, etc.
        return true; // Retorne true se o formulário estiver válido, caso contrário, retorne false
    }
</script>


        </div>
</div>




</html>