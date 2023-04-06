<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title'        => '', // Удалить значение по умолчанию
            'titleBefore'  => false, // Значение по умолчанию не меняем
            'description'  => '', // Удалить значение по умолчанию
            'separator'    => ' - ', // Использовать разделитель ' - '
            'keywords'     => [],
            'canonical'    => 'full', // Использовать полный URL страницы
            'robots'       => false, // Не добавлять мета-тег robots по умолчанию
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => false, // Отключить значения по умолчанию
            'description' => false, // Отключить значения по умолчанию
            'url'         => false, // Отключить значения по умолчанию
            'type'        => false,
            'site_name'   => false,
            'images'      => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            //'card'        => 'summary',
            //'site'        => '@LuizVinicius73',
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title'       => false, // Отключить значения по умолчанию
            'description' => false, // Отключить значения по умолчанию
            'url'         => false, // Отключить значения по умолчанию
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
