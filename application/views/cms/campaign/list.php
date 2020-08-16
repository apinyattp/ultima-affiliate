
<div class="row">
  <div class="col-md-12">
    <div class="card px-5 py-2">
      <div>
        <h4 class="title">Affiliate Campaigns</h4>
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
              <th scope="col" class="text-center">Creatives</th>
              <th scope="col">Reward</th>
              <th scope="col" class="text-center">Affiliated From</th>
              <th scope="col" class="text-center">Status</th>
              <th scope="col" class="text-center">Edit</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($a_campaign['lists'] as $campaign) { ?>
              <tr>
                <td scope="row">
                <div class="text-center">
                  <img src="<?php echo $campaign['image_url']?>" class="rounded">
                </div>
                </td>
                <td><?php echo $campaign['name']?></td>
                <td class="text-center">
                  <button type="button" class="btn btn-info">
                    <i class="fas fa-link"></i>
                  </button>
                </td>
                <td>
                  <ul>
                    <?php foreach($campaign['default_reward'] as $reward) { ?>
                        <li>
                          <?php $customerType = (isset($reward['customerType'])) ? 'Customer-Type : ' . $reward['customerType'] : ''; ?>
                          <?php echo  $customerType . ' ' . $reward['name'].' ('. $reward['reward'] .'%)'?>
                        </li>
                    <?php } ?>
                  </ul>
                </td>
                <td class="text-center"><?php echo $campaign['affiliated_date']?></td>
                <td class="text-center">
                  <?php 
                    $display_status = ['active' => 'Active', 'inactive' => 'Inactive'];
                    echo $display_status[$campaign['status']];
                  ?>
                </td>
                <td class="text-center">
                  <form action="<?php echo site_url('cms/campaign/edit/' . $campaign['id']); ?>">
                    <button class="btn btn-info btn-link">
                      <i class="far fa-edit"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
  
        <!-- pagination -->
        <?php
          $a_campaign['pagination']['path'] = 'cms/campaign';
          $this->load->view('cms/template/pagination', $a_campaign['pagination']) 
        ?>

      </div>
    </div>
  </div>
</div>
