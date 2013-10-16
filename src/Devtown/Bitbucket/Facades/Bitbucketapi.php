<?php namespace Devtown\Bitbucket\Facades;

use Illuminate\Support\Facades\Facade;

class Bitbucketapi extends Facade
{

  /**
   * Get the registered component.
   *
   * @return object
   */
  protected static function getFacadeAccessor(){ return 'bitbucketapi'; }

}
