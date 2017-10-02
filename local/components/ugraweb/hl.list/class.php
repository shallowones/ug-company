<?

use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\Entity\ExpressionField;

class HighloadListComponent extends CBitrixComponent
{
    /**
     * Обработка параметров
     * @param $params
     * @return mixed
     */
    public function onPrepareComponentParams($params)
    {
        $params['HL_CODE'] = trim($params['HL_CODE']);

        if (!$params['ITEMS_COUNT']) {
            $params['ITEMS_COUNT'] = 10;
        }

        if (!$params['DETAIL_URL']) {
            $params['DETAIL_URL'] = '/#ELEMENT_ID#/';
        }

        return $params;
    }

    /**
     * Удаляет элемент по ID
     * @param $deleteElementId
     */
    protected function deleteElement($deleteElementId)
    {
        global $APPLICATION;

        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);
        $result = $hl::delete($deleteElementId);
        if ($result->isSuccess()) {
            LocalRedirect($APPLICATION->GetCurDir());
        } else {
            ShowError(implode('<br>', $result->getErrorMessages()));
            return;
        }
    }

    /**
     * Выборка списка
     * @return $this
     */
    private function getList()
    {
        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);

        $params = [
            'select' => ['*'],
            'order' => ['ID' => 'DESC'],
        ];

        // Выбираемые поля, поумолчанию - все
        if (is_array($this->arParams['FIELDS']) && !empty($this->arParams['FIELDS'])) {
            $params['select'] = $this->arParams['FILTER'];
            if (!array_key_exists('ID', $params['select'])) {
                $params['select'][] = 'ID';
            }
        }

        // Произвольный фильтр из параметров
        if (is_array($this->arParams['FILTER']) && !empty($this->arParams['FILTER'])) {
            $params['filter'] = $this->arParams['FILTER'];
        }

        // Если задан, то сначала сортировка по параметру, потом по дефолтному полю
        if (is_array($this->arParams['ORDER']) && !empty($this->arParams['ORDER'])) {
            $params['order'] = array_merge($this->arParams['ORDER'], $params['order']);
        }

        if (is_array($this->arParams['RUNTIME']) && !empty($this->arParams['RUNTIME'])) {
            $params['runtime'] = $this->arParams['RUNTIME'];
        }

        // Кол-во выводимых элементов
        $navyParams = CDBResult::GetNavParams($this->arParams['ITEMS_COUNT']);
        $navyParams['PAGEN'] = intval($navyParams['PAGEN']);
        $navyParams['SIZEN'] = intval($navyParams['SIZEN']);

        $params['limit'] = $navyParams['SIZEN'];
        $params['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);

        $countQuery = new Query($hl::getEntity());
        if ($params['runtime']) {
            // если используются ReferenceField для подмешивания другого хайлоада,
            // то для правильного подсчета кол-ва элеметов (пагинации),
            // нужно этот ReferenceField подмешать и в объект Query
            array_map(function ($runtime) use ($countQuery) {
                /** @var Bitrix\Main\Entity\Field $runtime */
                if ($runtime instanceof \Bitrix\Main\Entity\ReferenceField) {
                    $countQuery->registerRuntimeField($runtime->getName(), [
                        'data_type' => $runtime->getRefEntityName(),
                        'reference' => $runtime->getReference()
                    ]);
                }
            }, $params['runtime']);
        }
        if ($params['filter']) {
            $countQuery->setFilter($params['filter']);
        }
        $countQuery->addSelect(new ExpressionField('CNT', 'COUNT(1)'));
        $totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
        $totalCount = intval($totalCount['CNT']);

        $totalPages = 1;
        if ($totalCount > 0) {
            $totalPages = ceil($totalCount / $navyParams['SIZEN']);
            if ($navyParams['PAGEN'] > $totalPages) {
                $navyParams['PAGEN'] = $totalPages;
            }
            $params['limit'] = $navyParams['SIZEN'];
            $params['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);
        } else {
            $navyParams['PAGEN'] = 1;
            $params['limit'] = $navyParams['SIZEN'];
            $params['offset'] = 0;
        }

        $rsData = new CDBResult;
        $rsData->InitFromArray($hl::getList($params)->fetchAll());
        $rsData->NavStart($params['limit'], false, $navyParams['PAGEN']);
        $rsData->NavRecordCount = $totalCount;
        $rsData->NavPageCount = $totalPages;
        $rsData->NavPageNomer = $navyParams['PAGEN'];

        while ($arHlElement = $rsData->fetch()) {
            // Ссылка на детальную страницу
            $arHlElement['DETAIL_URL'] = str_replace(
                '#ELEMENT_ID#',
                $arHlElement['ID'],
                $this->arParams['DETAIL_URL']
            );

            $this->arResult['LIST'][] = $arHlElement;
        }

        // Строка постраничной навигации
        $this->arResult['NAV_STRING'] = $rsData->GetPageNavStringEx(
            $navComponentObject,
            $this->arParams['PAGER_TITLE'],
            $this->arParams['PAGER_TEMPLATE'],
            $this->arParams['PAGER_SHOW_ALWAYS']
        );

        return $this;
    }

    public function executeComponent()
    {
        if (empty($this->arParams['HL_CODE'])) {
            ShowError('Не задан параметр HL_CODE');
            return;
        }

        Loader::includeModule('highloadblock');

        $deleteElementId = intval($this->arParams['DELETE_ELEMENT_ID']);
        if ($deleteElementId > 0) {
            $this->deleteElement($deleteElementId);
        }

        $this->getList()->IncludeComponentTemplate();
    }
}