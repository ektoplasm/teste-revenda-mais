## Instruções de execução

Crie os containers executando o comando abaixo na raíz do projeto:

```bash
docker compose -f 'docker-compose.yml' up -d --build
```

Após inicializar o ambiente, execute o bash no container "php":

```bash
docker exec -it php bash
```

Em seguida, no bash do container, execute os comandos a seguir:

```bash
# Instalar o Laravel
composer install

# Copiar arquivo com as variáveis de ambiente
cp .env.example .env

# Gera a chave da aplicação usada em cookies e criptografia
php artisan key:generate

# Executa as migrations e faz o seed do banco
php artisan migrate --seed
```

Para rodar os testes, execute o comando abaixo no bash do container "php"

```bash
php artisan test --testsuite=Feature
```

ou para executar sem acessar o container diretamente, execute no terminal:

```bash
docker exec -it php php artisan test --testsuite=Feature
```

## Rotas da API

Base URL: http://127.0.0.1:8080/api/v1

### Listar Fornecedores

#### GET `/suppliers`

**Todos os parâmetros são opcionais!**

| Parâmetros  | Tipo   | Descrição                                                                                                                 | Valor padrão |
| ----------- | ------ | ------------------------------------------------------------------------------------------------------------------------- | ------------ |
| `page`      | int    | Página da listagem                                                                                                        | 1            |
| `per_page`  | int    | Total de itens por página                                                                                                 | 15           |
| `sort`      | string | Campo para ser feita a ordenação (Opções: 'created_at', 'name', 'email', 'cpf_cnpj', 'city', 'state' e 'primary_contact') | 'created_at' |
| `sortOrder` | string | Direção da ordenação (Opções: 'ASC', 'DESC')                                                                              | 'DESC'       |

##### Exemplo de resposta

```json
{
    "data": {
        "suppliers": {
            "current_page": 1,
            "data": [
                {
                    "id": 748,
                    "cpf_cnpj": "04895490980",
                    "name": "Sabrina Jasmin Rico",
                    "email": "aline11@example.com",
                    "address": "Rua André Duarte, 97093",
                    "number": "75",
                    "city": "Santa Cristina",
                    "state": "RR",
                    "address_info": "69408-208",
                    "primary_contact": "Alonso de Arruda Matos Neto",
                    "primary_contact_email": "estevao.souza@example.org",
                    "created_at": "2025-06-06T19:32:47.000000Z",
                    "updated_at": "2025-06-06T19:32:47.000000Z"
                },
                ...
                {
                    "id": 8,
                    "cpf_cnpj": "49751227402",
                    "name": "Raphael Ícaro Vale",
                    "email": "joao.delvalle@example.net",
                    "address": "Avenida Emiliano Verdara, 4242. Bc. 02 Ap. 35",
                    "number": "25",
                    "city": "Rico do Leste",
                    "state": "BA",
                    "address_info": "82266-641",
                    "primary_contact": "Sônia Lúcia Aragão",
                    "primary_contact_email": "demian70@example.org",
                    "created_at": "2025-06-06T18:21:31.000000Z",
                    "updated_at": "2025-06-06T18:21:31.000000Z"
                }
            ],
            "first_page_url": "http://127.0.0.1:8080/api/v1/suppliers?page=1",
            "from": 1,
            "last_page": 2,
            "last_page_url": "http://127.0.0.1:8080/api/v1/suppliers?page=2",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "http://127.0.0.1:8080/api/v1/suppliers?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": "http://127.0.0.1:8080/api/v1/suppliers?page=2",
                    "label": "2",
                    "active": false
                },
                {
                    "url": "http://127.0.0.1:8080/api/v1/suppliers?page=2",
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": "http://127.0.0.1:8080/api/v1/suppliers?page=2",
            "path": "http://127.0.0.1:8080/api/v1/suppliers",
            "per_page": 15,
            "prev_page_url": null,
            "to": 15,
            "total": 21
        }
    }
}
```

### Adicionar Fornecedor

#### POST `/suppliers`

| Parâmetros              | Obrigatório | Descrição                                      |
| ----------------------- | ----------- | ---------------------------------------------- |
| `name`                  | Sim         | Nome do Fornecedor                             |
| `email`                 | Sim         | E-mail do Fornecedor                           |
| `cpf_cnpj`              | Sim         | CPF ou CNPJ do Fornecedor                      |
| `address`               | Sim         | Endereço do Fornecedor                         |
| `number`                | Sim         | Número do endereço do Fornecedor               |
| `city`                  | Sim         | Cidade do Fornecedor                           |
| `state`                 | Sim         | UF do Fornecedor                               |
| `address_info`          | Não         | Complemento do endereço do Fornecedor          |
| `primary_contact`       | Sim         | Contato Responsável do Fornecedor              |
| `primary_contact_email` | Sim         | E-mail do contato Responsável do do Fornecedor |

##### Exemplo de requisição:

```json
{
  "name": "Sabrina Jasmin Rico",
  "email": "aline11@example.com",
  "cpf_cnpj": "03995142000124",
  "address": "Rua André Duarte, 97093",
  "number": "75",
  "city": "Santa Cristina",
  "state": "RR",
  "address_info": "69408-208",
  "primary_contact": "Alonso de Arruda Matos Neto",
  "primary_contact_email": "estevao.souza@example.org"
}
```

##### Respostas:

| Tipo    | Código | Descrição               |
| ------- | ------ | ----------------------- |
| Sucesso | 201    | Fornecedor criado       |
| Erro    | 422    | Erro nos dados enviados |

##### Exemplo de resposta de sucesso

```json
{
  "data": {
    "cpf_cnpj": "03995142000124",
    "name": "Sabrina Jasmin Rico",
    "email": "aline11@example.com",
    "address": "Rua André Duarte, 97093",
    "number": "75",
    "city": "Santa Cristina",
    "state": "RR",
    "address_info": "69408-208",
    "primary_contact": "Alonso de Arruda Matos Neto",
    "primary_contact_email": "estevao.souza@example.org",
    "updated_at": "2025-06-06T19:32:47.000000Z",
    "created_at": "2025-06-06T19:32:47.000000Z",
    "id": 748
  }
}
```

##### Exemplo de resposta de erro

```json
{
  "message": "The CPF/CNPJ field is invalid.",
  "errors": {
    "cpf_cnpj": ["The CPF/CNPJ field is invalid."]
  }
}
```

### Alterar Fornecedor

#### PUT `/suppliers/{id}`

| Parâmetros              | Obrigatório | Descrição                                      |
| ----------------------- | ----------- | ---------------------------------------------- |
| `name`                  | Sim         | Nome do Fornecedor                             |
| `email`                 | Sim         | E-mail do Fornecedor                           |
| `cpf_cnpj`              | Sim         | CPF ou CNPJ do Fornecedor                      |
| `address`               | Sim         | Endereço do Fornecedor                         |
| `number`                | Sim         | Número do endereço do Fornecedor               |
| `city`                  | Sim         | Cidade do Fornecedor                           |
| `state`                 | Sim         | UF do Fornecedor                               |
| `address_info`          | Não         | Complemento do endereço do Fornecedor          |
| `primary_contact`       | Sim         | Contato Responsável do Fornecedor              |
| `primary_contact_email` | Sim         | E-mail do contato Responsável do do Fornecedor |

##### Exemplo de requisição:

```json
{
  "name": "Sabrina Jasmin Rico",
  "email": "aline11@example.com",
  "cpf_cnpj": "03995142000124",
  "address": "Rua André Duarte, 97093",
  "number": "75",
  "city": "Santa Cristina",
  "state": "RR",
  "address_info": "69408-208",
  "primary_contact": "Alonso de Arruda Matos Neto",
  "primary_contact_email": "estevao.souza@example.org"
}
```

##### Respostas:

| Tipo    | Código | Descrição               |
| ------- | ------ | ----------------------- |
| Sucesso | 200    | Fornecedor alterado     |
| Erro    | 422    | Erro nos dados enviados |

##### Exemplo de resposta de sucesso

```json
{
  "data": {
    "cpf_cnpj": "03995142000124",
    "name": "Sabrina Jasmin Rico",
    "email": "aline11@example.com",
    "address": "Rua André Duarte, 97093",
    "number": "75",
    "city": "Santa Cristina",
    "state": "RR",
    "address_info": "69408-208",
    "primary_contact": "Alonso de Arruda Matos Neto",
    "primary_contact_email": "estevao.souza@example.org",
    "updated_at": "2025-06-06T19:32:47.000000Z",
    "created_at": "2025-06-06T19:32:47.000000Z",
    "id": 748
  }
}
```

##### Exemplo de resposta de erro

```json
{
  "message": "The CPF/CNPJ field is invalid.",
  "errors": {
    "cpf_cnpj": ["The CPF/CNPJ field is invalid."]
  }
}
```

### Excluir Fornecedor

#### DELETE `/suppliers/{id}`

##### Respostas:

| Tipo    | Código | Descrição                  |
| ------- | ------ | -------------------------- |
| Sucesso | 410    | Fornecedor removido        |
| Erro    | 404    | Fornecedor inexistente     |
| Erro    | 400    | Erro ao remover fornecedor |

##### Exemplo de resposta de sucesso

```json
{
  "message": "Supplier removed successfully."
}
```

##### Exemplo de resposta de erro

```json
{
  "message": "The supplier does not exists."
}
```

### Pesquisar Fornecedor por CNPJ

Dados do CNPJ serão buscados na BrasilAPI.

#### GET `/suppliers/search/{cnpj}`

##### Respostas:

| Tipo    | Código | Descrição                 |
| ------- | ------ | ------------------------- |
| Sucesso | 200    | Dados do CNPJ encontrados |
| Erro    | 404    | CNPJ não encontrado       |

##### Exemplo de resposta de sucesso

```json
{
    "data": {
        ...
        "cnpj": "03995142000124",
        "pais": null,
        "email": null,
        "porte": "MICRO EMPRESA",
        "bairro": "AGUA VERDE",
        "numero": "3550",
        "ddd_fax": "4133425757",
        "municipio": "CURITIBA",
        "logradouro": "PRESIDENTE GETULIO VARGAS",
        "cnae_fiscal": 6209100,
       ...
        "descricao_tipo_de_logradouro": "AVENIDA",
        "descricao_motivo_situacao_cadastral": "SEM MOTIVO",
        "descricao_identificador_matriz_filial": "MATRIZ"
    }
}
```

##### Exemplo de resposta de erro

```json
{
  "message": "No data found."
}
```

## Teste para Desenvolvedor PHP/Laravel

Bem-vindo ao teste de desenvolvimento para a posição de Desenvolvedor PHP/Laravel.

O objetivo deste teste é desenvolver uma API Rest para o cadastro de fornecedores, permitindo a busca por CNPJ ou CPF, utilizando Laravel no backend.

## Descrição do Projeto

### Backend (API Laravel):

#### CRUD de Fornecedores:

- **Criar Fornecedor:**

  - Permita o cadastro de fornecedores usando CNPJ ou CPF, incluindo informações como nome/nome da empresa, contato, endereço, etc.
  - Valide a integridade e o formato dos dados, como o formato correto de CNPJ/CPF e a obrigatoriedade de campos.

- **Editar Fornecedor:**

  - Facilite a atualização das informações de fornecedores, mantendo a validação dos dados.

- **Excluir Fornecedor:**

  - Possibilite a remoção segura de fornecedores.

- **Listar Fornecedores:**
  - Apresente uma lista paginada de fornecedores, com filtragem e ordenação.

#### Migrations:

- Utilize migrations do Laravel para definir a estrutura do banco de dados, garantindo uma boa organização e facilidade de manutenção.

## Requisitos

### Backend:

- Implementar busca por CNPJ na [BrasilAPI](https://brasilapi.com.br/docs#tag/CNPJ/paths/~1cnpj~1v1~1{cnpj}/get) ou qualquer outro endpoint público.

## Tecnologias a serem utilizadas

- Framework Laravel (PHP) 9.x ou superior
- MySQL ou Postgres

## Critérios de Avaliação

- Adesão aos requisitos funcionais e técnicos.
- Qualidade do código, incluindo organização, padrões de desenvolvimento e segurança.
- Documentação do projeto, incluindo um README detalhado com instruções de instalação e operação.

## Bônus

- Implementação de Repository Pattern.
- Implementação de testes automatizados.
- Dockerização do ambiente de desenvolvimento.
- Implementação de cache para otimizar o desempenho.

## Entrega

- Para iniciar o teste, faça um fork deste repositório; Se você apenas clonar o repositório não vai conseguir fazer push.
- Crie uma branch com o nome que desejar;
- Altere o arquivo README.md com as informações necessárias para executar o seu teste (comandos, migrations, seeds, etc);
- Depois de finalizado, envie-nos o pull request;
