# Best Promotion REST API

This is a **REST API service** that accepts product information and returns the **best promotion** applicable for that
product.

## Goal

To determine and return **the single most beneficial promotion** — the one that results in the **lowest possible price**
for a product.

> ⚠ Multiple promotions may be valid for a product, but **only one can be applied** at a time. This service selects the
> one that maximizes customer savings.

---

## Example Use Case

- Client sends a request with product ID or product details.
- API checks all valid promotions.
- API returns the promotion that, when applied, gives the **lowest final price**.

---

## Sample Request

```http
POST /lowest-price
Content-Type: application/json

{
    "product_id": {{product_id}}
    "request_date": "2022-11-30",
    "quantity": -5,
    "request_location": "UK",
    "voucher_code": "OU812",
}