### PM helper Application
Итоговая работа по курсу продвинутый PHP компаниии Mediasoft

##### Развернуть проект
```
git clone git@github.com:aplatonov/php-learning-itog.git
cd php-learning-itog
composer install
```

##### Миграции и настройка storage
```
php artisan migrate --seed
php artisan storage:link
```

##### Настройки приложения в .env
Создайте БД, укажите доступ к ней в строках DB_***
```
/* для работы без почтового сервера укажите */
MAIL_DRIVER=log
MAIL_FROM_ADDRESS=admin@pmhelper.com
```
