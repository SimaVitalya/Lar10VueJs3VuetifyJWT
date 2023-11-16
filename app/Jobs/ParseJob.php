<?php

namespace App\Jobs;

use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Console\Command;

class ParseJob extends Command implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse job';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
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

        $postCount = 0; // Variable to track the number of posts added

        for ($page = 1; $page <= 2; $page++) {
            $url = $baseUrl . $page;
            $crawler = $client->request('GET', $url);

            // Получаем содержимое страницы
            $html = $crawler->html();

            // Возвращаем содержимое страницы
            $crawler->filter('div.my-4.w-full')->each(function ($div) use (&$postCount) {
                // Извлечь данные из каждого div элемента
                try {
                    $title = $div->filter('a[data-ga-item="title"]')->text();
                    $content = $div->filter('div.text-base')->text();

                    // Проверяем, существует ли уже запись с таким заголовком
                    if (\App\Models\Post::where('title', $title)->exists()) {
                        echo('Пост уже существует');
                        return; // Пропускаем сохранение и переходим к следующей итерации
                    }

                    $post = new \App\Models\Post();
                    $post->title = $title;
                    $post->content = $content;
                    $post->save();

                    // Выводим информацию о добавленном посте
                    echo('Добавлен пост');

                    $postCount++;

                    if ($postCount % 300 === 0) {
                        sleep(30);
                    }
                } catch (\Exception $e) {
                    echo('Ошибка при сохранении поста: ');
                }
            });
        }
    }
}
