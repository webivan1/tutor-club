# Install

Перед установкой зависимостей composer следует поставить Elasticsearch - https://www.elastic.co/downloads/elasticsearch

При использовании docker, все клонсольные команды начинаютя с `docker-compose exec php-cli`

- cp .env.example .env (Указать все параметры в новом файле .env)
- chmod -R 777 ./storage
- chmod -R 777 ./bootstrap
- chmod -R 777 ./public
- chmod -R 777 ./resources/lang

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
    
### Запустить создания индексов для Elasticsearch
    - php artisan search:init
    - php artisan search:import
    
### Server 
    - Запуск скрипта в фоне
        - https://ruhighload.com/%D0%9A%D0%B0%D0%BA+%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D1%82%D0%B8%D1%82%D1%8C+%D1%81%D0%BA%D1%80%D0%B8%D0%BF%D1%82+%D0%B2+%D1%84%D0%BE%D0%BD%D0%BE%D0%B2%D0%BE%D0%BC+%D1%80%D0%B5%D0%B6%D0%B8%D0%BC%D0%B5%3f
        - Supervisord - https://ruhighload.com/%D0%9A%D0%B0%D0%BA+%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D1%82%D0%B8%D1%82%D1%8C+php+worker%3f | http://veselov.sumy.ua/2019-12-gotovim-centos-7-ustanovka-i-nastroyka-supervisord-na-vorker.html
        - Пример запуска: nohup php -q /home/admin/web/dev.my-tutor.club/public_html/artisan queue:listen > /var/log/queue.worker.log 2>&1
        - Как проверить запуск - ps ax | grep 'queue:listen'
        
### Надо запустить в фоне
    - php /home/admin/web/dev.my-tutor.club/public_html/artisan queue:listen
    - php /home/admin/web/dev.my-tutor.club/public_html/artisan queue:work --queue=messages
    
### Javascript
    - Установить laravel-echo-server (https://github.com/tlaverdure/laravel-echo-server)
    - Демоны (SCREEN)
        - node ./resources/assets/js/ws.server/server.js
        - laravel-echo-server start
        - signalhub --cert /home/admin/conf/web/ssl.dev.my-tutor.club.pem --key /home/admin/conf/web/ssl.dev.my-tutor.club.key listen -p 6003
        - signalhub --cert /home/admin/conf/web/ssl.dev.my-tutor.club.pem --key /home/admin/conf/web/ssl.dev.my-tutor.club.key listen -p 6004
    - Как работать с js (Запуск скриптов в корне проекта)
        - Если первый раз производится запуск
            - npm install
        - Запуск watcher 
            - npm run watch
        - Собираем для продакшена
            - npm run prod

### Tests
    - Запуск тестов для винды - vendor\bin\phpunit