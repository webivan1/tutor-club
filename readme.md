- Добавление атрибутов - поле сортировка обязательное

- Каутеры категорий переделать под языки !!!

- Листинг объявлений
    - Вывести текст категории
    - Карточка
        - Кнопка с полным описанием
        - Кнопка отправить письмо репетитору
        - Подробнее
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