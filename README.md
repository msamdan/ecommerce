### Take-Home Assesment / COMMERCE  

INSTALLATION

STEP-1
Create .env file
```sh
cp .env.example .env
```

STEP-2
Start the containers in detached mode:

```sh
docker-compose up -d
```

STEP-3 Install Composer:

```sh
docker exec ecommerce-fpm composer install
```
STEP 4 Create database
```sh
docker exec ecommerce-postgres createdb -U postgres -T template0 db_ecommerce
docker exec ecommerce-postgres /usr/bin/pg_restore -v --jobs=8 --no-owner --username=postgres --no-acl -d db_ecommerce /database/backup/db.dump.gz
```

PROJECT DETAILS

Ecommerce order and discount RESTful API sample..etc.

### API Documentation:
https://documenter.getpostman.com/view/6184921/UVRHjPJq

### Controller:
Receives API requests (Order and Basket actions) and calls the required services...
```
App\Http\Controllers\BasketController.php
App\Http\Controllers\OrderController.php
```

### Service:
Manage Order and Basket...
```
App\Http\Services\BasketService.php
App\Http\Services\OrderService.php
```
### Classes:

Represents basket. Extends from Discount class. Collect items, remove items, check stock etc...
```
App\Classes\Basket.php
```

Discount rule manager. Rules registers here. Trigger rules on add, remove or update item... etc.
```
App\Classes\Discount\Discount.php
```

Rule interface for new rules...
```
App\Classes\Discount\Rules\Rule.php
```
