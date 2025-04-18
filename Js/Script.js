// FUNÇÕES BASICAS DO JS

// ABRIR E FECHAR CARRINHO DE COMPRAS
function abrirfechar() {
    var carrinhoBola = document.querySelector('.carrinhoBola');
    var carrinhoProdutos = document.querySelector('.carrinhoProdutos');

    if (carrinhoBola === 'none') {
        carrinhoBola.style.display = 'flex';
    } else {
        carrinhoBola.style.display = 'none';
        carrinhoProdutos.style.display = 'block';
    }
}

function abrirfechar2() {
    var carrinhoBola = document.querySelector('.carrinhoBola');
    var carrinhoProdutos = document.querySelector('.carrinhoProdutos');
    
    if (carrinhoProdutos === 'block') {
        carrinhoBola.style.display = 'none';

    } else {

        carrinhoBola.style.display = 'flex';
        carrinhoProdutos.style.display = 'none';
    }
}

// Mascaras
function mascararTelefone(input) {
    // Remover todos os caracteres que não sejam dígitos
    var digitos = input.value.replace(/\D/g, '');

    // Aplicar a máscara: (XX) XXXXX-XXXX
    if (digitos.length > 0) {
        if (digitos.length <= 2) {
            input.value = '(' + digitos;
        } else if (digitos.length <= 7) {
            input.value = '(' + digitos.substring(0, 2) + ') ' + digitos.substring(2);
        } else if (digitos.length <= 11) {
            input.value = '(' + digitos.substring(0, 2) + ') ' + digitos.substring(2, 7) + '-' + digitos.substring(7);
        } else {
            input.value = '(' + digitos.substring(0, 2) + ') ' + digitos.substring(2, 7) + '-' + digitos.substring(7, 11);
        }
    }
}

function mascararCPF(input) {
    // Remover todos os caracteres que não sejam dígitos
    var digitos = input.value.replace(/\D/g, '');

    // Aplicar a máscara: XXX.XXX.XXX-XX
    if (digitos.length > 0) {
        if (digitos.length <= 3) {
            input.value = digitos;
        } else if (digitos.length <= 6) {
            input.value = digitos.substring(0, 3) + '.' + digitos.substring(3);
        } else if (digitos.length <= 9) {
            input.value = digitos.substring(0, 3) + '.' + digitos.substring(3, 6) + '.' + digitos.substring(6);
        } else {
            input.value = digitos.substring(0, 3) + '.' + digitos.substring(3, 6) + '.' + digitos.substring(6, 9) + '-' + digitos.substring(9);
        }
    }
}

function mascararCEP(input) {
    // Remover todos os caracteres que não sejam dígitos
    var digitos = input.value.replace(/\D/g, '');

    // Aplicar a máscara: XXXXX-XXX
    if (digitos.length > 0) {
        if (digitos.length <= 5) {
            input.value = digitos;
        } else {
            input.value = digitos.substring(0, 5) + '-' + digitos.substring(5);
        }
    }
}

function togglePasswordVisibility(img) {
    var input = img.parentElement.previousElementSibling;
    if (input.type === "password") {
        input.type = "text";
        img.src = "Img/Icones/olho.png";
    } else {
        input.type = "password";
        img.src = "Img/Icones/close.png";
    }
}


// BUSCAR AUTOMATICO O CEP
async function buscarCEP(cep) {
    cep = cep.replace(/\D/g, '');
    
    if (cep.length === 8) {
        try {
            var response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            var data = await response.json();

            if (!data.erro) {
                preencherCamposEndereco(data);
            } else {
                limparCamposEndereco();
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
            alert('Erro ao buscar CEP. Por favor, tente novamente mais tarde.');
        }
    } else {
        limparCamposEndereco();
    }
}

function preencherCamposEndereco(data) {
    document.getElementById('logradouro').value = data.logradouro;
    document.getElementById('bairro').value = data.bairro;
    document.getElementById('cidade').value = data.localidade;
    document.getElementById('estado').value = data.uf;
}

function limparCamposEndereco() {
    document.getElementById('logradouro').value = '';
    document.getElementById('bairro').value = '';
    document.getElementById('cidade').value = '';
    document.getElementById('estado').value = '';
}

// REDIRECIONAMENTO
function home(){
    window.location.href = "index.php";
}

function entrar(){
    window.location.href = "entrar.php";
}

function cadastrar(){
    window.location.href = "cadastrar.php";
}

// Remover itens dos carrinhos
function removerItem(button) {
    // Navega até a div pai da div do botão e a remove
    var divProduto = button.parentNode.parentNode;

    // Obtém o nome do livro correspondente ao item a ser removido
    var nomeLivro = divProduto.querySelector(".titleLivro a").textContent;

    // Obtém o carrinho salvo no localStorage
    var carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Remove o item correspondente do carrinho
    carrinho = carrinho.filter(function(item) {
        return item.nome !== nomeLivro;
    });

    // Salva o carrinho atualizado no localStorage
    localStorage.setItem('carrinho', JSON.stringify(carrinho));

    // Remove o elemento HTML do produto do carrinho
    divProduto.remove();

    // Atualiza o preço total na página
    atualizarPrecoTotal();
}

// Adicionar itens dos carrinhos
function adicionarAoCarrinho(nomeLivro, precoLivro, imgLivro) {
    // Verificar se o item já está no carrinho
    var carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    var itemExistente = carrinho.find(function(item) {
        return item.nome === nomeLivro;
    });

    if (itemExistente) {


    } else {
        // Adicionar um novo item ao carrinho
        carrinho.push({
            nome: nomeLivro,
            preco: precoLivro,
            img: imgLivro
        });

        
    }

    // Salvar o carrinho atualizado no localStorage
    localStorage.setItem('carrinho', JSON.stringify(carrinho));

    // Atualizar a exibição do carrinho na página
    carregarCarrinho();
    atualizarPrecoTotal();
}

// Função para carregar o carrinho salvo no localStorage e exibir na página
function carregarCarrinho() {
    // Verificar se há um carrinho salvo no localStorage
    var carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Selecionar o container do carrinho
    var carrinhoProdutos = document.querySelector(".carrinhoProdutos .containerCarrinho");

    // Verificar se o elemento foi encontrado antes de tentar acessá-lo
    if (carrinhoProdutos) {
        // Limpar o conteúdo existente antes de adicionar os novos itens
        carrinhoProdutos.innerHTML = '';


        

        // Percorrer os itens do carrinho e adicionar ao conteúdo existente
        carrinho.forEach(function(item) {
            var divProduto = document.createElement("div");
            divProduto.className = "product";
            
            // Aplica a máscara de moeda ao preço
            var preco_formatado = item.preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        
            divProduto.innerHTML = `
                <img src="${item.img}" alt="${item.nome}" class="imgProduto">
                <div class="product-details">
                <p class="titleLivro"><a href="#" onclick="carregarDetalhesProduto(event, '${item.nome}')">${item.nome}</a></p>
                    <p class="price">${preco_formatado}</p> <!-- Preço formatado com máscara de moeda -->
                    <button class="remove" onclick="removerItem(this)">Remover do carrinho</button>
                </div>
            `;
            carrinhoProdutos.appendChild(divProduto);
            
        });
        atualizarPrecoTotal();
    } else {
    }
}

function carregarDetalhesProduto(event, nomeProduto) {
    // Impede o comportamento padrão do link
    event.preventDefault();

    // Redireciona para a página do produto com o nome do produto como parâmetro de consulta na URL
    window.location.href = `produto.php?nome=${encodeURIComponent(nomeProduto)}`;
}

// Chame a função carregarCarrinho() quando a página for carregada
document.addEventListener("DOMContentLoaded", function() {
    carregarCarrinho();
});

// Função para calcular o preço total do carrinho
function calcularPrecoTotal() {
    // Verificar se há um carrinho salvo no localStorage
    var carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Inicializar o preço total como zero
    var precoTotal = 0;

    // Percorrer os itens do carrinho e somar os preços de cada item
    carrinho.forEach(function(item) {
        precoTotal += parseFloat(item.preco);
    });

    // Retornar o preço total formatado com duas casas decimais
    return precoTotal.toFixed(2);
}

// Função para atualizar o preço total na página
function atualizarPrecoTotal() {
    // Selecionar o elemento do preço total
    var precoTotalElement = document.querySelector(".preçoTotal .price");

    // Calcular o preço total
    const precoTotal = calcularPrecoTotal();


    const precoTotalFormatado = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(precoTotal);

        // Atualizar o conteúdo do elemento do preço total com o preço total formatado
        // Remover o símbolo "R$" da formatação do preço total
    const precoTotalFormatadoSemSimbolo = precoTotalFormatado.replace('R$', '');

        // Atualizar o conteúdo do elemento do preço total sem o símbolo

    if (precoTotalElement) {
        precoTotalElement.textContent = precoTotalFormatadoSemSimbolo;
    } else {
    }

}

// Chame a função atualizarPrecoTotal() para exibir o preço total ao carregar a página
document.addEventListener("DOMContentLoaded", function() {
    atualizarPrecoTotal();
});




// -------------- PAGINA DE COMPRAS DE PRODUTOS --------------
// Função para enviar os dados do carrinho para comprarProdutos.php
function comprarProdutos() {
    var carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Verificar se o carrinho não está vazio antes de redirecionar
    if (carrinho.length > 0) {
        var carrinhoString = JSON.stringify(carrinho);
        var url = 'comprarProdutos.php?carrinho=' + encodeURIComponent(carrinhoString);
        window.location.href = url;
    } else {
        alert("Seu carrinho está vazio. Adicione itens antes de comprar.");
    }
}

function removerItem2(button) {
    // Navega até a div pai da div do botão e a remove
    var divProduto = button.parentNode;

    // Obtém o nome do livro correspondente ao item a ser removido
    var nomeLivro = divProduto.querySelector(".titleLivro").textContent;

    // Obtém o carrinho salvo no localStorage
    var carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Remove o item correspondente do carrinho
    carrinho = carrinho.filter(function(item) {
        return item.nome !== nomeLivro;
    });

    // Salva o carrinho atualizado no localStorage
    localStorage.setItem('carrinho', JSON.stringify(carrinho));

    // Remove o elemento HTML do produto do carrinho
    divProduto.remove();

    // Atualiza a barra de endereço com os dados do carrinho atualizados
    atualizarURLCarrinho(carrinho);

    // Atualiza o preço total na página (se necessário)
    atualizarPrecoTotal2();
    
}

function atualizarPrecoTotal2() {
    // Selecionar o elemento do preço total
    var precoTotalElement = document.querySelector(".valorTotal2 .price2");

    // Calcular o preço total
    const precoTotal = calcularPrecoTotal();

    const precoTotalFormatado = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(precoTotal);

    // Atualizar o conteúdo do elemento do preço total com o preço total formatado
    // Remover o símbolo "R$" da formatação do preço total
    const precoTotalFormatadoSemSimbolo = precoTotalFormatado.replace('R$', '');

    // Verificar se o elemento foi encontrado
    if (precoTotalElement) {
        precoTotalElement.textContent = precoTotalFormatadoSemSimbolo;
    } else {
        console.error("Elemento do preço total não encontrado no DOM.");
    }
}

document.addEventListener("DOMContentLoaded", function() {
    atualizarPrecoTotal2();
});

function atualizarURLCarrinho(carrinho) {
    // Cria uma string de consulta com os dados atualizados do carrinho
    var carrinhoString = encodeURIComponent(JSON.stringify(carrinho));

    // Atualiza a URL com os dados do carrinho
    history.pushState({}, '', '?carrinho=' + carrinhoString);
}

function atualizarTotal(index, novaQuantidade) {
    // Obtenha o carrinho do localStorage
    var carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Atualize a quantidade do produto no carrinho
    if (index >= 0 && index < carrinho.length) {
        carrinho[index].quantidade = novaQuantidade;
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
    } else {
        console.error("Índice do produto inválido: ", index);
    }

    // Acesse o novo valor da quantidade
    console.log("Nova quantidade do produto:", novaQuantidade);
}


function limparLocalStorage() {
    localStorage.clear(); // Limpa o localStorage
    console.log("localStorage limpo."); // Log para verificar se a limpeza foi realizada com sucesso
}

// Defina o intervalo para 5 minutos (300.000 milissegundos)
var intervaloLimpeza = setInterval(limparLocalStorage, 100000); // 300000 ms = 5 minutos

// Adicione um evento visibilitychange para pausar e retomar a limpeza quando a página estiver visível ou não
document.addEventListener("visibilitychange", function() {
    if (document.visibilityState === 'hidden') {
        // Pausar a limpeza quando a página estiver oculta
        clearInterval(intervaloLimpeza);
        console.log("Limpeza do localStorage pausada.");
    } else {
        // Retomar a limpeza quando a página estiver visível
        intervaloLimpeza = setInterval(limparLocalStorage, 300000);
        console.log("Limpeza do localStorage retomada.");
    }
});


// PAGAR
document.querySelector("button").addEventListener("click", function () {
    const total = parseFloat(document.querySelector(".price2").innerText.replace(",", "."));
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const chavePix = "w3books-pix-" + Date.now(); // Pode ser gerada dinamicamente

    // Preparar dados para envio
    const produtos = carrinho.map(item => ({
        id: item.idproduto,
        quantidade: item.quantidade,
        descricao: item.nome // ou item.titulo
    }));

    fetch("registrar_venda.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            valor_total: total,
            produtos: produtos,
            chave_pix: chavePix
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "sucesso") {
            alert("Pedido registrado com sucesso! Nº: " + data.pedido);
            localStorage.removeItem("carrinho");
            window.location.href = "meusPedidos.php"; // Ou página de sucesso
        } else {
            alert("Erro ao registrar o pedido.");
        }
    })
    .catch(err => {
        console.error("Erro ao registrar:", err);
    });
});
