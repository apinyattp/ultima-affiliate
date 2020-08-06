<?php
switch ($type) {
    case 'api':
        $this->load->view('document/template/subview/submenu_api', ['method' => $method, 'endpoint' => $endpoint, "path" => $path]);
        break;
    case 'sitemap':
        $this->load->view('document/template/subview/submenu_sitemap', ['page' => $endpoint, "path" => $path]);
        break;
}
