<div class="d-flex">
  <div class="p-2 flex-grow-1">
    <form action="<?php echo site_url('cms/campaign/list') ?>" method="get">
      <div class="row">
        <div class="form-group col-md-4">
          <label for="inputKeyword">Keyword</label>
          <input type="text" class="form-control" id="inputKeyword" name="keyword" value="<?php echo $keyword ?>">
        </div>
        <div class="form-group col-md-4">
          <label for="inputStatus">Status</label>
          <select id="inputStatus" name="status" class="form-control">
            <option value="" <?php echo empty($status) ? "selected" : "" ?>>All</option>
            <option value="active" <?php echo ($status == "active") ? "selected" : "" ?>>Active</option>
            <option value="inactive" <?php echo ($status == "inactive") ? "selected" : "" ?>>Inactive</option>
          </select>
        </div>
        <div class="col-md-2 align-self-end">
          <button id="searchBtn" class="btn btn-primary">Search</button>        
        </div>
      </div>
    </form>
  </div>
  <div class="p-2 align-self-end">
    <div class="">
      <button id="syncCampaignButton" class="btn btn-info" onclick="syncCampaign()">
        Sync Campaign
        <i class="pl-2 fas fa-download"></i>
        <i class="fas fa-circle-notch fa-spin d-none"></i>
      </button>        
    </div>
  </div>
</div>
