<?php
// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD']=='POST') {

// Получаем данные из тела запроса
    $data = json_decode(file_get_contents("php://input"));
    $result=array();

    $result['company'] = serchCompany($data);

}

function serchCompany(object $data) {
    $apiKey = 'your API key';
    $baseUrl = 'https://api-fns.ru/api/search';

    // Массив параметров для построения URL
    $params = [
        'q' => !empty($data->addFormCompany->search) ? $data->addFormCompany->search : 'any',
        'filter' => 'active onlyul withphone',
        'key' => $apiKey
    ];

    if (!empty($data->addFormCompany->okved)) {
        $params['filter'] .= ' okved' . $data->addFormCompany->okved;
    }

    if (!empty($data->addFormCompany->region)) {
        $params['filter'] .= ' region' . str_replace(' ', '|', $data->addFormCompany->region);
    }

    if (!empty($data->addFormCompany->people)) {
        $params['filter'] .= ' sotrudnikov>' . $data->addFormCompany->people;
    }

    if (!empty($data->addFormCompany->gain)) {
        $params['filter'] .= ' vyruchka>' . $data->addFormCompany->gain;
    }

    // Инициализация переменной для страницы
    $page = 1;
    $result = []; // Массив для хранения всех результатов

    // Цикл для получения данных с последующих страниц
    do {
        // Добавляем параметр page к запросу
        $params['page'] = $page;

        // Построение URL
        $url = $baseUrl . '?' . http_build_query($params);

        // Выполняем запрос
        $response = test($url);

        // Обработка данных ответа
        if (isset($response['items'])) {
            $result = array_merge($result, $response['items']);
        }

        // Увеличиваем номер страницы
        $page++;

        // Проверка условия продолжения цикла
    } while ($response['nextpage'] === true && $page <= 10);

    // Возвращаем все собранные данные
    return $result;
}

function test($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    // Обработка и декодирование JSON-ответа
    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die('Error decoding JSON');
    }

    // Возвращаем данные
    return $data;
}

