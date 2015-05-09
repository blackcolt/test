<?php

$_GET['action']();

function getHtml() {
    $response = new stdClass();
    $response->html = file_get_contents( str_replace('www.', 'http://',$_GET['address']));

    echo json_encode($response);
}
