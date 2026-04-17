<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  ======================================= 
 *  Author     : Team Tech Arise 
 *  License    : Protected 
 *  Email      : info@techarise.com 
 * 
 *  ======================================= 
 */
require_once APPPATH . "third_party/Simple_html_dom.php";
class Simplehtmldom extends Simple_html_dom {
    public function __construct() {
        parent::__construct();
    }
}