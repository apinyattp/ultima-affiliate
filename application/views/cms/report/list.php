
<div class="row">
  <div class="col-md-12">
    <div class="card px-5 py-2">
      <div>
        <h4 class="title">Conversion Report</h4>
        <p class="category"></p>
      </div>
      <form action="<?php echo site_url('cms/report/list');?>"  method="get" autocomplete="off">
        <div class="row">
          <div class="form-group col-md-3">
              <label for="inputCampaign">Campaign</label>
              <select id="inputCampaign" id="campaign" name="campaign_id" class="form-control">
                  <option value="" <?php if(empty($campaign_id)) echo 'selected' ?>>All</option>
                  <?php foreach($a_campaign as $campaign) { ?>
                      <option value="<?php echo $campaign['id'] ?>" <?php if($campaign_id == $campaign['id']) echo 'selected' ?>><?php echo $campaign['name'] ?></option>
                  <?php } ?>
              </select>
          </div>
          <div class="form-group col-md-2">
            <label for="startDatePicker">Start Date</label>
            <div class="input-group">
              <input id="startDatePicker" type="text" class="form-control date" name="start_date" placeholder="">
              <div class="input-group-append">
                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
              </div>
            </div>
          </div>
          <div class="form-group col-md-2">
            <label for="endDatePicker">Start Date</label>
            <div class="input-group">
              <input id="endDatePicker" type="text" class="form-control" name="end_date" placeholder="">
              <div class="input-group-append">
                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
              </div>
            </div>
          </div>
          <div class="form-group col-md-2">
              <label for="inputStatus">Status</label>
              <select id="inputStatus" class="form-control" name="status">
                  <option value="" selected>All</option>
                  <?php foreach($a_status as $value => $text) { ?>
                    <option value="<?php echo $value ?>"><?php echo $text ?></option>
                  <?php } ?>
              </select>
          </div>
          <div class="col-md-3 align-self-end">
            <button id="searchBtn" class="btn btn-primary">Search</button>        
          </div>
        </div>
      </form>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr class="text-nowrap">
              <!-- <th scope="col">Conversion ID</th> -->
              <th scope="col">Campaign</th>
              <th scope="col">Transaction ID</th>
              <th scope="col">Click Time</th>
              <th scope="col">Conversion Time</th>
              <th scope="col">Confirmation Time</th>
              <th scope="col">Reward</th>
              <th scope="col">Status</th>
              <th class="text-center" scope="col">#</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($a_conversion['lists'] as $conversion) { ?>
              <tr>
                <!-- <td><?php echo $conversion['conversion_id'] ?></td> -->
                <td><?php echo $conversion['a_campaign']['name'] ?></td>
                <td><?php echo $conversion['verification_id'] ?></td>
                <td><?php echo $conversion['click_time'] ?></td>
                <td><?php echo $conversion['conversion_time'] ?></td>
                <td><?php echo $conversion['confirmation_time'] ?></td>
                <td class="text-right"><?php echo $conversion['reward'] ?></td>
                <td><?php echo $conversion['status'] ?></td>
                <td class="text-center">
                  <button type="button" class="btn btn-info btn-sm">
                    <i class="far fa-eye"></i>
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
  
        <?php
          $a_conversion['pagination']['path'] = 'cms/report/list';
          $this->load->view('cms/template/pagination', $a_conversion['pagination']) 
        ?>

      </div>
    </div>
  </div>
</div>