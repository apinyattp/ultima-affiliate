$( document ).ready(function() {
    $("#file_import").change(function () {
        Swal.fire({
            title: 'Import Conversion',
            text: "Are your sure to import conversion?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, import it!'
          }).then((result) => {
            if (result.isConfirmed) {
                let file_data = $('#file_import').prop('files')[0];   
                let form_data = new FormData();                  
                form_data.append('file', file_data);
                
                $('.loading').show();

                $.ajax({
                    url: base_url + "cms/report/import_conversion",
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    error: function (response) {
                      responseJSON = response.responseJSON
                
                      const { response_code, response_msg, result } = responseJSON
        
                      if(response_code) {
                          Swal.fire({
                          title : 'เกิดข้อผิดพลาด',
                          text : `${response_msg} (${response_code})`,
                          icon : 'warning'
                        })
                      }
                    },
                    success: function (response) {
                        const { response_code, response_msg, result } = response
                        if(result.status == 'fail') {
                            error_html = ''
                            for(error of result.error) {
                             error_html += '<div class="error">' +error+ '</div>'
                            }
                            Swal.fire({
                                title: "เกิดข้อผิดพลาด", 
                                html: error_html,  
                                confirmButtonText: "OK", 
                              }); 
                        }else{
                            Swal.fire(
                                'SUCCESS',
                                'Update conversion success'
                            )
                        }
                        $('.loading').hide();
                    }
                  });
    
            }
        })
    });
});


function importConversion() {
    $('#file_import').click();
}