<div class="table-responsive">
  <table class="table">
    <thead>
      <tr class="text-nowrap">
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Reward</th>
        <th scope="col" class="text-center">Affiliated From</th>
        <th scope="col" class="text-center">Highlight</th>
        <th scope="col" class="text-center">Coming soon</th>
        <th scope="col" class="text-center">Status</th>
        <th scope="col" colspan="2" class="text-center">Manage</th>
        <!-- <th scope="col" class="text-center">DELETE</th> -->
      </tr>
    </thead>
    <tbody>
      <?php foreach($a_campaign['lists'] as $campaign) { ?>
        <?php $this->load->view('cms/campaign/item', $campaign)?>
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- pagination -->
<?php
  $a_campaign['pagination']['path'] = 'cms/campaign/list';
  $this->load->view('cms/template/pagination', $a_campaign['pagination']) 
?>
