<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TaxiCompany;
use App\Models\WmoBudget;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $taxiCompanies = TaxiCompany::query()
            ->withLastRegionId()
            ->with('lastRegion')
            ->get();

        $wmoBudgetStatusCounts = WmoBudget::query()->selectRaw('
            count(case when active = 1 then 1 end) as active_count,
            count(case when active = 0 then 1 end) as inactive_count
        ')->first();

        $regionsForFirstCompany = $taxiCompanies->first()->regions;
        $regionsForFirstCompany->each->setRelation('taxiCompany', $taxiCompanies->first());

        return view('dashboard.index', [
            'taxiCompanies' => $taxiCompanies,
            'wmoBudgetStatusCounts' => $wmoBudgetStatusCounts,
            'regionsForFirstCompany' => $regionsForFirstCompany,
        ]);
    }
}
