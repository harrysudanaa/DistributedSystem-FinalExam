## How To Set Up This Project
1. Open your folder that select to clone this project
3. Open CMD or Terminal in this folder
4. Run git init
2. Run git clone https://github.com/harrysudanaa/DistributedSystem-FinalExam.git
4. Change directory to books_store_app
5. Run composer install
6. Run cp .env.example .env
7. Run cd ..
8. Run php artisan migrate
9. Run docker-compose build
10. Run docker-compose up
11. docker exec books_store_app bash -c "php artisan key:generate"
12. docker exec books_store_app bash -c "php artisan migrate"
13. Go to link localhost:9000
