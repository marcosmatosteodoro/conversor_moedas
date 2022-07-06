var oldVal1
var oldVal2

$('.my_button').on('click', function(){
    var intervaloTexto = $(this).text()
    var moeda = $('#span1').attr('value')

    if(intervaloTexto == '7D') intervalo = 7;
    else if(intervaloTexto == '15D') intervalo = 15;
    else if(intervaloTexto == '1M') intervalo = 30;
    else if(intervaloTexto == '6M') intervalo = 180;
    else if(intervaloTexto == '1A') intervalo = 365;

    altera_tudo('3', moeda, intervalo)
})

$('.select_ativado').on('click', function(){
    $('.div_absolute').toggle()
})

$('.div_input_absolute').on('click', function(){
    var texto_spam = $(this).find('span').text()
    var value_spam = $(this).find('span').attr('value')
    var img = $(this).find('img').attr('src')
    var moeda = $(this).attr('moeda')

    substitui_seletor(value_spam, texto_spam, img, moeda)

    $('.div_absolute').toggle()
})

function substitui_seletor(value,texto, img, moeda){
    $('#span1').text(texto)
    $('#span1').attr('value', value)
    $('#img1').attr('src', img)

    altera_tudo('1', moeda)
}

function altera_tudo(chamada, moeda, intervalo=30){
    var link =`https://economia.awesomeapi.com.br/json/daily/${moeda}-BRL/${intervalo}`;

    $.ajax({
        url: link,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(restult) {
        var resultado = restult[0]

        var nome = resultado.name
        nome = nome.split("/")
        nome = nome[0].split(" ")
        nome = nome[0]

        var valor_moeda = resultado.ask
        var cod_moeda = resultado.code
        var variacao = resultado.varBid
        var porcentagem = resultado.pctChange

        alterar_conversor_moedas(chamada, valor_moeda)
        alterar_grafico(nome, valor_moeda, variacao, porcentagem, intervalo, restult)
        alterar_valores_convertidos(valor_moeda, cod_moeda, nome)

    })


}

function alterar_conversor_moedas(chamada, moeda, atualizaDate=1, retorno=0){

    var mu,mt,re,r,s,dt,to,dd,mm,yy,ho,min,seg;

    if(atualizaDate == 1){
        to = new Date();
        dd = String(to.getDate()).padStart(2, '0');
        mm = String(to.getMonth() + 1).padStart(2, '0');
        yy = to.getFullYear();
        ho = to.getHours().toString();
        min = to.getMinutes().toString();
        seg = to.getSeconds().toString();

        ho = ho.length == 1 ? '0' + ho : ho;
        min = min.length == 1 ? '0' + min : min;
        seg = seg.length == 1 ? '0' + seg : seg;

        to = dd + '/' + mm + '/' + yy + ' ' + ho + ':' + min + ':' + seg;

        dt = '* Última atualização: ' + to
        $('#ultima_atualizacao').text(dt)
    }
    

    if(chamada == 1){
        mu = $('#valor1').val()
        mu = mu == '' ? '0,00' : mu;

        mo = mu.split(",")
        mo[0] = mo[0].replace('.','')
        mu = mo[0] + '.' + mo[1]
        mt = mu == '' ? 0 : parseFloat(mu)

        s = moeda * mt
        
        s = s.toFixed(2)
        r = s.toString()

        re = r.replace('.',',')
        re = re + ' BRL'

        s = s.toString()
        si = s.split(".")

        si1 = si[0].length > 3 ? formata(si[0]) : si[0]
        si2 = si[1]

        se = si1 + ',' + si2

        $('#valor2').val(se)
        $('#valor_conversor').text(re)
        
        if(retorno == 1)
            return se;

    }else if(chamada == 2){
        mu = $('#valor2').val()
        mu = mu == '' ? '0,00' : mu;

        mo = mu.split(",")
        mo[0] = mo[0].replace('.','')
        mu = mo[0] + '.' + mo[1]
        mt = mu == '' ? 0 : parseFloat(mu)

        s = mt / moeda

        s = s.toFixed(2)

        s = s.toString()
        si = s.split(".")

        si1 = si[0].length > 3 ? formata(si[0]) : si[0]
        si2 = si[1]

        se = si1 + ',' + si2

        r = mu.replace('.',',')
        re = r + ' BRL'
                
        $('#valor1').val(se)
        $('#valor_conversor').text(re)

        if(retorno == 1)
            return se;
    }
    
}

function alterar_grafico(nome, valor_moeda, variacao, porcentagem, intervalo, json){
    var titulo,va,cor;
    var novos_valor = []
    var novas_datas = []

    json.forEach(function(item, indice) {
        var timestamp,date,dia,mes,ano;

        timestamp = parseInt(item.timestamp)
        date = new Date( timestamp * 1000);
        dia = String(date.getDate()).padStart(2, '0');
        mes = String(date.getMonth() + 1).padStart(2, '0');
        ano = date.getFullYear();

        novas_datas[indice] = dia + '/' + mes + '/' + ano ;
        novos_valor[indice] = item.ask

        
    })

    va = valor_moeda.toString()
    va = va.replace('.',',')

    titulo = 'Preço do ' + nome.toLowerCase() + ' nos últimos '+ intervalo +' dias'

    cor = variacao > 0 ? 'green' : 'red';
    vag = `${variacao} (${porcentagem}%)`;

    myChart.data.datasets[0].label = nome // Titulo
    myChart.data.datasets[0].data = novos_valor // eixo y (valores)
    myChart.data.labels = novas_datas // eixo x (datas) // :todo


    $('#variacao_grafico').text(vag)
    $('#variacao_grafico').css('color',cor)
    
    $('#titulo_numerico_grafico').text(va)
    $('#titulo_grafico').text(titulo)

    myChart.update();
}

function alterar_valores_convertidos(valor_moeda, cod_moeda, nome){

    var c2l1 = multiplica_transforma(valor_moeda, 1)
    var c2l2 = multiplica_transforma(valor_moeda, 100)
    var c2l3 = multiplica_transforma(valor_moeda, 500)
    var c2l4 = multiplica_transforma(valor_moeda, 1000)
    var c2l5 = multiplica_transforma(valor_moeda, 3000)
    var c2l6 = multiplica_transforma(valor_moeda, 5000)

    $('#valor1_valores_convertidos').text(nome)

    $('#c1l1').text(cod_moeda + ' 1,00')
    $('#c1l2').text(cod_moeda + ' 100,00')
    $('#c1l3').text(cod_moeda + ' 500,00')
    $('#c1l4').text(cod_moeda + ' 1.000,00')
    $('#c1l5').text(cod_moeda + ' 3.000,00')
    $('#c1l6').text(cod_moeda + ' 5.000,00')

    $('#c2l1').text(c2l1)
    $('#c2l2').text(c2l2)
    $('#c2l3').text(c2l3)
    $('#c2l4').text(c2l4)
    $('#c2l5').text(c2l5)
    $('#c2l6').text(c2l6)

}

function multiplica_transforma( valor_moeda, multiplicador){

    var cod_moeda = 'BRL'

    var r
    var v1
    var v2
    var c
    var r

    r = valor_moeda * multiplicador

    r = r.toFixed(2)
    r = r.split(".")

    v1 = r[0].toString()
    v2 = r[1].toString()

    c = v1.length 

    if(c > 3)
        r1 = v1.substr(0,c-3) + "." + v1.substr(-3)

    else
        r1 = v1

    r2 = cod_moeda + ' ' + r1 + ',' + v2

    return r2
}

function converter(){
    moeda = $('#titulo_numerico_grafico').text()
    moeda = moeda.replace(',','.')
    moeda = parseFloat(moeda)

    alterar_conversor_moedas(1, moeda)
}

setInterval(function() { verificaSeMudou() }, 500);

function verificaSeMudou(){

    if(typeof oldVal1 == undefined || typeof oldVal2 == undefined){
        oldVal1 = $('#valor1').val()
        oldVal2 = $('#valor2').val()
    }

    var newVal1 = $('#valor1').val()
    var newVal2 = $('#valor2').val()

    var mudouVal1 = newVal1 != oldVal1 ? 1 : 0;
    var mudouVal2 = newVal2 != oldVal2 ? 1 : 0;

    if(mudouVal1 == 0 && mudouVal2 == 0)
        return

    if(newVal1 == '') newVal1 = '0,00';
    if(newVal2 == '') newVal2 = '0,00';

    let moeda = $('#titulo_numerico_grafico').text()
    moeda = moeda.replace(',','.')
    moeda = moeda == '' ? 0 : parseFloat(moeda)

    if(mudouVal1 == 1){
        oldVal2 = alterar_conversor_moedas(1, moeda, atualizaDate=0, retorno=1)
        oldVal1 = newVal1
    }else if(mudouVal2 == 1){
        oldVal1 = alterar_conversor_moedas(2, moeda, atualizaDate=0, retorno=1)
        oldVal2 = newVal2
    }
}

function emdesenvolvimento(){
    alert('Está página se encontra em desenvolvimento!')
}


function formata(numeroPatametro){

    var cont, repeticoes, n, result;

    numero = numeroPatametro.toString()
    cont = numero.length

    repeticoesNumero = cont / 3

    repeticoesString = repeticoesNumero.toString().split(".")
    repeticoes = repeticoesString[0]

    n = 0
    result = ''

    for(var i = repeticoes; i >= 0; i-= 1){
        n = (cont + n) > 3 ? n-=3 : cont + n
        if(n < 0){

            if(result == ''){
                result = numero.substr(n, 3)
            }
            else{
                result = numero.substr( n, 3) + '.' + result
            }
        }else if(n > 0){
            result = numero.substr( 0, n) + '.' + result
        }
    }
    return result;
}
