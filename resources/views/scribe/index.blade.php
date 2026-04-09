<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>API Магических Товаров</title>

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
        var tryItOutBaseUrl = "{{ config("app.url") }}";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.2.1.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.2.1.js") }}"></script>

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
                                <a href="#autentifikaciia-POSTapi-v1-login">Вход в систему</a>
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
                                                                                <li class="tocify-item level-2" data-unique="autentifikaciia-GETapi-v1-verify-registration--user---hash-">
                                <a href="#autentifikaciia-GETapi-v1-verify-registration--user---hash-">Подтверждение email адреса</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentifikaciia-POSTapi-v1-verify-registration-resend">
                                <a href="#autentifikaciia-POSTapi-v1-verify-registration-resend">Повторная отправка письма подтверждения</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-glavnaia-stranica" class="tocify-header">
                <li class="tocify-item level-1" data-unique="glavnaia-stranica">
                    <a href="#glavnaia-stranica">Главная страница</a>
                </li>
                                    <ul id="tocify-subheader-glavnaia-stranica" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="glavnaia-stranica-GETapi-v1-home">
                                <a href="#glavnaia-stranica-GETapi-v1-home">Получить данные главной страницы</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-dostavka" class="tocify-header">
                <li class="tocify-item level-1" data-unique="dostavka">
                    <a href="#dostavka">Доставка</a>
                </li>
                                    <ul id="tocify-subheader-dostavka" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="dostavka-POSTapi-v1-shipping-calculate">
                                <a href="#dostavka-POSTapi-v1-shipping-calculate">Расчёт стоимости доставки (Metaship) 4444444</a>
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
                    <ul id="tocify-header-oplata-zakaza" class="tocify-header">
                <li class="tocify-item level-1" data-unique="oplata-zakaza">
                    <a href="#oplata-zakaza">Оплата заказа</a>
                </li>
                                    <ul id="tocify-subheader-oplata-zakaza" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="oplata-zakaza-POSTapi-v1-payments">
                                <a href="#oplata-zakaza-POSTapi-v1-payments">Создать платеж и получить ссылку на оплату.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="oplata-zakaza-POSTapi-v1-payments--payment--refund">
                                <a href="#oplata-zakaza-POSTapi-v1-payments--payment--refund">Возврат платежа.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="oplata-zakaza-POSTapi-v1-payments-alfabank-webhook">
                                <a href="#oplata-zakaza-POSTapi-v1-payments-alfabank-webhook">Webhook от Альфа-Банка для обновления статуса платежа.</a>
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
                                                                                <li class="tocify-item level-2" data-unique="oformlenie-zakaza-POSTapi-v1-order-address-suggest">
                                <a href="#oformlenie-zakaza-POSTapi-v1-order-address-suggest">Подсказки адреса через DaData.</a>
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
                    <ul id="tocify-header-sistemnye" class="tocify-header">
                <li class="tocify-item level-1" data-unique="sistemnye">
                    <a href="#sistemnye">Системные</a>
                </li>
                                    <ul id="tocify-subheader-sistemnye" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="sistemnye-GETapi-v1-mail-test">
                                <a href="#sistemnye-GETapi-v1-mail-test">Отправка тестового письма на email администратора.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-temy-i-soobshheniia" class="tocify-header">
                <li class="tocify-item level-1" data-unique="temy-i-soobshheniia">
                    <a href="#temy-i-soobshheniia">Темы и сообщения</a>
                </li>
                                    <ul id="tocify-subheader-temy-i-soobshheniia" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="temy-i-soobshheniia-GETapi-v1-topics">
                                <a href="#temy-i-soobshheniia-GETapi-v1-topics">Получение списка тем пользователя</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="temy-i-soobshheniia-POSTapi-v1-topics">
                                <a href="#temy-i-soobshheniia-POSTapi-v1-topics">Создание новой темы</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="temy-i-soobshheniia-GETapi-v1-topics-unread-count">
                                <a href="#temy-i-soobshheniia-GETapi-v1-topics-unread-count">Получение количества непрочитанных сообщений</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="temy-i-soobshheniia-GETapi-v1-topics--topicId-">
                                <a href="#temy-i-soobshheniia-GETapi-v1-topics--topicId-">Получение темы с сообщениями</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="temy-i-soobshheniia-POSTapi-v1-topics--topicId--messages">
                                <a href="#temy-i-soobshheniia-POSTapi-v1-topics--topicId--messages">Добавление сообщения в тему</a>
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
        <li>Last updated: April 9, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>API для интеграции с магазином магических товаров. Позволяет просматривать категории и товары, а также регистрировать новых пользователей.</p>
<aside>
    <strong>Base URL</strong>: <code>{{ config("app.url") }}</code>
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
<li><code>address</code> - Адресные данные</li>
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
    "{{ config("app.url") }}/api/v1/register" \
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
    \"address\": \"ул. Пушкина, д. 1\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/register"
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
            <blockquote>
            <p>Example response (422, Не удалось отправить письмо):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Не удалось отправить письмо для подтверждения. Проверьте адрес и попробуйте ещё раз.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;Не удалось доставить письмо подтверждения.&quot;
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

                    <h2 id="autentifikaciia-POSTapi-v1-login">Вход в систему</h2>

<p>
</p>

<p>Позволяет получить токен доступа по email и паролю.
После успешной аутентификации возвращается токен доступа и данные пользователя.</p>

<span id="example-requests-POSTapi-v1-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"gusengus57@gmail.com\",
    \"password\": \"password123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "gusengus57@gmail.com",
    "password": "password123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-login">
            <blockquote>
            <p>Example response (200, Успешный вход):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Login successful&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;first_name&quot;: &quot;Иван&quot;,
            &quot;last_name&quot;: &quot;Иванов&quot;,
            &quot;middle_name&quot;: &quot;Иванович&quot;,
            &quot;full_name&quot;: &quot;Иванов Иван Иванович&quot;,
            &quot;email&quot;: &quot;gusengus57@gmail.com&quot;,
            &quot;phone&quot;: &quot;+79001234567&quot;,
            &quot;address&quot;: &quot;Россия&quot;
            &quot;email_verified&quot;: true,
            &quot;created_at&quot;: &quot;2023-01-01T00:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2023-01-01T00:00:00+00:00&quot;
        },
        &quot;token&quot;: &quot;1|laravel_sanctum_hashed_token_example_123456789&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Email не подтвержден):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Email адрес не подтвержден&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Ошибка валидации):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;Указанные учетные данные не соответствуют нашим записям.&quot;
        ],
        &quot;password&quot;: [
            &quot;Пароль должен содержать не менее 8 символов.&quot;
        ]
    }
}</code>
 </pre>
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
               value="gusengus57@gmail.com"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>gusengus57@gmail.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-login"
               value="password123"
               data-component="body">
    <br>
<p>Пароль пользователя. Example: <code>password123</code></p>
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
    "{{ config("app.url") }}/api/v1/forgot-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"gusengus57@gmail.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/forgot-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "gusengus57@gmail.com"
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
               value="gusengus57@gmail.com"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>gusengus57@gmail.com</code></p>
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
    "{{ config("app.url") }}/api/v1/reset-password" \
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
    "{{ config("app.url") }}/api/v1/reset-password"
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
    "{{ config("app.url") }}/api/v1/logout" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/logout"
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
    "{{ config("app.url") }}/api/v1/change-password" \
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
    "{{ config("app.url") }}/api/v1/change-password"
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

                    <h2 id="autentifikaciia-GETapi-v1-verify-registration--user---hash-">Подтверждение email адреса</h2>

<p>
</p>

<p>Этот эндпоинт используется для подтверждения email адреса пользователя.
Ссылка генерируется автоматически при регистрации и отправляется на email.
Ссылка действительна в течение 60 минут и содержит цифровую подпись для безопасности.</p>

<span id="example-requests-GETapi-v1-verify-registration--user---hash-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/verify-registration/1/5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8?expires=1738156800&amp;signature=2b64a6c0a1f7a5d9cbb7f0e3c0a8b1a9d3c1f5e6" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/verify-registration/1/5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"
);

const params = {
    "expires": "1738156800",
    "signature": "2b64a6c0a1f7a5d9cbb7f0e3c0a8b1a9d3c1f5e6",
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

<span id="example-responses-GETapi-v1-verify-registration--user---hash-">
            <blockquote>
            <p>Example response (200, Успешное подтверждение):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Email успешно подтвержден&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, Email уже подтвержден):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Email адрес уже подтвержден&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Неверная ссылка):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Неверная или истекшая ссылка подтверждения&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Пользователь не найден):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-verify-registration--user---hash-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-verify-registration--user---hash-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-verify-registration--user---hash-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-verify-registration--user---hash-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-verify-registration--user---hash-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-verify-registration--user---hash-" data-method="GET"
      data-path="api/v1/verify-registration/{user}/{hash}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-verify-registration--user---hash-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-verify-registration--user---hash-"
                    onclick="tryItOut('GETapi-v1-verify-registration--user---hash-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-verify-registration--user---hash-"
                    onclick="cancelTryOut('GETapi-v1-verify-registration--user---hash-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-verify-registration--user---hash-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/verify-registration/{user}/{hash}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-verify-registration--user---hash-"
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
                              name="Accept"                data-endpoint="GETapi-v1-verify-registration--user---hash-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user"                data-endpoint="GETapi-v1-verify-registration--user---hash-"
               value="1"
               data-component="url">
    <br>
<p>ID пользователя. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>hash</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="hash"                data-endpoint="GETapi-v1-verify-registration--user---hash-"
               value="5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"
               data-component="url">
    <br>
<p>Хеш email адреса (sha1). Example: <code>5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expires</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="expires"                data-endpoint="GETapi-v1-verify-registration--user---hash-"
               value="1738156800"
               data-component="query">
    <br>
<p>Unix‑timestamp, срок действия ссылки. Example: <code>1738156800</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>signature</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="signature"                data-endpoint="GETapi-v1-verify-registration--user---hash-"
               value="2b64a6c0a1f7a5d9cbb7f0e3c0a8b1a9d3c1f5e6"
               data-component="query">
    <br>
<p>Подпись ссылки, формируется приложением. Example: <code>2b64a6c0a1f7a5d9cbb7f0e3c0a8b1a9d3c1f5e6</code></p>
            </div>
                </form>

                    <h2 id="autentifikaciia-POSTapi-v1-verify-registration-resend">Повторная отправка письма подтверждения</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-verify-registration-resend">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/verify-registration/resend" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"user@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/verify-registration/resend"
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

<span id="example-responses-POSTapi-v1-verify-registration-resend">
            <blockquote>
            <p>Example response (200, Письмо отправлено):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Письмо для подтверждения отправлено повторно.&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, Email уже подтвержден):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Email адрес уже подтвержден&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Пользователь не найден):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Пользователь не найден&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Не удалось отправить письмо):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Не удалось отправить письмо для подтверждения. Проверьте адрес и попробуйте ещё раз.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;Не удалось доставить письмо подтверждения.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-verify-registration-resend" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-verify-registration-resend"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-verify-registration-resend"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-verify-registration-resend" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-verify-registration-resend">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-verify-registration-resend" data-method="POST"
      data-path="api/v1/verify-registration/resend"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-verify-registration-resend', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-verify-registration-resend"
                    onclick="tryItOut('POSTapi-v1-verify-registration-resend');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-verify-registration-resend"
                    onclick="cancelTryOut('POSTapi-v1-verify-registration-resend');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-verify-registration-resend"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/verify-registration/resend</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-verify-registration-resend"
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
                              name="Accept"                data-endpoint="POSTapi-v1-verify-registration-resend"
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
                              name="email"                data-endpoint="POSTapi-v1-verify-registration-resend"
               value="user@example.com"
               data-component="body">
    <br>
<p>Email пользователя. Example: <code>user@example.com</code></p>
        </div>
        </form>

                <h1 id="glavnaia-stranica">Главная страница</h1>

    <p>API для получения контента главной страницы</p>

                                <h2 id="glavnaia-stranica-GETapi-v1-home">Получить данные главной страницы</h2>

<p>
</p>

<p>Возвращает контент главной страницы, включая:</p>
<ul>
<li>Первый экран (hero) с заголовком, подзаголовком, кнопкой и изображением</li>
<li>Блок &quot;Наша магия — ваша сила&quot; (about) с описанием, статистикой и изображениями</li>
<li>Выбранные категории товаров (без дочерних категорий) с товарами внутри каждой категории</li>
<li>Внутри каждой категории отображается до 3 товаров, которые привязаны к этой категории и всем её дочерним категориям (рекурсивно)</li>
</ul>

<span id="example-requests-GETapi-v1-home">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/home" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/home"
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

<span id="example-responses-GETapi-v1-home">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;hero&quot;: {
            &quot;title&quot;: &quot;МАГИЯ ЖИВЕТ В КАЖДОМ ИЗ НАС&quot;,
            &quot;subtitle&quot;: &quot;Вопрос в том, готовы ли вы её пробудить?&quot;,
            &quot;button&quot;: {
                &quot;label&quot;: &quot;Каталог&quot;,
                &quot;url&quot;: &quot;/catalog&quot;
            },
            &quot;image&quot;: &quot;http://localhost:8000/storage/home/hero_image.png&quot;,
            &quot;features&quot;: [
                {
                    &quot;text&quot;: &quot;🔮Авторские изделия заряженные энергией&quot;
                },
                {
                    &quot;text&quot;: &quot;🌙Традиционные рецепты и обряды&quot;
                },
                {
                    &quot;text&quot;: &quot;🕯️Ручная работа и натуральные материалы&quot;
                }
            ]
        },
        &quot;about&quot;: {
            &quot;title&quot;: &quot;🔮НАША МАГИЯ &ndash; ВАША СИЛА&quot;,
            &quot;description&quot;: &quot;Мы верим в силу природы...&quot;,
            &quot;trust&quot;: {
                &quot;title&quot;: &quot;🌙Почему нам доверяют?&quot;,
                &quot;items&quot;: [
                    {
                        &quot;title&quot;: &quot;Проверенные рецепты&quot;,
                        &quot;image&quot;: &quot;...&quot;
                    },
                    {
                        &quot;title&quot;: &quot;Только натуральные материалы&quot;,
                        &quot;image&quot;: &quot;...&quot;
                    },
                    {
                        &quot;title&quot;: &quot;Энергетическая зарядка каждого изделия&quot;,
                        &quot;image&quot;: &quot;...&quot;
                    }
                ]
            },
            &quot;motto&quot;: &quot;✨Магия в ваших руках &ndash; главное, использовать ее с осознанием.&quot;,
            &quot;images&quot;: {
                &quot;left&quot;: &quot;http://localhost:8000/storage/home/about_left_image.png&quot;,
                &quot;right&quot;: &quot;http://localhost:8000/storage/home/about_right_image.png&quot;
            },
            &quot;stats&quot;: {
                &quot;title&quot;: &quot;🧮Мы в цифрах&quot;,
                &quot;items&quot;: [
                    {
                        &quot;value&quot;: &quot;3600+&quot;,
                        &quot;label&quot;: &quot;Довольных клиентов&quot;,
                        &quot;text&quot;: &quot;...&quot;
                    },
                    {
                        &quot;value&quot;: &quot;6&quot;,
                        &quot;label&quot;: &quot;Лет&quot;,
                        &quot;text&quot;: &quot;...&quot;
                    },
                    {
                        &quot;value&quot;: &quot;500+&quot;,
                        &quot;label&quot;: &quot;Моделей свечей&quot;,
                        &quot;text&quot;: &quot;...&quot;
                    }
                ]
            },
            &quot;moreButton&quot;: {
                &quot;label&quot;: &quot;Подробнее о нас&quot;,
                &quot;url&quot;: &quot;/about&quot;
            }
        },
        &quot;categories&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Ритуальные Свечи&quot;,
                &quot;slug&quot;: &quot;ritualnye-svechi&quot;,
                &quot;description&quot;: &quot;Свечи для различных ритуалов&quot;,
                &quot;parent_id&quot;: null,
                &quot;is_visible&quot;: true,
                &quot;products&quot;: [
                    {
                        &quot;id&quot;: 1,
                        &quot;name&quot;: &quot;Ароматическая свеча Лаванда&quot;,
                        &quot;slug&quot;: &quot;aromaticheskaya-svecha-lavanda&quot;,
                        &quot;description&quot;: &quot;Успокаивающий аромат лаванды&quot;,
                        &quot;price&quot;: 1200.99,
                        &quot;old_price&quot;: 1500,
                        &quot;is_new&quot;: true,
                        &quot;is_bestseller&quot;: false,
                        &quot;dimensions&quot;: {
                            &quot;weight&quot;: 350,
                            &quot;width&quot;: 10,
                            &quot;height&quot;: 12,
                            &quot;length&quot;: 10
                        },
                        &quot;categories&quot;: [],
                        &quot;related&quot;: [],
                        &quot;images_urls&quot;: [
                            &quot;http://localhost:8000/storage/1/image.jpg&quot;
                        ],
                        &quot;image_url&quot;: &quot;http://localhost:8000/storage/1/image.jpg&quot;,
                        &quot;preview_url&quot;: &quot;http://localhost:8000/storage/1/preview.jpg&quot;,
                        &quot;thumb_url&quot;: &quot;http://localhost:8000/storage/1/preview.jpg&quot;,
                        &quot;thumb_small_url&quot;: &quot;http://localhost:8000/storage/1/thumb.jpg&quot;,
                        &quot;created_at&quot;: &quot;2025-01-01T00:00:00.000000Z&quot;,
                        &quot;updated_at&quot;: &quot;2025-01-01T00:00:00.000000Z&quot;
                    }
                ]
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-home" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-home"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-home"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-home" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-home">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-home" data-method="GET"
      data-path="api/v1/home"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-home', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-home"
                    onclick="tryItOut('GETapi-v1-home');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-home"
                    onclick="cancelTryOut('GETapi-v1-home');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-home"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/home</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-home"
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
                              name="Accept"                data-endpoint="GETapi-v1-home"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="dostavka">Доставка</h1>

    <p>Позволяет рассчитать стоимость доставки на основе списка товаров и адреса доставки.</p>

                                <h2 id="dostavka-POSTapi-v1-shipping-calculate">Расчёт стоимости доставки (Metaship) 4444444</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-shipping-calculate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/shipping/calculate" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"products\": [
        {
            \"id\": 1,
            \"quantity\": 2
        },
        {
            \"id\": 5,
            \"quantity\": 1
        }
    ],
    \"address\": \"191025, г Санкт-Петербург, Центральный р-н, Невский пр-кт, д 106\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/shipping/calculate"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "products": [
        {
            "id": 1,
            "quantity": 2
        },
        {
            "id": 5,
            "quantity": 1
        }
    ],
    "address": "191025, г Санкт-Петербург, Центральный р-н, Невский пр-кт, д 106"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-shipping-calculate">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;price&quot;: 350,
        &quot;options&quot;: [
            {
                &quot;carrier&quot;: &quot;CDEK&quot;,
                &quot;service&quot;: &quot;Курьер&quot;,
                &quot;price&quot;: 350
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-shipping-calculate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-shipping-calculate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-shipping-calculate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-shipping-calculate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-shipping-calculate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-shipping-calculate" data-method="POST"
      data-path="api/v1/shipping/calculate"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-shipping-calculate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-shipping-calculate"
                    onclick="tryItOut('POSTapi-v1-shipping-calculate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-shipping-calculate"
                    onclick="cancelTryOut('POSTapi-v1-shipping-calculate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-shipping-calculate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/shipping/calculate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-shipping-calculate"
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
                              name="Accept"                data-endpoint="POSTapi-v1-shipping-calculate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>products</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
<br>
<p>Список товаров для расчёта. Каждый элемент содержит id товара и количество.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="products.0.id"                data-endpoint="POSTapi-v1-shipping-calculate"
               value="1"
               data-component="body">
    <br>
<p>ID товара из каталога. The <code>id</code> of an existing record in the products table. Example: <code>1</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>quantity</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="products.0.quantity"                data-endpoint="POSTapi-v1-shipping-calculate"
               value="2"
               data-component="body">
    <br>
<p>Количество единиц товара (не менее 1). Example: <code>2</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-v1-shipping-calculate"
               value="191025, г Санкт-Петербург, Центральный р-н, Невский пр-кт, д 106"
               data-component="body">
    <br>
<p>Адрес доставки в формате с индексом (пример из доков Metaship). Must be at least 5 characters. Must not be greater than 255 characters. Example: <code>191025, г Санкт-Петербург, Центральный р-н, Невский пр-кт, д 106</code></p>
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
    --get "{{ config("app.url") }}/api/v1/orders?page=1&amp;per_page=15" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/orders"
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
<li><code>parent_id</code> - ID родительской категории (null для корневых категорий)</li>
<li><code>is_visible</code> - Флаг видимости категории</li>
<li><code>exclude_from_shipping</code> - Исключение категории из расчёта доставки</li>
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
    --get "{{ config("app.url") }}/api/v1/categories?show_hidden=&amp;ids[]=1&amp;ids[]=2&amp;ids[]=3" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/categories"
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
            &quot;parent_id&quot;: null,
            &quot;is_visible&quot;: true,
            &quot;exclude_from_shipping&quot;: false,
            &quot;children&quot;: [
                {
                    &quot;id&quot;: 2,
                    &quot;name&quot;: &quot;Ритуальные Свечи&quot;,
                    &quot;slug&quot;: &quot;ritualnye-svechi&quot;,
                    &quot;description&quot;: &quot;Свечи для различных ритуалов и церемоний&quot;,
                    &quot;parent_id&quot;: 1,
                    &quot;is_visible&quot;: true,
                    &quot;exclude_from_shipping&quot;: false
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
    --get "{{ config("app.url") }}/api/v1/categories/ritualnye-svechi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/categories/ritualnye-svechi"
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
        &quot;parent_id&quot;: 1,
        &quot;is_visible&quot;: true,
        &quot;exclude_from_shipping&quot;: false,
        &quot;children&quot;: [
            {
                &quot;id&quot;: 5,
                &quot;name&quot;: &quot;Свечи для привлечения денег&quot;,
                &quot;slug&quot;: &quot;svechi-dlya-privlecheniya-deneg&quot;,
                &quot;description&quot;: &quot;Специальные свечи для денежных ритуалов&quot;,
                &quot;parent_id&quot;: 2,
                &quot;is_visible&quot;: true,
                &quot;exclude_from_shipping&quot;: false
            },
            {
                &quot;id&quot;: 6,
                &quot;name&quot;: &quot;Любовные свечи&quot;,
                &quot;slug&quot;: &quot;lyubovnye-svechi&quot;,
                &quot;description&quot;: &quot;Свечи для привлечения любви и укрепления отношений&quot;,
                &quot;parent_id&quot;: 2,
                &quot;is_visible&quot;: true,
                &quot;exclude_from_shipping&quot;: false
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

                <h1 id="oplata-zakaza">Оплата заказа</h1>



                                <h2 id="oplata-zakaza-POSTapi-v1-payments">Создать платеж и получить ссылку на оплату.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-payments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/payments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"order_id\": 1,
    \"success_url\": \"https:\\/\\/shop.example.com\\/payment\\/success\",
    \"fail_url\": \"https:\\/\\/shop.example.com\\/payment\\/fail\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/payments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "order_id": 1,
    "success_url": "https:\/\/shop.example.com\/payment\/success",
    "fail_url": "https:\/\/shop.example.com\/payment\/fail"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-payments">
            <blockquote>
            <p>Example response (200, Успешно):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Платеж создан&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;uuid&quot;,
        &quot;payment_url&quot;: &quot;https://...&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-payments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-payments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-payments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-payments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-payments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-payments" data-method="POST"
      data-path="api/v1/payments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-payments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-payments"
                    onclick="tryItOut('POSTapi-v1-payments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-payments"
                    onclick="cancelTryOut('POSTapi-v1-payments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-payments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/payments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-payments"
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
                              name="Accept"                data-endpoint="POSTapi-v1-payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>order_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="order_id"                data-endpoint="POSTapi-v1-payments"
               value="1"
               data-component="body">
    <br>
<p>ID заказа. The <code>id</code> of an existing record in the orders table. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>success_url</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="success_url"                data-endpoint="POSTapi-v1-payments"
               value="https://shop.example.com/payment/success"
               data-component="body">
    <br>
<p>URL для редиректа после успешной оплаты. Must be a valid URL. Must not be greater than 2048 characters. Example: <code>https://shop.example.com/payment/success</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>fail_url</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="fail_url"                data-endpoint="POSTapi-v1-payments"
               value="https://shop.example.com/payment/fail"
               data-component="body">
    <br>
<p>URL для редиректа после ошибки оплаты. Must be a valid URL. Must not be greater than 2048 characters. Example: <code>https://shop.example.com/payment/fail</code></p>
        </div>
        </form>

                    <h2 id="oplata-zakaza-POSTapi-v1-payments--payment--refund">Возврат платежа.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-payments--payment--refund">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/payments/architecto/refund" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"amount\": 1990.5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/payments/architecto/refund"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "amount": 1990.5
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-payments--payment--refund">
</span>
<span id="execution-results-POSTapi-v1-payments--payment--refund" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-payments--payment--refund"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-payments--payment--refund"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-payments--payment--refund" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-payments--payment--refund">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-payments--payment--refund" data-method="POST"
      data-path="api/v1/payments/{payment}/refund"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-payments--payment--refund', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-payments--payment--refund"
                    onclick="tryItOut('POSTapi-v1-payments--payment--refund');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-payments--payment--refund"
                    onclick="cancelTryOut('POSTapi-v1-payments--payment--refund');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-payments--payment--refund"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/payments/{payment}/refund</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-payments--payment--refund"
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
                              name="Accept"                data-endpoint="POSTapi-v1-payments--payment--refund"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>payment</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment"                data-endpoint="POSTapi-v1-payments--payment--refund"
               value="architecto"
               data-component="url">
    <br>
<p>The payment. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-v1-payments--payment--refund"
               value="1990.5"
               data-component="body">
    <br>
<p>Сумма возврата (если не указана, возвращается полная сумма). Example: <code>1990.5</code></p>
        </div>
        </form>

                    <h2 id="oplata-zakaza-POSTapi-v1-payments-alfabank-webhook">Webhook от Альфа-Банка для обновления статуса платежа.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-payments-alfabank-webhook">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/payments/alfabank/webhook" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"orderId\": \"a1b2c3d4-e5f6-7890-abcd-ef1234567890\",
    \"mdOrder\": \"architecto\",
    \"orderStatus\": 2,
    \"status\": \"architecto\",
    \"amount\": 16
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/payments/alfabank/webhook"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "orderId": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
    "mdOrder": "architecto",
    "orderStatus": 2,
    "status": "architecto",
    "amount": 16
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-payments-alfabank-webhook">
</span>
<span id="execution-results-POSTapi-v1-payments-alfabank-webhook" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-payments-alfabank-webhook"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-payments-alfabank-webhook"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-payments-alfabank-webhook" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-payments-alfabank-webhook">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-payments-alfabank-webhook" data-method="POST"
      data-path="api/v1/payments/alfabank/webhook"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-payments-alfabank-webhook', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-payments-alfabank-webhook"
                    onclick="tryItOut('POSTapi-v1-payments-alfabank-webhook');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-payments-alfabank-webhook"
                    onclick="cancelTryOut('POSTapi-v1-payments-alfabank-webhook');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-payments-alfabank-webhook"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/payments/alfabank/webhook</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-payments-alfabank-webhook"
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
                              name="Accept"                data-endpoint="POSTapi-v1-payments-alfabank-webhook"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>orderId</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="orderId"                data-endpoint="POSTapi-v1-payments-alfabank-webhook"
               value="a1b2c3d4-e5f6-7890-abcd-ef1234567890"
               data-component="body">
    <br>
<p>ID заказа в платежной системе. This field is required when <code>mdOrder</code> is not present. Example: <code>a1b2c3d4-e5f6-7890-abcd-ef1234567890</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mdOrder</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="mdOrder"                data-endpoint="POSTapi-v1-payments-alfabank-webhook"
               value="architecto"
               data-component="body">
    <br>
<p>This field is required when <code>orderId</code> is not present. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>orderStatus</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="orderStatus"                data-endpoint="POSTapi-v1-payments-alfabank-webhook"
               value="2"
               data-component="body">
    <br>
<p>Статус заказа по шкале платежного шлюза. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-v1-payments-alfabank-webhook"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-v1-payments-alfabank-webhook"
               value="16"
               data-component="body">
    <br>
<p>Example: <code>16</code></p>
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
    "{{ config("app.url") }}/api/v1/order/calculate" \
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
    "{{ config("app.url") }}/api/v1/order/calculate"
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
    &quot;data&quot;: {
        &quot;items&quot;: [
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
        &quot;total_without_discount&quot;: 240,
        &quot;total_with_discount&quot;: 200,
        &quot;promo_code_status&quot;: &quot;applied&quot;
    },
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

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>items</code></b>&nbsp;&nbsp;
<small>string[][]</small>&nbsp;
 &nbsp;
<br>
<p>Массив товаров с рассчитанной стоимостью</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
<br>
<p>ID</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
<br>
<p>Название</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>count</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
<br>
<p>Количество</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>summery</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
<br>
<p>Итоговая стоимость товара (с учетом промокода)</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>summery_old</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
<br>
<p>Итоговая стоимость товара без промокода</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>discounted</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
<br>
<p>Применен ли промокод к товару</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>total_without_discount</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
<br>
<p>Общая сумма заказа без промокода</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>total_with_discount</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
<br>
<p>Общая сумма заказа с промокодом</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>promo_code_status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
<br>
<p>Статус промокода: &quot;not_sent&quot; (промокод не был отправлен), &quot;not_exists&quot; (промокод не существует / не активен), &quot;not_applied&quot; (промокод существует, но не применился к товарам), &quot;applied&quot; (промокод применился)</p>
        </div>
                        <h2 id="oformlenie-zakaza-POSTapi-v1-order">Оформление заказа (создание)</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-order">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/order" \
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
    \"register\": false,
    \"delivery_type\": \"PostOffice\",
    \"first_name\": \"Admin\",
    \"last_name\": \"System\",
    \"middle_name\": \"Root\",
    \"email\": \"admin@admin.ru\",
    \"phone\": \"+7 999 999 99 99\",
    \"address\": \"ул. Администраторская, д. 1\",
    \"password\": \"StrongPass123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/order"
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
    "register": false,
    "delivery_type": "PostOffice",
    "first_name": "Admin",
    "last_name": "System",
    "middle_name": "Root",
    "email": "admin@admin.ru",
    "phone": "+7 999 999 99 99",
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
<p>ID товара. The <code>id</code> of an existing record in the products table. Example: <code>1</code></p>
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
<p>Промокод. Must not be greater than 32 characters. Example: <code>PROMO2208</code></p>
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
<p>Зарегистрировать пользователя. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_type"                data-endpoint="POSTapi-v1-order"
               value="PostOffice"
               data-component="body">
    <br>
<p>Тип доставки (PostOffice или Cdek). Стоимость доставки рассчитывается на сервере автоматически через MetaShip. Example: <code>PostOffice</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>PostOffice</code></li> <li><code>Cdek</code></li></ul>
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
<p>Имя пользователя. Must not be greater than 64 characters. Example: <code>Admin</code></p>
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
<p>Фамилия пользователя. Must not be greater than 64 characters. Example: <code>System</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>middle_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="middle_name"                data-endpoint="POSTapi-v1-order"
               value="Root"
               data-component="body">
    <br>
<p>Отчество пользователя. Must not be greater than 64 characters. Example: <code>Root</code></p>
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
<p>Email пользователя. Must be a valid email address. Must not be greater than 128 characters. Example: <code>admin@admin.ru</code></p>
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
<p>Телефон пользователя. Must not be greater than 32 characters. Example: <code>+7 999 999 99 99</code></p>
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
<p>Адрес доставки. Must not be greater than 255 characters. Example: <code>ул. Администраторская, д. 1</code></p>
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
<p>Пароль (если регистрация). This field is required when <code>register</code> is <code>true</code>. Must be at least 8 characters. Must not be greater than 64 characters. Example: <code>StrongPass123</code></p>
        </div>
        </form>

                    <h2 id="oformlenie-zakaza-POSTapi-v1-order-address-suggest">Подсказки адреса через DaData.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-order-address-suggest">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/order/address/suggest" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"query\": \"москва хабар\",
    \"count\": 10,
    \"language\": \"ru\",
    \"division\": \"administrative\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/order/address/suggest"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "query": "москва хабар",
    "count": 10,
    "language": "ru",
    "division": "administrative"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-order-address-suggest">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;suggestions&quot;: [
            {
                &quot;value&quot;: &quot;г Москва, ул Хабаровская&quot;
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-order-address-suggest" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-order-address-suggest"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-order-address-suggest"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-order-address-suggest" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-order-address-suggest">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-order-address-suggest" data-method="POST"
      data-path="api/v1/order/address/suggest"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-order-address-suggest', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-order-address-suggest"
                    onclick="tryItOut('POSTapi-v1-order-address-suggest');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-order-address-suggest"
                    onclick="cancelTryOut('POSTapi-v1-order-address-suggest');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-order-address-suggest"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/order/address/suggest</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-order-address-suggest"
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
                              name="Accept"                data-endpoint="POSTapi-v1-order-address-suggest"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>query</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="query"                data-endpoint="POSTapi-v1-order-address-suggest"
               value="москва хабар"
               data-component="body">
    <br>
<p>Фрагмент адреса или полный адрес для подсказок. Must be at least 3 characters. Must not be greater than 300 characters. Example: <code>москва хабар</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="count"                data-endpoint="POSTapi-v1-order-address-suggest"
               value="10"
               data-component="body">
    <br>
<p>Количество подсказок (1-20). Example: <code>10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>language</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="language"                data-endpoint="POSTapi-v1-order-address-suggest"
               value="ru"
               data-component="body">
    <br>
<p>Язык ответа DaData (ru или en). Example: <code>ru</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>ru</code></li> <li><code>en</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>division</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="division"                data-endpoint="POSTapi-v1-order-address-suggest"
               value="administrative"
               data-component="body">
    <br>
<p>Тип деления адресов (administrative или municipal). Example: <code>administrative</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>administrative</code></li> <li><code>municipal</code></li></ul>
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
    --get "{{ config("app.url") }}/api/v1/products/&amp;quot;&amp;quot;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/products/&amp;quot;&amp;quot;"
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
        &quot;is_new&quot;: true,
        &quot;is_bestseller&quot;: false,
        &quot;dimensions&quot;: {
            &quot;width&quot;: 10,
            &quot;height&quot;: 12,
            &quot;length&quot;: 10,
            &quot;weight&quot;: 350
        },
        &quot;breadcrumbs&quot;: [
            {
                &quot;name&quot;: &quot;Главная&quot;,
                &quot;slug&quot;: &quot;/&quot;,
                &quot;type&quot;: &quot;home&quot;
            },
            {
                &quot;name&quot;: &quot;Ароматические свечи&quot;,
                &quot;slug&quot;: &quot;aromaticheskie-svechi&quot;,
                &quot;type&quot;: &quot;category&quot;
            },
            {
                &quot;name&quot;: &quot;Ароматическая свеча Лаванда&quot;,
                &quot;slug&quot;: &quot;aromaticheskaya-svecha-lavanda&quot;,
                &quot;type&quot;: &quot;product&quot;
            }
        ],
        &quot;categories&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Ароматические свечи&quot;,
                &quot;slug&quot;: &quot;aromaticheskie-svechi&quot;,
                &quot;description&quot;: &quot;Свечи с различными ароматами&quot;,
                &quot;parent_id&quot;: null,
                &quot;is_visible&quot;: true,
                &quot;exclude_from_shipping&quot;: false
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
    --get "{{ config("app.url") }}/api/v1/products?search=%22%22&amp;category=%22%22&amp;price_from=%22%22&amp;price_to=%22%22&amp;is_new=1&amp;is_bestseller=1&amp;ids=%22%22&amp;sort=%22%22&amp;per_page=15&amp;page=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/products"
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
            &quot;is_new&quot;: true,
            &quot;is_bestseller&quot;: false,
            &quot;dimensions&quot;: {
                &quot;width&quot;: 10,
                &quot;height&quot;: 12,
                &quot;length&quot;: 10,
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
    --get "{{ config("app.url") }}/api/v1/profile" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/profile"
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
    &quot;address&quot;: &quot;Не дом и не улица, www ленинград&quot;
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
    "{{ config("app.url") }}/api/v1/profile" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"Иван\",
    \"last_name\": \"Иванов\",
    \"middle_name\": \"Иванович\",
    \"email\": \"ivan@example.com\",
    \"phone\": \"+79999999999\",
    \"address\": \"ул. Пример, д. 1\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/profile"
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
    &quot;address&quot;: &quot;Не дом и не улица, www ленинград&quot;
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

                <h1 id="sistemnye">Системные</h1>

    <p>Этот endpoint отправляет тестовое письмо на указанный email для проверки работоспособности почтовой системы.</p>

                                <h2 id="sistemnye-GETapi-v1-mail-test">Отправка тестового письма на email администратора.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-mail-test">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/mail/test" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/mail/test"
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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Письмо отправлено&quot;
}</code>
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

                <h1 id="temy-i-soobshheniia">Темы и сообщения</h1>

    <p>API для работы с темами обращений и сообщениями пользователей</p>
<p>Темы обращений представляют собой диалоги между пользователем и администратором.
Каждая тема может содержать множество сообщений от пользователя и администратора.</p>
<h2>Структура темы</h2>
<p>Каждая тема содержит следующие основные поля:</p>
<ul>
<li><code>id</code> - Уникальный идентификатор темы</li>
<li><code>title</code> - Название темы</li>
<li><code>status</code> - Статус темы (new, resolved, requires_response)</li>
<li><code>user_id</code> - ID пользователя, создавшего тему.</li>
<li><code>unread_messages_count</code> - Количество непрочитанных сообщений</li>
<li><code>messages</code> - Массив сообщений в теме (если запрошены)</li>
</ul>
<h2>Структура сообщения</h2>
<p>Каждое сообщение содержит следующие основные поля:</p>
<ul>
<li><code>id</code> - Уникальный идентификатор сообщения</li>
<li><code>content</code> - Текст сообщения</li>
<li><code>user_id</code> - ID пользователя, отправившего сообщение.</li>
<li><code>topic_id</code> - ID темы, к которой относится сообщение.</li>
<li><code>attachments</code> - Массив вложений к сообщению (если есть)</li>
</ul>

                                <h2 id="temy-i-soobshheniia-GETapi-v1-topics">Получение списка тем пользователя</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Возвращает список всех тем, созданных аутентифицированным пользователем.</p>

<span id="example-requests-GETapi-v1-topics">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/topics?page=1&amp;per_page=15" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/topics"
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

<span id="example-responses-GETapi-v1-topics">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Список тем пользователя&quot;,
    &quot;data&quot;: {
        &quot;current_page&quot;: 1,
        &quot;data&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;title&quot;: &quot;Проблема с заказом&quot;,
                &quot;status&quot;: &quot;new&quot;,
                &quot;status_text&quot;: &quot;Новый&quot;,
                &quot;created_at&quot;: &quot;2023-06-15 10:30:00&quot;,
                &quot;updated_at&quot;: &quot;2023-06-15 10:30:00&quot;,
                &quot;messages_count&quot;: 2,
                &quot;unread_messages_count&quot;: 1
            },
            {
                &quot;id&quot;: 2,
                &quot;title&quot;: &quot;Вопрос о доставке&quot;,
                &quot;status&quot;: &quot;requires_response&quot;,
                &quot;status_text&quot;: &quot;Требует ответа&quot;,
                &quot;created_at&quot;: &quot;2023-06-14 15:45:00&quot;,
                &quot;updated_at&quot;: &quot;2023-06-14 16:20:00&quot;,
                &quot;messages_count&quot;: 3,
                &quot;unread_messages_count&quot;: 0
            }
        ],
        &quot;first_page_url&quot;: &quot;http://example.com/api/v1/topics?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 1,
        &quot;last_page_url&quot;: &quot;http://example.com/api/v1/topics?page=1&quot;,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;http://example.com/api/v1/topics?page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;active&quot;: false
            }
        ],
        &quot;next_page_url&quot;: null,
        &quot;path&quot;: &quot;http://example.com/api/v1/topics&quot;,
        &quot;per_page&quot;: 15,
        &quot;prev_page_url&quot;: null,
        &quot;to&quot;: 2,
        &quot;total&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Не авторизован):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-topics" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-topics"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-topics"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-topics" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-topics">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-topics" data-method="GET"
      data-path="api/v1/topics"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-topics', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-topics"
                    onclick="tryItOut('GETapi-v1-topics');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-topics"
                    onclick="cancelTryOut('GETapi-v1-topics');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-topics"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/topics</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-topics"
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
                              name="Content-Type"                data-endpoint="GETapi-v1-topics"
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
                              name="Accept"                data-endpoint="GETapi-v1-topics"
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
               step="any"               name="page"                data-endpoint="GETapi-v1-topics"
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
               step="any"               name="per_page"                data-endpoint="GETapi-v1-topics"
               value="15"
               data-component="query">
    <br>
<p>Количество тем на страницу. Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="temy-i-soobshheniia-POSTapi-v1-topics">Создание новой темы</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Создает новую тему обращения от имени аутентифицированного пользователя,
а также первое сообщение в этой теме.</p>

<span id="example-requests-POSTapi-v1-topics">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/topics" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "title=Проблема с отображением заказа"\
    --form "content=Здравствуйте, у меня не отображается мой последний заказ."\
    --form "attachments[]=@/private/var/folders/qb/ff0k8mwd1jj_b9q_52n_hjf80000gn/T/php1p7se9ppbbra2I2ccdX" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/topics"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('title', 'Проблема с отображением заказа');
body.append('content', 'Здравствуйте, у меня не отображается мой последний заказ.');
body.append('attachments[]', document.querySelector('input[name="attachments[]"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-topics">
            <blockquote>
            <p>Example response (201, Тема успешно создана):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Тема успешно создана&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 3,
        &quot;title&quot;: &quot;Проблема с отображением заказа&quot;,
        &quot;status&quot;: &quot;new&quot;,
        &quot;status_text&quot;: &quot;Новый&quot;,
        &quot;created_at&quot;: &quot;2023-06-16 12:00:00&quot;,
        &quot;updated_at&quot;: &quot;2023-06-16 12:00:00&quot;,
        &quot;messages_count&quot;: 1,
        &quot;messages&quot;: [
            {
                &quot;id&quot;: 3,
                &quot;content&quot;: &quot;Здравствуйте, у меня не отображается мой последний заказ.&quot;,
                &quot;user&quot;: {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Иванов Иван Иванович&quot;,
                    &quot;email&quot;: &quot;user@example.com&quot;
                },
                &quot;created_at&quot;: &quot;2023-06-16 12:00:00&quot;,
                &quot;updated_at&quot;: &quot;2023-06-16 12:00:00&quot;,
                &quot;attachments&quot;: [
                    {
                        &quot;id&quot;: 2,
                        &quot;file_name&quot;: &quot;screenshot.png&quot;,
                        &quot;mime_type&quot;: &quot;image/png&quot;,
                        &quot;size&quot;: 512000,
                        &quot;url&quot;: &quot;http://example.com/storage/2/screenshot.png&quot;,
                        &quot;thumbnail&quot;: &quot;http://example.com/storage/2/conversions/screenshot-thumb.jpg&quot;,
                        &quot;created_at&quot;: &quot;2023-06-16 12:00:00&quot;
                    }
                ]
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Ошибка валидации):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;title&quot;: [
            &quot;The title field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-topics" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-topics"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-topics"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-topics" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-topics">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-topics" data-method="POST"
      data-path="api/v1/topics"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-topics', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-topics"
                    onclick="tryItOut('POSTapi-v1-topics');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-topics"
                    onclick="cancelTryOut('POSTapi-v1-topics');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-topics"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/topics</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-topics"
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
                              name="Content-Type"                data-endpoint="POSTapi-v1-topics"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-topics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-v1-topics"
               value="Проблема с отображением заказа"
               data-component="body">
    <br>
<p>Заголовок темы. Example: <code>Проблема с отображением заказа</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>content</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="content"                data-endpoint="POSTapi-v1-topics"
               value="Здравствуйте, у меня не отображается мой последний заказ."
               data-component="body">
    <br>
<p>Текст первого сообщения. Example: <code>Здравствуйте, у меня не отображается мой последний заказ.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>attachments</code></b>&nbsp;&nbsp;
<small>file[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="attachments[0]"                data-endpoint="POSTapi-v1-topics"
               data-component="body">
        <input type="file" style="display: none"
               name="attachments[1]"                data-endpoint="POSTapi-v1-topics"
               data-component="body">
    <br>
<p>Массив вложений (скриншоты). Максимум 5 файлов, каждый до 2MB.</p>
        </div>
        </form>

                    <h2 id="temy-i-soobshheniia-GETapi-v1-topics-unread-count">Получение количества непрочитанных сообщений</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Возвращает общее количество непрочитанных сообщений для текущего пользователя.</p>

<span id="example-requests-GETapi-v1-topics-unread-count">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/topics/unread-count" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/topics/unread-count"
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

<span id="example-responses-GETapi-v1-topics-unread-count">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Количество непрочитанных сообщений&quot;,
    &quot;data&quot;: {
        &quot;unread_messages_count&quot;: 3
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-topics-unread-count" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-topics-unread-count"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-topics-unread-count"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-topics-unread-count" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-topics-unread-count">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-topics-unread-count" data-method="GET"
      data-path="api/v1/topics/unread-count"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-topics-unread-count', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-topics-unread-count"
                    onclick="tryItOut('GETapi-v1-topics-unread-count');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-topics-unread-count"
                    onclick="cancelTryOut('GETapi-v1-topics-unread-count');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-topics-unread-count"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/topics/unread-count</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-topics-unread-count"
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
                              name="Content-Type"                data-endpoint="GETapi-v1-topics-unread-count"
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
                              name="Accept"                data-endpoint="GETapi-v1-topics-unread-count"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="temy-i-soobshheniia-GETapi-v1-topics--topicId-">Получение темы с сообщениями</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Возвращает детальную информацию о теме, включая все сообщения в ней.
Тема должна принадлежать аутентифицированному пользователю.</p>

<span id="example-requests-GETapi-v1-topics--topicId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/topics/1" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/topics/1"
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

<span id="example-responses-GETapi-v1-topics--topicId-">
            <blockquote>
            <p>Example response (200, Успешный запрос):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Детали темы&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;Проблема с заказом&quot;,
        &quot;status&quot;: &quot;new&quot;,
        &quot;status_text&quot;: &quot;Новый&quot;,
        &quot;created_at&quot;: &quot;2023-06-15 10:30:00&quot;,
        &quot;updated_at&quot;: &quot;2023-06-15 10:30:00&quot;,
        &quot;messages_count&quot;: 2,
        &quot;unread_messages_count&quot;: 0,
        &quot;messages&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;content&quot;: &quot;У меня возникла проблема с последним заказом. Не получил подтверждение оплаты.&quot;,
                &quot;user&quot;: {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Иванов Иван Иванович&quot;,
                    &quot;email&quot;: &quot;user@example.com&quot;
                },
                &quot;created_at&quot;: &quot;2023-06-15 10:30:00&quot;,
                &quot;updated_at&quot;: &quot;2023-06-15 10:30:00&quot;,
                &quot;attachments&quot;: [
                    {
                        &quot;id&quot;: 1,
                        &quot;file_name&quot;: &quot;document.pdf&quot;,
                        &quot;mime_type&quot;: &quot;application/pdf&quot;,
                        &quot;size&quot;: 1024000,
                        &quot;url&quot;: &quot;http://example.com/storage/1/document.pdf&quot;,
                        &quot;thumbnail&quot;: &quot;http://example.com/storage/1/conversions/document-thumb.jpg&quot;,
                        &quot;created_at&quot;: &quot;2023-06-15 10:30:00&quot;
                    }
                ]
            },
            {
                &quot;id&quot;: 2,
                &quot;content&quot;: &quot;Здравствуйте! Проверим информацию по вашему заказу и свяжемся с вами в ближайшее время.&quot;,
                &quot;user&quot;: {
                    &quot;id&quot;: 2,
                    &quot;name&quot;: &quot;Администратор&quot;,
                    &quot;email&quot;: &quot;admin@example.com&quot;
                },
                &quot;created_at&quot;: &quot;2023-06-15 11:15:00&quot;,
                &quot;updated_at&quot;: &quot;2023-06-15 11:15:00&quot;,
                &quot;attachments&quot;: []
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Не авторизован):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Тема не найдена):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Тема не найдена или не принадлежит пользователю&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-topics--topicId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-topics--topicId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-topics--topicId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-topics--topicId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-topics--topicId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-topics--topicId-" data-method="GET"
      data-path="api/v1/topics/{topicId}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-topics--topicId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-topics--topicId-"
                    onclick="tryItOut('GETapi-v1-topics--topicId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-topics--topicId-"
                    onclick="cancelTryOut('GETapi-v1-topics--topicId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-topics--topicId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/topics/{topicId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-topics--topicId-"
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
                              name="Content-Type"                data-endpoint="GETapi-v1-topics--topicId-"
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
                              name="Accept"                data-endpoint="GETapi-v1-topics--topicId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>topicId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="topicId"                data-endpoint="GETapi-v1-topics--topicId-"
               value="1"
               data-component="url">
    <br>
<p>ID темы. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="temy-i-soobshheniia-POSTapi-v1-topics--topicId--messages">Добавление сообщения в тему</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Добавляет новое сообщение от имени аутентифицированного пользователя
в существующую тему. Пользователь должен быть владельцем темы.</p>

<span id="example-requests-POSTapi-v1-topics--topicId--messages">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "{{ config("app.url") }}/api/v1/topics/1/messages" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"content\": \"Вот дополнительная информация по моему вопросу.\",
    \"attachments\": null
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/topics/1/messages"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "Вот дополнительная информация по моему вопросу.",
    "attachments": null
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-topics--topicId--messages">
            <blockquote>
            <p>Example response (201, Сообщение успешно добавлено):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Сообщение успешно добавлено&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 4,
        &quot;content&quot;: &quot;Вот скриншот моей проблемы.&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Иванов Иван Иванович&quot;,
            &quot;email&quot;: &quot;user@example.com&quot;
        },
        &quot;created_at&quot;: &quot;2023-06-16 12:30:00&quot;,
        &quot;updated_at&quot;: &quot;2023-06-16 12:30:00&quot;,
        &quot;attachments&quot;: [
            {
                &quot;id&quot;: 3,
                &quot;file_name&quot;: &quot;screenshot-2.png&quot;,
                &quot;mime_type&quot;: &quot;image/png&quot;,
                &quot;size&quot;: 612000,
                &quot;url&quot;: &quot;http://example.com/storage/3/screenshot-2.png&quot;,
                &quot;thumbnail&quot;: &quot;http://example.com/storage/3/conversions/screenshot-2-thumb.jpg&quot;,
                &quot;created_at&quot;: &quot;2023-06-16 12:30:00&quot;
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-topics--topicId--messages" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-topics--topicId--messages"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-topics--topicId--messages"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-topics--topicId--messages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-topics--topicId--messages">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-topics--topicId--messages" data-method="POST"
      data-path="api/v1/topics/{topicId}/messages"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-topics--topicId--messages', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-topics--topicId--messages"
                    onclick="tryItOut('POSTapi-v1-topics--topicId--messages');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-topics--topicId--messages"
                    onclick="cancelTryOut('POSTapi-v1-topics--topicId--messages');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-topics--topicId--messages"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/topics/{topicId}/messages</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-topics--topicId--messages"
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
                              name="Content-Type"                data-endpoint="POSTapi-v1-topics--topicId--messages"
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
                              name="Accept"                data-endpoint="POSTapi-v1-topics--topicId--messages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>topicId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="topicId"                data-endpoint="POSTapi-v1-topics--topicId--messages"
               value="1"
               data-component="url">
    <br>
<p>ID темы. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>content</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="content"                data-endpoint="POSTapi-v1-topics--topicId--messages"
               value="Вот дополнительная информация по моему вопросу."
               data-component="body">
    <br>
<p>Текст сообщения. Example: <code>Вот дополнительная информация по моему вопросу.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>attachments</code></b>&nbsp;&nbsp;
<small>file[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="attachments[0]"                data-endpoint="POSTapi-v1-topics--topicId--messages"
               data-component="body">
        <input type="file" style="display: none"
               name="attachments[1]"                data-endpoint="POSTapi-v1-topics--topicId--messages"
               data-component="body">
    <br>
<p>Массив вложений (скриншоты). Каждый файл до 2MB.</p>
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
