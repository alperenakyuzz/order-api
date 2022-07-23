# order-api

Kurulum:

    $ git clone https://github.com/alperenakyuzz/order-api.git
    $ cd order-api
    $ docker-compose up -d —build
    $ ./docker/console composer install
    $ ./docker/console php artisan key:generate
    $ ./docker/console php artisan migrate
    $ ./docker/console php artisan db:seed

Base Uri: http://localhost:8888/api/

İşlem yapabilmek için Bareer Token'a ihtiyacınız var.
```bash
curl --location --request POST 'http://localhost:8888/api/login' \
--form 'email="test@customer.com"' \
--form 'password="password"'
```
## Orders

```bash
GET        api/orders Customer Orders Listing
POST       api/orders Create a Order
GET        api/orders/{order} Get a Order
DELETE     api/orders/{order} Delete a Order
```

### Örnek İstek:
```bash
curl --location --request POST 'http://localhost:8888/api/orders' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 3|F2OUONma0JcHI23YZzOlbrKGWC5q920VMWT7l1cJ' \
--header 'Content-Type: application/json' \
--data-raw '{
    "items": [
        {
            "product_id": 2,
            "quantity": 20
        },
        {
            "product_id": 3,
            "quantity": 10
        }
    ]
}'
```

## Discounts

```bash
GET        api/orders/{order}/apply-discount Calculate Order Discounts
```

### Örnek İstek:
```bash
curl --location --request GET 'http://localhost:8888/api/orders/18/apply-discounts' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 3|F2OUONma0JcHI23YZzOlbrKGWC5q920VMWT7l1cJ'
```

### Response:
```json
{
    "order_id": 18,
    "discounts": [
        {
            "discount_reason": "ApplyTenPercentOverThousandDiscount",
            "discount_amount": 1237.8,
            "subtotal": 11140.2
        },
        {
            "discount_reason": "ApplyBuyFiveGetOneDiscount",
            "discount_amount": 11.28,
            "subtotal": 11128.92
        },
        {
            "discount_reason": "ApplyBuyFiveGetOneDiscount",
            "discount_amount": 22.8,
            "subtotal": 11106.12
        },
        {
            "discount_reason": "ApplyTwentyPercentOnSameCategoryDiscount",
            "discount_amount": 19.8,
            "subtotal": 11086.32
        }
    ],
    "total_discount": 1291.68,
    "discounted_total": 11086.32
}
```