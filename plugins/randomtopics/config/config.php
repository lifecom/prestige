<?php
/*
  Random Topics
  s4people - 2013
  http://livestreet.ru/profile/s4people/
  http://wstandart.ru
*/


$config = array ();

// Количество топиков для вывода
$config ['Topics_Count'] = 9;


// Выводим блок
$config['widgets'][] = array(
    'name' => 'random',
    'group' => 'right',
    'priority' => 10,
    'params'=>array('plugin'=>'randomtopics'),
    'action' => array(
        'index',
        'blogs',
        'people',
        'blog',
        'blog' => array('{topics}','{topic}','{blog}'),
        'my',
        'personal_blog',
        'comments',
        'search'
    ),
);


return $config;

?>