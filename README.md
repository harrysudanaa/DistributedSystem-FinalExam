## How To Set Up This Project
1. Open CMD or Terminal
2. Change directory to books_store_app
3. Run composer install
4. Run cp .env.example .env
5. Run cd ..
6. Run php artisan migrate
7. Run docker-compose build
8. Run docker-compose up
9. docker exec books_store_app bash -c "php artisan key:generate"
10. docker exec books_store_app bash -c "php artisan migrate"
11. Go to link localhost:9000
