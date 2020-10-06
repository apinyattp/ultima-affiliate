$(document).ready(function(){
  $('#startDatePicker').datepicker({
    // 'date': $(this).val()
  });

  $('#endDatePicker').datepicker({
  });

  $('.input-daterange').each(function() {
  });

  $('#sortable').sortable();
  $('#sortable').disableSelection();

  $('form input.form-control').on('input', function() {
    const classes = ['has-danger', 'has-error']
    if ($(this).val() === '') {
      $(this).parent().addClass(classes)
    } else {
      $(this).parents('.form-group').removeClass('has-danger')
      $(this).parents('.form-group').removeClass('has-error')
    }
  })

  $('tr.disabled').find(":button").attr('disabled', 'disabled');

});