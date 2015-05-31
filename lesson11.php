<?php
header('Content-Type: text/html; charset=utf-8');

ini_set('display_errors', '1');
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

# include dbsimple
$project_root = $_SERVER['DOCUMENT_ROOT'];
require_once $project_root."/dbsimple/config.php";
require_once $project_root."/dbsimple/DbSimple/Generic.php";

# include FirePHP
require_once $project_root . ('/FirePHPCore/FirePHP.class.php');
$firephp = FirePHP::getInstance(true); 
$firephp->setEnabled(true);  


# include smarty
require('Smarty/libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->compile_check = true;
$smarty->debugging = false;

$projectroot = $_SERVER['DOCUMENT_ROOT'];
$smarty_dir = $projectroot . '/smarty/' ;

$smarty->template_dir = $smarty_dir . 'templates';
$smarty->compile_dir = $smarty_dir . 'templates_c';
$smarty->cache_dir = $smarty_dir . 'cache';
$smarty->config_dir = $smarty_dir . 'configs';

// variables 
$categorys['selected'] = 'Выберите категорию';
$categorys['Транспорт'] = array(
    9 => 'Автомобили с пробегом',
    109 => 'Новые автомобили'
);
$categorys['Недвижимость'] = array(
    23 => 'Комнаты',
    24 => 'Квартиры',
);

$smarty->assign('error', '');

// config
$config = array(
        'DB_HOST'            =>  'localhost',
        'DB_USERNAME'   =>  'root',
        'DB_PASSWORD'  =>  '123'
    );
// connect
$db = DbSimple_Generic::connect('mysqli://' . $config['DB_USERNAME'] . ':' . $config['DB_PASSWORD'] . '@' . $config['DB_HOST'] . '/xaver');
// setErrorHandler
$db->setErrorHandler('databaseErrorHandler');

// databaseErrorHandler
function databaseErrorHandler($message, $info)
{
    // Если использовалась @, ничего не делать.
    if (!error_reporting()) return;
    // Выводим подробную информацию об ошибке.
    echo "SQL Error: $message<br><pre>"; 
    print_r($info);
    echo "</pre>";
    exit();
}

$db->setLogger('myLogger');


function myLogger($db, $sql, $caller)
{
    global $firephp;
    global $advert_output_table;
    $firephp->group("at ".@$caller['file'].' line '.@$caller['line']);
    $firephp->log($sql);
    $firephp->groupEnd();
    $firephp->table('all adverts', $advert_output_table);
}


// output select_meta
$select_meta = $db->query('SELECT * FROM select_meta');
$citys = json_decode($select_meta[0]['options'], true);
$metro = json_decode($select_meta[1]['options'], true);
$smarty->assign('citys' ,$citys);
$smarty->assign('metro1' ,$metro);


// processing form. oop.
class advert
{
    public $title;
    public $description;
    public $price;

    function __construct($title, $description, $price)
    {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
    }
}

if (isset($_POST['main_form_submit'])){
    // проверка на наличие знаков у параметров формы
    if (empty($_POST['title'])) {
        $smarty->assign('error', 'Введите все данные');
    } else {
        $title = (string) $_POST['title'];
        $description = (string) $_POST['description'];
        $price = (int) $_POST['price'];

        $adv = new advert($title, $description, $price );
        echo $adv->plusTitle();


        array_pop($_POST);
        $db->query('INSERT INTO adverts (title, description, price) VALUES(?, ?, ?)', $title, $description, $price);
    }
}





// заполнение формы
if (isset($_GET['id'])){
    $id = (int) $_GET['id'];
    $data = $db->query("SELECT * FROM adverts WHERE id=?", $id);
    foreach ($data[0] as $key => $value) { 
        $$key = $value;
}
    $allow_mails = ( $allow_mails == 1) ? 'checked' : '';
    // пустые переменные для пустой формы
    } else {
        $title='';
        $price='';
        $seller_name='';
        $description='';
        $phone='';
        $email='';
        $allow_mails='';
        $private='';
        $city='';
        $metro1='';
        $category_id='';
}
$smarty->assign('private', $private);
$smarty->assign('seller_name', $seller_name);
$smarty->assign('email', $email);
$smarty->assign('allow_mails', $allow_mails);
$smarty->assign('phone', $phone);
$smarty->assign('city', $city);
$smarty->assign('metro', $metro);
$smarty->assign('title', $title);
$smarty->assign('description', $description);
$smarty->assign('price', $price);

    

//  удаление новости
if (isset($_GET['del'])) {
    $del = $_GET['del'];
    $db->query("DELETE FROM adverts WHERE id=?", $del );
    header('Location:' . $_SERVER['PHP_SELF']);
}

// вывод всех объявлений
$advert_output_table = $db->query('SELECT * FROM adverts');
$smarty->assign('advert_output_table', $advert_output_table);



$smarty->display('lesson11.tpl');

?>