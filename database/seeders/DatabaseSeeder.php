<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleAdmin;
use App\Models\ArticleStudent;
use App\Models\ArticleTeacher;
use App\Models\Catalog;
use App\Models\CatalogArticle;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Skill;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    private $faker;

    private $catalogs = [
        [
            'title' => 'Информационные технологии',
            'photo' => 'https://www.cleverence.ru/upload/manager/365/e6gntwmdw95w79ey5ua7bfaasmdtn819/content_img.jpeg'
        ],
        [
            'title' => 'Иностранные языки',
            'photo' => 'https://language-centers.ru/img/blog/2021/zachem-voobshche-izuchat-inostrannye-yazyki.jpg'
        ],
        [
            'title' => 'Бизнес и менеджмент',
            'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSW0tumB8qKOxIPE_zASp6QOdDVlFezTN5Vtg&s'
        ],
        [
            'title' => 'Творчество и дизайн',
            'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4piJNxI3gSC6iMcI62t1JKuZjyj6XATJVYQ&s'
        ],
        [
            'title' => 'Личностный рост',
            'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQDgQsbSXXK5RGrn1W5lfc6MTOzwNQU_eNq4g&s'
        ],
        [
            'title' => 'Педагогика',
            'photo' => 'https://distant-college.ru/uplfile/news_image/unnamed.png'
        ],
        // TODO: Добавить фотографии этим облостям
        // [
        //     'title' => 'Учебные и академические дисциплины',
        //     'photo' => 'https://distant-college.ru/uplfile/news_image/unnamed.png'
        // ],
        // [
        //     'title' => 'Архитектура и инженерное дело',

        // ],
        // [
        //     'title' => 'Технологии и инновации',

        // ],
        // [
        //     'title' => 'Досуг',

        // ],
        // [
        //     'title' => 'Здоровье и безопасность',

        // ],
        // [
        //     'title' => 'Общественная деятельность',

        // ],
        // [
        //     'title' => 'Создание курсов на Stepik',

        // ],
    ];

    private $categories = [
        '1' => [
            [
                'title' => 'Языки программирования',
                'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9Oa2TBQyLlyHuqXJPzEEuI-2dSmSOV02hgw&s'
            ],
            [
                'title' => 'Фреймворки и библиотеки',
                'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTMMsvIZy6RebenawmvS7mHa89BmYTWv0hzaQ&s'
            ],
            [
                'title' => 'Электроника и аппаратное обеспечение',
                'photo' => 'https://www.ranepa.ru/upload/iblock/086/es5xhhe7f16zb64bmotm38e5sytj4fjo.jpg'
            ],
            [
                'title' => 'Основы программирования и разработки ПО',
                'photo' => 'https://www.ranepa.ru/upload/iblock/086/es5xhhe7f16zb64bmotm38e5sytj4fjo.jpg'
            ],
            [
                'title' => 'Веб-разработка',
                'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSBQu7n4HgoaH06SoSmNrhjbAZcT55lyGK9eg&s'
            ],
        ]
    ];

    private $subcategories = [
        '1' => [
            'Python',
            'SQL',
            'C/C++',
            'JAVA',
        ],
        '2' => [
            'Django',
            'Pandas',
            'React',
            'Node.js',
            'Flask'
        ],
        '3' => [
            'Проектирование схем',
            'Микроконтроллеры',
            'Робототехника',
            'Arduino'
        ],
        '4' => [
            'С чего начать',
            'Операционные системы',
            'Алгоритмы и структуры данных',
            'Системы контроля версий',
            'Парадигмы программирования',
        ],
        '5' => [
            'Фронтенд-разработка',
            'Бэкенд-разработка',
            'HTML/CSS',
            'JavaScript'
        ],
    ];

    public function __construct()
    {
        $this->faker = Faker::create();
    }
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
        ]);

        User::factory(10)->create();
        Article::factory(10)->create();
        Module::factory(10)->create();

        foreach ($this->catalogs as $catalog) {
            Catalog::factory()->create([
                'title' => $catalog['title'],
                'photo' => $catalog['photo'],
            ]);
        }
        foreach ($this->categories as $key => $categories) {
            foreach ($categories as $category) {
                Category::factory()->create([
                    'title' => $category['title'],
                    'photo' => $category['photo'],
                    'catalog_id' => $key,
                    'description' => $this->faker->text(),
                ]);
            }
        }
        foreach ($this->subcategories as $subcategories) {
            foreach ($subcategories as $subcategory) {
                Subcategory::factory()->create([
                    'title' => $subcategory,
                ]);
            }
        }

        Lesson::factory(10)->create();
        CatalogArticle::factory(10)->create();
        ArticleAdmin::factory(10)->create();
        ArticleTeacher::factory(10)->create();
        ArticleStudent::factory(10)->create();

        Skill::factory(10)->create();
    }
}
