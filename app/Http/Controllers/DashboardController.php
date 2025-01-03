<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\TaxiCompany;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $taxiCompanies = TaxiCompany::addSelect([
            'last_region_created_at' => Region::query()
                ->select('created_at')
                ->whereColumn('taxi_company_id', 'taxi_companies.id')
                ->latest()
                ->take(1)
        ])
        ->withCasts(['last_region_created_at' => 'datetime'])
        ->get();

        foreach ($taxiCompanies as $company) {
            echo $company->last_region_created_at->format('d-m-Y') . '<br>';
        }

        return view('dashboard.index');
    }
}
