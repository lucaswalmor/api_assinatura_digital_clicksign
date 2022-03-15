<?php
    if (isset($_POST['nome'])) {
        $nome = $_POST['nome'];
        $telefone = trim($_POST['telefone']);
        $cpf = $_POST['cpf'];
        $email = $_POST['email'];

        function criarsignatario($nome, $telefone, $cpf, $email)
        {

            /*
                O template será o segundo passo da API, onde será enviado os dados do cliente para geração do documento
                -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                https://sandbox.clicksign.com/api/v1/templates/:key/documents?access_token={{acess_token}}, sendo :key a chave do template na Clicksign e o acess_token é o token gerado da API
                -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                os campos dentro do array de data deverão estar entre chaves dentro do documento colocado na plataforma da clicksign, 
                sendo assim {{exemplo_variavel}} dentro do ponto aonde deverá ser colocado os dados do cliente 
            */
            // url do endpoint de criação do signatário
            $url  = 'https://sandbox.clicksign.com/api/v1/signers?access_token=3e888197-29e0-46d2-8969-a501ecf3000a';

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

        function criarTemplate($name, $email, $documentation) 
        {
            /*
                O template será o segundo passo da API, onde será enviado os dados do cliente para geração do documento
                -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                https://sandbox.clicksign.com/api/v1/templates/:key/documents?access_token={{acess_token}}, sendo :key a chave do template na Clicksign e o acess_token é o token gerado da API
                -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                os campos dentro do array de data deverão estar entre chaves dentro do documento colocado na plataforma da clicksign, 
                sendo assim {{exemplo_variavel}} dentro do ponto aonde deverá ser colocado os dados do cliente 
            */

            // url do endpoint de criação do signatário
            $url  = 'https://sandbox.clicksign.com/api/v1/templates/2bcb23e6-45b3-42c2-b8fb-cebd234bfba1/documents?access_token=3e888197-29e0-46d2-8969-a501ecf3000a';

            // Array de dados para criação do signatário
            $document = array(
                "document" => [
                    "path" => "/Modelos/teste.docx",
                    "template" => [
                        "data" => [
                            "nome" => $name,
                            "endereco" => $email,
                            "cpf" => $documentation
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

        function addSignatarioTemplate($template_doc_key, $signatario_sign_key) 
        {
            $url  = 'https://sandbox.clicksign.com/api/v1/lists?access_token=3e888197-29e0-46d2-8969-a501ecf3000a';

            //$dados_signatario = json_decode(criarsignatario());
            //$dados_template = json_decode(criarTemplate());
            $mensagem = 'Esta mensagem e um texto de ok';

            // Array de dados para criação do signatário
            $add_signers = array(
                "list" => [
                    "document_key" => $template_doc_key,
                    "signer_key" => $signatario_sign_key,
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
            // $resultado = json_decode($resultado);

            // Close the CURL
            curl_close($CURL);

            return $resultado;
        }
        
        function sendEmail($request_signature_key, $list_url) 
        {
            $url  = 'https://sandbox.clicksign.com/api/v1/notifications?access_token=3e888197-29e0-46d2-8969-a501ecf3000a';

            //$finalizar_documento = json_decode(addSignatarioTemplate());
            $mensagem = 'Assina o documento ai';

            // Array de dados para criação do signatário
            $add_signers = array(
                "request_signature_key" => $request_signature_key,  
                "message" => $mensagem,
                "url" => $list_url
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
        
        // Criando Signatário
        $dados_signatario = json_decode(criarsignatario($nome, $telefone, $cpf, $email));

        // Criando Template
        $dados_template = json_decode(criarTemplate($dados_signatario->signer->name, 
                                                    $dados_signatario->signer->email, 
                                                    $dados_signatario->signer->documentation));

        // Adicionando signatário ao template
        $finalizar_documento = json_decode(addSignatarioTemplate($dados_template->document->key, $dados_signatario->signer->key));

        // Enviando por e-mail
        $request_key = json_decode(sendEmail($finalizar_documento->list->request_signature_key, $finalizar_documento->list->url));

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
    <form action="envio2.php" method="POST">
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