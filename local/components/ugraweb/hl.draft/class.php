<?

use Bitrix\Main\Loader;

class HighloadDraftComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        // символьный код хайлоада
        $params['HL_CODE'] = trim($params['HL_CODE']);

        return $params;
    }

    /**
     * Возвращает данные заявления из черновика
     * @param $draftID
     * @return array|false
     */
    function getDataDraft($draftID)
    {
        global $USER;

        $resultDraft = [];
        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);
        $rsDraft = $hl::getList([
            'select' => ['ID', 'UF_DATA'],
            'filter' => [
                'UF_USER_ID' => $USER->GetID(),
                'ID' => $draftID
            ]
        ]);
        if ($arDraft = $rsDraft->fetch()) {
            $resultDraft = json_decode($arDraft['UF_DATA'], true);
        }

        return $resultDraft;
    }

    /**
     * Добавляет или обновляет черновик
     * @param $arRequest
     */
    private function saveDraft($arRequest)
    {
        global $USER;

        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);

        $fieldsData = [];
        if (count($this->arParams['FIELDS_DATA']) > 0) {
            $fieldsData = $this->arParams['FIELDS_DATA'];
        }

        $currentDateTime = date('d.m.Y H:i:s');
        $fieldsSave = [
            'UF_DATE_INSERT' => $currentDateTime,
            'UF_DATE_UPDATE' => $currentDateTime,
            'UF_USER_ID' => $USER->GetID(),
            'UF_DATA' => json_encode($fieldsData),
            'UF_NAME' => 'Черновик от ' . $currentDateTime
        ];

        if (intval($this->arParams['HL_DRAFT_ID']) > 0) {
            $result = $hl::update($this->arParams['HL_DRAFT_ID'], $fieldsSave);
        } else {
            $result = $hl::add($fieldsSave);
        }

        if ($result->isSuccess()) {
            $url = $this->arParams['DRAFTS_PAGE_URL'];
            if (strlen($url) > 0) {
                LocalRedirect($url);
            }
        } else {
            ShowError(implode('<br>', $result->getErrorMessages()));
        }
    }

    public function executeComponent()
    {
        if (empty($this->arParams['HL_CODE'])) {
            ShowError('Не передан код хайлоад блока.');
            return [];
        }

        Loader::includeModule('highloadblock');

        $resultDraft = [];
        $request = $this->request;
        $arRequest = $request->toArray();

        // Возвращает данные заявления из черновика
        $draftID = intval($this->arParams['HL_DRAFT_ID']);
        if ($draftID > 0) {
            $resultDraft = $this->getDataDraft($draftID);
        }

        // добавление или обновление черновика
        if ($request->isPost() && count($arRequest['saveDraft']) > 0) {
            $this->saveDraft($arRequest);
        }

        return $resultDraft;
    }
}
