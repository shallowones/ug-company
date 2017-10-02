(function ($) {
  $(function () {

    // табы
    const buttons = $('.js-tabs').find('.tabs__item')
    const activeClass = 'active'
    buttons.each(function () {
      const $this = $(this)
      const selector = $this.data('tab')
      if ($this.hasClass(activeClass)) {
        $(selector).addClass(activeClass)
      }
      $this.on('click', function (e) {
        e.preventDefault()
        if (!$this.hasClass(activeClass)) {
          const selector = $this.data('tab')
          buttons.each(function () {
            $(this).removeClass(activeClass)
            $($(this).data('tab')).removeClass(activeClass)
          })
          $this.addClass(activeClass)
          $(selector).addClass(activeClass)
        }
      })
    })

    // кастомный инпут типа файл
    const $file = $('.js-file')
    const $fileInput = $file.find('input[type=file]')
    $fileInput.jfilestyle({
      buttonText: 'Выбрать файл',
      inputSize: '100%'
    })

    $file.find('.add-file').on('click', function (e) {
      e.preventDefault()

      const newInput = $('<input type="file">')

      $file
        .append(newInput)

      newInput.jfilestyle({
        buttonText: 'Выбрать файл',
        inputSize: '100%'
      })
    })

  })
}(jQuery))
