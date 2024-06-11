<?php
// Set the header for returning JSON
header('Content-Type: application/json');

// Check the request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get data from the request body
    $data = json_decode(file_get_contents("php://input"));

    // Check if the data was received correctly
    if (json_last_error() === JSON_ERROR_NONE) {
        // Return the result in JSON format
        echo json_encode(searchCompany($data));
    } else {
        // If the data was not received correctly, return an error
        echo json_encode(['error' => 'Invalid JSON input']);
    }
}

// Function to search for companies
function searchCompany(object $data) {
    // Your API key
    $apiKey = 'YOUR API KEY';
    $baseUrl = 'https://api-fns.ru/api/search';

    // Array of parameters to build the URL
    $params = [
        'q' => !empty($data->search) ? $data->search : 'any',
        'filter' => 'active onlyul withphone',
        'key' => $apiKey
    ];

    // Add parameters to the request if they are present
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

    // Initialize the page variable
    $page = 1;
    $result = []; // Array to store all results

    // Loop to get data from subsequent pages
    do {
        // Add the page parameter to the request
        $params['page'] = $page;

        // Build the URL
        $url = $baseUrl . '?' . http_build_query($params);

        // Execute the request
        $response = CurlSent($url);

        // Process the response data
        if (isset($response['items'])) {
            // Merge the current page results with the overall results
            $result = array_merge($result, $response['items']);
        }

        // Increment the page number
        $page++;

        // Check the condition to continue the loop
    } while ($response['nextpage'] === true && $page <= 10);

    // Return all collected data
    return $result;
}

// Function to execute the request to the API
function CurlSent($url) {
    // Initialize cURL
    $ch = curl_init();

    // Set the URL and parameters for cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Execute the request
    $response = curl_exec($ch);

    // Check for cURL execution errors
    if (curl_errno($ch)) {
        die('Curl error: ' . curl_error($ch));
    }

    // Close cURL
    curl_close($ch);

    // Process and decode the JSON response
    $data = json_decode($response, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        die('Error decoding JSON');
    }

    // Return the data
    return $data;
}
?>
