function myConfirm(dialogText, okFunc, cancelFunc, dialogTitle) {
  $('<div style="padding: 10px; max-width: 500px; word-wrap: break-word;">' + dialogText + '</div>').dialog({
    draggable: false,
    modal: true,
    resizable: false,
    width: 'auto',
    title: dialogTitle || 'Confirm',
    minHeight: 150,
    buttons: {
      valider: function () {
        if (typeof (okFunc) == 'function') {
          setTimeout(okFunc, 50);
        }
        $(this).dialog('destroy');
      },
      annuler: function () {
        if (typeof (cancelFunc) == 'function') {
          setTimeout(cancelFunc, 50);
        }
        $(this).dialog('destroy');
      }
    }
  });
}




