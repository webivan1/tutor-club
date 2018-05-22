### TODO

- Переделать online users

Создать глобальный эвент для добавления сообщений

- Сортировка по полю updated_at ДИАЛОГИ

Кнопка - отправить сообщение
Всплывает окно с текстовым полем для первого сообщения

Отправляем на сервер: 
    message, 
    from_id, 
    to_id, 
    url - урл страницы (листинг или карточка), 
    title - Заголовок (Название анкеты пользователя),
    
+ 1) Создаем диалог если его не было (отправляем событие на создание диалога пользователя по приватному каналу)
+ 2) Записываем сообщение в чат
+ 3) Отправляем событие тому кто послал сообщение, чтобы открыть чат и выбрать этот диалог
4) Отправляем всем остальным пользователям диалога событие на появления нового сообщения

-------------------------------------------------------

- События на авторизацию и выход юзера

- Сделать вебсокеты для https

- Визуальный редактор - Summernote

- Дополнительно
    - Поиск в категориях по алиасу

- Листинг объявлений
    - Карточка
        - Кнопка отправить письмо репетитору
        - Страница репетитора *TODO

- Форма объявлений
    - Опыт преподавания (лет) сдайдер?

- Добавить поля в advert
    - reject_reason - text
    - Возможность обновлять своё объявление раз в 6 часов
    
- Календарь репетитора, запись к репетитору на урок
    - Возможность отправить сообщение репетитору 
    с просьбой добавить его к определенному занятию
    
- Чат
    - Онлайн юзеры
        - (<div class="user-test {{ $user->isOnline() ? 'active' : '' }}" user-id="{{ $user->id }}" user-active-date="{{ (strtotime($user->active_at) ?? 0) * 1000 }}">
               {{ $user->name }}
           </div>)
    - Чат
        - Dialogs [created_at, updated_at, url (На которой был создан диалог), title?, status, admin_id]
            - admin диалога может заблокировать чат
        - Dialog users [dialog_id, user_id] -
            - любой юзер может покинуть диалог
        - Dialog messages [dialog_id, user_id, message, created_at] - Хранение в elastic|clickHouse|mongoDB
        - Message read [message_id, users_id, created_at]
    - Sockets
        - Следить за написанием в чате
        - Передавать новые сообщения
    
# Install

Перед установкой зависимостей composer следует поставить Elasticsearch - https://www.elastic.co/downloads/elasticsearch

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
    
### Запустить создания индексов для Elasticsearch
    - php artisan search:init
    - php artisan search:import
    
### Laravel Echo
    - https://github.com/tlaverdure/laravel-echo-server
    
### Server 
    - Запуск скрипта в фоне
        - https://ruhighload.com/%D0%9A%D0%B0%D0%BA+%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D1%82%D0%B8%D1%82%D1%8C+%D1%81%D0%BA%D1%80%D0%B8%D0%BF%D1%82+%D0%B2+%D1%84%D0%BE%D0%BD%D0%BE%D0%B2%D0%BE%D0%BC+%D1%80%D0%B5%D0%B6%D0%B8%D0%BC%D0%B5%3f
        - Supervisord - https://ruhighload.com/%D0%9A%D0%B0%D0%BA+%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D1%82%D0%B8%D1%82%D1%8C+php+worker%3f | http://veselov.sumy.ua/2019-12-gotovim-centos-7-ustanovka-i-nastroyka-supervisord-na-vorker.html
        - Пример запуска: nohup php -q /home/admin/web/dev.my-tutor.club/public_html/artisan queue:listen > /var/log/queue.worker.log 2>&1
        - Как проверить запуск - ps ax | grep 'queue:listen'
        
### Запустить в фоне
    - php /home/admin/web/dev.my-tutor.club/public_html/artisan queue:listen (Очереди)
    - php /home/admin/web/dev.my-tutor.club/public_html/artisan socket:listen (Websocket)