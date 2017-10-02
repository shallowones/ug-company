<?

namespace Sprint\Migration;

use HL\Base as HLHelper;

class Version20170405140500 extends Version {

    protected $description = "Добавление HL-блока со списком событий";
    protected $helper;

    function __construct()
    {
        $this->helper = new HelperManager();
    }

    private function getData()
    {
        $fileData = file_get_contents(__DIR__ . '/storage/Events.json');
        return json_decode($fileData, true);
    }

    private function addHLEvents()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'Events',
            'TABLE_NAME' => 'exp_events'
        ]);

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ENTITY_TYPE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Тип сущности', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Тип сущности', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Тип сущности', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_NAME',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Название события', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Название события', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Название события', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_CODE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Код события', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Код события', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Код события', 'en' => ''],
            ]
        );
    }

    private function addValues()
    {
        $hlEvents = HLHelper::initByCode('Events');

        foreach ($this->getData() as $row){

            $hlEvents::add($row);
        }
    }

    private function removeHLEvents()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('Events');
    }

    public function up(){
        $this->addHLEvents();
        $this->addValues();
    }

    public function down(){
        $this->removeHLEvents();
    }

}
