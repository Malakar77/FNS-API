Here's the `README.md` file for the GitHub repository:

```markdown
# Company Search API Integration

This repository contains a PHP script to search for companies using the FNS (Federal Tax Service) API.

## Requirements

- PHP 7.0 or higher
- cURL extension for PHP

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/company-search-api.git
    ```
2. Navigate to the project directory:
    ```bash
    cd company-search-api
    ```
3. Update the API key in `search_companies.php`:
    ```php
    $apiKey = 'your API key';
    ```

## Usage

### Request

The script expects a JSON input with the following structure:

```json
{
  "gain": "", 
  "okved": "01.1|01.11|01.11.1|...",
  "people": "",
  "search": "", 
  "type": "onlyul"
}
```

### Parameters

| Parameter | Type   | Example                 | Description                                                                                                                                                           |
|-----------|--------|-------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `q`       | string | `q=Гордиенко Василий`   | Search query. If `q=any`, all organizations matching the `filter` parameter will be returned.                                                                         |
| `page`    | int    | `page=2`                | Search page (search returns only the first 100 results, use `page` to display the next set of results).                                                               |
| `filter`  | string | `filter=active`         | Filtering options. Possible values (separated by `+`): `active`, `onlyul`, `onlyip`, `okved`, `okvedgroup`, `region`, `vyruchka`, `sotrudnikov`, `datereg`, `reestrmsp`, `reestrmsp1`, `reestrmsp2`, `reestrmsp3`, `withphone`, `withemail`. |

### Example Request

```bash
curl -X POST -H "Content-Type: application/json" -d '{
  "gain": "", 
  "okved": "01.1|01.11|01.11.1|...",
  "people": "",
  "search": "Гордиенко Василий", 
  "type": "onlyul"
}' http://yourserver.com/search_companies.php
```

## Response

The server returns a structured JSON document containing a list of found organizations.

### Response Fields

| Field         | Type   | Description                                                                                                      |
|---------------|--------|------------------------------------------------------------------------------------------------------------------|
| `items`       | array  | Array of search results                                                                                          |
| `ЮЛ`, `ИП`, `НР` | object | Type of found company (legal entity, individual entrepreneur, or foreign company representation)                |
| `ИНН`         | string | INN of the found company                                                                                         |
| `ОГРН`        | string | OGRN of the found company                                                                                        |
| `НаимСокрЮЛ`  | string | Full name of the legal entity (only for legal entities)                                                          |
| `НаимПолнЮЛ`  | string | Short name of the legal entity (only for legal entities)                                                         |
| `ФИОПолн`     | string | Full name of the individual entrepreneur (only for individual entrepreneurs)                                     |
| `ДатаРег`     | string | Registration date of the legal entity or individual entrepreneur in the format YYYY-MM-DD                        |
| `Статус`      | string | Status of the legal entity or individual entrepreneur: "Active", "Liquidated", "Under reorganization", etc.       |
| `ДатаПрекр`   | string | Date of termination of the legal entity or individual entrepreneur in the format YYYY-MM-DD (if activity ceased) |
| `АдресПолн`   | string | Full address of the organization                                                                                 |
| `ОснВидДеят`  | string | Main activity                                                                                                    |
| `ГдеНайдено`  | string | Where the search string was found: OGRN, INN, OGRNIP, INNIP, etc.                                                |
| `nextpage`    | object | `true` if there is a next page                                                                                   |
| `filter`      | object | Filter string, if the request includes the `filter` parameter                                                    |
| `filter_any_count` | int | Total number of found records when filtering (returned if the `filter` parameter and `req=any` are specified)  |
| `Count`       | int    | Number of found records                                                                                          |

### Example Response

```json
{
  "items": [
    {
      "ЮЛ": {
        "ИНН": "6732133377",
        "ОГРН": "1166733070492",
        "НаимСокрЮЛ": "СРФСОО \"ФДСО\"",
        "НаимПолнЮЛ": "СМОЛЕНСКАЯ РЕГИОНАЛЬНАЯ ФИЗКУЛЬТУРНО-СПОРТИВНАЯ ОБЩЕСТВЕННАЯ ОРГАНИЗАЦИЯ \"ФЕДЕРАЦИЯ ДАРТС СМОЛЕНСКОЙ ОБЛАСТИ\"",
        "ДатаРег": "2016-09-28",
        "Статус": "Действующее",
        "АдресПолн": "обл. Смоленская, г. Смоленск, ул. Попова, д.121, кв.152",
        "ОснВидДеят": "Деятельность в области спорта прочая",
        "ГдеНайдено": "ФИО учредителя (Борунов Алексей Владимирович, ИННФЛ: 673105975402)"
      }
    },
    ...
  ],
  "Count": 6
}
```

## License

This project is licensed under the MIT License.


