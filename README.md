# api_assinatura_digital_clicksign
**Documentação oficial:** https://developers.clicksign.com/docs

Passos para utilização da API
-----------------------------

**1º Passo:**  Acessar a plataforma da clicksign e criar uma conta.

**2º Passo:** Ir ate as configurações, acessar a aba "API" e gerar seu token para uso da API, no botão "Gerar Acess Token".

**3º Passo:** após o token gerado poderá testar o mesmo usando o postman, insonia ou outra plataforma da sua preferência com a URL: https://sandbox.clicksign.com/api/v1/accounts?access_token={{access_token}} substituindo {{access_token}} pelo seu access_token.

**4º Passo:** Para gerar um documento através de um modelo, você deverá realizar o upload de um modelo de sua preferência na plataforma da clicksign, o qual deve conter as variáveis
de sua necessidade, a declaração de variavel e feita da seguinte forma, exemplo: Eu {{ NOME }} solicito a assinatura de {{ NOME_CLIENTE }}, essas variaveis irão receber os valores através de um JSON eviado pelo seu sistema.

# Criando Signtário
**Endpoint:** /api/v1/signers?access_token={{access_token}}

**Dados:**  Exemplo de dados que deverão ser enviados para requisação fazer a criação do signatário.
para mais informações acessar a documentação https://developers.clicksign.com/docs/criar-signatario

    { 
	    "signer": { 
		    "email":  "fulano@example.com", 
		    "phone_number":  "11999999999", 
		    "auths":  [ "email"  ], 
		    "name":  "Marcos Zumba", 
		    "documentation":  "123.321.123-40", 
		    "birthday":  "1983-03-31", 
		    "has_documentation":  true, 
		    "selfie_enabled":  false, 
		    "handwritten_enabled":  false, 
		    "official_document_enabled":  false, 
		    "liveness_enabled":  false, 
		    "facial_biometrics_enabled":  false  
	    }
     }

# Criando Documento via modelo
**Endpoint:** /api/v1/templates/:key/documents?access_token={{access_token}}

**Dados:**  Exemplo de dados que deverão ser enviados para requisação fazer a criação do documento.
para mais informações acessar a documentação https://developers.clicksign.com/docs/criar-documento-via-modelos

Os campos dentro de data deverão ser os mesmos campos que foi adicionado as variaveis dentro do documento upado nos modelos da clicksign.

    { 
	    "document":  { 
		    "path":  "/Modelos/Teste-123.docx", 
		    "template":  { 
			    "data":  { 
				    "Company Name":  
				    "Clicksign Gestão de Documentos S.A.", 
				    "Address":  "R. Teodoro Sampaio 2767, 10° andar", 
				    "Phone":  "(11) 3145-2570", 
				    "Website":  "https://www.clicksign.com"  
			    } 
		    } 
	    } 
    }
    
# Adicionando o signatário ao documento
**Endpoint:** /api/v1/lists?access_token={{access_token}}

**Dados:** Dentro de list deverá ser adicionado o document_key que se encontra localizado dentro da plataforma da clicksign, aonde foi colocado o modelo do documento, basta pegar a CHAVE que se encontra no modelo criado.

A signer_key será gerada após a criação do signatário, o qual conterá a mesma.

Para mais exemplos: https://developers.clicksign.com/docs/adicionar-signatario-a-documento

    { 
	    "list":  { 
		    "document_key":  "27b02527-a576-46ee-b01c-bb4e694036c4", 
		    "signer_key":  "79301388-9567-4320-90ce-9e6f60e70d28", 
		    "sign_as":  "sign", 
		    "refusable":  true, 
		    "group":  1, 
		    "message":  "Prezado João,\nPor favor assine o documento.\n\nQualquer dúvida estou à 					  disposição.\n\nAtenciosamente,\nGuilherme Alvez"  
	    } 
    }
    
# Enviando a documentação ao cliente
**Endpoint:** /api/v1/notifications?access_token={{access_token}}

**Dados:** Este passo juntará informações de todos os passos anteriores, e enviará ao cliente um email com link para assinatura do documento.

Para mais exemplos: https://developers.clicksign.com/docs/solicitar-assinatura-por-email

**request_signature_key:** será gerada após o signatário ser adicionado ao documento
**mensagem:** será definida de acordo o solicitado
**url:** será gerada após o signatário ser adicionado ao documento
    { 
	    "request_signature_key":  "0d5a9615-2bb8-3a23-6584-33ff436bb990", 
	    "message":  "Prezado João,\nPor favor assine o documento.\n\nQualquer dúvida estou à disposição.\n\nAtenciosamente,\nGuilherme Alvez", 
	    "url":  "https://www.example.com/abc" 
    }
