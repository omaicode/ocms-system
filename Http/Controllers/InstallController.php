<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\Admin;
use Modules\System\Facades\DotenvEditor;
use Modules\System\Http\Requests\UpdateDatabaseRequest;
use Throwable;

class InstallController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $env  = DotenvEditor::load();
        $data = $env->getKeys(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD']);

        if($this->request->session()->has('install_success')) {
            //Create install file in storage
            File::put(storage_path('install'), 'INSTALLED');
        }

        return view('system::install.index', $data);
    }

    public function setup(UpdateDatabaseRequest $request)
    {
        //Convert settings to uppercase and re-map array
        $data = collect($request->only([
            'db_host',
            'db_port',
            'db_database',
            'db_username',
            'db_password'
        ]))
        ->map(function($value, $key) {
            return [
                'key'   => strtoupper($key),
                'value' => $value
            ];
        })
        ->values()
        ->toArray();
        
        //Save settings
        $env  = DotenvEditor::load();
        $env->setKeys($data);
        $env->save();

        //Check conncetion
        try {
            DB::connection()->getPdo();
            if(!DB::connection()->getDatabaseName()){
                return redirect()
                ->back()
                ->withErrors(['db_database' => [__('system::messages.db_database_failed', ['db' => $request->db_database])]]);
            }
        } catch (Throwable $e) {
            return redirect()
            ->back()
            ->withErrors(['db_host' => [__('system::messages.db_connection_failed')]]);
        }

        //Run migration & seed
        Artisan::call('ocms:migrate');
        Artisan::call('ocms:seed');

        //Create admin account
        Admin::firstOrCreate([
            'username' => $request->admin_username
        ], [
            'password' => $request->admin_password,
            'email'    => $request->admin_email,
            'name'     => $request->admin_username
        ])
        ->syncRoles('Super Admin');

        return redirect()->back()->with('install_success', true);
    }
}