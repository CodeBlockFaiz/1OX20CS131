<?php
function fetchNumbersFromUrl($url) {
    $options = array(
        'http' => array(
            'timeout' => 0.5, 
        ),
    );
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['numbers']) && is_array($data['numbers'])) {
            return $data['numbers'];
        }
    }
    return array(); 
}


if (isset($_GET['url'])) {
   
    $urls = $_GET['url'];

    if (!is_array($urls)) {
        $urls = array($urls); 
    }

   
    $allNumbers = array();

   
    foreach ($urls as $url) {
        $numbers = fetchNumbersFromUrl($url);
        $allNumbers = array_merge($allNumbers, $numbers);
    }
   
    $allNumbers = array_unique($allNumbers);
    sort($allNumbers);
    header('Content-Type: application/json');
    echo json_encode(array('numbers' => $allNumbers));
} else {
    http_response_code(400); 
    echo "Missing 'url' parameter";
}
