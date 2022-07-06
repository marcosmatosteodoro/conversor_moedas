<?php

function tras_moedas($moeda){
    
    $json = "https://economia.awesomeapi.com.br/json/last/$moeda";

    $json_file = file_get_contents($json);   
    $json_str = json_decode($json_file, true);

    $var = str_replace('-', '', $moeda);

    $nome = explode('/', $json_str[$var]['name']);
    $nome_fragmentado1 = explode(' ', $nome[0]);
    $nome_fragmentado2 = explode(' ', $nome[1]);

    $nome_moeda1 = $nome[0];
    $nome_moeda2 = $nome[1];

    $cod_moeda1 = $json_str[$var]['code'];
    $cod_moeda2 = $json_str[$var]['codein'];

    $valor_moeda = round($json_str[$var]['ask'], 2);

    $valor_moeda_grafico = str_replace('.', ',',$json_str[$var]['ask']);
    $valor_compra = str_replace('.', ',',$valor_moeda);
    $valor_venda = str_replace('.', ',',round($json_str[$var]['bid'], 2));
    $valor_variacao = str_replace('.', ',',$json_str[$var]['varBid']);
    $porcentagem_variacao = str_replace('.', ',',$json_str[$var]['pctChange']);
    $cor_variaca = $porcentagem_variacao >= 0 ? 'green' : 'red';

    $result = [
        'nome' => $nome,
        'nome_fragmentado1' => $nome_fragmentado1,
        'nome_fragmentado2' => $nome_fragmentado2,
        'nome_moeda1' => $nome_moeda1,
        'nome_moeda2' => $nome_moeda2,
        'cod_moeda1' => $cod_moeda1,
        'cod_moeda2' => $cod_moeda2,
        'valor_moeda' => $valor_moeda,
        'valor_compra' => $valor_compra,
        'valor_venda' => $valor_venda,
        'valor_variacao' => $valor_variacao,
        'porcentagem_variacao' => $porcentagem_variacao,
        'cor_variaca' => $cor_variaca,
        'valor_moeda_grafico' => $valor_moeda_grafico,
    ];
    return $result;
}

function valores_convertidos($valor){

    for($i = 1; $i <= 6; $i++){

        if($i == 1) $numero = 1;
        elseif($i == 2) $numero = 100;
        elseif($i == 3) $numero = 500;
        elseif($i == 4) $numero = 1000;
        elseif($i == 5) $numero = 3000;
        elseif($i == 6) $numero = 5000;

        $multiplica = $numero * $valor;
        $multiplica = number_format($multiplica, 2);

        $separa = explode('.', $multiplica);

        $resultado = str_replace(',', '.', $separa[0]);
        $resultado .= ','.$separa[1];

        $result[$numero] = $resultado;
    }
    
    return $result;
}


function dados_grafico($moeda, $intervalo){
    $json = "https://economia.awesomeapi.com.br/json/daily/$moeda/$intervalo";

    $json_file = file_get_contents($json);   
    $json_str = json_decode($json_file, true);

    $venda = [];
    $data = [];

    $nm_moeda = explode(' ', $json_str[0]['name']);
    $nm_moeda = lcfirst($nm_moeda[0]);

    $nome = $json_str[0]['code'];

    foreach($json_str as $row){
        extract($row);
        array_push($venda, round($ask, 2));
        array_push($data, date('d/m/Y', $timestamp));
    }

    $venda = json_encode(array_reverse($venda));
    $data = json_encode(array_reverse($data));

    $result = [
        'array_datas' => $data,
        'array_venda' => $venda,
        'titulo_grafico' => "Preço do $nm_moeda nos últimos $intervalo dias"
    ];
    return $result;

}

$div_absolute = "<div class='form-control div_absolute'>

    <div class='div_input_absolute' style='cursor: pointer' moeda='USD'>
        <img class='card_img' src='assets/img/moedas/USD.png' alt='simbolo do BITCOIN'>
        <span value='USD'>Dólar Americano (USD)</span>
        
    </div>
    
    <div class='div_input_absolute' style='cursor: pointer' moeda='EUR'>
        <img class='card_img' src='assets/img/moedas/EUR.png' alt='simbolo do BITCOIN'>
        <span value='EUR'>Euro (EUR)</span>
        
    </div>
    
    <div class='div_input_absolute' style='cursor: pointer' moeda='CAD'>
        <img class='card_img' src='assets/img/moedas/CAD.png' alt='simbolo do BITCOIN'>
        <span value='CAD'>Dólar Canadense (CAD)</span>
        
    </div>
    
    <div class='div_input_absolute' style='cursor: pointer' moeda='GBP'>
        <img class='card_img' src='assets/img/moedas/GBP.png' alt='simbolo do BITCOIN'>
        <span value='GBP'>Libra Esterlina (GBP)</span>
        
    </div>
    
    <div class='div_input_absolute' style='cursor: pointer' moeda='CHF'>
        <img class='card_img' src='assets/img/moedas/CHF.png' alt='simbolo do BITCOIN'>
        <span value='CHF'>Franco Suíço (CHF)</span>
    </div>
</div>";