<?php

include $_SERVER['DOCUMENT_ROOT'] . "/inc/function.php";

if (empty($page_show)) {
    $page_show = '1';
}
$cweb = explode('/', parse_url("$_SERVER[REQUEST_URI]", PHP_URL_PATH))[2];
$link_url = "http" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . "/api/" . $cweb . "/genre.php";
$json1 = curl_json($link_url);
$genre_item_list = $json1['data']['item_list'];
$genre_item = $genre_item_list[$index]["input"];
$genre_item_title = $genre_item_list[$index]["title"];

if (!empty($genre_item)) {
    $item_list = array();
    $link_url1 = str_replace('{{page}}', $page_show, $genre_item);

    $json2 = curl_json($link_url1);
    $json3 = $json2["data"];
    foreach ($json3 as $item) {
        if (isset($item['book_name']))
        {
            $cover_img = str_replace('http://', 'https://', $item['cover']);

            $novel = array(
                'book_name' => $item['book_name'],
                'book_id' => $item['id'],
                'cover_img' => $cover_img,
                'author' => $item['author_name'],
            );
            array_push($item_list, $novel);
        }
    }

    $json_response = array(
        "data" =>
        array("item_list" => $item_list)
    );
    $json_response["data"]["title"] = $genre_item_title;
    header('Content-Type: application/json');
    echo json_encode($json_response);
}
?>