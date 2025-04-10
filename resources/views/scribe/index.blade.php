<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>API Магазина Магических Товаров</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.1.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.1.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
            <img src="img/logo.png" alt="logo" class="logo" style="padding-top: 10px;" width="100%"/>
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-api-magazina-magiceskix-tovarov" class="tocify-header">
                <li class="tocify-item level-1" data-unique="api-magazina-magiceskix-tovarov">
                    <a href="#api-magazina-magiceskix-tovarov">API Магазина Магических Товаров</a>
                </li>
                                    <ul id="tocify-subheader-api-magazina-magiceskix-tovarov" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="dobro-pozalovat-v-dokumentaciiu-api">
                                <a href="#dobro-pozalovat-v-dokumentaciiu-api">Добро пожаловать в документацию API</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-autentifikaciia" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autentifikaciia">
                    <a href="#autentifikaciia">Аутентификация</a>
                </li>
                                    <ul id="tocify-subheader-autentifikaciia" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-register">
                                <a href="#autentifikaciia-POSTapi-v1-register">Регистрация нового пользователя</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-kategorii" class="tocify-header">
                <li class="tocify-item level-1" data-unique="kategorii">
                    <a href="#kategorii">Категории</a>
                </li>
                                    <ul id="tocify-subheader-kategorii" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="kategorii-GETapi-v1-categories">
                                <a href="#kategorii-GETapi-v1-categories">Получение списка категорий</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="kategorii-GETapi-v1-categories--slug-">
                                <a href="#kategorii-GETapi-v1-categories--slug-">Получение категории по уникальному идентификатору (slug)</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-obshhie-endpointy" class="tocify-header">
                <li class="tocify-item level-1" data-unique="obshhie-endpointy">
                    <a href="#obshhie-endpointy">Общие эндпоинты</a>
                </li>
                                    <ul id="tocify-subheader-obshhie-endpointy" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="obshhie-endpointy-GETapi-health">
                                <a href="#obshhie-endpointy-GETapi-health">GET api/health</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="obshhie-endpointy-GETapi-v1-health">
                                <a href="#obshhie-endpointy-GETapi-v1-health">GET api/v1/health</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-produkty" class="tocify-header">
                <li class="tocify-item level-1" data-unique="produkty">
                    <a href="#produkty">Продукты</a>
                </li>
                                    <ul id="tocify-subheader-produkty" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="produkty-GETapi-v1-products--slug-">
                                <a href="#produkty-GETapi-v1-products--slug-">Получить детальную информацию о продукте</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="produkty-GETapi-v1-products">
                                <a href="#produkty-GETapi-v1-products">Получить список продуктов с фильтрацией, сортировкой и пагинацией.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: April 4, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="api-magazina-magiceskix-tovarov">API Магазина Магических Товаров</h1>
<h2 id="dobro-pozalovat-v-dokumentaciiu-api">Добро пожаловать в документацию API</h2>
<p>Данное API предоставляет доступ к каталогу магических товаров, включая категории и продукты. Используйте его для интеграции нашего магического ассортимента в ваши приложения.</p>
<h3 id="nacalo-raboty">Начало работы</h3>
<ol>
<li>Изучите документацию эндпоинтов</li>
<li>Используйте предоставленные примеры запросов</li>
<li>Тестируйте запросы прямо из документации с помощью &quot;Try it out&quot;</li>
</ol>
<h3 id="osnovnye-razdely-api">Основные разделы API</h3>
<ul>
<li><strong>Аутентификация</strong> — регистрация новых пользователей</li>
<li><strong>Категории</strong> — получение списка категорий и подкатегорий товаров</li>
<li><strong>Продукты</strong> — получение списка товаров с фильтрацией и подробной информацией о товаре</li>
</ul>
<h3 id="formaty-dannyx">Форматы данных</h3>
<p>Все ответы API возвращаются в формате JSON, с соответствующим HTTP-кодом состояния.</p>

        <!-- Этот файл намеренно оставлен пустым -->

        <h1 id="autentifikaciia">Аутентификация</h1>

    <p>API для регистрации и авторизации пользователей</p>
<p>Для работы с API вам необходимо зарегистрировать пользователя через эндпоинт <code>POST /api/v1/register</code>.
После успешной регистрации вы получите доступ ко всем эндпоинтам API.</p>
<h2>Формат регистрационных данных</h2>
<p>Регистрационные данные должны быть отправлены в формате JSON с необходимыми полями:</p>
<ul>
<li><code>first_name</code> - Имя пользователя</li>
<li><code>last_name</code> - Фамилия пользователя</li>
<li><code>email</code> - Электронный адрес (должен быть уникальным)</li>
<li><code>password</code> - Пароль (минимум 8 символов)</li>
</ul>
<p>Остальные поля являются опциональными:</p>
<ul>
<li><code>middle_name</code> - Отчество</li>
<li><code>phone</code> - Номер телефона</li>
<li><code>country</code>, <code>region</code>, <code>city</code>, <code>postal_code</code>, <code>address</code> - Адресные данные</li>
</ul>

                                <h2 id="autentifikaciia-POSTapi-v1-register">Регистрация нового пользователя</h2>

<p>
</p>

<p>Этот эндпоинт позволяет создать нового пользователя. После успешной регистрации возвращается токен доступа
и данные пользователя. Обязательными полями являются first_name, last_name, email и password.</p>

<span id="example-requests-POSTapi-v1-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"Иван\",
    \"last_name\": \"Иванов\",
    \"middle_name\": \"Иванович\",
    \"email\": \"user@example.com\",
    \"password\": \"password123\",
    \"password_confirmation\": \"password123\",
    \"phone\": \"+7 (999) 123-45-67\",
    \"country\": \"Россия\",
    \"region\": \"Московская область\",
    \"city\": \"Москва\",
    \"postal_code\": \"123456\",
    \"address\": \"ул. Пушкина, д. 1\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "Иван",
    "last_name": "Иванов",
    "middle_name": "Иванович",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+7 (999) 123-45-67",
    "country": "Россия",
    "region": "Московская область",
    "city": "Москва",
    "postal_code": "123456",
    "address": "ул. Пушкина, д. 1"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-register">
            <blockquote>
            <p>Example response (201, Успешная регистрация):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;token&quot;: &quot;9|MfOSV0Iqv4yGIJZGMhUZpzb4Yjs24rGhQHZJ7zOY&quot;,
    &quot;user&quot;: {
        &quot;id&quot;: 11,
        &quot;first_name&quot;: &quot;Иван&quot;,
        &quot;last_name&quot;: &quot;Иванов&quot;,
        &quot;middle_name&quot;: &quot;Иванович&quot;,
        &quot;email&quot;: &quot;user@example.com&quot;,
        &quot;email_verified_at&quot;: null,
        &quot;phone&quot;: &quot;+7 (999) 123-45-67&quot;,
        &quot;country&quot;: &quot;Россия&quot;,
        &quot;region&quot;: &quot;Московская область&quot;,
        &quot;city&quot;: &quot;Москва&quot;,
        &quot;postal_code&quot;: &quot;123456&quot;,
        &quot;address&quot;: &quot;ул. Пушкина, д. 1&quot;,
        &quot;created_at&quot;: &quot;2023-04-04T12:30:45.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-04T12:30:45.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Ошибка валидации):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;The email has already been taken.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-register" data-method="POST"
      data-path="api/v1/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-register"
                    onclick="tryItOut('POSTapi-v1-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-register"
                    onclick="cancelTryOut('POSTapi-v1-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="POSTapi-v1-register"
               value="Иван"
               data-component="body">
    <br>
<p>Имя пользователя. Example: <code>Иван</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-v1-register"
               value="Иванов"
               data-component="body">
    <br>
<p>Фамилия пользователя. Example: <code>Иванов</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>middle_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="middle_name"                data-endpoint="POSTapi-v1-register"
               value="Иванович"
               data-component="body">
    <br>
<p>Отчество пользователя. Example: <code>Иванович</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-register"
               value="user@example.com"
               data-component="body">
    <br>
<p>Email пользователя (должен быть уникальным). Example: <code>user@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-register"
               value="password123"
               data-component="body">
    <br>
<p>Пароль (минимум 8 символов). Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-v1-register"
               value="password123"
               data-component="body">
    <br>
<p>Пароль (минимум 8 символов). Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-v1-register"
               value="+7 (999) 123-45-67"
               data-component="body">
    <br>
<p>Номер телефона в формате +7 (XXX) XXX-XX-XX. Example: <code>+7 (999) 123-45-67</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="POSTapi-v1-register"
               value="Россия"
               data-component="body">
    <br>
<p>Страна. Example: <code>Россия</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>region</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="region"                data-endpoint="POSTapi-v1-register"
               value="Московская область"
               data-component="body">
    <br>
<p>Регион/область. Example: <code>Московская область</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-v1-register"
               value="Москва"
               data-component="body">
    <br>
<p>Город. Example: <code>Москва</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>postal_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="postal_code"                data-endpoint="POSTapi-v1-register"
               value="123456"
               data-component="body">
    <br>
<p>Почтовый индекс. Example: <code>123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-v1-register"
               value="ул. Пушкина, д. 1"
               data-component="body">
    <br>
<p>Адрес. Example: <code>ул. Пушкина, д. 1</code></p>
        </div>
        </form>

                <h1 id="kategorii">Категории</h1>

    <p>API для работы с категориями товаров</p>
<p>Категории представляют иерархическую структуру типов товаров магазина.
Каждая категория может иметь родительскую категорию и дочерние категории,
что позволяет строить многоуровневое дерево категорий.</p>
<h2>Структура категории</h2>
<p>Каждая категория содержит следующие основные поля:</p>
<ul>
<li><code>id</code> - Уникальный идентификатор категории</li>
<li><code>name</code> - Название категории</li>
<li><code>slug</code> - Уникальный текстовый идентификатор для URL</li>
<li><code>description</code> - Описание категории</li>
<li><code>icon</code> - URL иконки категории</li>
<li><code>parent_id</code> - ID родительской категории (null для корневых категорий)</li>
<li><code>sort_order</code> - Порядок сортировки</li>
<li><code>is_visible</code> - Флаг видимости категории</li>
<li><code>children</code> - Массив дочерних категорий (если запрошены)</li>
</ul>
<h2>Использование API категорий</h2>
<p>API категорий позволяет получить как полный список категорий с их иерархией,
так и детальную информацию по отдельной категории. Для идентификации конкретной
категории используется её slug (например, &quot;ritualnye-svechi&quot;).</p>

                                <h2 id="kategorii-GETapi-v1-categories">Получение списка категорий</h2>

<p>
</p>

<p>Возвращает список всех категорий магазина. По умолчанию включает все видимые категории.
Вы можете использовать параметр <code>show_hidden</code> для отображения скрытых категорий.</p>

<span id="example-requests-GETapi-v1-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/categories?show_hidden=" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/categories"
);

const params = {
    "show_hidden": "0",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-categories">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Все свечи&quot;,
            &quot;slug&quot;: &quot;vse-svechi&quot;,
            &quot;description&quot;: &quot;Категория, включающая все типы свечей&quot;,
            &quot;icon&quot;: &quot;http://localhost:8000/storage/1/candle2.svg&quot;,
            &quot;parent_id&quot;: null,
            &quot;sort_order&quot;: 1,
            &quot;is_visible&quot;: true,
            &quot;children&quot;: [
                {
                    &quot;id&quot;: 2,
                    &quot;name&quot;: &quot;Ритуальные Свечи&quot;,
                    &quot;slug&quot;: &quot;ritualnye-svechi&quot;,
                    &quot;description&quot;: &quot;Свечи для различных ритуалов и церемоний&quot;,
                    &quot;icon&quot;: &quot;http://localhost:8000/storage/2/candle3.svg&quot;,
                    &quot;parent_id&quot;: 1,
                    &quot;sort_order&quot;: 1,
                    &quot;is_visible&quot;: true
                }
            ]
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-categories" data-method="GET"
      data-path="api/v1/categories"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-categories"
                    onclick="tryItOut('GETapi-v1-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-categories"
                    onclick="cancelTryOut('GETapi-v1-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>show_hidden</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-v1-categories" style="display: none">
            <input type="radio" name="show_hidden"
                   value="1"
                   data-endpoint="GETapi-v1-categories"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-categories" style="display: none">
            <input type="radio" name="show_hidden"
                   value="0"
                   data-endpoint="GETapi-v1-categories"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Показать скрытые категории. Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="kategorii-GETapi-v1-categories--slug-">Получение категории по уникальному идентификатору (slug)</h2>

<p>
</p>

<p>Возвращает детальную информацию о категории включая дочерние категории.</p>

<span id="example-requests-GETapi-v1-categories--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/categories/ritualnye-svechi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/categories/ritualnye-svechi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-categories--slug-">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Ритуальные Свечи&quot;,
        &quot;slug&quot;: &quot;ritualnye-svechi&quot;,
        &quot;description&quot;: &quot;Свечи для различных ритуалов и церемоний&quot;,
        &quot;icon&quot;: &quot;http://localhost:8000/storage/2/candle3.svg&quot;,
        &quot;parent_id&quot;: 1,
        &quot;sort_order&quot;: 1,
        &quot;is_visible&quot;: true,
        &quot;children&quot;: [
            {
                &quot;id&quot;: 5,
                &quot;name&quot;: &quot;Свечи для привлечения денег&quot;,
                &quot;slug&quot;: &quot;svechi-dlya-privlecheniya-deneg&quot;,
                &quot;description&quot;: &quot;Специальные свечи для денежных ритуалов&quot;,
                &quot;icon&quot;: &quot;http://localhost:8000/storage/5/candle2.svg&quot;,
                &quot;parent_id&quot;: 2,
                &quot;sort_order&quot;: 1,
                &quot;is_visible&quot;: true
            },
            {
                &quot;id&quot;: 6,
                &quot;name&quot;: &quot;Любовные свечи&quot;,
                &quot;slug&quot;: &quot;lyubovnye-svechi&quot;,
                &quot;description&quot;: &quot;Свечи для привлечения любви и укрепления отношений&quot;,
                &quot;icon&quot;: &quot;http://localhost:8000/storage/6/candle2.svg&quot;,
                &quot;parent_id&quot;: 2,
                &quot;sort_order&quot;: 2,
                &quot;is_visible&quot;: true
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Категория не найдена):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Category].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-categories--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-categories--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-categories--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-categories--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-categories--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-categories--slug-" data-method="GET"
      data-path="api/v1/categories/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-categories--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-categories--slug-"
                    onclick="tryItOut('GETapi-v1-categories--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-categories--slug-"
                    onclick="cancelTryOut('GETapi-v1-categories--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-categories--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/categories/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-categories--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-categories--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-v1-categories--slug-"
               value="ritualnye-svechi"
               data-component="url">
    <br>
<p>Уникальный идентификатор категории. Example: <code>ritualnye-svechi</code></p>
            </div>
                    </form>

                <h1 id="obshhie-endpointy">Общие эндпоинты</h1>

    

                                <h2 id="obshhie-endpointy-GETapi-health">GET api/health</h2>

<p>
</p>



<span id="example-requests-GETapi-health">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/health" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/health"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-health">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;ok&quot;,
    &quot;message&quot;: &quot;Service is healthy&quot;,
    &quot;timestamp&quot;: &quot;2025-04-04T08:55:57+00:00&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-health" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-health"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-health"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-health" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-health">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-health" data-method="GET"
      data-path="api/health"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-health', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-health"
                    onclick="tryItOut('GETapi-health');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-health"
                    onclick="cancelTryOut('GETapi-health');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-health"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/health</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-health"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-health"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="obshhie-endpointy-GETapi-v1-health">GET api/v1/health</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-health">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/health" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/health"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-health">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;ok&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-health" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-health"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-health"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-health" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-health">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-health" data-method="GET"
      data-path="api/v1/health"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-health', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-health"
                    onclick="tryItOut('GETapi-v1-health');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-health"
                    onclick="cancelTryOut('GETapi-v1-health');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-health"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/health</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-health"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-health"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="produkty">Продукты</h1>

    <p>API для работы с продуктами магазина</p>
<p>Продукты - основные товары, доступные в магазине магических товаров.
API предоставляет возможности для получения списка продуктов с фильтрацией,
сортировкой и пагинацией, а также детальной информации о конкретном продукте.</p>
<h2>Структура продукта</h2>
<p>Каждый продукт содержит следующие основные поля:</p>
<ul>
<li><code>id</code> - Уникальный идентификатор продукта</li>
<li><code>name</code> - Название продукта</li>
<li><code>slug</code> - Уникальный текстовый идентификатор для URL</li>
<li><code>description</code> - Описание продукта</li>
<li><code>price</code> - Текущая цена</li>
<li><code>dimensions</code> - Физические характеристики (ширина, высота, глубина, вес)</li>
<li><code>categories</code> - Категории, к которым относится продукт</li>
<li><code>images_urls</code> - Массив URL изображений продукта</li>
<li><code>is_new</code> - Флаг новинки</li>
<li><code>is_bestseller</code> - Флаг хита продаж</li>
</ul>
<h2>Фильтрация и сортировка</h2>
<p>API продуктов предоставляет разнообразные возможности фильтрации:</p>
<ul>
<li>По категории</li>
<li>По ценовому диапазону</li>
<li>По наличию статуса новинки или хита продаж</li>
<li>По текстовому поиску в названии</li>
</ul>
<p>Доступные варианты сортировки:</p>
<ul>
<li>По цене (возрастание/убывание)</li>
<li>По названию (возрастание/убывание)</li>
<li>По дате добавления (убывание)</li>
</ul>

                                <h2 id="produkty-GETapi-v1-products--slug-">Получить детальную информацию о продукте</h2>

<p>
</p>

<p>Этот эндпоинт возвращает детальную информацию о конкретном продукте, включая его категории,
связанные товары и медиафайлы. Продукт идентифицируется по его уникальному slug.</p>

<span id="example-requests-GETapi-v1-products--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/products/aromaticheskaya-svecha-lavanda" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/products/aromaticheskaya-svecha-lavanda"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-products--slug-">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ароматическая свеча Лаванда&quot;,
        &quot;slug&quot;: &quot;aromaticheskaya-svecha-lavanda&quot;,
        &quot;description&quot;: &quot;Успокаивающий аромат лаванды для безмятежного отдыха&quot;,
        &quot;price&quot;: 1200.99,
        &quot;dimensions&quot;: {
            &quot;width&quot;: 10,
            &quot;height&quot;: 12,
            &quot;depth&quot;: 10,
            &quot;weight&quot;: 350
        },
        &quot;categories&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Ароматические свечи&quot;,
                &quot;slug&quot;: &quot;aromaticheskie-svechi&quot;,
                &quot;description&quot;: &quot;Свечи с различными ароматами&quot;,
                &quot;icon&quot;: &quot;http://localhost:8000/storage/7/candle4.svg&quot;,
                &quot;parent_id&quot;: null,
                &quot;sort_order&quot;: 4,
                &quot;is_visible&quot;: true
            }
        ],
        &quot;related&quot;: [],
        &quot;images_urls&quot;: [
            &quot;http://localhost:8000/storage/1/images/candle1.jpg&quot;
        ],
        &quot;created_at&quot;: &quot;2023-01-01T12:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-01-01T12:00:00.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Продукт не найден):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Запрашиваемый ресурс не найден&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-products--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-products--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-products--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-products--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-products--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-products--slug-" data-method="GET"
      data-path="api/v1/products/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-products--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-products--slug-"
                    onclick="tryItOut('GETapi-v1-products--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-products--slug-"
                    onclick="cancelTryOut('GETapi-v1-products--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-products--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/products/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-products--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-products--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-v1-products--slug-"
               value="aromaticheskaya-svecha-lavanda"
               data-component="url">
    <br>
<p>Уникальный идентификатор продукта. Example: <code>aromaticheskaya-svecha-lavanda</code></p>
            </div>
                    </form>

                    <h2 id="produkty-GETapi-v1-products">Получить список продуктов с фильтрацией, сортировкой и пагинацией.</h2>

<p>
</p>

<p>Этот эндпоинт возвращает пагинированный список продуктов с возможностью фильтрации
по различным параметрам, включая категорию, ценовой диапазон и статус &quot;новинка&quot;.</p>

<span id="example-requests-GETapi-v1-products">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/products?search=%D1%81%D0%B2%D0%B5%D1%87%D0%B0&amp;category=aromaticheskie-svechi&amp;price_from=100&amp;price_to=500&amp;is_new=1&amp;is_bestseller=1&amp;ids=1%2C2%2C3&amp;sort=price_asc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/products"
);

const params = {
    "search": "свеча",
    "category": "aromaticheskie-svechi",
    "price_from": "100",
    "price_to": "500",
    "is_new": "1",
    "is_bestseller": "1",
    "ids": "1,2,3",
    "sort": "price_asc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-products">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Ароматическая свеча Лаванда&quot;,
            &quot;slug&quot;: &quot;aromaticheskaya-svecha-lavanda&quot;,
            &quot;description&quot;: &quot;Успокаивающий аромат лаванды для безмятежного отдыха&quot;,
            &quot;price&quot;: 1200.99,
            &quot;dimensions&quot;: {
                &quot;width&quot;: 10,
                &quot;height&quot;: 12,
                &quot;depth&quot;: 10,
                &quot;weight&quot;: 350
            },
            &quot;images_urls&quot;: [
                &quot;http://localhost:8000/storage/1/images/candle1.jpg&quot;
            ],
            &quot;created_at&quot;: &quot;2023-01-01T12:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2023-01-01T12:00:00.000000Z&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/api/v1/products?page=1&quot;,
        &quot;last&quot;: &quot;/api/v1/products?page=5&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;/api/v1/products?page=2&quot;
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 5,
        &quot;path&quot;: &quot;/api/v1/products&quot;,
        &quot;per_page&quot;: 15,
        &quot;to&quot;: 15,
        &quot;total&quot;: 75
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-products" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-products"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-products"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-products">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-products" data-method="GET"
      data-path="api/v1/products"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-products', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-products"
                    onclick="tryItOut('GETapi-v1-products');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-products"
                    onclick="cancelTryOut('GETapi-v1-products');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-products"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/products</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-products"
               value="свеча"
               data-component="query">
    <br>
<p>Строка для поиска продуктов по названию. Example: <code>свеча</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="category"                data-endpoint="GETapi-v1-products"
               value="aromaticheskie-svechi"
               data-component="query">
    <br>
<p>Slug категории для фильтрации продуктов. Example: <code>aromaticheskie-svechi</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>price_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="price_from"                data-endpoint="GETapi-v1-products"
               value="100"
               data-component="query">
    <br>
<p>numeric Минимальная цена для фильтрации. Example: <code>100</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>price_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="price_to"                data-endpoint="GETapi-v1-products"
               value="500"
               data-component="query">
    <br>
<p>numeric Максимальная цена для фильтрации. Example: <code>500</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>is_new</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-v1-products" style="display: none">
            <input type="radio" name="is_new"
                   value="1"
                   data-endpoint="GETapi-v1-products"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-products" style="display: none">
            <input type="radio" name="is_new"
                   value="0"
                   data-endpoint="GETapi-v1-products"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Фильтр для отображения только новых продуктов. Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>is_bestseller</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-v1-products" style="display: none">
            <input type="radio" name="is_bestseller"
                   value="1"
                   data-endpoint="GETapi-v1-products"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-products" style="display: none">
            <input type="radio" name="is_bestseller"
                   value="0"
                   data-endpoint="GETapi-v1-products"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Фильтр для отображения только хитов продаж. Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ids</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="ids"                data-endpoint="GETapi-v1-products"
               value="1,2,3"
               data-component="query">
    <br>
<p>Список ID продуктов через запятую для фильтрации. Example: <code>1,2,3</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-products"
               value="price_asc"
               data-component="query">
    <br>
<p>Сортировка результатов (price_asc, price_desc, name_asc, name_desc, created_at_desc). Example: <code>price_asc</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-products"
               value="15"
               data-component="query">
    <br>
<p>Количество результатов на странице (от 1 до 100). Example: <code>15</code></p>
            </div>
                </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
