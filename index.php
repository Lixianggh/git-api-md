<?php

$md = isset($_GET['md']) ? $_GET['md'] : 'index.md';
$title = isset($_GET['title']) ? $_GET['title'] : 'md文档';

$markdown_filename = './md/'.$md;

if(!file_exists($markdown_filename)){
    echo '未找到文件';
    exit();
}

$markdown_text = file_get_contents($markdown_filename);

$render_url = 'https://api.github.com/markdown';

$request_array['text'] = $markdown_text;
$request_array['mode'] = 'markdown';

$html_article_body = curl_raw($render_url, json_encode($request_array));

echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>' . $title . '</title><link rel="stylesheet" href="./md_github.css" type="text/css" /></head>';
echo '<article class="markdown-body">';
echo $html_article_body;
echo '</article></body></html>';

function curl_raw($url, $content) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json",
              "User-Agent: " . $_SERVER['HTTP_USER_AGENT']));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    return $json_response;
}