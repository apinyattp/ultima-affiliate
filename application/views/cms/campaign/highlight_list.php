<table class="table">
  <thead>
    <tr class="text-nowrap">
      <!-- <th scope="col"></th> -->
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Reward</th>
      <th scope="col" class="text-center">Affiliated From</th>
      <th scope="col" class="text-center">Highlight Status</th>
    </tr>
  </thead>
  <tbody id="sortable">

  <?php foreach($a_highlight_campaign as $campaign) { ?>
    <?php $this->load->view('cms/campaign/pin_item', $campaign)?>
  <?php } ?>

  </tobody>
</table>
