<?
use Bitrix\Main\Loader;
use UW\Form\Controls;
use UW\Form\Steps;

class HighloadDraftComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        // символьный код хайлоада
        $params['HL_CODE'] = trim($params['HL_CODE']);

        return $params;
    }

    /**
     * Возвращает список черновиков
     */
    private function getList()
    {
        global $USER;

        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);
        $rsDrafts = $hl::getList([
            'select' => ['ID', 'UF_NAME'],
            'filter' => ['UF_USER_ID' => $USER->GetID()],
            'order' => ['ID' => 'DESC']
        ]);

        while ($arDraft = $rsDrafts->fetch()) {
            $this->arResult['drafts'][] = $arDraft;
        }
    }

    /**
     * Добавляет или обновляет черновик
     * @param $arRequest
     */
    private function saveDraft($arRequest)
    {
        global $USER;

        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);
        // извлекаем все пользовательские поля из хайлоада
        $fields = HL\Base::getFields($this->arParams['HL_CODE_STATEMENTS'], $this->arParams['HL_FIELDS']);

        // в CONTROLS_VALUES есть все дефолтные значения
        if (!empty($this->arParams['CONTROLS_VALUES'])) {
            $rows = $this->arParams['CONTROLS_VALUES'];
            $fields = array_map(function ($field) use ($rows) {
                $code = $field['FIELD_NAME'];

                if (array_key_exists($code, $rows)) {
                    $field['VALUE'] = $rows[$code];
                }

                return $field;
            }, $fields);
        }

        // для создания объекта Controls
        $items = Controls::prepareFields(
            $this->arParams['HL_FIELDS'],
            $fields
        );

        // создаем объект контролов
        $controls = new Controls($items);
        // вытаскиваем в контролы значения из сессии
        $controls->restoreValues();

        // Создаем объект шага
        $steps = new Steps($this->arParams['STEPS'], $controls);
        $key = $arRequest['currentStep'];
        // Устанавливаем текущий шаг, т.к. его может не быть в сессии
        $steps->key($key);
        $currentControls = $steps->current()->controls();
        // Сохраняем значения полей текущего шага из реквеста в объект контролов
        $currentControls->fill($arRequest);

        $fieldsData = [];
        foreach ($this->arParams['HL_FIELDS'] as $code) {
            $control = $controls[$code];
            $fieldsData[$control->id()] = $control->value;
        }

        $currentDateTime = date('d.m.Y H:i:s');
        $fieldsSave = [
            'UF_DATE_CREATE' => $currentDateTime,
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
            $controls->clearValues();

            $url = $this->arParams['DRAFTS_PAGE_URL'];
            if (strlen($url) > 0) {
                LocalRedirect($url);
            }
        } else {
            ShowError(implode('<br>', $result->getErrorMessages()));
        }
    }

    /**
     * Удаление черновика
     * @param $draftID
     */
    private function deleteDraft($draftID)
    {
        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);
        $result = $hl::delete($draftID);
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
            return;
        }

        Loader::includeModule('highloadblock');

        $request = $this->request;
        $arRequest = $request->toArray();

        // добавление или обновление черновика
        if ($request->isPost() && count($arRequest['saveDraft'])) {
            $this->saveDraft($arRequest);
        }

        // список черновиков
        if ($this->arParams['ADD_PAGE_URL']) {

            // запрос на удаление черновика
            $draftID = intval($arRequest['draft']);
            if ($draftID > 0 && $arRequest['delete'] === 'Y') {
                $this->deleteDraft($draftID);
            }

            // возвратить список черновиков
            $this->getList();

            $this->includeComponentTemplate();
        }
    }
}
