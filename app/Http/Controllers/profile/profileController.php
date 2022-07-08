<?php

namespace App\Http\Controllers\profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\tenantModel;

class profileController extends Controller
{
    public function index($code)
    {
        $a = tenantModel::where('bms_tenant.tenant_code', $code);
        $tenant = $a
                    ->leftjoin('bms_tenant_company', 'bms_tenant_company.tenant_code', '=', 'bms_tenant.tenant_code')
                    ->select('bms_tenant.*', 'bms_tenant_company.*')
                    ->first();
        return view('profile.index', [
            'tenant' => $tenant
        ]);
    }
}
