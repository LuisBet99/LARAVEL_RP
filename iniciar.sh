composer install                        &&
php artisan key:generate                &&
php artisan migrate:reset               &&
php artisan migrate                     &&
php artisan migrate:fresh --seed
