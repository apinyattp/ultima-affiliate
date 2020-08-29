function update_status(id) {

    element = $('#statusToggle'+id)

    status = (element.attr('checked')) ? 'inactive' : 'active'

    $.ajax({
        url: base_url + "cms/campaign/update_status",
        type: 'POST',
        data: {
            'campaign_id': id,
            'status' : status,
        },
        dataType: 'JSON',
        complete: function(response){}
    });
}

function update_pin(id, is_highlight) {
    element = $('#pinBtn'+id)

    type = (is_highlight) ? 'unpin' : 'pin';

    $.ajax({
        url: base_url + "cms/campaign/update_highlight_pin",
        type: 'POST',
        data: {
            'campaign_id': id,
            'type' : type,
        },
        dataType: 'JSON',
        complete: function(response){
            result = response.responseJSON
            console.log(result)
            if(result.response_code == '6001') {
                alert(result.response_msg);
            }else {
                location.reload()
            }
        }
    });

}

function displayDeleteModal(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    let modalDetails = {
      title: 'Deleted!',
      text: 'Your campaign has been deleted',
      icon: 'success'
    }
    if (result.value) {
      $.ajax({
        url: base_url + "cms/campaign/delete/"+id,
        type: 'DELETE',
        success: function() {},
        error: function(response) {
          result = response.responseJSON
          const { response_code, response_msg } = result
          modalDetails.title = 'เกิดข้อผิดพลาด'
          modalDetails.text = `${response_msg} (${response_code})`
          modalDetails.icon = 'warning'
        },
        complete: function(){
          Swal.fire(modalDetails).then(() =>
            location.reload()
          )
        }
      });
    }
  })
}