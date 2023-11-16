<?php

namespace App\Http\Controllers;

use DiDom\Document;
use Goutte\Client;
use App\Models\Parse;

//use GuzzleHttp\Client;

use Sunra\PhpSimple\HtmlDomParser;

//библиотека для парсинга

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParseController extends Controller
{
    public function index()
    {


        $client = new Client();
        $baseUrl = 'https://lifehacker.com/money/page/1';
        $crawler = $client->request('GET', $baseUrl);

        // получаем содержимое страницы
        $html = $crawler->html();

        $lastPageLink = $crawler->filter('ul[aria-label="navigation"] li')->last()->previousAll()->filter('a')->first();
        $maxPage = $lastPageLink->attr('href');
        // извлекаем номер последней страницы из URL
        $maxPage = (int)substr($maxPage, strrpos($maxPage, '/') + 1);
        //        dd($maxPage);

        for ($page = 1; $page <= 13; $page++) {
            $url = $baseUrl . $page;
            $crawler = $client->request('GET', $url);

            // Получаем содержимое страницы
            $html = $crawler->html();

            // Возвращаем содержимое страницы
            $crawler->filter('div.my-4.w-full')->each(function ($div) {
                // Извлечь данные из каждого div элемента
                $title = $div->filter('a[data-ga-item="title"]')->text();
                $content = $div->filter('div.text-base')->text();

                // Создаем новый экземпляр модели Parse и сохраняем данные в базу
                $parse = new Parse();
                $parse->title = $title;
                $parse->content = $content;
                $parse->save();

                // Sleep for 30 seconds before making the next request
                sleep(30);
            });
        }
    }
}
