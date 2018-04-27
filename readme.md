- Добавить поля в advert
    - reject_reason - text
    - Возможность обновлять своё объявление раз в 6 часов
    
- Календарь репетитора, запись к репетитору на урок
    - Возможность отправить сообщение репетитору 
    с просьбой добавить его к определенному занятию
    
# Install

### Установка ролей
    - docker-compose exec php-cli php artisan user:roles
    
### После регистрации, активируем своего пользователя
    - docker-compose exec php-cli php artisan user:verify {email}
    
### Устанавливаем роль админа
    - docker-compose exec php-cli php artisan user:assign {email} {role}