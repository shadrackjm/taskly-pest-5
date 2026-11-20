<?php

arch('no debugging statements survive into the app')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'die', 'exit'])
    ->not->toBeUsed();

arch('models live in the right namespace and extend Model')
    ->expect('App\Models')
    ->toBeClasses()
    ->ignoring('App\Models\User');

arch('controllers are suffixed and extend our base controller')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller')
    ->toExtend('App\Http\Controllers\Controller')
    ->ignoring('App\Http\Controllers\Controller');

arch('form requests are suffixed correctly')
    ->expect('App\Http\Requests')
    ->toHaveSuffix('Request')
    ->toExtend('Illuminate\Foundation\Http\FormRequest');

arch('policies are suffixed correctly')
    ->expect('App\Policies')
    ->toHaveSuffix('Policy');

arch('jobs can be queued')
    ->expect('App\Jobs')
    ->toImplement('Illuminate\Contracts\Queue\ShouldQueue');

arch('enums are enums')
    ->expect('App\Enums')
    ->toBeEnums();

arch('controllers never talk to the HTTP client directly')
    ->expect('App\Http\Controllers')
    ->not->toUse('Illuminate\Support\Facades\Http');

arch('services stay free of HTTP request objects')
    ->expect('App\Services')
    ->not->toUse('Illuminate\Http\Request');

arch()->preset()->php();

arch()->preset()->security();
