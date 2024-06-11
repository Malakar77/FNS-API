<?php
// Устанавливаем заголовок для возвращаемого JSON
header('Content-Type: application/json');

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Получаем данные из тела запроса
    $data = json_decode(file_get_contents("php://input"));

    // Проверяем, что данные были получены правильно
    if (json_last_error() === JSON_ERROR_NONE) {
        // Вызываем функцию для поиска компаний и сохраняем результат
        $result['company'] = serchCompany($data);
        
        // Возвращаем результат в формате JSON
        echo json_encode($result);
    } else {
        // Если данные не получены правильно, возвращаем ошибку
        echo json_encode(['error' => 'Invalid JSON input']);
    }
}

// Функция для поиска компаний
function serchCompany(object $data) {
    // Ваш API-ключ
    $apiKey = 'ВАШ КЛЮЧ API';
    $baseUrl = 'https://api-fns.ru/api/search';

    // Массив параметров для построения URL
    $params = [
        'q' => !empty($data->search) ? $data->search : 'any',
        'filter' => 'active onlyul withphone',
        'key' => $apiKey
    ];

    // Добавляем параметры в запрос, если они присутствуют
    if (!empty($data->okved)) {
        $params['filter'] .= ' okved' . $data->okved;
    }

    if (!empty($data->region)) {
        $params['filter'] .= ' region' . str_replace(' ', '|', $data->region);
    }

    if (!empty($data->people)) {
        $params['filter'] .= ' sotrudnikov>' . $data->people;
    }

    if (!empty($data->gain)) {
        $params['filter'] .= ' vyruchka>' . $data->gain;
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
        $response = CurlSent($url);

        // Обработка данных ответа
        if (isset($response['items'])) {
            // Объединяем результаты текущей страницы с общим результатом
            $result = array_merge($result, $response['items']);
        }

        // Увеличиваем номер страницы
        $page++;

        // Проверка условия продолжения цикла
    } while ($response['nextpage'] === true && $page <= 10);

    // Возвращаем все собранные данные
    return $result;
}

// Функция для выполнения запроса к API
function CurlSent($url) {
    // Инициализация cURL
    $ch = curl_init();

    // Установка URL и параметров для cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Выполнение запроса
    $response = curl_exec($ch);

    // Проверка на ошибки выполнения cURL
    if (curl_errno($ch)) {
        die('Curl error: ' . curl_error($ch));
    }

    // Закрытие cURL
    curl_close($ch);

    // Обработка и декодирование JSON-ответа
    $data = json_decode($response, true);

    // Проверка на ошибки декодирования JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        die('Error decoding JSON');
    }

    // Возвращаем данные
    return $data;
}
