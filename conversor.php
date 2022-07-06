<?php

require_once "config.php";
require_once "classes/db.php";
require_once "classes/html.php";
require_once "funcoes.php";

$moeda_base = 'USD';
$moeda_calculo = 'BRL';
$moeda = "$moeda_base-$moeda_calculo";

$result = tras_moedas($moeda);
extract($result);

$valores_convertidos = valores_convertidos($valor_moeda);

date_default_timezone_set('America/Sao_Paulo');
$atualizacao = date('d/m/Y H:i:s');

$variavel = dados_grafico($moeda, 30);
extract($variavel);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/icon/favicon.ico">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/conversor.css">
    <script src="assets/js/bootstrap.min.js"></script>

    <title>Conversor</title>
</head>
<body>

    <header>

        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: rgb(0 0 0 / 10%) !important;">
            
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav minha_nav">
                        <a class="nav-link" aria-current="page" href="#" onclick="emdesenvolvimento()">Home</a>
                        <a class="nav-link" href="#" onclick="emdesenvolvimento()">Cotações</a>
                        <a class="nav-link" href="#" onclick="emdesenvolvimento()">Criptomoedas</a>
                        <a class="nav-link active" href="conversor.php">Conversor</a>
                        <a class="nav-link" href="#" onclick="emdesenvolvimento()">Inflação</a>
                        <a class="nav-link" href="#" onclick="emdesenvolvimento()">Noticias</a>
                        <a class="nav-link" href="#" onclick="emdesenvolvimento()">Institucional</a>
                        <a class="nav-link" href="#" onclick="emdesenvolvimento()">Mais</a>
                    </div>
                    
                </div>
            </div>
        </nav>

    </header>

    <section>

        <div id="card_conversor">

            <div class="card_titulo">
                Conversor de moeda
            </div>
            
            <div class="linha margin-top15">

                <div class="form-control linha_input"> 
                <?= $div_absolute ?>
                    <div class="div_input select_ativado">
                    
                        <img id='img1' class="card_img" src="assets/img/moedas/USD.png" alt="simbolo do BITCOIN">
                        <span id='span1' value='<?= $cod_moeda1 ?>'><?= $nome_moeda1 ?> (<?= $cod_moeda1 ?>)</span>
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </div>
                    <div>
                        <input id='valor1' type="text" class="form-control my_input" value="1,00">
                    </div>
                </div>
<!--
                <button class="btn btn-light my_btn" onclick="converter()">
                    <i class="fa fa-exchange" aria-hidden="true" style="font-size: 21px;"></i>
                </button>
-->
                <div class="form-control linha_input"> 
                    <div class="div_input">
                        <img class="card_img" src="assets/img/moedas/BRL.png" alt="simbolo do BITCOIN">
                        <span><?= $nome_moeda2 ?> (<?= $cod_moeda2 ?>)</span>
                    </div>
                    <div>
                        <input id='valor2' type="text" class="form-control my_input" value="<?= $valor_compra ?>">
                    </div>
                </div>
                
            </div>
            <div class="margin-top30">
                Faça conversões entre 5 tipos de moedas.
            </div>
            <div id="valor_conversor" class="card_titulo margin-top15">
                <?= "$valor_compra $cod_moeda2" ?>
            </div>
            <div id="ultima_atualizacao" class="margin-top15">
                * Última atualização: <?= $atualizacao ?>
            </div>
        </div>

        <div class="grafico_do_dolar container">
            <h1 id='titulo_grafico'><?= $titulo_grafico ?></h1>
            <div style="display: flex;align-items: center;width: auto;justify-content: space-between;">
                <div style="margin-left: 15px;">
                    <h2 id="titulo_numerico_grafico" style="color: rgb(17, 51, 102); font-size: 40px; color: rgb(17, 51, 102);"><?= $valor_moeda_grafico ?></h2>
                    <div>
                        <h5 id='variacao_grafico' style="color:<?= $cor_variaca ?>;"><?= "$valor_variacao ($porcentagem_variacao%)"?></h5>
                    </div>
                </div>

                <div class="my_group_button">

                    <button class="btn btn-light my_button" >7D</button>
                    <button class="btn btn-light my_button">15D</button>
                    <button class="btn btn-light my_button">1M</button>
                    <button class="btn btn-light my_button">6M</button>
                    <button class="btn btn-light my_button">1A</button>
                    
                </div>

            </div>
            <div id='canvas_aqui'>
                <canvas id="canvas"></canvas>
            </div>
        </div>

        <div id="valores_convertidos">
            <div class="titulo">
                <h2>Valores Convertidos</h2>
            </div>

            <table class="table table-hover text-center" style="width: 80%;">
                <thead>
                    <tr style="border-bottom: black;">
                      <th id='valor1_valores_convertidos' scope="col"><?= $nome_fragmentado1[0] ?></th>
                      <th id='valor2_valores_convertidos'scope="col"><?= $nome_fragmentado2[0] ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                      <td id='c1l1'><?= $cod_moeda1 ?> 1,00</td>
                      <td id='c2l1'><?= $cod_moeda2. ' '. $valores_convertidos['1'] ?></td>
                    </tr>
                    <tr>
                      <td id='c1l2'><?= $cod_moeda1 ?> 100,00</td>
                      <td id='c2l2'><?= $cod_moeda2. ' '. $valores_convertidos['100'] ?></td>
                    </tr>
                    <tr>
                        <td id='c1l3'><?= $cod_moeda1 ?> 500,00</td>
                        <td id='c2l3'><?= $cod_moeda2. ' '. $valores_convertidos['500'] ?></td>
                    </tr>
                    <tr>
                        <td id='c1l4'><?= $cod_moeda1 ?> 1.000,00</td>
                        <td id='c2l4'><?= $cod_moeda2. ' '. $valores_convertidos['1000'] ?></td>
                    </tr>
                    <tr>
                        <td id='c1l5'><?= $cod_moeda1 ?> 3.000,00</td>
                        <td id='c2l5'><?= $cod_moeda2. ' '. $valores_convertidos['3000'] ?></td>
                    </tr>
                    <tr>
                        <td id='c1l6'><?= $cod_moeda1 ?> 5.000,00</td>
                        <td id='c2l6'><?= $cod_moeda2. ' '. $valores_convertidos['5000'] ?></td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div id="conteudo">

            <div class="blocoConteudo">
                <h2 class="tituloConteudo">
                    Funcionamento e importancia do conversor de moedas
                </h2>
                <p class="textoConteudo">
                    O conversor irá transformar o valor da moeda do país de origem para o valor correspondente da moeda do país de destino. Basta indicar o país de destino da conversão, escrever o valor desejado para ser transformado no equivalnte em real e clicar no botão de conversão: em poucos segundos o valor será mostrado!
                </p>
                <p class="textoConteudo">
                    A conversão de moeda ou taxa de câmbio indica qual o custo da moeda em relação a outra após a sua conversão. É utilizada para identificar em quanto está sendo estimado a moeda no dia e qual o valor da moeda local correspondente a moeda do país de destino escolhido.<br>
                    A importância de entender como funciona a conversão da moeda está na necessidade de uso em situações cotidianas. Por exemplo: ao adquirir produtos e serviços estrangeiros, ao planejar viagens internacionais, realizar operações de comércio exterior ou até mesmo para receber por algum serviço prestado a empresas de outro país.     
                </p>
            </div>

            <div class="blocoConteudo">
                <h2 class="tituloConteudo">
                    Impost X Conversão
                </h2>
                <p class="textoConteudo">
                    A taxa cobrada nas operações de câmbio feitas no Brasil é o iof – imposto sobre operações financeiras - imposto federal, instituído pelo código tributário nacional de 1966. O percentual de cada operação pode variar de acordo com a atividade realizada.  
                </p>
                <p class="textoConteudo">
                    São elas:<br>
                    •	Compra de moeda em espécie;<br>
                    •	Remessa internacional;<br>
                    •	Recarga de cartão internacional pré-pago.
                </p>

                <h3 class="subtituloConteudo">
                    Moeda em espécie
                </h3>
                <p class="textoConteudo">
                    Ao realizar compras de moedas em espécie, ou seja, moedas físicas e palpáveis em sua forma não virtual, incidirá a alíquota de 1,1% sobre o valor adquirido.
                </p>

                <h3 class="subtituloConteudo">
                    Remessa internacional
                </h3>
                <p class="textoConteudo">
                    Uma remessa ou transferência internacional consiste no envio de dinheiro, através de transferência eletrônica, de um país para outro. Para isso é necessário fazer o câmbio das moedas de origem e destino, antes de realizar a transferência em propriamente dita.<br>
                    Em uma remessa internacional, podem ser cobradas duas alíquotas diferentes de iof: 0,38% ou 1,1% sobre o valor total da quantia a ser enviada. Isso porque no caso de transferências internacionais, a alíquota pode variar de acordo com o beneficiário da operação.
                </p>

                <h3 class="subtituloConteudo">
                    Remessa internacional e Código Swift
                </h3>
                <p class="textoConteudo">
                    Por falar em remessas internacionais e taxas de câmbio internacionais, existe uma outra tarifa que deve ser considerada nas operações de envio de dinheiro para o exterior. É o caso do código swift ou bic, como também é chamado.<br>
                    Se você não faz ideia do que se trata essa tarifa, aqui vai uma explicação prática: para garantir a segurança entre transferências internacionais, a associação internacional de bancos possui uma rede de comunicação própria.<br>
                    Nesse caso, o que garante a segurança dessa rede de comunicação é o fato de que cada banco possui o seu código único. Isso permite que uma instituição financeira internacional confirme os dados necessários para efetuar as operações com agilidade e segurança.<br>
                    Acontece que todo esse processo tem um custo de transmissão de dados e logística. Custo esse que é adicionado ao valor final de cada transferência internacional.<br>
                    Ou seja, se o envio for feito a terceiros, incidirá 0,38% de iof na operação; se o envio for feito para conta da mesma titularidade, incidirá a alíquota de 1,1%.
                </p>

                <h3 class="subtituloConteudo">
                    Recarga de cartão internacional pré-pago
                </h3>
                <p class="textoConteudo">
                    Também há incidência de iof em recargas de cartão de viagem ou cartão internacional pré-pago. Nessa operação, a alíquota correspondente é de 6,38% sobre o valor total de cada recarga.
                </p>
            </div>

            <div class="blocoConteudo">
                <h2 class="tituloConteudo">
                    Taxa de câmbio
                </h2>
                <p class="textoConteudo">
                    Chegando ao fim da lista de taxas de câmbio internacionais temos, por último, o custo mais óbvio, o do câmbio. A taxa de câmbio é cobrada sempre que houver a necessidade de conversão entre duas ou mais moedas estrangeiras. Mas você sabia que existem duas cotações diferentes de câmbio? Entenda cada uma delas:
                </p>

                <h3 class="subtituloConteudo">
                    Câmbio comercial
                </h3>
                <p class="textoConteudo">
                    Essa taxa é a que serve de referência no mercado. Ou seja, o valor base utilizado em grandes transações comerciais entre países, exportações e importações.<br>
                    Quando você, pessoa física, compra dólar para viajar, não é com essa taxa que as conversões são feitas. Sendo assim, não adianta fazer o seu checklist de viagem com esse valor em mente. É preciso ter em mente o câmbio turismo.
                </p>

                <h3 class="subtituloConteudo">
                    Câmbio turistico
                </h3>
                <p class="textoConteudo">
                    O câmbio turismo é a cotação utilizada por instituições financeiras e também casas de câmbio para vender dólares, euros ou qualquer outra moeda ao público de pessoa física.<br>
                    Essa taxa tem o câmbio comercial como base, mas adiciona a ele custos com distribuição, armazenamento de papel moeda entre outros, de forma arbitrária. Por isso, pode variar de acordo com o provedor do serviço.
                </p>
            </div>

        </div>

        


    </section>

    <footer>

    <script>
        var valor_moeda_grafico = '<?= $valor_moeda_grafico ?>';
        var valor_variacao = '<?= $valor_variacao ?>';
        var porcentagem_variacao = '<?= $porcentagem_variacao ?>';
        var cor_variaca = '<?= $cor_variaca ?>';

        var labels_grafico =  <?= $array_datas ?>;
        var data_grafico =  <?= $array_venda ?>;

        var titulo_grafico = '<?= $nome_fragmentado1[0] ?>';

        const div_absolute = `<?= $div_absolute ?>`;

    </script>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/jquery.maskMoney.min.js"></script>
    <script src="assets/js/setup.js"></script>
    <script src="assets/js/conversor.js"></script>

    <script src="https://kit.fontawesome.com/6ad2ca7995.js" crossorigin="anonymous"></script>
    <!--
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    -->
   </footer>
    

</body>
</html>