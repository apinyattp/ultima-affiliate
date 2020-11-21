<form action="<?php echo site_url('cms/report/list');?>"  method="get" autocomplete="off">
  <div class="row">
    <div class="form-group col-md-6">
      <label for="inputKeyword">Keyword (uid)</label>
      <input id="keyword" name="keyword" type="text" class="form-control" placeholder="" value="<?php echo $keyword?>">
    </div>
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
      <label for="inputStatus">Status</label>
      <select id="inputStatus" id="status" name="status" class="form-control">
          <option value="" <?php if(empty($status)) echo 'selected' ?>>All</option>
          <option value="pending" <?php if($status == 'PENDING') echo 'selected' ?>>Pending</option>
          <option value="approved" <?php if($status == 'APPROVED') echo 'selected' ?>>Approved</option>
          <option value="rejected" <?php if($status == 'REJECTED') echo 'selected' ?>>Rejected</option>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 align-self-center">
      <div class="row">
        <div class="col">
          <div class="form-check form-check-radio">
              <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="period_base" id="period_base1" value="conversion_time" <?php if($period_base == 'conversion_time') echo 'checked'?>>
                  Conversion Time
                  <span class="form-check-sign"></span>
              </label>
          </div>
        </div>
        <div class="col">
          <div class="form-check form-check-radio">
              <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="period_base" id="period_base2" value="confirmation_time" <?php if($period_base == 'confirmation_time') echo 'checked'?>>
                  Confirmation Time
                  <span class="form-check-sign"></span>
              </label>
          </div>
        </div>
        <div class="col">
          <div class="form-check form-check-radio">
              <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="period_base" id="period_base3" value="datetime_updated" <?php if($period_base == 'datetime_updated') echo 'checked'?>>
                  Updated Time
                  <span class="form-check-sign"></span>
              </label>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group col-md-2">
      <label for="startDatePicker">Start Date</label>
      <div class="input-group">
        <input id="startDatePicker" type="text" class="form-control date" name="start_date" placeholder="" value="<?php echo $start_date?>">
        <div class="input-group-append">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
      </div>
    </div>
    <div class="form-group col-md-2">
      <label for="endDatePicker">End Date</label>
      <div class="input-group">
        <input id="endDatePicker" type="text" class="form-control" name="end_date" placeholder="" value="<?php echo $end_date?>">
        <div class="input-group-append">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-1 my-auto">
      <button id="searchBtn" class="btn btn-primary">Search</button>        
    </div>
  </div>
</form>