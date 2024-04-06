<?php

namespace App\Providers;

use App\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    /** @var \Illuminate\Validation\Factory $validator */
    $validator = $this->app['validator'];
    $validator->resolver(function($translator, $data, $rules, $messages){
      return new Validator($translator, $data, $rules, $messages);
    });
  }

  public function register(): void
  {
  }
}
