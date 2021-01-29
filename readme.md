### Apple - тестовый проект с яблоками

Тестовый проект с яблоками. Основанный на advanced шаблоне yii2.

Для установки клонируем проект:
>git clone git@github.com:ivphpan/apple.git

Переходим в каталог с проектом:
>cd apple`

Запускаем контайнеры:
>docker-composer up -d`

Заходим в контейнер backend:
>docker-composer exec backend bash`

Инициализируем окружение:
>./init` - Выбираем любое окружение

Устанавливаем немобходимые расширения:
>composer install`

Выполняем миграции:
>./yii migrate`

Прописываем в ***host*** файле записи:

>**127.0.0.1** backend.prhol

>**127.0.0.1** frontend.prhol

Сохраняем

Переходим по адресу:
>http://backend.prhol/apple-garden


Вводим:

**Логин**: *ivphpan*

**Пароль**: *123*

Для консольного тестирования доступен контроллер:
>`backend/src/console/controllers/AppleController.php`