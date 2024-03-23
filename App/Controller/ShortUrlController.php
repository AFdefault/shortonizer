<?php

namespace App\Controller;
use App\Core\Helpers;
use App\Models\ShortUrl;

class ShortUrlController
{
    private Helpers $helpers;

    public function __construct()
    {
        $this->helpers = new Helpers();
    }

    public function show(): void
    {
        echo file_get_contents(__DIR__.'/../../view/index.html');
    }

    public function get($data): void
    {
        if($_GET['url']) {
            header('location: '.$_GET['url']);
            exit();
        }

        $short = new ShortUrl();
        if(!$url = $short->getById($data['url_id'])['url']) {
            http_redirect('/');
        }
        header('location: '.$url);
    }

    public function create(): void
    {
        if(!array_key_exists('url',$_POST)) Helpers::errorResponse('Invalid request, required parameters were not found', 400);
        if(!filter_var($url = $_POST['url'], FILTER_VALIDATE_URL)) Helpers::errorResponse(json_encode(['errors' => ['message' => 'Переданный параметр не является валидным URL']]), 400);

        $shortUrl = new ShortUrl();

        $url_id = $shortUrl->getByUrl($url)['id'] ?? $shortUrl->create($url);

        $this->helpers->jsonResponse([
            'url_id' => $url_id,
            'short_url' => $this->helpers->getConfig('app_url'). '/' .$url_id,
        ]);

    }
}