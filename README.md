### Bulk Insert

- clone this repo.
- then `composer install`.
- then `npm install` and `npm run dev`
- then `artisan migrate`

### Routes

`domain/products` is responsible for get data,

`domain/products/import` is responsible for insert 100k data,

// ====================>

I attach the 100k csv format data in root of project `test_products_100k.csv`

upload this file,

- then run `artisan product:import` command.

after this finished run `artisan queue:work`

I can't solve this problem,

5. If there is any duplicate sku or barcode then in the Vue modal you have to show it in
   Invalid_data column (Duplicate sku/barcode: #number)
