<form action="<?php echo site_url('cms/missing_conversion/list');?>"  method="get" autocomplete="off">
  <div class="row">
    <div class="form-group col-md-4">
      <label for="inputKeyword">Keyword (UID, Order ID)</label>
      <input id="keyword" name="keyword" type="text" class="form-control" placeholder="" value="<?php echo $keyword?>">
    </div>
    <div class="form-group col-md-2">
        <label for="inputCampaign">Campaign</label>
        <select id="inputCampaign" id="campaign" name="campaign_id" class="form-control">
            <option value="" <?php if(empty($campaign_id)) echo 'selected' ?>>All</option>
            <?php foreach($a_campaign as $campaign) { ?>
                <option value="<?php echo $campaign['id'] ?>" <?php if($campaign_id == $campaign['id']) echo 'selected' ?>><?php echo $campaign['name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-2">
        <label for="inputCampaign">Source</label>
        <select id="source" id="source" name="source" class="form-control">
            <option value="" <?php if(empty($source)) echo 'selected' ?>>All</option>
            <option value="accesstrade" <?php if($source == 'accesstrade') echo 'selected' ?>>Accesstrade</option>
            <option value="involve_asia" <?php if($source == 'involve_asia') echo 'selected' ?>>Involve Asia</option>
        </select>
    </div>
    <div class="form-group col-md-2">
        <label for="inputStatusFilter">Status</label>
        <select id="status_filter" name="status" class="form-control">
            <option value="" <?php if(empty($status_filter)) echo 'selected' ?>>All</option>
            <option value="new" <?php if($status_filter == 'new') echo 'selected' ?>>New</option>
            <option value="send_to_affiliate" <?php if($status_filter == 'send_to_affiliate') echo 'selected' ?>>Send to Affiliate</option>
        </select>
    </div>
    <div class="form-group col-md-2">
        <label for="inputStatusFilter">Status Rejected</label>
        <select id="status_reject" name="status_reject" class="form-control">
            <option value="" <?php if(empty($status_reject)) echo 'selected' ?>>All</option>
            <option value="send_to_advertiser" <?php if($status_reject == 'send_to_advertiser') echo 'selected' ?>>Not Rejected</option>
            <option value="rejected" <?php if($status_reject == 'rejected') echo 'selected' ?>>Rejected</option>
        </select>
    </div>
    
  </div>
  <div class="row">
   <div class="form-group col-md-3">
        <label for="inputStatus">Company</label>
        <select id="inputStatus" id="company" name="company" class="form-control">
            <option value="" <?php if(empty($company)) echo 'selected' ?>>All</option>
            <?php foreach($companies as $company_item) { ?>
              <option value="<?php echo strtolower($company_item);?>" <?php if(strtolower($company_item) == $company) echo 'selected' ?>><?php echo $company_item?></option>
            <?php } ?>
        </select>
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
    <div class="form-group col-md-3">
      <label for="inputSort">Sort by</label>
        <select id="inputSort" id="sort" name="sort" class="form-control">
            <?php foreach($a_sort as $sort_data => $sort_text) { ?>
              <option value="<?php echo $sort_data;?>" <?php if($sort_data == $sort) echo 'selected' ?>><?php echo $sort_text?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-1 my-auto">
      <button id="searchBtn" class="btn btn-primary">Search</button>        
    </div>
  </div>
</form>
