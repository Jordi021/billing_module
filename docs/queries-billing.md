# GraphQL Query Tests - Billing Module

This document contains example queries for testing the billing module's GraphQL API.  
Base URL: https://billingmodule.lat/graphql 

## Available Queries

### 1. Get All Clients with Their Invoices

```graphql
query {
    clientsWithAllInvoices {
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
                quantity
                unit_price
                subtotal
                vat_amount
                created_at
                updated_at
            }
        }
    }
}
```

### 2. Get Single Client with All Invoices

```graphql
query {
    clientWithAllInvoices(id: "1234567890") {
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
                quantity
                unit_price
                subtotal
                vat_amount
                created_at
                updated_at
            }
        }
    }
}
```

### 3. Get Specific Client with Credit Invoices

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
                quantity
                unit_price
                subtotal
                vat_amount
                created_at
                updated_at
            }
        }
    }
}
```

### 4. Get Clients with Credit Invoices

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
                quantity
                unit_price
                subtotal
                vat_amount
                created_at
                updated_at
            }
        }
    }
}
```

### 5. Get Single Invoice

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
        details {
            id
            product_id
            quantity
            unit_price
            subtotal
            vat_amount
            created_at
            updated_at
        }
    }
}
```

### 6. Get All Invoices

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
        details {
            id
            product_id
            quantity
            unit_price
            subtotal
            vat_amount
            created_at
            updated_at
        }
    }
}
```

### 7. Get Inactive Clients

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

### 8. Get Active Clients

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

### 9. Get Credit Clients

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

### 10. Get Cash Clients

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

## Testing Notes

1. All dates are in YYYY-MM-DD format
2. These queries can be tested using tools like:
    - GraphQL Playground
    - Insomnia
    - Postman
    - Any GraphQL client
