# Products

Manage your products with this simple API!

## Installation
- Clone the project to your local machine
- Run the following command inside the project folder:
```
composer install
```

Normally, a migration and seeder would be implemented to insert
data into the database. For the purposes of this however, it will simply 
be an sql file to import.

- First, enter your database connection information into the .env
file in these areas, putting 'products' as the DB_DATABASE:
```
DB_DATABASE=products
DB_USERNAME=
DB_PASSWORD=
```
- You may test the connection information with:
```
php artisan migrate
```
- It will throw an error if your database connection information is
incorrect, not turned on, or not yet set up. However, we will not be
using these tables that are provided for us by Laravel.
- Instead, find the ```Products_products.sql``` file inside the "importable" folder,
at the root of this project. Run or import this inside MySQL, to make the products 
table and database schema. It will also add data into the ```products``` database table.
- If you have Postman, you may also import the ```Products.postman_collection.json```
file from the "importable" folder to create the collection required to interact with 
this API through postman. It also includes example JSON to send to each endpoint as desired.
If you do not have postman, you may visit the urls present in this project manually, 
please examine the "API Routes" section of this README.

## Running the project
- Enter the project folder and run the following command:
```
php artisan serve
```


----


## Data Explanation

### Product Code
Each product has a code, which is intended to identify the given product.
It could be the SDK, or an internal code as determined by the program.

### Locale
Each product has a locale. This is a maximum of a five-character code, 
sometimes with or without a hyphen. en-gb and en-us are two different languages,
though both English at their core, for example. Bulgarian (bg) does not have
a variation, so it remains two characters.

An example website to refer to for locale codes is:
https://www.andiamo.co.uk/resources/iso-language-codes/

### Composite key
There is a composite key set to be unique, across product code and locale. You cannot
have multiple products in the same locale with the same product code.
This is to allow the same product to be sold with a different description,
based on the locale. For example, a hairdryer may have a description in french
and one in english but for all intents and purposes apart from the language of the
listing on the website, it is the same product.

An example has been included in the initial database .sql file, with a price difference.

---

## API Routes
// Get
- GET /product/{id}
- GET /product/{code}/{locale}

// Create
- POST /product

// Update
- PUT /product/{id}

// Delete
- DELETE /product/{id}

The code for these routes is in ```ProductController.php```

----

## Example data to send to POST/PUT 
```
{
    "product_name": "One Product GB",
    "product_desc": "Description 1",
    "product_category": "One Category",
    "product_price": 1,
    "product_code": "ABC",
    "locale": "en-gb"
}
```

- POST/PUT endpoints will have data validation.
- In the event of a request not finding the requested data such as the given
product ID, a "not found" exception will be returned.
