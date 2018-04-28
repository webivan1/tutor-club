- Добавить поля в advert
    - reject_reason - text
    - Возможность обновлять своё объявление раз в 6 часов
    
- Календарь репетитора, запись к репетитору на урок
    - Возможность отправить сообщение репетитору 
    с просьбой добавить его к определенному занятию
    
# Install

При использовании docker, все клонсольные команды начинаютя с `docker-compose exec php-cli`

- cp .env.example .env (Указать все параметры в новом файле .env)
- chmod -R 777 ./storage
- chmod -R 777 ./bootstrap

### Проблема с запуском
    - php artisan key:generate - Генерация ключа
    - Please provide a valid cache path (https://stackoverflow.com/questions/38483837/please-provide-a-valid-cache-path)
    
### Миграции
    - php artisan migrate

### Установка ролей
    - php artisan user:roles
    
### После регистрации, активируем своего пользователя
    - php artisan user:verify {email}
    
### Устанавливаем роль админа
    - php artisan user:assign {email} {role}