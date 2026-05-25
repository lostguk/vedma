# Подсказки адресов (DaData)

## Описание
API позволяет преобразовать строковый адрес клиента в объект подсказок DaData для дальнейшего выбора на фронтенде.

## API Endpoints
- `POST /api/v1/order/address/suggest` - получить подсказки адреса

## Параметры запроса
- `query` - текст запроса (часть адреса или полный адрес)
- `count` - количество подсказок (1-20, опционально)
- `language` - язык ответа (`ru` или `en`, опционально)
- `division` - тип деления (`administrative` или `municipal`, опционально)

## Пример запроса
```json
{
  "query": "москва хабар",
  "count": 5,
  "language": "ru",
  "division": "administrative"
}
```

## Пример ответа
```json
{
  "status": "success",
  "message": "Success",
  "data": {
    "suggestions": [
      {
        "value": "г Москва, ул Хабаровская"
      }
    ]
  }
}
```

## Источник данных
- DaData подсказки адресов: https://dadata.ru/api/suggest/address/
