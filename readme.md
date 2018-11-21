## PM helper Application
Итоговая работа по курсу продвинутый PHP компаниии Mediasoft: Project Manager helper. Основные возможности:
- управление пользователями двух возможных ролей: администратор и менеджер (контактная информация, аватарка, файл с портфолио, блокировка/разблокировка, изменение роли)
- CRUD для проектов (названия. описание, направление, сроки начала и окончания, вложенная документация, технологии проекта, чек-лист задач по проекту)
- CRUDs для вспомогательных сущностей: технологии, направления, категории оповещений
- возможность оставлять отзывы о проекте в целом, о менеджере
- оповещения пользователей о некоторых видах событий (просмотр информации о проекте, получение отзыва и пр.)
- страница настроек приложения
- TODO: restApi
- TODO: оповещение о пропущенных сроках проекта/задач из чеклиста

#### Развернуть проект
```
git clone git@github.com:aplatonov/php-learning-itog.git
cd php-learning-itog
composer install
```

#### Миграции и настройка storage
```
php artisan migrate --seed
php artisan storage:link
```

#### Настройки приложения в .env
Создайте БД, укажите доступ к ней в строках DB_***
```
/* для работы без почтового сервера укажите */
MAIL_DRIVER=log
MAIL_FROM_ADDRESS=admin@pmhelper.com
```

Сгенерируйте ключ приложения (может понадобиться очистка кэша конфига)
```
php artisan key:generate
php artisan config:cache
```

#### Настройка работы api
Для api-аутентификации подготовьте к использованию Laravel Passport (полная реализация OAuth2 сервера)  
```
php artisan passport:install
```
##### Api-ресурсы
###### Пользователи (менеджеры)
Login: POST, `$URL%/api/login` (parameters: login, password)  
Register: POST, `$URL%/api/register` (parameters: login, email, password, password_confirmation, name, contact_person, phone)  
List: GET, `$URL%/api/users` (headers: Authorization "Bearer %token%", Accept "application/json") (далее во всех ресурсах, требующих авторизации передаются эти заголовки)   
Confirm user: GET, `$URL%/api/user/{id}/confirm` (headers...)  
###### Проекты
List: GET, `$URL%/api/projects` (headers...)   
Show project: GET, `$URL%/api/projects/{id}` (headers...)
