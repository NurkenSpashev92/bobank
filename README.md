## Сервис: БО Банк

Требуется простая версия АПИ сервиса, для управления пользователями и переводами средств между их аккаунтами. “БО Банк” должен включать в себя методы:
- Обновления пользователя (name, email, age)
- Пополнение баланса пользователя. Отрицательный баланс не может существовать
- Перевода средств между пользователями

Для работы использовать:
- DB Postgres
- Laravel framework

Постарайтесь использовать лучшие методологии и практики, о которых Вы знаете и предусмотреть нюансы при работе с балансом.

## Deploy
```bash
    cd deploy
    docker-compose up -d --build
    docker-compose exec -it api bash
```
```bash
www@a0ec421f3914:/var/www/html$ cp .env.example .env
www@a0ec421f3914:/var/www/html$ composer install
www@a0ec421f3914:/var/www/html$ php artisan migrate
www@a0ec421f3914:/var/www/html$ php artisan key:generate
```

http://localhost:8000/swagger
