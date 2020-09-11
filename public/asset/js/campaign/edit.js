
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
      var text = $('#logo_image_file').text()
      var logo_image_file = JSON.parse(text)
      if(!jQuery.isEmptyObject(logo_image_file)) {
        const { id, url, file_name, file_size, file_type } = logo_image_file
        var mockFile = { name: file_name, size: file_size, type: file_type }

        this.displayExistingFile(mockFile, url);
        this.files.push(mockFile);

        $('#input-image_file_id').val(id)
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
      $('#logo_image_file').text('')
      const { response_code, response_msg, result } = response
      if(response_code != '200') {
        Swal.fire({
          title : 'เกิดข้อผิดพลาด',
          text : `${response_msg} (${response_code})`,
          icon : 'warning'
        })
      }
      $('#input-image_file_id').val(result.id)
    }
  })

  ClassicEditor.create( document.querySelector( '#descriptionEditor' ) )
  .catch( error => {
    // console.error( error );
  } );

  ClassicEditor.create( document.querySelector( '#input-condition_do' ) )
  .then( newEditor => {
      doEditor = newEditor;
  } )
  .catch( error => {
    // console.error( error );  
  } );

  ClassicEditor.create( document.querySelector( '#input-condition_dont' ) )
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

  // $("#editForm").submit()
  $("#editForm").serialize(),

  $.ajax({
    url: base_url + "cms/campaign/validate/"+id,
    type: 'POST',
    data: {
      'display_name': $('#input-display_name').val(),
      'cashback': $('#input-cashback').val(),
      'image_file_id': $('#input-image_file_id').val(),
      'description': $('#input-description').val(),
      'status': $('#input-status').val(),
      'condition_do': doEditor.getData(),
      'condition_dont': dontEditor.getData(),
      'note': noteEditor.getData(),
      'a_set_reward[new]': $('#input-setRewardNewUser').val(),
      'a_set_reward[existing]': $('#input-setRewardExistingUser').val()
    },
    dataType: 'JSON',
    error: function (response) {
      responseJSON = response.responseJSON

      const { result } = responseJSON

      $.each(result, function(key, value) {

        if(value) {
          inputElement = $('#input-' + key)

          // inputElement.parents('.form-group').addClass('has-danger has-error');
          inputElement.parents('.form-group').find('#error').html('<div class="text-danger">'+value+'</div>');
        }

      });
      // result = response.responseJSON
      // const { response_code, response_msg } = result
      // Swal.fire({
      //   title : 'เกิดข้อผิดพลาด',
      //   text : `${response_msg} (${response_code})`,
      //   icon : 'warning'
      // })
    },
    success: function (response) {
      Swal.fire({
        title : 'Update Successful',
        timer: 750,
        text : '',
        icon : 'success',
        showCancelButton: false,
        showConfirmButton: false
      }).then(function () {},
        function (dismiss) {
          if (dismiss === 'timer') {}
        }
      ).then(function () {
        $("#editForm").submit()
      })
    }
  });

}

function copyText() {
  var copyText = document.getElementById("input-quicklink");

  copyText.select()
  copyText.setSelectionRange(0, 99999)

  document.execCommand('copy');
}