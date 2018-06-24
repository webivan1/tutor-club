$(document).ready(function () {
  $('[data-clone-container]').on('click', function () {
    var nameContainer = $(this).data('clone-container');
    var container = $(nameContainer);
    if (!container.length) {
      console.error('Undefined container ' + nameContainer);
      return false;
    }

    var cloneblock = container.find('.js-item').eq(0).clone();
    cloneblock.find('input, textarea, select').each(function () {
      $(this).val('');
    });
    container.append(cloneblock);
    return false;
  });

  window.deleteItem = function (element, containerName) {
    return confirm('Вы уверены?') && $(element).closest(containerName).find('.js-item').length > 1
      ? $(element).closest('.js-item').remove()
      : null;
  };
});