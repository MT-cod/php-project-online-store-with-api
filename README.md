## Учебный проект «Online store»
<h3>(PHP(8.1)/Laravel(8.73)/Bootstrap/JS/REST API)</h3>

[![PHP%20CI](https://github.com/MT-cod/php-project-online-store-with-api/workflows/PHP%20CI/badge.svg)](https://github.com/MT-cod/php-project-online-store-with-api/actions)
[![Code Climate](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/badges/gpa.svg)](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api)
[![Test Coverage](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/badges/coverage.svg)](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/coverage)

<h2>Описание</h2>
В целях закрепления и усиления знаний при обучении программированию на PHP было найдено одно из понравившихся тестовых заданий, выдаваемых претендентам при найме на работу.
Помимо выполнения самого задания было принято решение разрабатывать собственную версию "онлайн склада (магазина)".

<h2>Текст тестового задания</h2>

Нужно спроектировать каталог товаров, корзину и заказы для интернет-магазина. Затем реализовать для него JSON API. Для реализации использовать фреймворк Laravel.

**Требования к структуре каталога.**<br>
Каталог состоит из дерева категорий (максимальная вложенность – 3) и товаров, которые принадлежат к одной из категорий второго/третьего уровня.<br>
Товары должны иметь следующие поля:<br>
·Название<br>
·Описание<br>
·Автогенерируемый slug<br>
·Категория второго/третьего уровня<br>
·Цена<br>
·Несколько дополнительных характеристики (например длина, ширина, вес).

**Требования к корзине и заказам.**<br>
Взаимодействовать с корзиной и оформлять заказы могут как авторизованные, так и неавторизованные пользователи. Заказы должны содержать контактную информацию покупателя (например email и телефон), а также список купленных товаров. Для авторизированных пользователей контактная информация должна подтягиваться из профиля автоматически.

**Требования к API.**<br>
API должно поддерживать авторизацию (рекомендуется использовать пакет Sanctum).

**Рекомендуемый состав методов API.**<br>
·Методы для регистрации/авторизации пользователей.<br>
·Метод для получения дерева категорий.<br>
·Метод для получения товаров. Должен поддерживать фильтрацию по категории/категориям любого уровня, а также по цене и дополнительным характеристикам. Значения фильтров должны валидироваться. Метод для получения товара по slug.<br>
·Методы для работы с корзиной (добавление товара, редактирование количества товара/товаров, удаление товара).<br>
·Метод для оформления заказа. Метод для получения списка заказов авторизованного пользователя.

**Дополнения к заданию.**<br>
Будет плюсом, если дополнительные характеристики товаров будут вынесены в отдельную таблицу, а также будет реализовано API (не требующее авторизации) для добавления/удаления данных характеристик. При этом должны работать динамические фильтры для этих характеристик в методе получения товаров.
Будет плюсом, если будут написаны сидеры для каталога товаров.
Также будет плюсом, если разработанное приложение будет разворачиваться с помощью Docker.
Желательно приложить небольшую документацию к API (рекомендуется использовать Postman).

## Документация к API проекта на getpostman.com:
https://documenter.getpostman.com/view/18230245/UVJfhuYT

## Развёрнутый проект на Heroku:
<a href="http://php-online-store-with-api.herokuapp.com/">php-online-store-with-api.herokuapp.com</a>

## Готовый docker-образ с проектом:
<a href="https://hub.docker.com/r/mtcod/php-project-online-store-with-api">mtcod/php-project-online-store-with-api</a>

###### Пример загрузки и запуска контейнера проекта:
<code>docker run -p 80:8000 -d mtcod/php-project-online-store-with-api php /srv/php-project-online-store-with-api/artisan serve --host 0.0.0.0</code>
