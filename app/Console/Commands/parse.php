<?php

namespace App\Console\Commands;

use App\Models\Post;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Mockery\Generator\StringManipulation\Pass\Pass;

class parse extends Command
{
    protected $signature = 'parse';

    protected $description = 'Parse RSS feed and save new posts to the database';

    public function handle()
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

        $postCount = 0; // variable to track the number of posts added

        for ($page = 1; $page <= $maxPage; $page++) {
            $url = $baseUrl . $page;
            $crawler = $client->request('GET', $url);

            // Получаем содержимое страницы
            $html = $crawler->html();

            // Возвращаем содержимое страницы
            $crawler->filter('div.my-4.w-full')->each(function ($div) use (&$postCount) {
                // ивлечь данные из каждого div элемента
                try {
                    $title = $div->filter('a[data-ga-item="title"]')->text();
                    $content = $div->filter('div.text-base')->text();

                    // Проверяем, существует ли уже запись с таким заголовком
                    if (\App\Models\Post::where('title', $title)->exists()) {
                        echo('Пост уже существует');
                        return; // Пропускаем сохранение и переходим к следующей итерации
                    }
                    $parse = new \App\Models\Post();
                    $parse->title = $title;
                    $parse->content = $content;
                    $parse->save();

                    // выводим информацию о добавленном посте
                    $this->info('Добавлен пост: ' . $title);

                    $postCount++;

                    if ($postCount % 300 === 0) {
                        sleep(30);
                    }
                } catch (\Exception $e) {
                    $this->error('Ошибка при сохранении поста: ' . $e->getMessage());
                }
            });
        }

        // Выводим сообщение об успешном выполнении команды
        $this->info('Команда выполнена успешно!');
    }


}
