API for Searching Companies

This repository provides a PHP script for searching companies using the api-fns.ru API.
Input Data Format

The input data should be a JSON object with the following structure:

json

{
    "gain": "",
    "okved": "01.1|01.11|01.11.1|01.11.11|01.11.12|01.11.13|01.11.14|01.11.15|01.11.16|01.11.19|01.11.2|01.11.3|01.11.31|01.11.32|01.11.33|01.11.39|01.12|01.13|01.13.1|01.13.11|01.13.12|01.13.2|01.13.3|01.13.31|01.13.39|01.13.4|01.13.5|01.13.51|01.13.52|01.13.6|01.13.9|01.14|01.15|01.16|01.16.1|01.16.2|01.16.3|01.16.9|01.19|01.19.1|01.19.2|01.19.21|01.19.22|01.19.3|01.19.9|01.2|01.21|01.22|01.23|01.24|01.25|01.25.1|01.25.2|01.25.3|01.26|01.27|01.27.1|01.27.9|01.28|01.28.1|01.28.2|01.28.3|01.29|01.3|01.30|01.4|01.41|01.41.1|01.41.11|01.41.12|01.41.2|01.41.21|01.41.29|01.42|01.42.1|01.42.11|01.42.12|01.42.2|01.43|01.43.1|01.43.2|01.43.3|01.44|01.45|01.45.1|01.45.2|01.45.3|01.45.4|01.46|01.46.1|01.46.11|01.46.12|01.46.2|01.47|01.47.1|01.47.11|01.47.12|01.47.2|01.47.3|01.49|01.49.1|01.49.11|01.49.12|01.49.13|01.49.2|01.49.21|01.49.22|01.49.3|01.49.31|01.49.32|01.49.4|01.49.41|01.49.42|01.49.43|01.49.44|01.49.5|01.49.6|01.49.7|01.49.9|01.5|01.50|01.6|01.61|01.62|01.63|01.64|01.7|01.70",
    "people": "",
    "search": "",
    "type": "onlyul"
}

Request Parameters

The request parameters are as follows:
Parameter	Type and Example	Description
q	string	q=Гордиенко Василий - Search query string. If q=any, all organizations matching the filter parameter will be returned.
page	integer	page=2 - Search result page (only the first 100 results are returned, use the page parameter to get more results).
Optional Parameters
Parameter	Type and Example	Description
filter	string	filter=active - Filter results. Possible values (separated by +):
		active - Returns only active organizations
		onlyul - Returns only legal entities (excluding individual entrepreneurs)
		onlyip - Returns only individual entrepreneurs (excluding legal entities)
		okved - Returns organizations with specified main OKVED, separated by
		okvedgroup - Returns organizations with specified OKVED group (e.g., okvedgroup69).
		region - Returns organizations registered in specified regions (e.g., `region77
		vyruchka - Returns organizations with revenue in specified range (e.g., vyruchka>5000<20000).
		sotrudnikov - Returns organizations with specified number of employees (e.g., sotrudnikov>5<20).
		datereg - Returns organizations with registration date in specified range (e.g., datereg>01.01.2019<01.01.2018).
		reestrmsp - Returns organizations included in the SME registry on the latest available date.
		reestrmsp1, reestrmsp2, or reestrmsp3 - Selects Micro, Small, or Medium enterprises, respectively.
		withphone - Returns organizations with a phone number in contacts.
		withemail - Returns organizations with an email in contacts.
Response Format

The response document (HTTP response) is a structured JSON document containing a list of found organizations.
Fields in the Response Document
Name	Type	Description
items	array	Array of search results
ЮЛ, ИП или НР	object	Type of found company (legal entity, individual entrepreneur, or foreign company representation)
ИНН	string	INN of the found company
ОГРН	string	OGRN of the found company
НаимСокрЮЛ	string	Full name of the legal entity (only for legal entities)
НаимПолнЮЛ	string	Short name of the legal entity (only for legal entities)
ФИОПолн	string	Full name of the individual entrepreneur (only for individual entrepreneurs)
ДатаРег	string	Registration date of the legal entity (individual entrepreneur) in YYYY-MM-DD format
Статус	string	Status of the legal entity (individual entrepreneur): "Active", "Liquidated", "In Reorganization", etc.
ДатаПрекр	string	Date of termination of the legal entity (individual entrepreneur) in YYYY-MM-DD format (if terminated)
АдресПолн	string	Full address of the organization
ОснВидДеят	string	Main activity type
ГдеНайдено	string	Where the search string was found:
		- OGRN, INN
		- OGRNIP, INNIP
		- Founder's INN, former founder's INN
		- Director's INN, former director's INN
		- Leader's INN, former leader's INN (if the leader is not the first)
		- Legal entity name
		- Founder's full name, former founder's full name
		- Director's full name, former director's full name
		- Individual entrepreneur's full name
		- Address
		- Participation in the authorized capital with the share specified
		- Filter
nextpage	object	true if there is a next page in the request
filter	object	Filter string if the request includes a filter
filter_any_count	integer	Total number of found records when filtering (displayed if the filter parameter and req=any are specified)
Count	integer	Number of found records
Example Server Response

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
    {
      "ЮЛ": {
        "ИНН": "2311146207",
        "ОГРН": "1122311005453",
        "НаимСокрЮЛ": "СНП \"МЕГАТРОН\"",
        "НаимПолнЮЛ": "САДОВОДЧЕСКОЕ НЕКОММЕРЧЕСКОЕ ПАРТНЕРСТВО \"МЕГАТРОН\"",
        "ДатаРег": "2012-06-20",
        "Статус": "Действующее",
        "АдресПолн": "край Краснодарский, р-н Динской, п. Южный, ул. Строителей, д.64",
        "ОснВидДеят": "Управление эксплуатацией нежилого фонда за вознаграждение или на договорной основе",
        "ГдеНайдено": "ФИО бывшего учредителя (Борунов Алексей Владимирович, ИННФЛ: 231121127449)"
      }
    },
    {
      "ЮЛ": {
        "ИНН": "7614005733",
        "ОГРН": "1127609000309",
        "НаимСокрЮЛ": "ООО \"ЛИГА\"",
        "НаимПолнЮЛ": "ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ \"ЛИГА\"",
        "ДатаРег": "2012-03-22",
        "Статус": "Ликвидировано по 129-ФЗ",
        "ДатаПрекр": "2016-11-21",
        "АдресПолн": "обл. Ярославская, р-н Борисоглебский, п. Борисоглебский, ул. Допризывная, д.30",
        "ОснВидДеят": "Производство пластмассовых изделий для упаковывания товаров",
        "ГдеНайдено": "ФИО директора (Борунов Алексей Владимирович, ИННФЛ: 761400964351)"
      }
    },
    {
      "ИП": {
        "ИНН": "673105975402",
        "ОГРН": "308673128200018",
        "ФИОПолн": "Борунов Алексей Владимирович",
        "ДатаРег": "2008-10-08",
        "Статус": "Прекратило деятельность",
        "ДатаПрекр": "2010-07-05",
        "АдресПолн": "обл. Смоленская, г. Смоленск",
        "ОснВидДеят": "Ремонт бытовых изделий и предметов личного пользования",
        "ГдеНайдено": "ФИО ИП полное"
      }
    },
    {
      "ИП": {
        "ИНН": "231121127449",
        "ОГРН": "308231118900012",
        "ФИОПолн": "Борунов Алексей Владимирович",
        "ДатаРег": "2008-07-07",
        "Статус": "Прекратило деятельность",
        "ДатаПрекр": "2012-11-14",
        "АдресПолн": "край Краснодарский, г. Краснодар",
        "ОснВидДеят": "Техническое обслуживание и ремонт автотранспортных средств",
        "ГдеНайдено": "ФИО ИП полное"
      }
    },
    {
      "ИП": {
        "ИНН": "111800978544",
        "ОГРН": "312293215200092",
        "ФИОПолн": "Борунов Алексей Владимирович",
        "ДатаРег": "2012-05-31",
        "Статус": "Прекратило деятельность",
        "ДатаПрекр": "2015-04-16",
        "АдресПолн": "обл. Архангельская, р-н Лешуконский, с. Койнас",
        "ОснВидДеят": "Прочая деятельность по организации отдыха и развлечений",
        "ГдеНайдено": "ФИО ИП полное"
      }
    }
  ],
  "Count": 6
}
