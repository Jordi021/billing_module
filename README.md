# MÓDULO DE FACTURACIÓN

<figure align="center">
    <img src="./public/bill.svg" width="100">
</figure>

<br>
<br>

## Instalación

### Windows:

```sh
git clone https://github.com/Jordi021/modulo_facturacion.git; cd modulo_facturacion; composer install; cp .env.example .env; php artisan key:generate; npm install; npm run build; php artisan migrate --seed
```

### Linux:

```sh
git clone https://github.com/Jordi021/modulo_facturacion.git && cd modulo_facturacion && composer install && cp .env.example .env && php artisan key:generate && npm install && npm run build && php artisan migrate --seed
```

<br>
<br>

## Prettier en VSCode

Utiliza **Prettier** como el formateador predeterminado para asegurar un formateo consistente en los archivos del proyecto.

### 1. Instalar la Extensión de Prettier

Busca `Prettier - Code formatter` e instálalo.

### 2. Configurar el Formateador Predeterminado

Agrega la siguiente configuración a tu archivo de **User Settings (`settings.json`)** en VSCode:

```json
{
    "editor.defaultFormatter": "esbenp.prettier-vscode"
}
```

<br>
<br>

Ejemplos para probar la API GraphQL (método POST).  
URL: http://127.0.0.1:8000/billing/graphql

---

## Test de queries

### Método: `client(id: String @eq): Client @find`

#### Consulta:

```graphql
query {
    client(id: "1234567890") {
        id
        name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
    }
}
```

---

### Método: `clients: [Client!]! @paginate(defaultCount: 10)`

#### Consulta:

```graphql
query {
    clients {
        data {
            id
            name
            client_type
            email
        }
        paginatorInfo {
            currentPage
            lastPage
            total
        }
    }
}
```

---

### Método: `allClients: [Client!]! @all @orderBy(column: "created_at", direction: DESC)`

#### Consulta:

```graphql
query {
    allClients {
        id
        name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
    }
}
```

<br>
<br>

## Test de mutaciones

### Método: `createClient(input: InputCreateClient!): Client`

#### Consulta:

```graphql
mutation {
    createClient(
        input: {
            id: "1010101010"
            name: "user"
            birth_date: "1990-01-01"
            client_type: "Crédito"
            address: "123 Main Street"
            phone: "555123456"
            email: "user@example.com"
        }
    ) {
        id
        name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
    }
}
```

<br>
<br>

### Método: `updateClient(id: ID!, input: InputUpdateClient!): Client`

#### Consulta:

```graphql
mutation {
    updateClient(
        id: "1010101010"
        input: { name: "user updated", phone: "555987654" }
    ) {
        id
        name
        phone
        email
        status
        created_at
        updated_at
    }
}
```

<br>
<br>

### Método: `deleteClient(id: ID!): Client`

#### Consulta:

```graphql
mutation {
    deleteClient(id: "1010101010") {
        id
        name
        status
    }
}
```

<br>
<br>

### Método: `restoreClient(id: ID!): Client`

#### Consulta:

```graphql
mutation {
    restoreClient(id: "1010101010") {
        id
        name
        status
    }
}
```

<br>
<br>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
