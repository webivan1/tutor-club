- Почта
    - Нанять админа для настройки почты + мануал для меня

- Дополнительно
    - Поиск к категориях по алиасу

- Очереди
    - Обработка Mail через очереди (!!! Проверить)

- Листинг объявлений
    - Карточка
        - Кнопка отправить письмо репетитору
        - Страница репетитора *TODO

- Форма объявлений
    - Опыт преподавания (лет) сдайдер?

- Добавить поле в таблицу category для контента страницы

- Добавить поля в advert
    - reject_reason - text
    - Возможность обновлять своё объявление раз в 6 часов
    
- Календарь репетитора, запись к репетитору на урок
    - Возможность отправить сообщение репетитору 
    с просьбой добавить его к определенному занятию
    
- Добавить groupBy динамически - Admin\Role, Admin\Keywords
    
# Install

При использовании docker, все клонсольные команды начинаютя с `docker-compose exec php-cli`

- cp .env.example .env (Указать все параметры в новом файле .env)
- chmod -R 777 ./storage
- chmod -R 777 ./bootstrap
- chmod -R 777 ./public

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
    
### Server 
    - Запуск скрипта в фоне
        - https://ruhighload.com/%D0%9A%D0%B0%D0%BA+%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D1%82%D0%B8%D1%82%D1%8C+%D1%81%D0%BA%D1%80%D0%B8%D0%BF%D1%82+%D0%B2+%D1%84%D0%BE%D0%BD%D0%BE%D0%B2%D0%BE%D0%BC+%D1%80%D0%B5%D0%B6%D0%B8%D0%BC%D0%B5%3f
        - Supervisord - https://ruhighload.com/%D0%9A%D0%B0%D0%BA+%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D1%82%D0%B8%D1%82%D1%8C+php+worker%3f
        - Пример запуска: nohup php -q /home/admin/web/dev.my-tutor.club/public_html/php artisan queue:work > /var/log/queue.worker.log 2>&1
        - Как проверить запуск - ps ax | grep 'queue:work'