$(document).ready(function(){
  $('#startDatePicker').datepicker({
  });

  $('#endDatePicker').datepicker({
  });

  $('#sortable').sortable();
  $('#sortable').disableSelection();

  ClassicEditor.create( document.querySelector( '#descriptionEditor' ) )
  .catch( error => {
    // console.error( error );
  } );

  ClassicEditor.create( document.querySelector( '#doEditor' ) )
  .catch( error => {
    // console.error( error );  
  } );

  ClassicEditor.create( document.querySelector( '#dontEditor' ) )
  .catch( error => {
    // console.error( error );
  } );

  ClassicEditor.create( document.querySelector( '#noteEditor' ) )
  .catch( error => {
    // console.error( error );
  } );

  function edit_campaign() {

  }

});