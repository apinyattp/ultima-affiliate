$(document).ready(function(){
  $('#startDatePicker').datepicker({
  });

  $('#endDatePicker').datepicker({
  });

  $('#sortable').sortable();
  $('#sortable').disableSelection();

  $('form input.form-control').on('input', function() {
    const classes = ['has-danger', 'has-error']
    if ($(this).val() === '') {
      $(this).parent().addClass(classes)
    } else {
      $(this).first().parent().removeClass(classes)
      $(this).parent().children('label.error-message').text('')
    }
  })

});
