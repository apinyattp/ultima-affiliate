function update_rejected(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reject it!'
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + "cms/missing_conversion/update_rejected",
                type: 'POST',
                data: {
                  'id': id
                },
                dataType: 'JSON',
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
                    $('#status_reject_' + id).html('<h5 class="mb-0"><span class="badge badge-danger">REJECTED</span></h5>')
                    Swal.fire(
                        'Reject !',
                        'missing conversion has been rejected.',
                        'success'
                    )
                }
              });

        }
    })

  }

  function update_status(id) {
    $.ajax({
      url: base_url + "cms/missing_conversion/update_status",
      type: 'POST',
      data: {
        'id': id
      },
      dataType: 'JSON',
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
        const { response_code, response_msg, result } =  response
        if(response_code != 200) {
        if(response_code) {
              Swal.fire({
              title : 'เกิดข้อผิดพลาด',
              text : `${response_msg} (${response_code})`,
              icon : 'warning'
            })
          }  
        }

        text_status = 'NEW'
        btn_color = 'info'
        status = result.status
        if(status != 'new') {
          text_status = 'SEND'
          btn_color = 'warning'
        }
        html = '<button class="btn btn-'+btn_color+' btn-sm" onclick="update_status('+id+')">' +text_status+ '</button>'
        $('#status_' + id).html(html)
      }
    });

  }