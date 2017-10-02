<?
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
require('./vendor/PluploadHandler.php');

$ph = new PluploadHandler([
    'target_dir' => $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/file.uploader',
    'cb_sanitize_file_name' => ['CUtil', 'ConvertToLangCharset'],
    'allow_extensions' => 'jpg,jpeg,png,doc,docx,xls,xlsx,rtf,pdf,zip,rar,rtf,txt,pptx'
]);

$ph->sendNoCacheHeaders();
$ph->sendCORSHeaders([
    'Content-Type' => 'application/json'
]);
$result = $ph->handleUpload();

if (!$result) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 ' . $ph->getErrorMessage(), true, 500);
    die(json_encode([
        'status' => 0,
        'error' => [
            'code' => $ph->getErrorCode(),
            'message' => $ph->getErrorMessage()
        ]
    ]));
}

// если есть ключ chunk, то выходим выходим,
// потому что это только часть файла, а не весь. надо дождаться пока загрузится целиком
if (array_key_exists('chunk', $result)) {
    die(json_encode([
        'status' => 1,
        'info' => $result
    ]));
}

$fileId = CFile::SaveFile(CFile::MakeFileArray($result['path']), 'main');
\Bitrix\Main\IO\File::deleteFile($result['path']);

die(json_encode([
    'status' => 2,
    'fileId' => $fileId
]));