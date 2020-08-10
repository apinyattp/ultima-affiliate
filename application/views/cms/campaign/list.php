
<div class="row">
  <div class="col-md-12">
    <div class="card px-5 py-2">
      <div class="card-title">
        <h4 class="title">Affiliate Campaigns</h4>
        <p class="category"></p>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Creatives</th>
              <th scope="col">Reward</th>
              <th scope="col" class="text-nowrap">Affiliated From</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($campaigns as $campaign) { ?>
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
                    <?php foreach($campaign['reward'] as $reward) { ?>
                        <li>
                          <?php $customerType = (isset($reward['customerType'])) ? 'Customer-Type : ' . $reward['customerType'] : ''; ?>
                          <?php echo  $customerType . ' ' . $reward['name'].' ('. $reward['reward'] .'%)'?>
                        </li>
                    <?php } ?>
                  </ul>
                </td>
                <td class="text-center"><?php echo $campaign['affiliated_date']?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>
