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