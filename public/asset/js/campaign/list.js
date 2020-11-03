function update_comingsoon_status(id) {

    element = $('#comingSoonToggle'+id)

    coming_soon = (element.attr('checked')) ? 0 : 1

    $.ajax({
        url: base_url + "cms/campaign/update_comingsoon_status",
        type: 'POST',
        data: {
            'campaign_id': id,
            'coming_soon' : coming_soon,
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
        error: function (response) {
          result = response.responseJSON
          const { response_code, response_msg } = result
          Swal.fire({
            title : 'เกิดข้อผิดพลาด',
            text : `${response_msg} (${response_code})`,
            icon : 'warning'
          })
        },
        success: function () {
          location.reload()
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

function syncCampaign() {
  $.ajax({
    url: base_url + "crontab/campaign/update_campaign",
    type: 'GET',
    beforeSend: function(element) {
      $('.fa-download').addClass("d-none");
      $('.fa-circle-notch').removeClass("d-none");
      $('#syncCampaignButton').attr('disabled', 'disabled')
    },
    error: function (response) {
      result = response.responseJSON
      const { response_code, response_msg } = result
      Swal.fire({
        title : 'เกิดข้อผิดพลาด',
        text : `${response_msg} (${response_code})`,
        icon : 'warning'
      })
    },
    success: function () {
      Swal.fire({
        title : 'Updated !',
        text : 'Campaign has been updated',
        icon : 'success'
      }).then(() =>
        location.reload()
      )
    }
  });
}