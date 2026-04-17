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
require_once APPPATH . "/third_party/Mobile_Detect.php";
class Mdetect extends Mobile_Detect {
    public function __construct() {
        parent::__construct();
    }
}
