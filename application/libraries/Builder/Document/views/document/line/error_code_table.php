<?php
if($table === FALSE){
    ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Error Code</th>
                <th>Error Message</th>
            </tr>
        </thead>
        <tbody>
    <?php
}
$table = $line['type'];
?>
<tr>
    <td><?php echo htmlspecialchars($line['value']) ?></td>
    <td><?php echo htmlspecialchars(lang("errorcode_".$line['value'])) ?></td>
</tr>
