<div class="row">
  <div class="col-md-12">
    <div class="card px-5 py-2">
      <div>
        <h4 class="title">Conversion Report</h4>
        <p class="category"></p>
      </div>
      <div class="card-body">
        <?php $this->load->view('cms/report/list/search_filter'); ?></div>
        <?php $this->load->view('cms/report/list/summary'); ?>
        <div class="text-right">
          <a href="<?php echo base_url().'cms/report/export?'.http_build_query($_GET) ?>">
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
                <th scope="col">Conversion ID</th>
                <!-- <th scope="col">Transaction ID</th> -->
                <th scope="col">Campaign</th>
                <th scope="col">UID</th>
                <th scope="col">Cashback</th>
                <th scope="col">Cashback <br><small>(Include Vat)</small></th>
                <th scope="col" class="text-center">Click Time</th>
                <th scope="col">Conversion Time</th>
                <th scope="col">Confirmation Time</th>
                <th scope="col">Updated Time</th>
                <th scope="col">Status</th>
                <th class="text-center" scope="col">Detail</th>
              </tr>
            </thead>
            <tbody class="font-size-08">
              <?php foreach($a_conversion['lists'] as $conversion) { ?>
                <tr>
                  <td><?php echo $conversion['conversion_id'] ?></td>
                  <!-- <td><?php echo $conversion['verification_id'] ?></td> -->
                  <td><?php echo $conversion['a_campaign']['name'] ?></td>
                  <td><?php echo $conversion['uid'] ?></td>
                  <td class="text-right"><?php echo $conversion['reward'] ?></td>
                  <td class="text-right">
                    <?php 
                      $vat = ($conversion['reward'] / 100) * 7;
                      echo $conversion['reward'] + $vat;
                    ?>
                  </td>
                  <td class="text-center"><?php echo $conversion['click_time'] ?></td>
                  <td class="text-center"><?php echo $conversion['conversion_time'] ?></td>
                  <td class="text-center"><?php echo ($conversion['confirmation_time'] == '0000-00-00 00:00:00') ? '' : $conversion['confirmation_time'] ?></td>
                  <td class="text-center"><?php echo $conversion['datetime_updated'] ?></td>
                  <td class="text-center">
                    <?php
                      $a_badge_color = ['APPROVED' => 'badge-success', 'PENDING' => 'badge-warning', 'REJECTED' => 'badge-danger'];
                      $badge_color = $a_badge_color[$conversion['status']];
                    ?>
                    <h5 class="mb-0"><span class="badge <?php echo $badge_color ?>"><?php echo $conversion['status'] ?></span></h5>
                  </td>
                  <td class="text-center">
                    <form action="<?php echo site_url('cms/report/detail/' . $conversion['id']); ?>">
                      <button class="btn btn-info btn-sm">
                        <i class="far fa-eye"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
  
        <?php
          $a_conversion['pagination']['path'] = 'cms/report/list';
          $this->load->view('cms/template/pagination', $a_conversion['pagination']) 
        ?>

      </div>
    </div>
  </div>
</div>