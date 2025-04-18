<?php
    session_start();
    include_once('config.php');

    // Verifica se o usuário está logado
    if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header('Location: entrar.php');
        exit();
    }

    $email_logado = $_SESSION['email'];

    // Obtém o ID do usuário
    $stmt = $conexao->prepare("SELECT id_usuario FROM login WHERE email = ?");
    $stmt->bind_param("s", $email_logado);
    $stmt->execute();
    $resultado_login = $stmt->get_result();

    if ($resultado_login->num_rows > 0) {
        $row_login = $resultado_login->fetch_assoc();
        $id_usuario = $row_login['id_usuario'];

        // Obtém o nome do usuário
        $stmt = $conexao->prepare("SELECT nome FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado_usuario = $stmt->get_result();

        if ($resultado_usuario->num_rows > 0) {
            $row_usuario = $resultado_usuario->fetch_assoc();
            $nome_usuario = $row_usuario['nome'];
        } else {
            $nome_usuario = "Usuário";
        }
    } else {
        $nome_usuario = "Usuário";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W3-Books</title>
    <link rel="icon" type="image/png" sizes="16x16"  href="Img/IconBook.png">

    <!-- jQuery e Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <!-- QR Code -->
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/qrious.min.js"></script>

    <!-- Estilos -->
    <link rel="stylesheet" href="Styles/StylePadrao.css">
    <link rel="stylesheet" href="Styles/StyleCarrinho.css">
    <link rel="stylesheet" href="Styles/StyleCarrossel.css">
    <link rel="stylesheet" href="Styles/StyleFooter.css">
    <link rel="stylesheet" href="Styles/StyleProduto.css">

    <!-- Scripts -->
    <script src="Js/Script.js"></script>
    
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
                <button class="btnCadastrar" onclick="cadastrar()">Cadastrar</button>
            </div>
            <div class="usuarioLogado" style="display: none;">
                <h3 class="saudacao">Olá,
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn"> 
                            <span class="usuario"><?php echo htmlspecialchars($nome_usuario); ?></span>
                            <img src="Img/Icones/Seta.png" alt="Seta" style="width: 20px; height: 20px;">
                        </a>
                        <div class="dropdown-content">
                            <a href="minhaConta.php" class="linhaMenuTopo">Minha Conta</a>
                            <a href="meusPedidos.php" class="linhaMenuTopo">Meus Pedidos</a>
                            <a href="#" class="linhaMenuTopo">Suporte</a>
                            <a href="sair.php" class="linhaMenuTopo">Sair</a>
                        </div>
                    </li>
                </h3>
            </div>
        </nav>
        <script>
            // Mostrar/esconder botões dependendo do login
            const logado = <?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>;
            if (logado) {
                document.querySelector('.logar').style.display = 'none';
                document.querySelector('.usuarioLogado').style.display = 'block';
            }
        </script>
    </header>

    <div class="boxCompraProdutos">
        <h6><b>Pagamento</b></h6>
        <ul id="lista-carrinho"></ul>

        <div class="container" id="qr-container">
            <div id="qr-code">
                <img src="#" alt="QR Code" id="qr-code-img">
            </div>
        </div>

            <p>Total: <strong id="total-compra">R$ 0,00</strong></p>
            <button onclick="confirmarPagamento()">Confirmar Pagamento</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const chavePix = "nicolasalmeida1@yahoo.com";
            const nome = "NICOLAS";
            const cidade = "SAOPAULO";

            function formatTag(id, value) {
                const size = String(value.length).padStart(2, '0');
                return `${id}${size}${value}`;
            }

            function gerarPayloadPix(chave, nome, cidade, valor) {
                const payloadFormatIndicator = formatTag("00", "01");
                const merchantAccountInfo = formatTag("26",
                    formatTag("00", "BR.GOV.BCB.PIX") +
                    formatTag("01", chave)
                );
                const merchantCategoryCode = formatTag("52", "0000");
                const transactionCurrency = formatTag("53", "986");
                const transactionAmount = formatTag("54", valor);
                const countryCode = formatTag("58", "BR");
                const merchantName = formatTag("59", nome);
                const merchantCity = formatTag("60", cidade);
                const additionalDataField = formatTag("62", formatTag("05", "***"));

                let payload = (
                    payloadFormatIndicator +
                    merchantAccountInfo +
                    merchantCategoryCode +
                    transactionCurrency +
                    transactionAmount +
                    countryCode +
                    merchantName +
                    merchantCity +
                    additionalDataField
                );

                payload += "6304";

                const crc = crc16(payload).toUpperCase();
                return payload + crc;
            }

            function crc16(str) {
                let crc = 0xFFFF;
                for (let i = 0; i < str.length; i++) {
                    crc ^= str.charCodeAt(i) << 8;
                    for (let j = 0; j < 8; j++) {
                        if ((crc & 0x8000) !== 0) {
                            crc = (crc << 1) ^ 0x1021;
                        } else {
                            crc <<= 1;
                        }
                        crc &= 0xFFFF;
                    }
                }
                return crc.toString(16).padStart(4, '0');
            }

            function gerarQRCode(valor) {
                const payload = gerarPayloadPix(chavePix, nome, cidade, valor);
                const qrCodeImg = document.getElementById("qr-code-img");
                const container = document.getElementById("qr-container");

                qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(payload)}`;
                qrCodeImg.onload = () => {
                    container.classList.add("active");
                    console.log("QR Code carregado com sucesso!");
                };
            }

            let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
            let nomes = carrinho.map(item => item.nome);

            fetch('get_preco.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ produtos: nomes })
            })
            .then(response => response.json())
            .then(dados => {
                if (dados.erro) {
                    console.error('Erro:', dados.erro);
                    return;
                }

                let total = 0;

                dados.forEach(produto => {
                    let preco = parseFloat(produto.preco);
                    total += preco;
                });

                document.getElementById("total-compra").textContent = `R$ ${total.toFixed(2)}`;
                gerarQRCode(total.toFixed(2));
            })
            .catch(erro => console.error('Erro ao buscar preços:', erro));

            window.confirmarPagamento = function () {
                alert("Pagamento confirmado! Obrigado 🙌");
            }
        });
    </script>



    <footer class="rodape" id="contatos">
        <h1 class="titleRodape">Alguma dúvida?</h1>
        <p class="txtRodape">Obrigado pela sua atenção, aqui vão alguns dos meus contatos:</p>
        <p class="txtRodape">Email: nicolasalmeida1@yahoo.com</p>
        <p class="txtRodape">WhatsApp: (11) 94137-7733</p>
        <div class="boxRodapeImg">
            <a href="https://www.linkedin.com/in/nicolas-almeida-376284244/"><img class="redeImg" src="Img/Icones/linkedin2.png" alt="LinkedIn"></a>
            <a href="https://github.com/NicolasStudio"><img class="redeImg" src="Img/Icones/github2.png" alt="GitHub"></a>
            <a href="https://www.instagram.com/nicolas29sousa/"><img class="redeImg" src="Img/Icones/instagram2.png" alt="Instagram"></a>
            <a href="https://www.facebook.com/nicolas.almeida.1042/"><img class="redeImg" src="Img/Icones/facebook2.png" alt="Facebook"></a>
            <a href="https://api.whatsapp.com/send?phone=11941377733"><img class="redeImg" src="Img/Icones/whatsapp.png" alt="WhatsApp"></a>
        </div>
        <p class="txtRodape">© Nicolas de Almeida Sousa. Todos os direitos reservados.</p>
        <center class="linkDireitosAutorais">
            <!-- Créditos de ícones omitidos por brevidade -->
        </center>
    </footer>
</body>
</html>
