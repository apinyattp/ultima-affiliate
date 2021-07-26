<div class="row">
  <div class="col-md-12">
    <div class="card px-5 py-2">
      <div>
        <h4 class="title">Conversion Report</h4>
        <p class="category"></p>
      </div>
      <div class="card-body">
        <?php $this->load->view('cms/missing_conversion/list/search_filter'); ?></div>
        <div class="text-right">
          <a href="<?php echo base_url().'cms/missing_conversion/export?'.http_build_query($_GET) ?>">
            <button id="exportConversionReportButton" class="btn btn-info">
              Export
              <i class="pl-2 fas fa-download"></i>
            </button>
          </a>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="text-nowrap">
                <th scope="col">Order ID</th>
                <th scope="col">Campaign</th>
                <th scope="col">UID</th>
                <th scope="col">Company</th>
                <th scope="col">Amount</th>
                <th scope="col">Order date</th>
                <th scope="col">Updated Time</th>
              </tr>
            </thead>
            <tbody class="font-size-08">
              <?php foreach($a_conversion['lists'] as $conversion) { ?>
                <tr>
                  <td><?php echo $conversion['order_id'] ?></td>
                  <td><?php echo $conversion['a_campaign']['name'] ?></td>
                  <td><?php echo $conversion['uid'] ?></td>
                  <td><?php echo $conversion['company'] ?></td>
                  <td><?php echo $conversion['amount'] ?></td>
                  <td class="text-center"><?php echo $conversion['order_date'] ?></td>
                  <td class="text-center"><?php echo $conversion['datetime_updated'] ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
  
        <?php
          $a_conversion['pagination']['path'] = 'cms/missing_conversion/list';
          $this->load->view('cms/template/pagination', $a_conversion['pagination']) 
        ?>

      </div>
    </div>
  </div>
</div>
