<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('roles')->insert([
            ['title' => 'Администратор', 'slug' => 'admin'],
            ['title' => 'Пользователь', 'slug' => 'user']
        ]);  

        DB::table('users')->insert([
            ['login' => 'admin',
            'email' => 'a432974@yandex.ru',
            'password' => bcrypt('admin'),
            'name' => 'Юля П.',
            'contact_person' => 'Петрова Юлия',
            'phone' => '+7-927-456-78-90',
            'role_id' => 1,
            'valid' => true,
            'confirmed' => true],

            ['login' => 'user',
            'email' => 'fake@yandex.ru',
            'password' => bcrypt('user'),
            'name' => 'Юра К.',
            'contact_person' => 'Юрий Васильевич Копков',
            'phone' => '+7-123-456-78-90',
            'role_id' => 2,
            'valid' => true,
            'confirmed' => true]
        ]);

        DB::table('speciality')->insert([
            ['name' => 'Веб-разработка'],
            ['name' => 'Мобильные приложения'],
            ['name' => 'Системное ПО'],
            ['name' => 'Прикладное ПО'],
            ['name' => 'Интеграция'],
            ['name' => 'DevOps']
        ]); 

        DB::table('technology')->insert([
            ['name' => 'MySQL'],
            ['name' => 'MSSQL'],
            ['name' => 'HTML/CSS'],
            ['name' => 'JavaScript'],
            ['name' => 'PHP'],
            ['name' => 'Java'],
            ['name' => 'SCALA'],
            ['name' => 'Python'],
            ['name' => 'BigData'],
            ['name' => '.NET'],
            ['name' => 'Smarty'],
            ['name' => 'ООП'],
            ['name' => 'Design Patterns'],
            ['name' => 'Laravel'],
            ['name' => 'Yii'],
            ['name' => 'Zend'],
            ['name' => 'Bitrix'],
            ['name' => 'Vagrant'],
            ['name' => 'Docker'],
            ['name' => 'CSS'],
            ['name' => 'Яндекс.Директ'],
            ['name' => 'Google AdWords']
        ]);

        DB::table('notes_category')->insert([
            ['name' => 'Заметка'],
            ['name' => 'Запрос контактов менеджера'],
            ['name' => 'Запрос чеклиста проекта'],
            ['name' => 'Просмотр проекта'],
            ['name' => 'Отзыв о компании'],
            ['name' => 'Отзыв о сервисе'],
            ['name' => 'Форма обратной связи']
        ]);

        DB::table('settings')->insert([
            [
                'settings_name' => 'main',
                'how_it_works_1' => <<<EOT
Приложение позволяет менеджерам проектов компании вести учет проектов, отмечая, при необходимости, выполнение задач по проекту.
Регистрация пользователей свободная, но для получения доступа к функционалу необходимо активизация логина и подтверждение со стороны администратора.  
Проекты имеют название и описание, категорию-направление, сроки начала и окончания, также список технологий проекта и другие атрибуты. Проекты можно публиковать в общем списке и тогда его просмотр станет доступным для других менеджеров.
В приложении реализована система оповещений пользователей о событиях: просмотра инфомации о проекте, получении отзыва, заполнении формы обратной связи и других.<br>
<b>Основные возможности</b>:
<ul>
<li> управление пользователями двух возможных ролей: администратор и менеджер (контактная информация, аватарка, файл с портфолио, блокировка/разблокировка, изменение роли)</li>
<li> CRUD для проектов (названия. описание, направление, сроки начала и окончания, вложенная документация, технологии проекта, чек-лист задач по проекту)</li>
<li> CRUDs для вспомогательных сущностей: технологии, направления, категории оповещений</li>
<li> возможность оставлять отзывы о проекте в целом, о менеджере</li>
<li> оповещения пользователей о некоторых видах событий (просмотр информации о проекте, получение отзыва и пр.)</li>
<li> страница настроек приложения</li>
<li> restApi для пользователей (Laravel/Passport реализация OAuth2),</li> 
<li> restApi проектов</li>
<li> эндпоинты для получения списка технологий, направлений</li>
</ul>
EOT
                ,
                'how_it_works_2' => <<<EOT
<b>Api-ресурсы</b>
<small><b>Пользователи (менеджеры)</b>
Login: POST, `%URL%/api/login` (parameters: login, password)  
Register: POST, `%URL%/api/register` (parameters: login, email, password, password_confirmation, name, contact_person, phone)  
List: GET, `%URL%/api/users` (headers: Authorization "Bearer %token%", Accept "application/json") (далее во всех ресурсах, требующих авторизации передаются эти заголовки)   
Confirm user: GET, `%URL%/api/user/{id}/confirm` (headers...)  
<b>Проекты</b>
List: GET, `%URL%/api/projects` (headers...)   
Show project: GET, `%URL%/api/projects/{id}` (headers...)  
Create project: POST, `%URL%/api/projects` (headers..., parameters: project_name, description, speciality_id)  
Delete project: DELETE, `%URL%/api/projects/{id}` (headers...)  
Update project: PUT, `%URL%/api/projects/{id}` (headers..., parameters: project_name, description, speciality_id)
<b>Технологии проектов</b>
List: GET, `%URL%/api/techs` (headers...)
<b>Специализации (направления проектов)</b>
List: GET, `%URL%/api/specs` (headers...)
<b>Чек-лист проекта</b>      
List: GET, `%URL%/api/projects/{id}/marks` (headers...)  
Mark as completed: PUT, `%URL%/api/projects/{id}/marks/{id_mark}/done` (headers..., parameters: is_done)

<b>Пользователи по-умолчанию</b>
admin/admin
user/user
</small>
EOT
                ,
                'how_contact_us' => 'Связаться с нами очень легко',
                'address' => 'ЮАР, Кейптаун, ул. Манделы, 25',
                'phone' => '+12345678990',
                'email' => 'admin@lalala.ru'
            ],
        ]);
    }
}
