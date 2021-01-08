<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

class News
{
    use \Sulao\EasyCache\EasyCache;

    public $offset = 0;

    public function getTopNews($limit = 5)
    {
        $news = [
            ['id' => 1, 'title' => 'news 1'],
            ['id' => 2, 'title' => 'news 2'],
            ['id' => 3, 'title' => 'news 3'],
            ['id' => 4, 'title' => 'news 4'],
            ['id' => 5, 'title' => 'news 5'],
            ['id' => 6, 'title' => 'news 6'],
            ['id' => 7, 'title' => 'news 7'],
            ['id' => 8, 'title' => 'news 8'],
            ['id' => 9, 'title' => 'news 9'],
            ['id' => 10, 'title' => 'news 10'],
        ];

        return array_slice($news, $this->offset++, $limit);
    }
}
