<div class="card px-5 py-2">
  <div>
    <h4 class="title">Campaign List</h4>
    <p class="category"></p>
  </div>
  <form action="<?php echo site_url('cms/campaign/list');?>" method="get">
    <div class="row">
      <div class="form-group col-md-4">
        <label for="inputKeyword">Keyword</label>
        <input type="text" class="form-control" id="inputKeyword" name="keyword" value="<?php echo $keyword?>">
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
  <div class="card-body">
    <table class="table">
      <thead>
        <tr class="text-nowrap">
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Reward</th>
          <th scope="col" class="text-center">Affiliated From</th>
          <th scope="col" class="text-center">Highlight</th>
          <th scope="col" class="text-center">Status</th>
          <th scope="col" class="text-center">Edit</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($a_campaign['lists'] as $campaign) { ?>
          <?php $this->load->view('cms/campaign/item', $campaign)?>
        <?php } ?>
      </tbody>
    </table>

    <!-- pagination -->
    <?php
      $a_campaign['pagination']['path'] = 'cms/campaign/list';
      $this->load->view('cms/template/pagination', $a_campaign['pagination']) 
    ?>

  </div>
</div>
