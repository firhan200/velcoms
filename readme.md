## Velcoms

velcoms is an laravel admin content management system, with velcoms you can build basic CRUD with a few simple steps.

## Technology

- Laravel 5.8
- Vue JS 2.5.17
- Bootstrap 4

## CMS Features

- Login, Logout & Forgot Password
- Update Profile & Change Password
- Pages Management
- Articles Management
- Sliders Banner Management
- Galleries & Photos Management
- Social Link Management
- Read Contact

## Screenshot
<img src="https://i.ibb.co/tXcjbQF/Screenshot-from-2019-10-05-08-46-53.png" />
<img src="https://i.ibb.co/TqZXnzp/Screenshot-from-2019-10-05-08-47-13.png" />
<img src="https://i.ibb.co/r0M9gtV/Screenshot-from-2019-10-05-08-47-33.png" />

## How to Install
- clone or download this repo
- go to downloaded folder & run <b>php artisan key:generate</b>
- run <b>npm install</b>
- run <b>composer install</b>
- run <b>php artisan migrate:refresh</b>
- run <b>php artisan db:seed</b>
- run <b>php artisan serve</b>

your app is now ready.

## Tips

how to add new table including create,read,update & delete on admin: (ex: products table)

step 1: <b>php artisan make:migration create_products_table</b>
create table on database with migration

step 2: <b>php artisan make:model Models/Product</b>
create model on laravel eloquent

step 3: <b>php artisan make:controller Admin/ProductController</b>
create controller on admin folder

step 3: <b>copy all files on app/Http/Controllers/Admin/CRUDController.php</b>
copy all code on those file and rename models name with <b>new \App\Models\Product</b>.

step 4: 
<b>
/* Social Links */
Route::prefix('products')->group(function(){
    Route::get('/', 'Admin\ProductController@index')->middleware('jwt.verify');
    Route::get('/{id}', 'Admin\ProductController@details')->middleware('jwt.verify');
    Route::post('/', 'Admin\ProductController@create')->middleware('jwt.verify');
    Route::put('/{id}', 'Admin\ProductController@update')->middleware('jwt.verify');
    Route::delete('/{id}', 'Admin\ProductController@delete')->middleware('jwt.verify');
});
</b>
copy code above and put on <b>routes/api.php</b> inside admin group routes, in this step you have successfully built a CRUD functional with API end-point access.
