
# Laravel Ecommerce

This is a demo for ecommerce API using laravel 9

# Features
- Merchant can create account
- Merchant can create store 
- Merchant can add products to each store
- Merchant can update store data
    - Store name 
    - URL
    - Shipping type
    - Shipping fees
    - VAT tex value
- User can register 
- User can create view store products 
- User can add produtc to cart 
- User can remove product from cart 
- User can view cart with products and final totals


## Installation

clone then update .env with database credentials 

```bash
cd /laravel-ecommerce
composer install 
php artisan migrate 
```
    
## API Reference

#### Register

```http
  POST /api/auth/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required**. Name |
| `email` | `string` | **Required**. Email |
| `password` | `string` | **Required**. Password |
| `is_merchant` | `boolean` | flag for user type ( 1 if the user is merchant) |

#### Login

```http
  POST /api/auth/login
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Email |
| `password` | `string` | **Required**. Password |

#### Create Store (Merchant Auth Required)

```http
  POST /api/store
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required**. Name |
| `url` | `string` | **Required**. URL |
| `shipping_type` | `string` | **Required**. shipping cost value type ( percentage or fixed ) |
| `shipping_fees` | `float` | **Required**. Shipping fees |
| `vat_value` | `float` | **Required**. VAT Value (always considered %) |

#### Update Store (Merchant Auth Required)

```http
  PATCH /api/store/${url}
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` |  Name |
| `shipping_type` | `string` |  shipping cost value type ( percentage or fixed ) |
| `shipping_fees` | `float` |  Shipping fees |
| `vat_value` | `float` |  VAT Value (always considered %) |


#### Create Product (Merchant Auth Required)

```http
  POST /api/product
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name_en` | `string` | **Required**. Name In English |
| `name_ar` | `string` |  Name In Arabic |
| `description_en` | `string` | **Required**. Description In English |
| `description_ar` | `string` | **Required**. Description In Arabic |
| `price` | `float` | **Required**. Product Price |
| `tax_included` | `boolean` | **Required**. Flag if the price is tax included |
| `store_id` | `int` | **Required**. Store id |

#### Create Cart (User Auth Required)

```http
  POST /api/cart
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `store_id` | `int` | **Required**. Store id |

#### Add to Cart (User Auth Required)

```http
  POST /api/add-to-cart
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `qty` | `int` | **Required**. qty of product |
| `sku` | `string` | **Required**. product's SKU |


#### Remove From Cart (User Auth Required)

```http
  POST /api/emove-from-cart/${SKU}
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
    --------------------NONE--------------------




#### Get Store by URL 

```http
  GET /api/api/store/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
    --------------------NONE--------------------

#### Get Store Products 

```http
  GET /api/api/store/${id}/products
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
    --------------------NONE--------------------

#### Get User Cart  (User Auth Required)

```http
  GET /api/api/store/${id}/products
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
    --------------------NONE--------------------





## Appendix

POSTMAN Collection Link

https://www.getpostman.com/collections/129ef5766c5730ab6a92


Draw.io database table digram 

https://drive.google.com/file/d/1UaqbWNuRmVihfUqX4By73RCCi9SOntOX/view?usp=sharing
