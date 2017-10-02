<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

class CStatementMessagesListComponent extends CBitrixComponent
{
    function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    private function getHLBlock()
    {
        $arItems = [];

        $classR = HL\Base::initByCode('Statements');
        $listParamsR = [
            'select' => ['UF_USER_ID'],
            'filter' => [
                'ID' => $this->arParams['ID']
            ],
        ];

        $RequestSender = 0;
        $rsR = $classR::GetList($listParamsR);
        if ($arR = $rsR->Fetch()) {
            $RequestSender = intval($arR['UF_USER_ID']);
        }

        $listParams = [
            'select' => ['*'],
            'filter' => ['UF_STATEMENT_ID' => $this->arParams['ID']],
            'order' => ['UF_DATE_INSERT' => 'ASC'],
            'limit' => 60,
        ];

        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        $class = HL\Base::initByCode('StatementsMessages');
        $rsRequests = $class::GetList($listParams);

        while ($arRequests = $rsRequests->Fetch()) {

            $mDateCreate = MakeTimeStamp($arRequests['UF_DATE_INSERT']);
            $arRequests['DATE_DISPLAY'] = date('d.m.Y', $mDateCreate) . ', ' . date('H:i', $mDateCreate);

            $arItems[$mDateCreate] = $arRequests;
        }

        if (count($arItems) > 0) {
            $db_res = new \CDBResult;
            $db_res->InitFromArray($arItems);

            CPageOption::SetOptionString("main", "nav_page_in_session", "N");
            $db_res->NavStart(5, false);
            $this->arResult["NAV_STRING"] = $db_res->GetPageNavStringEx($navComponentObject, "Комментарии",
                "");
            $arItems = $db_res->arResult;
            $this->arResult['NAV_PAGE_COUNT'] = $db_res->NavPageCount;
        }

        return $arItems;
    }

    function LoadForm()
    {
        $obRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        $this->arResult['CurDir'] = $obRequest->getRequestedPageDirectory() . '/';

        $arRequest = $obRequest->toArray();

        $this->arResult['param_page'] = $this->arResult['param_page1'] = '';
        if ($arRequest['PAGEN_1']) {
            $this->arResult['param_page'] = '&PAGEN_1=' . htmlspecialcharsEx(intval($arRequest['PAGEN_1']));
            $this->arResult['param_page1'] = '?' . substr($this->arResult['param_page'], 1);
        }

        // -- Удаление сообщения
        $msg_id = intval($arRequest['msg_id']);
        if ($arRequest['delete'] == 'Y' && $msg_id > 0) {
            $strError = '';
            try {
                //\UW\UserProxy::call('DeleteMessage', [$msg_id]);
            } catch (Exception $e) {
                $strError = '<div style="color:red;">' . $e->getMessage() . '</div>';
            }

            if (empty($strError)) {
                LocalRedirect($obRequest->getRequestedPageDirectory() . '/' . $this->arResult['param_page1'] . '#messages');
            } else {
                echo $strError;
            }
        }
    }

    function executeComponent()
    {
        $this->LoadForm();

        krsort($this->arResult['ITEMS']);
        $this->arResult['ITEMS'] = $this->getHLBlock();

        $page = htmlspecialcharsEx(intval($_REQUEST['PAGEN_1']));
        if ($_REQUEST['PAGEN_1'] && $this->arResult['NAV_PAGE_COUNT'] < $page) {
            $page--;
            if ($page > 0) {
                $url = $this->arResult['CurDir'] . '?PAGEN_1=' . $page;
                LocalRedirect($url);
            }
        }

        if ($_REQUEST['PAGEN_1'] && $_REQUEST['add_success'] == 'Y' && $this->arResult['NAV_PAGE_COUNT'] > $page) {
            $page++;
            LocalRedirect($this->arResult['CurDir'] . '?PAGEN_1=' . $page);
        }

        if ($this->arResult['NAV_PAGE_COUNT'] && !$_REQUEST['PAGEN_1']) {
            LocalRedirect($this->arResult['CurDir'] . '?PAGEN_1=' . $this->arResult['NAV_PAGE_COUNT']);
        }

        $this->includeComponentTemplate();
    }
}
