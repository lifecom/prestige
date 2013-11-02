<?php

/**
 * Настройка Виджетов
 */
/*
 * $config['widgets'][] = array(
    'name' => 'stream',     // виджет
    'wgroup' => 'right',    // имя группы
    'priority' => 100,      // приоритет - чем выше приоритет, тем раньше в группе выводится виджет
                            // виджеты с приоритетом 'top' выводятся раньше других в группе
    'on' => array('index', 'blog'), // где показывать виджет
    'off' => array('admin/*', 'settings/*', 'profile/*', 'talk/*', 'people/*'), // где НЕ показывать виджет
    'action' => array(
        'blog' => array('{topics}', '{topic}', '{blog}'), // для совместимости с LiveStreet
    ),
    'display' => true,  // true - выводить, false - не выводить,
                        // array('date_from'=>'2011-10-10', 'date_upto'=>'2011-10-20') - выводить с... по...
);

 */
// Прямой эфир
$config['widgets'][] = array(
    'name' => 'stream',     // исполняемый виджет Stream
    'wgroup' => 'right',
    'priority' => 100,      // приоритет
    'action' => array(
        'index',
        'community',
        'filter',
        'blogs',
        'blog' => array('{topics}', '{topic}', '{blog}'),
        'tag',
        'personal_blog',
        'search',
    ),
    'title' => 'Прямой эфир',
);

// Инфо о блоге топика
$config['widgets'][] = array(
    'name' => 'widgets/widget.blogInfo.tpl',  // шаблонный виджет
    'wgroup' => 'right',
    'action' => array(
        'content' => array('{add}', '{edit}'),
    ),
);

// Теги
$config['widgets'][] = array(
    'name' => 'tags',
    'wgroup' => 'panel_tags',
    'priority' => 50,
    'action' => array(
        'community',
        'filter',
        'blog' => array('{topics}', '{topic}', '{blog}'),
        'tag',
        'index',
        'blogs',
        'people',
        'blog',
        'profile',
        'talk',
        'settings',
        'my',
        'personal_blog',
        'topic',
        'comments',
        'search',
        'stream'
    ),
);

// Блоги
$config['widgets'][] = array(
    'name' => 'blogs',
    'wgroup' => 'panel_blogs',
    'priority' => 50,
    'action' => array(
        'community',
        'filter',
        'blog' => array('{topics}', '{topic}', '{blog}'),
        'tag',
        'index',
        'blogs',
        'people',
        'blog',
        'profile',
        'talk',
        'settings',
        'my',
        'personal_blog',
        'topic',
        'comments',
        'search',
        'stream'
    ),
);

// Блоки раздел люди
$config['widgets'][] = array(
    'name' => 'actions/ActionPeople/sidebar.tpl',
    'wgroup' => 'right',
    'on' => 'people',
    'priority' => 50,
);

// Блоки разделы профайл
$config['widgets'][] = array(
    'name' => 'actions/ActionProfile/sidebar.tpl',
    'wgroup' => 'right',
    'on' => 'profile, talk, settings',
    'priority' => 50,
);

// Подписка на блоги
$config['widgets'][] = array(
    'name' => 'userfeedBlogs',
    'wgroup' => 'right',
    'action' => array(
        'feed' => array('{index}'),
    ),
    'priority' => 50,
);

// Подписка на людей
$config['widgets'][] = array(
    'name' => 'userfeedUsers',
    'wgroup' => 'right',
    'action' => array(
        'feed' => array('{index}'),
    ),
);


$config['widgets'][] = array(
    'name' => 'widgets/widget.blog.tpl',
    'wgroup' => 'right',
    'priority' => 300,
    'action' => array(
        'blog' => array('{topic}')
    ),
);

// EOF
