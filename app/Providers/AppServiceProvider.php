<?php

namespace App\Providers;

use App\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function boot()
  {
    /** @var \Illuminate\Validation\Factory $validator */
    $validator = $this->app['validator'];
    $validator->resolver(function($translator, $data, $rules, $messages){
      return new Validator($translator, $data, $rules, $messages);
    });
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
