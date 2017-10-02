<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Скрипт написан на PHP только для того чтобы можно было пробросить параметры
 * в component_epilog.php он подключаелся с помошью Asset::addString()
 *
 * $arParams[OPTIONS] параметры для инифиализации плагина
 * $arParams[UI] интерфейс
 * $arParams[UI][INPUT_NAME] имя скрытого инпута, который потом будет передан на сервер, в нем будет хранится ID файла
 * $arParams[UI][FILE_LIST] список, куда будут вставляться файлы для загрузки
 * $arParams[UI][CONSOLE] блок, куда будут вставляться ошибки
 * $arParams[UI][START_UPLOAD] кнопка начала загрузки файлов
 *
 * @var array $arParams
 */

$options = json_encode($arParams['OPTIONS']);
$ui = $arParams['UI'];
$inputName = $arParams['OPTIONS']['multi_selection'] === true
    ? $ui['INPUT_NAME'] . '[]'
    : $ui['INPUT_NAME'];
?><script>
  (function (plupload) {
    if (plupload === undefined) {
      return;
    }

    const { Uploader } = plupload;

    function init () {
      const uploader = new Uploader(<? echo $options; ?>)
      const createInput = (fileId) => {
        const input = document.createElement('input')
        input.type = 'hidden'
        input.name = '<? echo $inputName; ?>'
        input.value = fileId

        return input
      }

      uploader.init()

      uploader.bind('FilesAdded', (up)  => {
        if (up.getOption('multi_selection') === false) {
          up.files.slice(0, -1).forEach(file => up.removeFile(file))
        }

        document.querySelector('#<? echo $ui['FILE_LIST'] ?>').innerHTML = up.files.reduce(
          (acc, file) => `${acc}<li id="${file.id}">${file.name} (${plupload.formatSize(file.size)}) <b></b></li>`,
          ''
        );
      });

      uploader.bind('UploadProgress', (up, file) => {
        document.querySelector(`#${file.id}`).querySelector('b').innerHTML = `<span>${file.percent}%</span>`
      })

      uploader.bind('Error', (up, err) => {
        let message
        let code

        try {
          const { error } = JSON.parse(err.response)

          message = error.message
          code = error.code
        } catch (e) {
          message = err.message
          code = err.code
        }

        document.querySelector('#<? echo $ui['CONSOLE'] ?>').innerHTML += "\nError #" + code + ": " + message
      })

      uploader.bind('FileUploaded', (up, file, info) => {
        try {
          const json = JSON.parse(info.response)
          console.log(json)
          const input = createInput(json.fileId)
          const item = document.querySelector(`#${file.id}`)

          item.appendChild(input)
        } catch (e) {

        }
      })

      document.querySelector('#<? echo $ui['START_UPLOAD'] ?>')
        .addEventListener('click', (e) => {
          e.preventDefault()
          uploader.start()
        })
    }

    document.addEventListener('DOMContentLoaded', init);
  }(window.plupload))
</script>