<?
use UW\Form\Controls;
use UW\Form\Steps;
use Bitrix\Main\Loader;

class HighloadFormComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        // детальная страница, на которую попадет пользователь после успешного сохранения формы
        $params['DETAIL_PAGE_URL'] = trim($params['DETAIL_PAGE_URL']);
        // символьный код хайлоада
        $params['HL_CODE'] = trim($params['HL_CODE']);
        // ID элемента хайлоада (не обязательно), если указан, то покажется форма для редактирования элемента
        $params['HL_ELEMENT_ID'] = intval($params['HL_ELEMENT_ID']);

        // поля хайлоада
        if (empty($params['HL_FIELDS'])) {
            $params['HL_FIELDS'] = [];
        }

        return $params;
    }

    public function createControls()
    {
        // извлекаем все пользовательские поля из хайлоада,
        $fields = HL\Base::getFields($this->arParams['HL_CODE'], $this->arParams['HL_FIELDS']);

        // если в параметрах передали ID элемента, значит это редактирование, и нужно получить из него значения
        if ($this->arParams['HL_ELEMENT_ID'] > 0) {
            $hl = HL\Base::initByCode($this->arParams['HL_CODE']);
            $params = [
                'select' => $this->arParams['HL_FIELDS'],
                'filter' => [
                    '=ID' => $this->arParams['HL_ELEMENT_ID']
                ]
            ];
            $rows = $hl::getRow($params);

            if (!empty($rows)) {
                $fields = array_map(function ($field) use ($rows) {
                    $code = $field['FIELD_NAME'];

                    if (array_key_exists($code, $rows)) {
                        $field['VALUE'] = $rows[$code];
                    }

                    return $field;
                }, $fields);
            }
        }

        // подготавливаем массив полученный из хайлоада в массив для работы с контролами
        $items = Controls::prepareFields(
            $this->arParams['HL_FIELDS'],
            $fields
        );

        // сами контролы
        $controls = new Controls($items);
        $controls->restoreValues();

        return $controls;
    }

    public function createSteps(Controls $controls)
    {
        $this->arResult['steps'] = new Steps($this->arParams['STEPS'], $controls);
        return $this->arResult['steps'];
    }

    /**
     * Возвращает массив меню шагов
     * @return array
     */
    protected function getMenuSteps()
    {
        $count = 0;
        $menuSteps = [];

        /** @var UW\Form\Step $step */
        $step = $this->arResult['step'];

        foreach ($this->arParams['STEPS'] as $arStep) {
            $menuStep = [
                'id' => $arStep['id'],
                'label' => $arStep['label'],
                'current' => ($step->id === $arStep['id'])
            ];
            if ($count > 0) {
                $menuStep['label'] = ' / ' . $menuStep['label'];
            }

            $menuSteps[$count] = $menuStep;

            $count++;
        }

        return $menuSteps;
    }

    public function executeComponent()
    {
        if (empty($this->arParams['HL_CODE'])) {
            ShowError('Не передан код хайлоад блока.');
            return;
        }

        Loader::includeModule('highloadblock');

        $this->arResult['ELEMENT_ID'] = $this->arParams['HL_ELEMENT_ID'];
        $this->arResult['SAVE_SUCCESS'] = false;
        $request = $this->request;
        $controls = $this->createControls();
        $steps = $this->createSteps($controls);

        // текущий шаг, по умолчанию равен первому
        // обновляем текущий шаг, если он пришел в запросе
        if (($key = $request->get('currentStep')) !== null) {
            $steps->key($key);
        }
        // обновляем текущий шаг, если пришел предыдущий шаг
        if (($key = $request->get('prevStep')) !== null && !$request->isPost()) {
            $steps->key($key);
        }

        // если есть nextStep, то нужно валидировать текущий шаг и перейти на следующий, или показать ошибки на текущем
        if (($nextStep = $request->get('nextStep')) !== null) {
            // перед тем как перейти на новый шаг формы, сначала валидируем текущую
            $currentControls = $steps->current()->controls();
            // заполняем данными пришедшими из формы
            $currentControls->fill($request->toArray());

            if ($currentControls->validate() === false) {
                // если следующий шаг пустой
                if (empty($nextStep)) {
                    // значит пора сохранять всю форму
                    $hl = HL\Base::initByCode($this->arParams['HL_CODE']);

                    $fields = [];
                    $currentDateTime = date('d.m.Y H:i:s');
                    foreach ($this->arParams['HL_FIELDS'] as $code) {
                        $control = $controls[$code];
                        $fields[$control->id()] = $control->value;
                    }

                    if (is_array($this->arParams['HL_FIELDS_EXT']) && count($this->arParams['HL_FIELDS_EXT']) > 0) {
                        $fields = array_merge($fields, $this->arParams['HL_FIELDS_EXT']);
                    }

                    if ($this->arParams['HL_ELEMENT_ID'] > 0) {
                        $fields['UF_DATE_UPDATE'] = $currentDateTime;
                        $result = $hl::update($this->arParams['HL_ELEMENT_ID'], $fields);
                    } else {
                        $fields['UF_DATE_INSERT'] = $currentDateTime;
                        $result = $hl::add($fields);
                    }

                    if ($result->isSuccess()) {
                        $this->arResult['ELEMENT_ID'] = $result->getId();
                        $this->arResult['SAVE_SUCCESS'] = true;
                        $controls->clearValues();

                        $url = $this->arParams['DETAIL_PAGE_URL'];
                        if (strlen($url) > 0) {
                            $url = str_replace('#ID#', $result->getId(), $url);
                            LocalRedirect($url);
                        }
                    } else {
                        ShowError(implode('<br>', $result->getErrorMessages()));
                    }
                } else {
                    // сохраним данные из формы в хранилище (в данном случае это $_SESSION)
                    $currentControls->saveValues();
                    // только теперь перейдем на следующую форму
                    $steps->key($nextStep);
                }
            }
        }

        $this->arResult['step'] = $steps->current();
        $this->arResult['nextStep'] = $steps->nextStepCode();
        $this->arResult['prevStep'] = $steps->prevStepCode();
        $this->arResult['menuSteps'] = $this->getMenuSteps();

        $this->includeComponentTemplate();

        return $this->arResult['steps'];
    }
}
