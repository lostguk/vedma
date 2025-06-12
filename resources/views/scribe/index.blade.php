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
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-autentifikaciia" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autentifikaciia">
                    <a href="#autentifikaciia">Аутентификация</a>
                </li>
                                    <ul id="tocify-subheader-autentifikaciia" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-register">
                                <a href="#autentifikaciia-POSTapi-v1-register">Регистрация нового пользователя</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-login">
                                <a href="#autentifikaciia-POSTapi-v1-login">POST api/v1/login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-forgot-password">
                                <a href="#autentifikaciia-POSTapi-v1-forgot-password">Запрос на сброс пароля</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-reset-password">
                                <a href="#autentifikaciia-POSTapi-v1-reset-password">Сброс пароля</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-logout">
                                <a href="#autentifikaciia-POSTapi-v1-logout">POST api/v1/logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-change-password">
                                <a href="#autentifikaciia-POSTapi-v1-change-password">Смена пароля</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-zakazy-polzovatelia" class="tocify-header">
                <li class="tocify-item level-1" data-unique="zakazy-polzovatelia">
                    <a href="#zakazy-polzovatelia">Заказы пользователя</a>
                </li>
                                    <ul id="tocify-subheader-zakazy-polzovatelia" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="zakazy-polzovatelia-GETapi-v1-orders">
                                <a href="#zakazy-polzovatelia-GETapi-v1-orders">Получить список заказов текущего пользователя</a>
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
                                                                                <li class="tocify-item level-2" data-unique="obshhie-endpointy-GETapi-v1-mail-test">
                                <a href="#obshhie-endpointy-GETapi-v1-mail-test">GET api/v1/mail/test</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-oformlenie-zakaza" class="tocify-header">
                <li class="tocify-item level-1" data-unique="oformlenie-zakaza">
                    <a href="#oformlenie-zakaza">Оформление заказа</a>
                </li>
                                    <ul id="tocify-subheader-oformlenie-zakaza" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="oformlenie-zakaza-POSTapi-v1-order-calculate">
                                <a href="#oformlenie-zakaza-POSTapi-v1-order-calculate">Расчет стоимости заказа с учетом промокода.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="oformlenie-zakaza-POSTapi-v1-order">
                                <a href="#oformlenie-zakaza-POSTapi-v1-order">Оформление заказа (создание)</a>
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
                    <ul id="tocify-header-profil" class="tocify-header">
                <li class="tocify-item level-1" data-unique="profil">
                    <a href="#profil">Профиль</a>
                </li>
                                    <ul id="tocify-subheader-profil" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="profil-GETapi-v1-profile">
                                <a href="#profil-GETapi-v1-profile">Получить профиль текущего пользователя

Этот эндпоинт возвращает данные профиля аутентифицированного пользователя.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="profil-PATCHapi-v1-profile">
                                <a href="#profil-PATCHapi-v1-profile">Редактировать профиль текущего пользователя

Этот эндпоинт позволяет обновить данные профиля аутентифицированного пользователя.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-stranicy" class="tocify-header">
                <li class="tocify-item level-1" data-unique="stranicy">
                    <a href="#stranicy">Страницы</a>
                </li>
                                    <ul id="tocify-subheader-stranicy" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="stranicy-GETapi-v1-pages">
                                <a href="#stranicy-GETapi-v1-pages">Получить список всех страниц

Возвращает массив всех страниц.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="stranicy-GETapi-v1-pages--id-">
                                <a href="#stranicy-GETapi-v1-pages--id-">Получить страницу по ID</a>
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
        <li>Last updated: June 12, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>API для интеграции с магазином магических товаров. Позволяет просматривать категории и товары, а также регистрировать новых пользователей.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_AUTH_KEY}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Вставьте Bearer-токен, полученный через /login.</p>

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

                    <h2 id="autentifikaciia-POSTapi-v1-login">POST api/v1/login</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"test@example.com\",
    \"password\": \"password\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "test@example.com",
    "password": "password"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-login">
</span>
<span id="execution-results-POSTapi-v1-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-login" data-method="POST"
      data-path="api/v1/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-login"
                    onclick="tryItOut('POSTapi-v1-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-login"
                    onclick="cancelTryOut('POSTapi-v1-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-login"
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
                              name="Accept"                data-endpoint="POSTapi-v1-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-login"
               value="test@example.com"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>test@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-login"
               value="password"
               data-component="body">
    <br>
<p>Пароль пользователя. Example: <code>password</code></p>
        </div>
        </form>

                    <h2 id="autentifikaciia-POSTapi-v1-forgot-password">Запрос на сброс пароля</h2>

<p>
</p>

<p>Отправляет пользователю на email ссылку для сброса пароля, если пользователь существует.</p>

<span id="example-requests-POSTapi-v1-forgot-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/forgot-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"user@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/forgot-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "user@example.com"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-forgot-password">
            <blockquote>
            <p>Example response (200, Ссылка отправлена):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Ссылка на смену пароля отправлена.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-forgot-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-forgot-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-forgot-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-forgot-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-forgot-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-forgot-password" data-method="POST"
      data-path="api/v1/forgot-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-forgot-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-forgot-password"
                    onclick="tryItOut('POSTapi-v1-forgot-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-forgot-password"
                    onclick="cancelTryOut('POSTapi-v1-forgot-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-forgot-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/forgot-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-forgot-password"
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
                              name="Accept"                data-endpoint="POSTapi-v1-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-forgot-password"
               value="user@example.com"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>user@example.com</code></p>
        </div>
        </form>

                    <h2 id="autentifikaciia-POSTapi-v1-reset-password">Сброс пароля</h2>

<p>
</p>

<p>Позволяет установить новый пароль, используя email и временный токен.</p>

<span id="example-requests-POSTapi-v1-reset-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/reset-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"user@example.com\",
    \"token\": \"abc123\",
    \"password\": \"NewPassword456\",
    \"password_confirmation\": \"NewPassword456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reset-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "user@example.com",
    "token": "abc123",
    "password": "NewPassword456",
    "password_confirmation": "NewPassword456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-reset-password">
            <blockquote>
            <p>Example response (200, Пароль успешно изменён):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Пароль успешно изменён.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Неверный или просроченный токен):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;token&quot;: [
            &quot;Неверный или просроченный токен&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-reset-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-reset-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-reset-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-reset-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-reset-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-reset-password" data-method="POST"
      data-path="api/v1/reset-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-reset-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-reset-password"
                    onclick="tryItOut('POSTapi-v1-reset-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-reset-password"
                    onclick="cancelTryOut('POSTapi-v1-reset-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-reset-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/reset-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-reset-password"
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
                              name="Accept"                data-endpoint="POSTapi-v1-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-reset-password"
               value="user@example.com"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>user@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token"                data-endpoint="POSTapi-v1-reset-password"
               value="abc123"
               data-component="body">
    <br>
<p>Токен сброса, полученный из email. Example: <code>abc123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-reset-password"
               value="NewPassword456"
               data-component="body">
    <br>
<p>Новый пароль (минимум 8 символов). Example: <code>NewPassword456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-v1-reset-password"
               value="NewPassword456"
               data-component="body">
    <br>
<p>Подтверждение нового пароля. Example: <code>NewPassword456</code></p>
        </div>
        </form>

                    <h2 id="autentifikaciia-POSTapi-v1-logout">POST api/v1/logout</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/logout" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/logout"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-logout">
</span>
<span id="execution-results-POSTapi-v1-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-logout" data-method="POST"
      data-path="api/v1/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-logout"
                    onclick="tryItOut('POSTapi-v1-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-logout"
                    onclick="cancelTryOut('POSTapi-v1-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-logout"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-logout"
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
                              name="Accept"                data-endpoint="POSTapi-v1-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="autentifikaciia-POSTapi-v1-change-password">Смена пароля</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Этот эндпоинт позволяет сменить пароль авторизованного пользователя.
Текущий пароль проверяется автоматически на валидность.</p>

<span id="example-requests-POSTapi-v1-change-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/change-password" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"current_password\": \"oldpassword123\",
    \"new_password\": \"newpassword456\",
    \"new_password_confirmation\": \"newpassword456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/change-password"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "current_password": "oldpassword123",
    "new_password": "newpassword456",
    "new_password_confirmation": "newpassword456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-change-password">
            <blockquote>
            <p>Example response (200, Успешная смена пароля):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;message&quot;: &quot;Пароль успешно обновлён.&quot;,
  &quot;user&quot;: {
    &quot;id&quot;: 1,
    &quot;first_name&quot;: &quot;Иван&quot;,
    &quot;last_name&quot;: &quot;Иванов&quot;,
    &quot;email&quot;: &quot;user@example.com&quot;,
    ...
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
        &quot;current_password&quot;: [
            &quot;Текущий пароль указан неверно.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-change-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-change-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-change-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-change-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-change-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-change-password" data-method="POST"
      data-path="api/v1/change-password"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-change-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-change-password"
                    onclick="tryItOut('POSTapi-v1-change-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-change-password"
                    onclick="cancelTryOut('POSTapi-v1-change-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-change-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/change-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-change-password"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-change-password"
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
                              name="Accept"                data-endpoint="POSTapi-v1-change-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="current_password"                data-endpoint="POSTapi-v1-change-password"
               value="oldpassword123"
               data-component="body">
    <br>
<p>Текущий пароль. Example: <code>oldpassword123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_password"                data-endpoint="POSTapi-v1-change-password"
               value="newpassword456"
               data-component="body">
    <br>
<p>Новый пароль (не менее 8 символов). Example: <code>newpassword456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_password_confirmation"                data-endpoint="POSTapi-v1-change-password"
               value="newpassword456"
               data-component="body">
    <br>
<p>Подтверждение нового пароля. Example: <code>newpassword456</code></p>
        </div>
        </form>

                <h1 id="zakazy-polzovatelia">Заказы пользователя</h1>

    

                                <h2 id="zakazy-polzovatelia-GETapi-v1-orders">Получить список заказов текущего пользователя</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-orders">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/orders?page=1&amp;per_page=15" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders"
);

const params = {
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-orders">
            <blockquote>
            <p>Example response (200, Успешно):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;status&quot;: &quot;success&quot;,
  &quot;message&quot;: &quot;Success&quot;,
  &quot;data&quot;: {
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
      {&quot;id&quot;: 1, &quot;user_id&quot;: 2, ...}
    ],
    ...
  }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-orders" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-orders"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-orders"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-orders" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-orders">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-orders" data-method="GET"
      data-path="api/v1/orders"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-orders', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-orders"
                    onclick="tryItOut('GETapi-v1-orders');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-orders"
                    onclick="cancelTryOut('GETapi-v1-orders');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-orders"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/orders</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-orders"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-orders"
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
                              name="Accept"                data-endpoint="GETapi-v1-orders"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-orders"
               value="1"
               data-component="query">
    <br>
<p>Номер страницы. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-orders"
               value="15"
               data-component="query">
    <br>
<p>Количество заказов на страницу. Example: <code>15</code></p>
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
    --get "http://localhost:8000/api/v1/categories?show_hidden=&amp;ids[]=1&amp;ids[]=2&amp;ids[]=3" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/categories"
);

const params = {
    "show_hidden": "0",
    "ids[0]": "1",
    "ids[1]": "2",
    "ids[2]": "3",
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
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ids</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="ids[0]"                data-endpoint="GETapi-v1-categories"
               data-component="query">
        <input type="text" style="display: none"
               name="ids[1]"                data-endpoint="GETapi-v1-categories"
               data-component="query">
    <br>
<p>Список идентификаторов категорий.</p>
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
    &quot;timestamp&quot;: &quot;2025-06-12T09:06:14+00:00&quot;
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

                    <h2 id="obshhie-endpointy-GETapi-v1-mail-test">GET api/v1/mail/test</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-mail-test">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/mail/test" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/mail/test"
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

<span id="example-responses-GETapi-v1-mail-test">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">content-type: text/html; charset=UTF-8
cache-control: no-cache, private
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-mail-test" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-mail-test"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-mail-test"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-mail-test" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-mail-test">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-mail-test" data-method="GET"
      data-path="api/v1/mail/test"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-mail-test', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-mail-test"
                    onclick="tryItOut('GETapi-v1-mail-test');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-mail-test"
                    onclick="cancelTryOut('GETapi-v1-mail-test');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-mail-test"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/mail/test</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-mail-test"
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
                              name="Accept"                data-endpoint="GETapi-v1-mail-test"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="oformlenie-zakaza">Оформление заказа</h1>

    

                                <h2 id="oformlenie-zakaza-POSTapi-v1-order-calculate">Расчет стоимости заказа с учетом промокода.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-order-calculate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/order/calculate" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"items\": [
        \"architecto\"
    ],
    \"promo_code\": \"PROMO10\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/order/calculate"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "items": [
        "architecto"
    ],
    "promo_code": "PROMO10"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-order-calculate">
            <blockquote>
            <p>Example response (200, Успешный расчет):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Товар 1&quot;,
            &quot;price&quot;: 100,
            &quot;old_price&quot;: 120,
            &quot;count&quot;: 2,
            &quot;summery&quot;: 200,
            &quot;summery_old&quot;: 240,
            &quot;discounted&quot;: true
        }
    ],
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Success&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-order-calculate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-order-calculate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-order-calculate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-order-calculate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-order-calculate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-order-calculate" data-method="POST"
      data-path="api/v1/order/calculate"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-order-calculate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-order-calculate"
                    onclick="tryItOut('POSTapi-v1-order-calculate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-order-calculate"
                    onclick="cancelTryOut('POSTapi-v1-order-calculate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-order-calculate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/order/calculate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-order-calculate"
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
                              name="Accept"                data-endpoint="POSTapi-v1-order-calculate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>items</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>
<p>Список товаров для расчета. Пример: [{&quot;id&quot;:1,&quot;count&quot;:3}]</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.id"                data-endpoint="POSTapi-v1-order-calculate"
               value="1"
               data-component="body">
    <br>
<p>ID товара. Example: <code>1</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.count"                data-endpoint="POSTapi-v1-order-calculate"
               value="3"
               data-component="body">
    <br>
<p>Количество товара. Example: <code>3</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>promo_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="promo_code"                data-endpoint="POSTapi-v1-order-calculate"
               value="PROMO10"
               data-component="body">
    <br>
<p>Промокод (опционально). Example: <code>PROMO10</code></p>
        </div>
        </form>

                    <h2 id="oformlenie-zakaza-POSTapi-v1-order">Оформление заказа (создание)</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-order">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/order" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"items\": [
        {
            \"id\": 1,
            \"count\": 2
        }
    ],
    \"promo_code\": \"PROMO2208\",
    \"register\": true,
    \"first_name\": \"Admin\",
    \"last_name\": \"System\",
    \"middle_name\": \"Root\",
    \"email\": \"admin@admin.ru\",
    \"phone\": \"+7 999 999 99 99\",
    \"country\": \"Россия\",
    \"region\": \"Московская область\",
    \"city\": \"Москва\",
    \"postal_code\": \"123456\",
    \"address\": \"ул. Администраторская, д. 1\",
    \"password\": \"StrongPass123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/order"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "items": [
        {
            "id": 1,
            "count": 2
        }
    ],
    "promo_code": "PROMO2208",
    "register": true,
    "first_name": "Admin",
    "last_name": "System",
    "middle_name": "Root",
    "email": "admin@admin.ru",
    "phone": "+7 999 999 99 99",
    "country": "Россия",
    "region": "Московская область",
    "city": "Москва",
    "postal_code": "123456",
    "address": "ул. Администраторская, д. 1",
    "password": "StrongPass123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-order">
            <blockquote>
            <p>Example response (201, Успешное оформление):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Order created&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-order" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-order"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-order"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-order" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-order">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-order" data-method="POST"
      data-path="api/v1/order"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-order', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-order"
                    onclick="tryItOut('POSTapi-v1-order');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-order"
                    onclick="cancelTryOut('POSTapi-v1-order');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-order"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/order</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-order"
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
                              name="Accept"                data-endpoint="POSTapi-v1-order"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>items</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
<br>
<p>Массив позиций заказа.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.id"                data-endpoint="POSTapi-v1-order"
               value="1"
               data-component="body">
    <br>
<p>ID товара. Example: <code>1</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.count"                data-endpoint="POSTapi-v1-order"
               value="2"
               data-component="body">
    <br>
<p>Количество. Example: <code>2</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>promo_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="promo_code"                data-endpoint="POSTapi-v1-order"
               value="PROMO2208"
               data-component="body">
    <br>
<p>Промокод. Example: <code>PROMO2208</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>register</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-v1-order" style="display: none">
            <input type="radio" name="register"
                   value="true"
                   data-endpoint="POSTapi-v1-order"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-order" style="display: none">
            <input type="radio" name="register"
                   value="false"
                   data-endpoint="POSTapi-v1-order"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Зарегистрировать пользователя. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="POSTapi-v1-order"
               value="Admin"
               data-component="body">
    <br>
<p>Имя пользователя. Example: <code>Admin</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-v1-order"
               value="System"
               data-component="body">
    <br>
<p>Фамилия пользователя. Example: <code>System</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>middle_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="middle_name"                data-endpoint="POSTapi-v1-order"
               value="Root"
               data-component="body">
    <br>
<p>Отчество пользователя. Example: <code>Root</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-order"
               value="admin@admin.ru"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>admin@admin.ru</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-v1-order"
               value="+7 999 999 99 99"
               data-component="body">
    <br>
<p>Телефон пользователя. Example: <code>+7 999 999 99 99</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="POSTapi-v1-order"
               value="Россия"
               data-component="body">
    <br>
<p>Страна. Example: <code>Россия</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>region</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="region"                data-endpoint="POSTapi-v1-order"
               value="Московская область"
               data-component="body">
    <br>
<p>Регион. Example: <code>Московская область</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-v1-order"
               value="Москва"
               data-component="body">
    <br>
<p>Город. Example: <code>Москва</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>postal_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="postal_code"                data-endpoint="POSTapi-v1-order"
               value="123456"
               data-component="body">
    <br>
<p>Почтовый индекс. Example: <code>123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-v1-order"
               value="ул. Администраторская, д. 1"
               data-component="body">
    <br>
<p>Адрес доставки. Example: <code>ул. Администраторская, д. 1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-order"
               value="StrongPass123"
               data-component="body">
    <br>
<p>Пароль (если регистрация). Example: <code>StrongPass123</code></p>
        </div>
        </form>

                <h1 id="produkty">Продукты</h1>

    <p>API для работы с продуктами магазина</p>

                                <h2 id="produkty-GETapi-v1-products--slug-">Получить детальную информацию о продукте</h2>

<p>
</p>

<p>Этот эндпоинт возвращает детальную информацию о конкретном продукте, включая его категории,
связанные товары и медиафайлы. Продукт идентифицируется по его уникальному slug.</p>

<span id="example-requests-GETapi-v1-products--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/products/&amp;quot;&amp;quot;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/products/&amp;quot;&amp;quot;"
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
        &quot;old_price&quot;: 1500,
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
               value=""""
               data-component="url">
    <br>
<p>Уникальный идентификатор продукта. Example: <code>""</code></p>
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
    --get "http://localhost:8000/api/v1/products?search=%22%22&amp;category=%22%22&amp;price_from=%22%22&amp;price_to=%22%22&amp;is_new=1&amp;is_bestseller=1&amp;ids=%22%22&amp;sort=%22%22&amp;per_page=15&amp;page=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/products"
);

const params = {
    "search": """",
    "category": """",
    "price_from": """",
    "price_to": """",
    "is_new": "1",
    "is_bestseller": "1",
    "ids": """",
    "sort": """",
    "per_page": "15",
    "page": "1",
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
            &quot;old_price&quot;: 1500,
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
               value=""""
               data-component="query">
    <br>
<p>Строка для поиска продуктов по названию. Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="category"                data-endpoint="GETapi-v1-products"
               value=""""
               data-component="query">
    <br>
<p>Slug категории для фильтрации продуктов. Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>price_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="price_from"                data-endpoint="GETapi-v1-products"
               value=""""
               data-component="query">
    <br>
<p>numeric Минимальная цена для фильтрации. Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>price_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="price_to"                data-endpoint="GETapi-v1-products"
               value=""""
               data-component="query">
    <br>
<p>numeric Максимальная цена для фильтрации. Example: <code>""</code></p>
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
               value=""""
               data-component="query">
    <br>
<p>Список ID продуктов через запятую для фильтрации. Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-products"
               value=""""
               data-component="query">
    <br>
<p>Сортировка результатов (price_asc, price_desc, name_asc, name_desc, created_at_desc). Example: <code>""</code></p>
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
<p>Количество заказов на страницу. Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-products"
               value="1"
               data-component="query">
    <br>
<p>Номер страницы. Example: <code>1</code></p>
            </div>
                </form>

                <h1 id="profil">Профиль</h1>

    

                                <h2 id="profil-GETapi-v1-profile">Получить профиль текущего пользователя

Этот эндпоинт возвращает данные профиля аутентифицированного пользователя.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/profile" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/profile"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-profile">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;first_name&quot;: &quot;Иван&quot;,
        &quot;last_name&quot;: &quot;Иванов&quot;,
        &quot;middle_name&quot;: &quot;Иванович&quot;,
        &quot;full_name&quot;: &quot;Иванов Иван Иванович&quot;,
        &quot;email&quot;: &quot;ivan@example.com&quot;,
        &quot;phone&quot;: &quot;+79999999999&quot;,
        &quot;address&quot;: {
            &quot;country&quot;: &quot;Россия&quot;,
            &quot;region&quot;: &quot;Москва&quot;,
            &quot;city&quot;: &quot;Москва&quot;,
            &quot;postal_code&quot;: &quot;101000&quot;,
            &quot;address&quot;: &quot;ул. Пример, д. 1&quot;
        },
        &quot;email_verified&quot;: true,
        &quot;created_at&quot;: &quot;2024-05-28T12:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2024-05-28T12:00:00+00:00&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-profile" data-method="GET"
      data-path="api/v1/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-profile"
                    onclick="tryItOut('GETapi-v1-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-profile"
                    onclick="cancelTryOut('GETapi-v1-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-profile"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-profile"
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
                              name="Accept"                data-endpoint="GETapi-v1-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="profil-PATCHapi-v1-profile">Редактировать профиль текущего пользователя

Этот эндпоинт позволяет обновить данные профиля аутентифицированного пользователя.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PATCHapi-v1-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost:8000/api/v1/profile" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"Иван\",
    \"last_name\": \"Иванов\",
    \"middle_name\": \"Иванович\",
    \"email\": \"ivan@example.com\",
    \"phone\": \"+79999999999\",
    \"country\": \"Россия\",
    \"region\": \"Москва\",
    \"city\": \"Москва\",
    \"postal_code\": \"101000\",
    \"address\": \"ул. Пример, д. 1\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/profile"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "Иван",
    "last_name": "Иванов",
    "middle_name": "Иванович",
    "email": "ivan@example.com",
    "phone": "+79999999999",
    "country": "Россия",
    "region": "Москва",
    "city": "Москва",
    "postal_code": "101000",
    "address": "ул. Пример, д. 1"
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-profile">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;first_name&quot;: &quot;Иван&quot;,
        &quot;last_name&quot;: &quot;Иванов&quot;,
        &quot;middle_name&quot;: &quot;Иванович&quot;,
        &quot;full_name&quot;: &quot;Иванов Иван Иванович&quot;,
        &quot;email&quot;: &quot;ivan@example.com&quot;,
        &quot;phone&quot;: &quot;+79999999999&quot;,
        &quot;address&quot;: {
            &quot;country&quot;: &quot;Россия&quot;,
            &quot;region&quot;: &quot;Москва&quot;,
            &quot;city&quot;: &quot;Москва&quot;,
            &quot;postal_code&quot;: &quot;101000&quot;,
            &quot;address&quot;: &quot;ул. Пример, д. 1&quot;
        },
        &quot;email_verified&quot;: true,
        &quot;created_at&quot;: &quot;2024-05-28T12:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2024-05-28T12:00:00+00:00&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-v1-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-v1-profile" data-method="PATCH"
      data-path="api/v1/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-profile"
                    onclick="tryItOut('PATCHapi-v1-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-profile"
                    onclick="cancelTryOut('PATCHapi-v1-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-v1-profile"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-v1-profile"
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
                              name="Accept"                data-endpoint="PATCHapi-v1-profile"
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
                              name="first_name"                data-endpoint="PATCHapi-v1-profile"
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
                              name="last_name"                data-endpoint="PATCHapi-v1-profile"
               value="Иванов"
               data-component="body">
    <br>
<p>Фамилия пользователя. Example: <code>Иванов</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>middle_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="middle_name"                data-endpoint="PATCHapi-v1-profile"
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
                              name="email"                data-endpoint="PATCHapi-v1-profile"
               value="ivan@example.com"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>ivan@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="PATCHapi-v1-profile"
               value="+79999999999"
               data-component="body">
    <br>
<p>Телефон пользователя. Example: <code>+79999999999</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="PATCHapi-v1-profile"
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
                              name="region"                data-endpoint="PATCHapi-v1-profile"
               value="Москва"
               data-component="body">
    <br>
<p>Регион. Example: <code>Москва</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="PATCHapi-v1-profile"
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
                              name="postal_code"                data-endpoint="PATCHapi-v1-profile"
               value="101000"
               data-component="body">
    <br>
<p>Почтовый индекс. Example: <code>101000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="PATCHapi-v1-profile"
               value="ул. Пример, д. 1"
               data-component="body">
    <br>
<p>Адрес. Example: <code>ул. Пример, д. 1</code></p>
        </div>
        </form>

                <h1 id="stranicy">Страницы</h1>

    

                                <h2 id="stranicy-GETapi-v1-pages">Получить список всех страниц

Возвращает массив всех страниц.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-pages">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/pages" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/pages"
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

<span id="example-responses-GETapi-v1-pages">
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
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-pages" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-pages"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-pages"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-pages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-pages">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-pages" data-method="GET"
      data-path="api/v1/pages"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-pages', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-pages"
                    onclick="tryItOut('GETapi-v1-pages');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-pages"
                    onclick="cancelTryOut('GETapi-v1-pages');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-pages"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/pages</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-pages"
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
                              name="Accept"                data-endpoint="GETapi-v1-pages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
<br>
<p>ID страницы</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
<br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
<br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>text</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
<br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_visible_in_header</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
<br>
<p>Показывать в шапке</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_visible_in_footer</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
<br>
<p>Показывать в футере</p>
        </div>
                        <h2 id="stranicy-GETapi-v1-pages--id-">Получить страницу по ID</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-pages--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/pages/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/pages/16"
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

<span id="example-responses-GETapi-v1-pages--id-">
            <blockquote>
            <p>Example response (404):</p>
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
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Resource not found.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-pages--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-pages--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-pages--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-pages--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-pages--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-pages--id-" data-method="GET"
      data-path="api/v1/pages/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-pages--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-pages--id-"
                    onclick="tryItOut('GETapi-v1-pages--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-pages--id-"
                    onclick="cancelTryOut('GETapi-v1-pages--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-pages--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/pages/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-pages--id-"
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
                              name="Accept"                data-endpoint="GETapi-v1-pages--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-pages--id-"
               value="16"
               data-component="url">
    <br>
<p>ID страницы. Пример: 1 Example: <code>16</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
<br>
<p>ID страницы</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
<br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
<br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>text</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
<br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_visible_in_header</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
<br>
<p>Показывать в шапке</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_visible_in_footer</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
<br>
<p>Показывать в футере</p>
        </div>
                

        
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
