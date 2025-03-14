<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\TaxiCompany;
use App\Models\User;
use App\Models\WmoBudget;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $taxiCompanies = Pipeline::send(TaxiCompany::query())
            ->through([
                fn($query, $next) => $next($query->withAlphabeticalFirstRegionCreatedAt()),
                fn($query, $next) => $next($query->withLastRegionId()),
                fn($query, $next) => $next($query->with('lastRegion')),
                fn($query, $next) => $next($query->orderByRaw('id % 2 = 1 desc')),
            ])
            ->then(fn($query) => $query->get());

        $wmoBudgetsSortedByUserName = WmoBudget::query()
            ->orderBy(
                User::whereColumn('id', 'wmo_budgets.user_id')->select('name')->take(1)
            )
            ->with('user')
            ->get();

        $wmoBudgetStatusCounts = WmoBudget::query()->selectRaw('
            count(case when active = 1 then 1 end) as active_count,
            count(case when active = 0 then 1 end) as inactive_count
        ')->first();

        $regionsForFirstCompany = $taxiCompanies->first()->regions;
        $regionsForFirstCompany->each->setRelation('taxiCompany', $taxiCompanies->first());

        $usersSortedByVerifiedAt = User::query()
            // Put records at the end where email_verified_at is null.
            ->orderByRaw('email_verified_at is null')
            ->orderBy('email_verified_at')
            ->get();

        return view('dashboard.index', [
            'taxiCompanies' => $taxiCompanies,
            'wmoBudgetStatusCounts' => $wmoBudgetStatusCounts,
            'regionsForFirstCompany' => $regionsForFirstCompany,
            'wmoBudgetsSortedByUserName' => $wmoBudgetsSortedByUserName,
            'usersSortedByVerifiedAt' => $usersSortedByVerifiedAt,
        ]);
    }
}
