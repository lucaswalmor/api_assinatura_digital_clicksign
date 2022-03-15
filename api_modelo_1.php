<?php 

    /*
        Qualquer duvida ou implementação necessitada de acordo com a sua necessidade, acessar a documentação da clicksign
        Link da documentação = https://developers.clicksign.com/docs
    */
    
    // Verificar se todos os dados forão preenchidos
    if (isset($_POST['nome']) && 
    isset($_POST['telefone']) && 
    isset($_POST['cpf']) && 
    isset($_POST['email'])) {

        /*
            Os dados solicitados deverão ser os mesmo ao qual foi solcitado no documento enviado no modelo da clicksign
        */
        $nome = $_POST['nome'];
        $telefone = trim($_POST['telefone']);
        $cpf = $_POST['cpf'];
        $email = $_POST['email'];

        function criarsignatario() 
        {
            /*
                Está função ira gerar o signatário do cliente ao qual sera enviado o documento para assinatura
                ----------------------------------------------------------------------------------
                url de endpoint da API de acordo com a solicitação atual
                {{SEU_TOKEN}} É encontrado aonde foi gerado o token da API no site da clicksign
            */

            // $url  = 'https://sandbox.clicksign.com/api/v1/signers?access_token={{SEU_TOKEN}}';
            global $nome;
            global $telefone;
            global $cpf;
            global $email;

            // Array de dados para criação do signatário
            $signers = array(
                "signer" => [
                    "email" => $email,
                    "phone_number" => $telefone,
                    "auths" => [
                        "email"
                    ],
                    "name" => $nome,
                    "documentation" => $cpf,
                    "birthday" => "1993-11-21",
                    "has_documentation" => true,
                    "delivery" => "email",
                    "selfie_enabled" => false,
                    "handwritten_enabled" => false,
                    "official_document_enabled" => false,
                    "liveness_enabled" => false,
                    "facial_biometrics_enabled" => false
                ]
            );
            
            // Transforma o array de dados do signatário em json
            $dados = json_encode($signers);

            // Header attribute
            $header = array("cache-control: no-cache", 
                'Content-Type: application/json; charset=utf-8',
            );

            // Start the CURL
            $CURL = curl_init();

            // Configure the CURL
            curl_setopt($CURL, CURLOPT_URL, $url);
            curl_setopt($CURL, CURLOPT_POSTFIELDS, $dados);
            curl_setopt($CURL, CURLOPT_HTTPHEADER, $header);
            curl_setopt($CURL, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($CURL, CURLOPT_ENCODING, "");
            curl_setopt($CURL, CURLOPT_MAXREDIRS, 10);
            curl_setopt($CURL, CURLOPT_TIMEOUT, 30);
            curl_setopt($CURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, 'POST');

            $status = curl_getinfo($CURL, CURLINFO_HTTP_CODE);
            $err = curl_error($CURL);
            
            $resultado = curl_exec($CURL);

            // Close the CURL
            curl_close($CURL);

            return $resultado;
        }

        function criarTemplate() 
        {

            /*
                Está função ira criar o documento com os dados que foi fornecido pelo ou do cliente
                ----------------------------------------------------------------------------------
                O template será o segundo passo da API, onde será enviado os dados do cliente para geração do documento
                -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                https://sandbox.clicksign.com/api/v1/templates/:key/documents?access_token={{acess_token}}, sendo :key a chave do template na Clicksign e o acess_token é o token gerado da API
                -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                os campos dentro do array de data deverão estar entre chaves dentro do documento colocado na plataforma da clicksign, 
                sendo assim {{exemplo_variavel}} dentro do ponto aonde deverá ser colocado os dados do cliente 
            */

            $dados_signatario = json_decode(criarsignatario());
            
            /*
                url de endpoint da API de acordo com a solicitação atual
                {{SEU_TOKEN}} É encontrado aonde foi gerado o token da API no site da clicksign
                Basta adicionar o token e remover o comentario para usar a url
            */

            // $url  = 'https://sandbox.clicksign.com/api/v1/templates/2bcb23e6-45b3-42c2-b8fb-cebd234bfba1/documents?access_token={{SEU_TOKEN}}';

            // Array de dados para criação do signatário
            $document = array(
                "document" => [
                    "path" => "/Modelos/teste.docx",
                    "template" => [
                        "data" => [
                            "nome" => $dados_signatario->signer->name,
                            "endereco" => $dados_signatario->signer->email,
                            "cpf" => $dados_signatario->signer->documentation
                        ]
                    ]
                ]
            );
            
            // Transforma o array de dados do signatário em json
            $dados = json_encode($document);

            // Header attribute
            $header = array("cache-control: no-cache", 
                'Content-Type: application/json; charset=utf-8',
            );

            // Start the CURL
            $CURL = curl_init();

            // Configure the CURL
            curl_setopt($CURL, CURLOPT_URL, $url);
            curl_setopt($CURL, CURLOPT_POSTFIELDS, $dados);
            curl_setopt($CURL, CURLOPT_HTTPHEADER, $header);
            curl_setopt($CURL, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($CURL, CURLOPT_ENCODING, "");
            curl_setopt($CURL, CURLOPT_MAXREDIRS, 10);
            curl_setopt($CURL, CURLOPT_TIMEOUT, 30);
            curl_setopt($CURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, 'POST');

            $status = curl_getinfo($CURL, CURLINFO_HTTP_CODE);
            $err = curl_error($CURL);
            
            $resultado = curl_exec($CURL);

            // Close the CURL
            curl_close($CURL);

            return $resultado;

        }

        function addSignatarioTemplate() 
        {
            /*
                Está função ira adicionar o cliente ao documento que foi gerado
                ----------------------------------------------------------------------------------
                url de endpoint da API de acordo com a solicitação atual

                {{SEU_TOKEN}} É encontrado aonde foi gerado o token da API no site da clicksign

                Basta adicionar o token e remover o comentario para usar a url
            */
            // $url  = 'https://sandbox.clicksign.com/api/v1/lists?access_token={{SEU_TOKEN}}';

            $dados_signatario = json_decode(criarsignatario());
            $dados_template = json_decode(criarTemplate());
            $mensagem = 'Esta mensagem e um texto de ok';

            // Array de dados para criação do signatário
            $add_signers = array(
                "list" => [
                    "document_key" => $dados_template->document->key,
                    "signer_key" => $dados_signatario->signer->key,
                    "sign_as" => "sign",
                    "refusable" => true,
                    "group" => 0,
                    "message" => $mensagem
                ]
            );
            
            // Transforma o array de dados do signatário em json
            $dados = json_encode($add_signers);

            // Header attribute
            $header = array("cache-control: no-cache", 
                'Content-Type: application/json; charset=utf-8',
            );

            // Start the CURL
            $CURL = curl_init();

            // Configure the CURL
            curl_setopt($CURL, CURLOPT_URL, $url);
            curl_setopt($CURL, CURLOPT_POSTFIELDS, $dados);
            curl_setopt($CURL, CURLOPT_HTTPHEADER, $header);
            curl_setopt($CURL, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($CURL, CURLOPT_ENCODING, "");
            curl_setopt($CURL, CURLOPT_MAXREDIRS, 10);
            curl_setopt($CURL, CURLOPT_TIMEOUT, 30);
            curl_setopt($CURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, 'POST');

            $status = curl_getinfo($CURL, CURLINFO_HTTP_CODE);
            $err = curl_error($CURL);
            
            $resultado = curl_exec($CURL);

            // Close the CURL
            curl_close($CURL);

            return $resultado;
        }
        
        function sendEmail() 
        {
            /*
                Está função ira enviar o email com o link para o cliente final assinar o documento
                ----------------------------------------------------------------------------------
                url de endpoint da API de acordo com a solicitação atual

                {{SEU_TOKEN}} É encontrado aonde foi gerado o token da API no site da clicksign

                Basta adicionar o token e remover o comentario para usar a url
            */

            // $url  = 'https://sandbox.clicksign.com/api/v1/notifications?access_token={{SEU_TOKEN}}';

            $finalizar_documento = json_decode(addSignatarioTemplate());

            /*
                A mensagem aparecerá no corpo do email para o cliente
                ela podera ser descrita da forma que você necessitar
            */
            $mensagem = 'Digite aqui sua mensagem';

            // Array de dados para criação do signatário
            $add_signers = array(
                "request_signature_key" => $finalizar_documento->list->request_signature_key,  
                "message" => $mensagem,
                "url" => $finalizar_documento->list->url
            );
            
            // Transforma o array de dados do signatário em json
            $dados = json_encode($add_signers);

            // Header attribute
            $header = array("cache-control: no-cache", 
                'Content-Type: application/json; charset=utf-8',
            );

            // Start the CURL
            $CURL = curl_init();

            // Configure the CURL
            curl_setopt($CURL, CURLOPT_URL, $url);
            curl_setopt($CURL, CURLOPT_POSTFIELDS, $dados);
            curl_setopt($CURL, CURLOPT_HTTPHEADER, $header);
            curl_setopt($CURL, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($CURL, CURLOPT_ENCODING, "");
            curl_setopt($CURL, CURLOPT_MAXREDIRS, 10);
            curl_setopt($CURL, CURLOPT_TIMEOUT, 30);
            curl_setopt($CURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, 'POST');

            $status = curl_getinfo($CURL, CURLINFO_HTTP_CODE);
            $err = curl_error($CURL);
            
            $resultado = curl_exec($CURL);
            // $resultado = json_decode($resultado);

            // Close the CURL
            curl_close($CURL);

            return $resultado;
        }
        
        $request_key = json_decode(sendEmail());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://raw.githubusercontent.com/clicksign/embedded/main/build/embedded.js">
    <title>Document</title>
</head>
<body>
    <form action="index.php" method="POST">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" name="telefone">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control" name="cpf">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
  
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>


</body>
</html>