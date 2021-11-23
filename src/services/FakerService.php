<?php


namespace matfish\Blogify\services;


class FakerService
{
    protected static $wordList = [
        'alias', 'consequatur', 'aut', 'perferendis', 'sit', 'voluptatem',
        'accusantium', 'doloremque', 'aperiam', 'eaque', 'ipsa', 'quae', 'ab',
        'illo', 'inventore', 'veritatis', 'et', 'quasi', 'architecto',
        'beatae', 'vitae', 'dicta', 'sunt', 'explicabo', 'aspernatur', 'aut',
        'odit', 'aut', 'fugit', 'sed', 'quia', 'consequuntur', 'magni',
        'dolores', 'eos', 'qui', 'ratione', 'voluptatem', 'sequi', 'nesciunt',
        'neque', 'dolorem', 'ipsum', 'quia', 'dolor', 'sit', 'amet',
        'consectetur', 'adipisci', 'velit', 'sed', 'quia', 'non', 'numquam',
        'eius', 'modi', 'tempora', 'incidunt', 'ut', 'labore', 'et', 'dolore',
        'magnam', 'aliquam', 'quaerat', 'voluptatem', 'ut', 'enim', 'ad',
        'minima', 'veniam', 'quis', 'nostrum', 'exercitationem', 'ullam',
        'corporis', 'nemo', 'enim', 'ipsam', 'voluptatem', 'quia', 'voluptas',
        'sit', 'suscipit', 'laboriosam', 'nisi', 'ut', 'aliquid', 'ex', 'ea',
        'commodi', 'consequatur', 'quis', 'autem', 'vel', 'eum', 'iure',
        'reprehenderit', 'qui', 'in', 'ea', 'voluptate', 'velit', 'esse',
        'quam', 'nihil', 'molestiae', 'et', 'iusto', 'odio', 'dignissimos',
        'ducimus', 'qui', 'blanditiis', 'praesentium', 'laudantium', 'totam',
        'rem', 'voluptatum', 'deleniti', 'atque', 'corrupti', 'quos',
        'dolores', 'et', 'quas', 'molestias', 'excepturi', 'sint',
        'occaecati', 'cupiditate', 'non', 'provident', 'sed', 'ut',
        'perspiciatis', 'unde', 'omnis', 'iste', 'natus', 'error',
        'similique', 'sunt', 'in', 'culpa', 'qui', 'officia', 'deserunt',
        'mollitia', 'animi', 'id', 'est', 'laborum', 'et', 'dolorum', 'fuga',
        'et', 'harum', 'quidem', 'rerum', 'facilis', 'est', 'et', 'expedita',
        'distinctio', 'nam', 'libero', 'tempore', 'cum', 'soluta', 'nobis',
        'est', 'eligendi', 'optio', 'cumque', 'nihil', 'impedit', 'quo',
        'porro', 'quisquam', 'est', 'qui', 'minus', 'id', 'quod', 'maxime',
        'placeat', 'facere', 'possimus', 'omnis', 'voluptas', 'assumenda',
        'est', 'omnis', 'dolor', 'repellendus', 'temporibus', 'autem',
        'quibusdam', 'et', 'aut', 'consequatur', 'vel', 'illum', 'qui',
        'dolorem', 'eum', 'fugiat', 'quo', 'voluptas', 'nulla', 'pariatur',
        'at', 'vero', 'eos', 'et', 'accusamus', 'officiis', 'debitis', 'aut',
        'rerum', 'necessitatibus', 'saepe', 'eveniet', 'ut', 'et',
        'voluptates', 'repudiandae', 'sint', 'et', 'molestiae', 'non',
        'recusandae', 'itaque', 'earum', 'rerum', 'hic', 'tenetur', 'a',
        'sapiente', 'delectus', 'ut', 'aut', 'reiciendis', 'voluptatibus',
        'maiores', 'doloribus', 'asperiores', 'repellat','superbum'
    ];

    public static function sentence($l = null)
    {
        $l = $l ?: rand(8, 14);

        return self::words($l);
    }

    public static function paragraph($sentences = null)
    {
        $s = $sentences ?? rand(8, 16);

        $res = [];

        for ($i = 1; $i <= $s; $i++) {
            $res[] = self::sentence();
        }

        return implode('. ', $res) . '.';
    }


    public static function postContent()
    {
        $html = '<p>' . self::paragraph(3) . '</p>';
        $html .= self::subheading();
        $html .= self::paragraphs(2);
        $html .= self::subheading();
        $html .= self::paragraphs(3);

        return $html;
    }

    public static function arrayElement($arr)
    {
        $key = array_rand($arr);

        return $arr[$key];
    }

    public static function arrayElements($arr, $n)
    {
        $keys = array_rand($arr, $n);

        return array_map(function ($key) use ($arr) {
            return $arr[$key];
        }, $keys);
    }

    private static function words($n)
    {
        $res = [];

        for ($i = 1; $i < $n; $i++) {
            $res[] = self::arrayElement(self::$wordList);
        }

        return implode(' ', $res);
    }

    private static function subheading()
    {
        return '<h2>' . self::sentence(rand(6,8)) . '</h2>';
    }

    private static function paragraphs($n = null)
    {
        $n = $n ?: rand(2, 3);

        $res = [];

        for ($i = 1; $i <= $n; $i++) {
            $res[] = '<p>' . self::paragraph() . '</p>';
        }

        return implode('', $res);

    }

}