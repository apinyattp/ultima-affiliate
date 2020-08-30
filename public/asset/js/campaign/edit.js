
Dropzone.autoDiscover = false
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

    const myDropzone = new Dropzone('div#logoDropzone', {
      url: base_url + 'api/file/upload',
      headers: { 'Authorization': document.cookie.split('=')[1] },
      method: 'POST',
      paramName: 'file',
      params: { endpoint: 'campaign_logo' },
      acceptedFiles: 'image/*',
      maxFiles:1,
      maxFilesize: 1,
      init: function() {
        this.on('maxfilesexceeded', function(file) {
              this.removeAllFiles();
              this.addFile(file);
        });
      },  
      accept: function(file, done) {
        done()
      },
      success: function(file, response) {
        const { response_code, response_msg, result } = response
        if(response_code != '200') {
          Swal.fire({
            title : 'เกิดข้อผิดพลาด',
            text : `${response_msg} (${response_code})`,
            icon : 'warning'
          })
        }
        $('#logoFileId').val(result.id)
      }
    })

});
