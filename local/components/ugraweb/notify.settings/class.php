<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


class NotifySettingsComponent extends CBitrixComponent
{

    public function executeComponent()
    {
        $this->IncludeComponentTemplate();
    }

}