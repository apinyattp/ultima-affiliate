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