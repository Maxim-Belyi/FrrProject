# ФРР РБ

## Выгрузка на html.xpager.ru
1. Скопировать ssh-ключ (делается единоразово)
	```
	ssh-copy-id html.xpager.ru@html.xpager.ru
	```
    [passbolt](https://passbolt.xpage.ru/app/passwords/view/ee163979-2386-4b39-9e13-4ac5c361990c)
2. Запустить команду:
	```
	npm run deploy-layout
	```

### Требования

- [Node.js](https://nodejs.org/en/) >= 22

### Доступные команды

### Конверторы
- [Фавиконки](https://realfavicongenerator.net/)
- [Шрифты](https://convertio.co/ru/)
- [Шрифты (альтернатива)](https://transfonter.org/)

## Структура папок и файлов

```
|-- config                                    # Настройки сборки
|-- src
|   |-- app                                   # Основной модуль
|   |   |-- app.components.global.ts          # Регистрация глобальных компонентов
|   |   |-- app.ts
|   |   |-- main.ts
|   |   |-- modules                           # Логически изолированные модули
|   |   |   |-- forms
|   |   |   |   |-- api
|   |   |   |   |-- components
|   |   |   |   `-- store
|   |   |   `-- user
|   |   |   |   |-- api
|   |   |   |   |-- store
|   |   |   |-- RootComponent.vue
|   |   `-- shared                            # Общие модули
|   |       |-- api
|   |       |-- components                    # Универсальные компоненты
|   |       |-- composables                   # Пользовательские хуки и функции
|   |       |-- consts
|   |       |-- declarations
|   |       |-- directives
|   |       |-- router
|   |       |-- scripts
|   |       |-- stores
|   |       |   |-- base
|   |       |   `-- modals
|   |       `-- utils
|   |-- assets
|   |   `-- icons
|   |-- index.html
|   |-- public
|   |   |-- favicons
|   |   |-- fonts
|   |   `-- img
|   |-- styles
|   |   |-- base
|   |   |   |-- animations.sass
|   |   |   |-- base.sass
|   |   |   |-- _index.sass
|   |   |   |-- keyframes.sass
|   |   |   |-- mixins
|   |   |   |-- null.sass
|   |   |   |-- preview-styles.sass
|   |   |   |-- text.sass
|   |   |   `-- variables.sass
|   |   |-- content
|   |   |-- emails
|   |   `-- shared
|   |   |-- index.sass
|   |-- templates
|   |   |-- base
|   |   |-- content
|   |   |-- emails
|   |   |-- main
|   |   `-- shared
|   `-- views
```
