<?

class FileUploader extends CBitrixComponent
{
    /** @var \Bitrix\Main\Page\Asset */
    protected $asset = null;

    public function __construct($component)
    {
        parent::__construct($component);
        $this->asset = \Bitrix\Main\Page\Asset::getInstance();
    }

    // параметы, которые можно передать из вне:
    // CHUNK_SIZE - размеры файла можно указывать: 204800 или "204800b" или "200kb"
    // MAX_FILE_SIZE - размеры файла можно указывать: 204800 или "204800b" или "200kb"
    // INPUT_NAME - имя для инпута
    // MULTIPLE - Y/N
    // DEV_MODE - Y/N
    // TODO добавить параметр фильтр расширеинй
    public function onPrepareComponentParams($params)
    {
        $requiredOptions = [
            'browse_button' => $this->getEditAreaId('browse'),
            'url' => $this->getPath() . '/save.php'
        ];

        // размеры файла можно указывать: 204800 или "204800b" или "200kb"
        // тоже самое и в параметах компонента
        $options = [
            'flash_swf_url' => $this->getPath() . '/vendor/js/Moxie.swf',
            'silverlight_xap_url' => $this->getPath() . '/vendor/js/Moxie.xap',
            'filters' => [
                'max_file_size' => '10mb'
            ],
            'chunk_size' => '200kb',
            'multi_selection' => true
        ];

        if (array_key_exists('CHUNK_SIZE', $params)) {
            $options['chunk_size'] = $params['CHUNK_SIZE'];
        }
        if (array_key_exists('MAX_FILE_SIZE', $params)) {
            $options['filters']['max_file_size'] = $params['MAX_FILE_SIZE'];
        }
        if (array_key_exists('MULTIPLE', $params)) {
            $options['multi_selection'] = $params['MULTIPLE'] === 'Y';
        }

        $params['PLUPLOAD_OPTIONS'] = array_merge($requiredOptions, $options);

        // интерфейс
        $params['UI'] = [
            'INPUT_NAME' => $params['INPUT_NAME'],              // имя скрытого инпута, который потом будет передан на сервер, в нем будет хранится ID файла
            'FILE_LIST' => $this->getEditAreaId('filelist'),    // список, куда будут вставляться файлы для загрузки
            'CONSOLE' => $this->getEditAreaId('console'),       // блок, куда будут вставляться ошибки
            'START_UPLOAD' => $this->getEditAreaId('upload'),   // кнопка, по клику на которую будет происходить загрузка
            'CONTAINER' => $this->getEditAreaId('container')
        ];

        // режим разработки, подключаются не сжатые скрипты
        $params['DEV_MODE'] = trim($params['DEV_MODE']) === 'Y';

        return $params;
    }

    protected function addScripts()
    {
        if ($this->arParams['DEV_MODE'] === true) {
            $this->asset->addJs($this->getPath() . '/vendor/js/moxie.js', true);
            $this->asset->addJs($this->getPath() . '/vendor/js/plupload.dev.js', true);
        } else {
            $this->asset->addJs($this->getPath() . '/vendor/js/plupload.full.min.js', true);
        }
        $this->asset->addJs($this->getPath() . '/vendor/js/i18n/ru.js', true);

        return $this;
    }

    public function executeComponent()
    {
        if (!$this->arParams['INPUT_NAME']) {
            ShowError('Не задано INPUT_NAME');
            return;
        }

        $this->addScripts()->includeComponentTemplate();
    }
}