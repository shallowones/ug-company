<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

if ($arResult['ELEMENT_ID'] > 0 && $request->isPost()) {
    /** @var CUser */
    (new CUser())->Update(\CUser::GetID(), ['UF_ORG_ID' => $arResult['ELEMENT_ID']]);
    LocalRedirect($request->getRequestedPageDirectory() . '/');
}