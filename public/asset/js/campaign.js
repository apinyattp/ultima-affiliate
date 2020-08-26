$(document).ready(function(){

    ClassicEditor.create( document.querySelector( '#descriptionEditor' ) )
    .catch( error => {
      // console.error( error );
    } );
  
    ClassicEditor.create( document.querySelector( '#doEditor' ) )
    .then( newEditor => {
        doEditor = newEditor;
    } )
    .catch( error => {
      // console.error( error );  
    } );
  
    ClassicEditor.create( document.querySelector( '#dontEditor' ) )
    .then( newEditor => {
        dontEditor = newEditor;
    } )
    .catch( error => {
      // console.error( error );
    } );
  
    ClassicEditor.create( document.querySelector( '#noteEditor' ) )
    .then( newEditor => {
        noteEditor = newEditor;
    } )
    .catch( error => {
      // console.error( error );
    } );

    $('.form-control').find('input[type="text"]').on('input', function() {
      $(this).closest('.form-group').removeClass('has-error');
      $(this).closest('.form-group').removeClass('has-danger');  
    });

});
