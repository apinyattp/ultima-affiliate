<tr>
  <td scope="row">
  <div class="text-center">
      <img src="<?php echo $image_url ?>" class="rounded">
  </div>
  </td>
  <td><?php echo $name?></td>
  <td>
      <ul>
      <?php foreach($default_reward as $reward) { ?>
          <li>
              <?php $customerType = (isset($reward['customerType'])) ? 'Customer-Type : ' . $reward['customerType'] : ''; ?>
              <?php echo  $customerType . ' ' . $reward['name'].' ('. $reward['reward'] .'%)'?>
          </li>
      <?php } ?>
      </ul>
  </td>
  <td class="text-center"><?php echo $affiliated_date ?></td>
  <td class="text-center">
    <button class="btn <?php echo !($is_highlight) ? 'btn-success' : 'btn-default' ?> btn-round btn-sm" 
        id="pinBtn<?php echo $id ?>" 
        onclick="update_pin(<?php echo $id.','.($is_highlight) ?>)">
        <span><?php echo !($is_highlight) ? 'pin' : 'unpin' ?></span>
    </button>
  </td>
</tr>