<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="heading-flush">
                    <i class="fa fa-btn fa-home"></i>
                    @yield('title')
                </h2>
            </div>
            <div class="panel-body">
                @foreach($taxiCompanies as $company)
                    <p>{{ $company->lastRegion->name }} | {{ $company->lastRegion->created_at->format('d-m-Y') }}</p>
                @endforeach

                <br>
                <br>
                <br>

                <p>Active: {{ $wmoBudgetStatusCounts->active_count }}</p>
                <p>Inactive: {{ $wmoBudgetStatusCounts->inactive_count }}</p>
            </div>
        </div>
    </div>
</div>
