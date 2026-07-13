<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;
class Filters extends BaseConfig {
    public array $aliases = [
        'csrf'      => \CodeIgniter\Filters\CSRF::class,
        'toolbar'   => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'  => \CodeIgniter\Filters\Honeypot::class,
        'authAdmin' => \App\Filters\AuthAdmin::class,
        'authUser'  => \App\Filters\AuthUser::class,
        'guest'     => \App\Filters\Guest::class,
    ];
    public array $globals = [];
    public array $methods = [];
    public array $filters = [];
}