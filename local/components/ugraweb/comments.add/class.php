<?
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

class CStatementMessagesAddComponent extends CBitrixComponent
{
    function onPrepareComponentParams($arParams)
    {
        $arParams['CurUser'] = \CUser::GetID();

        return $arParams;
    }

    private function validateForm(&$arRequest)
    {
        $arError = [];
        if (strlen(trim($arRequest["UF_MESSAGE"])) < 1) {
            $arError[] = "Не заполнено поле «Ваше сообщение»";
        }

        return $arError;
    }

    private function SaveHLBlock($obRequest)
    {
        $arRequest = $obRequest->toArray();

        $classReception = HL\Base::initByCode('StatementsMessages');
        $msg_id = intval($arRequest['msg_id']);

        $strError = '';
        if ($arRequest['msg_update'] == 'Y' && $msg_id > 0) {
            try {
                $arParamProxy = [$msg_id, $arRequest['UF_MESSAGE']];
                //\UW\UserProxy::call('EditMessage', $arParamProxy);
            } catch (Exception $e) {
                $strError = '<div style="color:red;">' . $e->getMessage() . '</div>';
            }
        } else {
            $arAdd = [
                'UF_MESSAGE' => htmlspecialcharsEx($arRequest['UF_MESSAGE']),
                'UF_DATE_INSERT' => new Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', time()), 'Y-m-d H:i:s'),
                'UF_USER_ID' => $this->arParams['CurUser'],
                'UF_STATEMENT_ID' => $this->arParams['ID'],
            ];

            $result = $classReception::Add($arAdd);

            if (!$result->isSuccess()) {
                $strError = 'Не удалось создать сообщение.';
            }
        }

        if (empty($strError)) {


            if ($arRequest['msg_update'] == 'Y') {
                $name_success = 'update_success';
            } else {
                $name_success = 'add_success';
            }

            $_SESSION['SEND_MESSAGE_OK'] = 'ok';

            $par = '';
            $page = htmlspecialcharsEx(intval($_REQUEST['PAGEN_1']));
            if ($_REQUEST['PAGEN_1']) {
                $par = '?PAGEN_1=' . $page . '&' . $name_success . '=Y';
            }

            // Делаем редирект, чтобы не осталось данных в post
            LocalRedirect($obRequest->getRequestedPageDirectory() . '/' . $par . '#messages');
        } else {
            echo $strError;
        }

    }

    private function LoadForm()
    {

        if ($_SESSION['SEND_MESSAGE_OK']) {
            $this->arResult['SEND_MESSAGE_OK'] = true;
            $_SESSION['SEND_MESSAGE_OK'] = 0;
        }

    }

    function executeComponent()
    {
        $this->LoadForm();

        $obRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        if ($obRequest->isPost()) {
            $arRequest = $obRequest->toArray();

            if (strlen($arRequest['send_message']) > 0) {
                $this->arResult['ERROR'] = $this->validateForm($arRequest);
                if (count($this->arResult['ERROR']) > 0) {
                    $this->arResult['UF_MESSAGE'] = $arRequest['UF_MESSAGE'];
                } else {
                    $this->SaveHLBlock($obRequest);
                }
            }

        }

        $this->includeComponentTemplate();

    }

}