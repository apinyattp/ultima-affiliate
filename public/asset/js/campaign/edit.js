
Dropzone.autoDiscover = false

$(document).ready(function(){

  $('#copyQuicklinkBtn').on('click', function() { copyText() })

  const myDropzone = new Dropzone('div#logoDropzone', {
    url: base_url + 'api/file/upload',
    headers: { 'Authorization': document.cookie.split('=')[1] },
    method: 'POST',
    paramName: 'file',
    params: { endpoint: 'campaign_logo' },
    acceptedFiles: 'image/*',
    maxFiles: 1,
    maxFilesize: 1,
    clickable: '.uploadImageBtn',
    init: function() {
      var text = $('#banner_image_file').text()
      var banner_image_file = JSON.parse(text)
      if(!jQuery.isEmptyObject(banner_image_file)) {
        const { id, url, file_name, file_size, file_type } = banner_image_file
        var mockFile = { name: file_name, size: file_size, type: file_type }

        this.displayExistingFile(mockFile, url);
        this.files.push(mockFile);

        $('#logoFileId').val(id)
      }

      this.on("addedfile", function(file) {
        if (this.files.length > 1) {
          this.removeFile(this.files[0]);
        }
      })
      this.on('maxfilesexceeded', function(file) {
        this.removeAllFiles()
        this.addFile(file)
      })
    },
    accept: function(file, done) {
      done()
    },
    success: function(file, response) {
      $('#banner_image_file').text('')
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

  ClassicEditor.create( document.querySelector( '#descriptionEditor' ) )
  .catch( error => {
    // console.error( error );
  } );

  ClassicEditor.create( document.querySelector( '#input-conditiondo' ) )
  .then( newEditor => {
      doEditor = newEditor;
  } )
  .catch( error => {
    // console.error( error );  
  } );

  ClassicEditor.create( document.querySelector( '#input-conditiondont' ) )
  .then( newEditor => {
      dontEditor = newEditor;
  } )
  .catch( error => {
    // console.error( error );
  } );

  ClassicEditor.create( document.querySelector( '#input-note' ) )
  .then( newEditor => {
      noteEditor = newEditor;
  } )
  .catch( error => {
    // console.error( error );
  } );

  $('#btn-save').on('click', function (e) {
    id = $('#campaignId').val()
    validate(id)
  });

});

function validate(id) {

  $("#editForm").submit()

  // $.ajax({
  //   url: base_url + "cms/campaign/validate/"+id,
  //   type: 'POST',
  //   data: $("#editForm").serialize(),
  //   dataType: 'JSON',
  //   error: function (response) {
  //     console.log(response)

  //     // $.each(data, function(key, value) {
  //     //   $('#input-' + key).addClass('is-invalid');

  //     //   $('#input-' + key).parents('.form-group').find('#error').html(value);
  //     // });

  //     // result = response.responseJSON
  //     // const { response_code, response_msg } = result
  //     // Swal.fire({
  //     //   title : 'เกิดข้อผิดพลาด',
  //     //   text : `${response_msg} (${response_code})`,
  //     //   icon : 'warning'
  //     // })
  //   },
  //   success: function (response) {
  //     console.log(response)
  //   }
  // });
}

function copyText() {
  var copyText = document.getElementById("input-quicklink");

  copyText.select()
  copyText.setSelectionRange(0, 99999)

  document.execCommand('copy');
}