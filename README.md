### Bulk Insert

- clone this repo.
- then `composer install`.
- then `npm install`
- then `artisan migrate`

I attach the 100k data in the root `test_products.csv`

upload this file,

- then run `artisan product:import` command.

after this finished run `artisan queue:work`

I can't solve this problem,

5. If there is any duplicate sku or barcode then in the Vue modal you have to show it in
   Invalid_data column (Duplicate sku/barcode: #number)
