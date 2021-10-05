## Учебный проект «Online shop» (PHP(Laravel)/Bootstrap/JS/with API)

[![PHP%20CI](https://github.com/MT-cod/php-project-online-store-with-api/workflows/PHP%20CI/badge.svg)](https://github.com/MT-cod/php-project-online-store-with-api/actions)
<br>
[![Code Climate](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/badges/gpa.svg)](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api)
[![Issue Count](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/badges/issue_count.svg)](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/issues)
[![Test Coverage](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/badges/coverage.svg)](https://codeclimate.com/github/MT-cod/php-project-online-store-with-api/coverage)

<h2>Цель</h2>

Нужно спроектировать каталог товаров, корзину и заказы для интернет-магазина. Затем реализовать для него JSON API. Для реализации использовать фреймворк Laravel.

Требования к структуре каталога.
Каталог состоит из дерева категорий (максимальная вложенность – 3) и товаров, которые принадлежат к одной из категорий второго/третьего уровня. Товары должны иметь следующие поля:
·         Название
·         Описание
·         Автогенерируемый slug
·         Категория второго/третьего уровня
·         Цена
·         Несколько дополнительных характеристики (например длина, ширина, вес).

Требования к корзине и заказам.
Взаимодействовать с корзиной и оформлять заказы могут как авторизованные, так и неавторизованные пользователи. Заказы должны содержать контактную информацию покупателя (например email и телефон), а также список купленных товаров. Для авторизированных пользователей контактная информация должна подтягиваться из профиля автоматически.

Требования к API.
API должно поддерживать авторизацию (рекомендуется использовать пакет Sanctum).

Рекомендуемый состав методов API.
·         Методы для регистрации/авторизации пользователей.
·         Метод для получения дерева категорий.
·         Метод для получения товаров. Должен поддерживать фильтрацию по категории/категориям любого уровня, а также по цене и дополнительным характеристикам. Значения фильтров должны валидироваться.
·         Метод для получения товара по slug.
·         Методы для работы с корзиной (добавление товара, редактирование количества товара/товаров, удаление товара).
·         Метод для оформления заказа.
·         Метод для получения списка заказов авторизированного пользователя.


Дополнения к заданию.
Будет плюсом, если дополнительные характеристики товаров будут вынесены в отдельную таблицу, а также будет реализовано API (не требующее авторизации) для добавления/удаления данных характеристик. При этом должны работать динамические фильтры для этих характеристик в методе получения товаров.
Будет плюсом, если будут написаны сидеры для каталога товаров.
Также будет плюсом, если разработанное приложение будет разворачиваться с помощью Docker.
Желательно приложить небольшую документацию к API (рекомендуется использовать Postman).
