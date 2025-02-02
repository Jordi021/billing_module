# GraphQL Query Tests - For Inventory Module

This document contains example queries for testing the inventory module's GraphQL API.  
Base URL: https://billingmodule.lat/graphql  

## Available Queries

### 1. Get Single Invoice

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

### 2. Get All Invoices

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

## Testing Notes

1. All dates are in YYYY-MM-DD format
2. These queries can be tested using tools like:
    - GraphQL Playground
    - Insomnia
    - Postman
    - Any GraphQL client
