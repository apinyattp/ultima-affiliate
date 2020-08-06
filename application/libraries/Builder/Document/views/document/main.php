<div class="row"><div class="col-12">
<?php
$is_endpoint = FALSE;
$table = FALSE;
$a_line = $a_data[$type][$file]->get_line();
foreach($a_line as $line){
    if($table !== FALSE && $table !== $line['type']){
        echo "</tbody></table>";
        $table = FALSE;
    }
    switch ($line['type']) {
        case 'text':
            $this->load->view('document/line/text', ['line' => $line]);
            break;

        case 'endpoint':
            if($is_endpoint) echo "</div></div>";
            $this->load->view('document/line/endpoint', ['line' => $line]);
            $is_endpoint = TRUE;
            break;

        case 'sitemap':
            if($is_endpoint) echo "</div></div>";
            $this->load->view('document/line/sitemap', ['line' => $line]);
            $is_endpoint = TRUE;
            break;

        case 'page':
            if($is_endpoint) echo "</div></div>";
            $this->load->view('document/line/page', ['line' => $line]);
            $is_endpoint = FALSE;
            break;

        case 'input_table':
            $this->load->view('document/line/input_table', ['line' => $line, 'table' => $table]);
            $table = $line['type'];
            break;

        case 'header_table':
            $this->load->view('document/line/header_table', ['line' => $line, 'table' => $table]);
            $table = $line['type'];
            break;

        case 'error_code_table':
            $this->load->view('document/line/error_code_table', ['line' => $line, 'table' => $table]);
            $table = $line['type'];
            break;

        case 'json':
            $this->load->view('document/line/json', ['line' => $line]);
            break;

        case 'hr':
            if($is_endpoint) echo "</div></div>";
            $is_endpoint = FALSE;
            echo "<hr>";
            break;

        default:
            var_dump($line);
            echo "<hr>";
            break;
    }
}
if($table) echo "</tbody></table>";
if($is_endpoint) echo "</div></div>";
?>
</div></div>