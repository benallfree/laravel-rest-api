<?php
namespace App\Helpers;

trait StoresDerivedValues
{
  protected function doRecalc()
  {  
  }
  
  public function recalc($should_save=false)
  {
    $this->doRecalc();
    if($should_save) $this->save();
  }
}