<div class="card px-5 py-2">
  <div>
    <h4 class="title">Highlight Campaigns</h4>
    <p class="category"></p>
  </div>
  <div class="card-body">
    <?php $this->load->view('cms/campaign/highlight_list'); ?>
  </div>
</div>

<div class="card px-5 py-2">
  <div>
    <h4 class="title">Campaign List</h4>
    <p class="category"></p>
  </div>
  <?php $this->load->view('cms/campaign/search_filter', $search_filter)?>
  <div class="card-body">
    <?php $this->load->view('cms/campaign/list'); ?>
  </div>
</div>

