<?php 

class Adz {
  // public static $plugin;
  public $config = [];

  public static function setConfig(Adz $adzClass, Array $config)
  {
    $adzClass->config = $config;
    return true;
  }

  public static function getConfig(Adz $adzClass)
  {
    return $adzClass->config;
  }
}