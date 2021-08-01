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
                    $('#status_reject_' + id).html('<h5 class="mb-0"><span class="badge badge-danger">Rejected</span></h5>')
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