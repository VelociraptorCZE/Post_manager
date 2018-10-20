<?php
/** Post manager
 *  Copyright (C) Simon Raichl 2018
 *  MIT License
 *  Use this as you want, share it as you want, do basically whatever you want with this :)
 */

error_reporting(E_ERROR | E_PARSE);
ini_set("default_charset", 'iso-8859-15');

include("Vendor.php");

class Core extends Vendor
{
    public function __construct()
    {
        $this->run();
    }
}