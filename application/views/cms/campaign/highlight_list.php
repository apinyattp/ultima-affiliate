<div class="card px-5 py-2">
  <div>
    <h4 class="title">Highlight Campaigns</h4>
    <p class="category"></p>
  </div>
  <div class="card-body">
  <table class="table">
    <thead>
      <tr class="text-nowrap">
        <!-- <th scope="col"></th> -->
        <th scope="col"></th>
        <th scope="col">Name</th>
        <th scope="col">Reward</th>
        <th scope="col" class="text-center">Affiliated From</th>
        <th scope="col" class="text-center">Highlight Status</th>
      </tr>
    </thead>
    <tbody id="sortable">

    <?php foreach($a_highlight_campaign as $campaign) { ?>
      <tr>
        <!-- <td class="text-center">
          <i class="fas fa-bars"></i>
        </td> -->
        <td scope="row">
        <div class="text-center">
          <img src="<?php echo $campaign['image_url']?>" class="rounded">
        </div>
        </td>
        <td><?php echo $campaign['name']?></td>
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
          <form action="<?php echo site_url('cms/campaign/edit/' . $campaign['id']); ?>">
            <?php if($campaign['is_highlight']) {
              echo '<button class="btn btn-success btn-round btn-sm"><span>pin</span></button>';
            }else {
              echo '<button class="btn btn-default btn-round btn-sm"><span>unpin</span></button>';
            } ?>
          </form>
        </td>
      </tr>
    <?php } ?>

    </tobody>
  </table>
  </div>
</div>
