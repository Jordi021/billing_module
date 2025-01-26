# GraphQL Query Tests - Billing Module

This document contains example queries for testing the billing module's GraphQL API.  
Base URL: https://billingmodule.lat/graphql 

## Available Queries

### 1. Get Specific Client with Credit Invoices

```graphql
query {
    clientWithCreditInvoices(id: "1234567890") {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
            created_at
            updated_at
            details {
                id
                product_id
                product_name
                quantity
                unit_price
                subtotal
                created_at
                updated_at
            }
        }
    }
}
```

### 2. Get Clients with Credit Invoices

```graphql
query {
    clientsWithCreditInvoices {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
            created_at
            updated_at
            details {
                id
                product_id
                product_name
                quantity
                unit_price
                subtotal
                created_at
                updated_at
            }
        }
    }
}
```

### 3. Get Single Invoice

```graphql
query {
    invoice(id: "1") {
        id
        payment_type
        invoice_date
        total
        note
        created_at
        updated_at
        client {
            id
            name
            last_name
            birth_date
            client_type
            address
            phone
            email
            status
            created_at
            updated_at
        }
        details {
            id
            product_id
            product_name
            quantity
            unit_price
            subtotal
            created_at
            updated_at
        }
    }
}
```

### 4. Get All Invoices

```graphql
query {
    invoices {
        id
        payment_type
        invoice_date
        total
        note
        created_at
        updated_at
        client {
            id
            name
            last_name
            birth_date
            client_type
            address
            phone
            email
            status
            created_at
            updated_at
        }
        details {
            id
            product_id
            product_name
            quantity
            unit_price
            subtotal
            created_at
            updated_at
        }
    }
}
```

### 5. Get Inactive Clients

```graphql
query {
    clientsInactive {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
        }
    }
}
```

### 6. Get Active Clients

```graphql
query {
    clientsActive {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
        }
    }
}
```

### 7. Get Credit Clients

```graphql
query {
    clientsCredit {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
        }
    }
}
```

### 8. Get Cash Clients

```graphql
query {
    clientsCash {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
        }
    }
}
```

### 9. Get All Clients (Sorted by Creation Date)

```graphql
query {
    allClients {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
        }
    }
}
```

### 10. Get Paginated Clients

```graphql
query {
    clients {
        data {
            id
            name
            last_name
            birth_date
            client_type
            address
            phone
            email
            status
            created_at
            updated_at
            invoices {
                id
                payment_type
                invoice_date
                total
                note
            }
        }
        paginatorInfo {
            currentPage
            lastPage
            total
            perPage
            hasMorePages
        }
    }
}
```

### 11. Get Single Client

```graphql
query {
    client(id: "1234567890") {
        id
        name
        last_name
        birth_date
        client_type
        address
        phone
        email
        status
        created_at
        updated_at
        invoices {
            id
            payment_type
            invoice_date
            total
            note
            details {
                id
                product_id
                product_name
                quantity
                unit_price
                subtotal
                created_at
                updated_at
            }
            created_at
            updated_at
        }
    }
}
```

## Testing Notes

1. All dates are in YYYY-MM-DD format
2. These queries can be tested using tools like:
    - GraphQL Playground
    - Insomnia
    - Postman
    - Any GraphQL client
