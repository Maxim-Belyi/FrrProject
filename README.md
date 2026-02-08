### Проектный менеджер 
Ульяна Иванова.

### Бэкенд - разработчики 
Белый Максим, Ильин Илья, Жилин Павел

### Фронтенд 
разработчик - ФИО.

### Верстка 
```
https://html.xpager.ru/frr-rb/
```

### Макет 
ссылка на макет в Фигме.

### Dev площадка 
```sh
https://bely.students.xpage.dev/
```

### Продакшн 
```sh
https://bely.students.xpage.dev/
```
* для ветки 'main' настроен ci\cd;
по бэку и доступам можно писать <a href="https://t.me/the_white_m" target="_blank"><img align="center" src="https://img.shields.io/badge/Telegram-26A5E4?style=flat&logo=telegram&logoColor=white" alt="Telegram"/></a>

### Развернуть локально

1.  **Клонируйте репозиторий:**
    ```sh
    git clone https://gitlab.in-progress.ru/students/belyiproject.git;
    ```

2.  **Перейдите в папку проекта:**
    ```sh
    cd MyDocs
    ```

3.  **Установите зависимости:**
    ```sh
    npm install
    ```

4.  **Запустите сервер для разработки:**
    ```sh
    npm run start
    ```
### Общая информация о структуре проекта
#### Директории и файлы:

* /public/local/php_interface/classes/ – код с бизнес-логикой приложения

* /public/local/routes – маршруты

* /public/pages – все публичные страницы проекта

#### Особенности архитектуры:

* Используются модули xpage.core, xpage.settings, xpage - интеграция сс hh

#### Сервисы к которым обращается сайт
* Яндекс капча для выявления ботов;
* для отзывов вставлен сторонний виджет.

#### Особенности фронт сборки
* при первом запуске необходимо установить зависимости с версией node >= 18.18.0 или >= 20.9.0;
* сборка frontend исходников в папку public/local/frontend-build.