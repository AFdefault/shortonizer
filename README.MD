## URL Shortonizer
#### Предисловие
Этот проект не для использования в продакшн среде. Это тестовое задание.

---
#### Запуск приложения
Для запуска приложения необходимо разместить его на веб сервер.

Для веб сервера nginx необходимо указать параметр перенаправления вcех запросов
на файл index.php, вот пример:
```yaml
location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
```
Для apache следующие параметры:
```html
    <Directory /путь/до/приложения/>
            AllowOverride All
            Require all granted
    </Directory>
```

Ну или же просто запустить встроенный в php вебсервер:
```shell
php -S localhost:8080
```
После того как сервер будет запущен, необходимо отредактировать файл __config.php__
который находится в корне проекта. А именно указать в нем верный __app_url__ он служит для 
генерации ссылки.
---
#### Что можно было бы улучшить
1. Добавить валидацию данных на стороне фронтенда
2. Реализовать полноценную обработку исключений
3. Довести до совершенства маршрутизацию
