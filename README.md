# core-billet
Responsável por processar arquivo de boleto

# installation

## Application level
`composer install`
`docker-compose up -d` para subir mongodb e localstack

## Localstack level
Para criar os recursos necessários para que a aplicaçao funcione, rode os seguintes comandos
`AWS_ACCESS_KEY_ID=any_non_empty_string AWS_SECRET_ACCESS_KEY=any_non_empty_string aws --endpoint-url=http://localhost:4566 s3 mb s3://billets`

`AWS_ACCESS_KEY_ID=any_non_empty_string AWS_SECRET_ACCESS_KEY=any_non_empty_string aws --endpoint-url=http://localhost:4566 sqs create-queue --queue-name process-billet-file --region us-east-1`

`AWS_ACCESS_KEY_ID=any_non_empty_string AWS_SECRET_ACCESS_KEY=any_non_empty_string aws --endpoint-url=http://localhost:4566 sns create-topic --name file-processed --region us-east-1`

## Mongodb level
Para o mongodb, é necessário criar uma base chamada 'core_billet' e duas collections 'files' e 'billets'

`docker exec -it mongodb mongo -u root -p root --authenticationDatabase admin` para se conectar no mongo

Obs.: caso esse comando retorne erro de credenciais, tente rodar

`docker exec -it mongodb mongo admin --eval "db.createUser({ user: 'root', pwd: 'root', roles: [{ role: 'root', db: 'admin' }] })"`

Após se conectar na base rode os comandos na seguinte ordem:
`mongo`

`use core_billet`

`db.createCollection("billets")`

`db.createCollection("files")`

Com isso finalizamos o set up.

# Running the application

Rode `symfony server:start`
Em outro terminal rode: `php bin/console app:sqs-process-file-worker` para iniciarlizar o consumer

# Usage

endpoint : POST `/api/billets/file`

curl: `curl --location 'https://127.0.0.1:8000/api/billets/file' \
--form 'csvFile=@"/path/to/file/teste.csv"'`

# ADR (Architectural Decision Record)

## Application level

Decidi usar uma arquitetura DDD talvez próxima do Hexagonal mas com uma abordagem mais simplificada.
A ideia principal é manter a inversão de dependência fazendo com que camadas de "dentro" não conheçam as camadas mais para "fora".

## Architectural level

Overview:

![Alt text](./high-level-architecture.png?raw=true "Title")

Ao fazer o upload do arquivo via endpoint os seguintes passos acontecem:

1 - Upload para o S3

2 - Insere o arquivo na base com o status "PROCESSING_PENDING"

3 - Envia mensagem para o SQS para processamento de arquivo

4 - Em assíncrono a mensagem será consumida e o arquivo será processado

5 - Os boletos serão criados e o status do arquivo será alterado para PROCESSED

6 - Evento de arquivo processado será enviado


# Checklist

O que está feito:
- Endpoint para consumir o arquivo
- Comunicação com S3, SQS e SNS
- Processamento do arquivo criando os boletos

O que faltou fazer:
- API doc
- Unit tests
- Integration tests com Test Container
- Webhook para recebimento de pagamentos
    - Aqui a ideia seria ter uma outra aplicação chamada core-payment para receber os pagamentos.
    Essa aplicação se comunicaria com core-billet para verificar se o 'debtId' recebido existe, de fato, no core-billet, caso exista o pagamento seria registrado.
- Código para envio do boleto para o cliente
    - A decisão de arquitetura foi primeiramente processar o arquivo e inserir os boletos e, depois disso, fazer consulta paginada paginada na base para buscar os boletos criados e enviar     
    para os clientes.
- Dockerizar o core-billet

# Disclaimer

PHP e Symfony não é minha stack principal, então tive muitos problemas com configuração da aplicação, o que me fez "perder" muito tempo e por isso não consegui fazer os pontos levantados no "O que faltou fazer".

