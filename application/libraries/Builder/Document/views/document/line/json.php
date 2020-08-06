<?php
$json_decode = $this->load->json($line['value']);
echo "<pre class='json' data-json='".htmlspecialchars(json_encode($json_decode))."'>".json_encode($json_decode, JSON_PRETTY_PRINT)."</pre>";
