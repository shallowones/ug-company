<?
/**
 * Распечатывает массивы
 * @param $var
 * @param int $mode
 * @param string $str
 * @param int $die
 */
function gG($var, $mode = 0, $str = 'Var', $die = 0)
{
    switch ($mode) {
        case 0:
            echo "<pre>";
            echo "######### {$str} ##########<br/>";
            print_r($var);
            echo "</pre>";
            if ($die) die();
            break;
        case 2:
            $handle = fopen($_SERVER["DOCUMENT_ROOT"] . "/upload/debug.txt", "a+");
            fwrite($handle, "######### {$str} ##########\n");
            fwrite($handle, (string)$var);
            fwrite($handle, "\n\n\n");

            fclose($handle);
            break;
    }
}

CModule::AddAutoloadClasses('', [
    'Psr\Loader' => '/local/libs/Psr/Loader.php'
]);

\Bitrix\Main\Loader::includeModule('highloadblock');

$loader = new Psr\Loader();
$loader->register();
$loader->addNamespace('UW', $_SERVER['DOCUMENT_ROOT'] . '/local/libs/UW');
$loader->addNamespace('HL', $_SERVER['DOCUMENT_ROOT'] . '/local/libs/HL');
$loader->addNamespace('Phalcon', $_SERVER['DOCUMENT_ROOT'] . '/local/external-libs/Phalcon');
$loader->addNamespace('Configula', $_SERVER['DOCUMENT_ROOT'] . '/local/external-libs/Configula/src/Configula');

//$loader->loadComposerLib('Log4php');
//$loader->loadComposerLib('Slim');

AddEventHandler('main', 'onProlog', function () {
    UW\Acl\Helper::configureAcl(new \UW\Config());
});
AddEventHandler('main', 'OnBeforeUserRegister', function (&$fields) {
    $email = $fields['EMAIL'];

    if (empty($email) || !check_email($email, true)) {
        /** @global CMain $APPLICATION */
        global $APPLICATION;
        $APPLICATION->ThrowException('Неверный адрес @-почты.');

        return false;
    }

    $fields['LOGIN'] = $email;

    return true;
});

UW\EventHandler::register();
