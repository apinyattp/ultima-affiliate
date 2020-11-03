<div class="d-flex justify-content-center">
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">total order</h6>
            <h5 class="card-text"><?php echo $a_conversion['pagination']['total_items'] ?></h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['total']['transaction_amount'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">pending reward</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['pending']['reward'], 2)?> THB</h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['pending']['transaction_amount'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">approved reward</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['approved']['reward'], 2)?> THB</h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['approved']['transaction_amount'], 2)?> THB</small>
        </div>
    </div>
    <div class="card px-2 mx-auto">
        <div class="card-body">
            <h6 class="card-title">total reward</h6>
            <h5 class="card-text"><?php echo number_format($a_summary['total']['reward'], 2)?> THB</h5>
            <small>Transaction Amount : <?php echo number_format($a_summary['total']['transaction_amount'], 2)?> THB</small>
        </div>
    </div>
</div>
