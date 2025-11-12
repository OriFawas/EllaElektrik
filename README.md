Build Instruction 

composer update

<<<<<<< HEAD
cd .env.example .env

php artisan db:seed

php artisan migrate

=======
copy .env.example .env

php artisan migrate

php artisan db:seed

>>>>>>> deeab084d5997b89c4ed55a325bea112aeb0f33b
npm run build

npm run dev

php artian serve
