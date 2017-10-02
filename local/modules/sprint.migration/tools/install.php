<?php
if (php_sapi_name() != 'cli') {
    die('Can not run in this mode. Bye!');
}

set_time_limit(0);
error_reporting(E_ERROR );

defined('NO_KEEP_STATISTIC') || define('NO_KEEP_STATISTIC', "Y");
defined('NO_AGENT_STATISTIC') || define('NO_AGENT_STATISTIC', "Y");
defined('NOT_CHECK_PERMISSIONS') || define('NOT_CHECK_PERMISSIONS', true);

if (empty($_SERVER["DOCUMENT_ROOT"])){
    $_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . '/../../../../');
}

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
global $DB, $APPLICATION;

/** @noinspection PhpIncludeInspection */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (\CModule::IncludeModule('sprint.migration')){
    echo 'Модуль успешно установлен' . PHP_EOL;

    exit(0);
}

function moduleInstall($id, $installed, $Module)
{
    foreach(GetModuleEvents("main", "OnModuleInstalled", true) as $arEvent)
    {
        ExecuteModuleEventEx($arEvent, array($id, $installed));
    }

    $cModules = COption::GetOptionString("main", "mp_modules_date", "");
    $arModules = array();
    if(strlen($cModules) > 0)
        $arModules = unserialize($cModules);

    $arModules[] = array("ID" => $id, "NAME" => htmlspecialcharsbx($Module->MODULE_NAME), "TMS" => time());
    if(count($arModules) > 3)
        $arModules = array_slice($arModules, -3);

    COption::SetOptionString("main", "mp_modules_date", serialize($arModules));
}

$id = 'sprint.migration';
/** @noinspection PhpDynamicAsStaticMethodCallInspection */
$Module = CModule::CreateModuleObject($id);

if (strtolower($DB->type)=="mysql" && defined("MYSQL_TABLE_TYPE") && strlen(MYSQL_TABLE_TYPE)>0)
{
    $DB->Query("SET storage_engine = '".MYSQL_TABLE_TYPE."'", true);
}

moduleInstall($id, 'Y', $Module);
if(COption::GetOptionString("main", "event_log_marketplace", "Y") === "Y"){
    /** @noinspection PhpDynamicAsStaticMethodCallInspection */
    CEventLog::Log("INFO", "MP_MODULE_INSTALLED", "main", $id);
}


if($Module->DoInstall() == false)
{
    if($e = $APPLICATION->GetException()){
        echo $e->GetString();
    }
} else {
    echo 'Модуль успешно установлен' . PHP_EOL;
}