<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DnController;
use App\Http\Controllers\DsController;
use App\Http\Controllers\CocController;
use App\Http\Controllers\AamsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustController;
use App\Http\Controllers\PepoController;
use App\Http\Controllers\PpmsController;
use App\Http\Controllers\PposController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RisksController;
use App\Http\Controllers\PtasksController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PstatusController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MilestonesController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;








Route::get('/', [AuthController::class, 'login']);

Route::group(
    ['middleware' => ['auth']],
    function () {




        Route::resource('dashboard', controller: DashboardController::class)->only(['index']);
        // Dashboard Print & PDF Export
        Route::get('dashboard/print/{prNumber}', [DashboardController::class, 'printProject'])->name('dashboard.print');
        Route::get('dashboard/print-filtered', [DashboardController::class, 'printFiltered'])->name('dashboard.print.filtered');
        Route::get('dashboard/pdf-filtered', [DashboardController::class, 'exportFilteredPDF'])->name('dashboard.pdf.filtered');
        Route::get('dashboard/export/pdf/{prNumber}', [DashboardController::class, 'exportProjectPDF'])->name('dashboard.export.pdf');

        /*Project*/
        // Projects PDF Export (must be before resource route)
        Route::get('project/export/pdf', [ProjectsController::class, 'exportPDF'])->name('projects.export.pdf');
        // Projects Print View (must be before resource route)
        Route::get('project/print', [ProjectsController::class, 'printView'])->name('projects.print');

        Route::resource('project', controller: ProjectsController::class)->names([
            'index' => 'projects.index',
            'create' => 'projects.create',
            'store' => 'projects.store',
            'show' => 'projects.show',
            'edit' => 'projects.edit',
            'update' => 'projects.update',
            'destroy' => 'projects.destroy',
        ]);
        // Route::resource('/project/{id}', 'ProjectsController@getprojects');

        /*Customer*/
        // Customer PDF Export & Print (must be before resource route)
        Route::get('customer/export/pdf', [CustController::class, 'exportPDF'])->name('customer.export.pdf');
        Route::get('customer/print', [CustController::class, 'printView'])->name('customer.print');
        Route::resource('customer', CustController::class);

        /*AM*/
        Route::get('am/export/pdf', [AamsController::class, 'exportPDF'])->name('am.export.pdf');
        Route::get('am/print', [AamsController::class, 'printView'])->name('am.print');
        Route::resource('am', AamsController::class);
        /*PM*/
        Route::get('pm/export/pdf', [PpmsController::class, 'exportPDF'])->name('pm.export.pdf');
        Route::get('pm/print', [PpmsController::class, 'printView'])->name('pm.print');
        Route::resource('pm', PpmsController::class);
        /*Vendors */
        Route::get('vendors/export/pdf', [VendorsController::class, 'exportPDF'])->name('vendors.export.pdf');
        Route::get('vendors/print', [VendorsController::class, 'printView'])->name('vendors.print');
        Route::resource('vendors', VendorsController::class);
        /*d/s */
        Route::get('ds/export/pdf', [DsController::class, 'exportPDF'])->name('ds.export.pdf');
        Route::get('ds/print', [DsController::class, 'printView'])->name('ds.print');
        Route::resource('ds', DsController::class);
        // invoice
        Route::get('invoices/export/pdf', [InvoicesController::class, 'exportPDF'])->name('invoices.export.pdf');
        Route::get('invoices/print', [InvoicesController::class, 'printView'])->name('invoices.print');
        Route::resource('invoices', InvoicesController::class);
        // /*DN  */
        Route::get('dn/export/pdf', [DnController::class, 'exportPDF'])->name('dn.export.pdf');
        Route::get('dn/print', [DnController::class, 'printView'])->name('dn.print');
        Route::resource('dn', DnController::class);
        //         /*CoC */
        Route::get('coc/export/pdf', [CocController::class, 'exportPDF'])->name('coc.export.pdf');
        Route::get('coc/print', [CocController::class, 'printView'])->name('coc.print');
        Route::resource('coc', CocController::class);
        // /*Project POs Form */
        Route::get('ppos/export/pdf', [PposController::class, 'exportPDF'])->name('ppos.export.pdf');
        Route::get('ppos/print', [PposController::class, 'printView'])->name('ppos.print');
        Route::resource('ppos', PposController::class);
        Route::delete('ppos/destroy', [PposController::class, 'destroy']);
        Route::get('ppos/categories/{pr_number}', [PposController::class, 'getCategoriesByProject'])->name('ppos.categories');

        // /*Project Status  */
        Route::get('pstatus/export/pdf', [PstatusController::class, 'exportPDF'])->name('pstatus.export.pdf');
        Route::get('pstatus/print', [PstatusController::class, 'printView'])->name('pstatus.print');
        Route::resource('pstatus', PstatusController::class);
        Route::delete('pstatus/destroy', [PstatusController::class, 'destroy']);
        // /*Project Tasks */
        Route::get('ptasks/export/pdf', [PtasksController::class, 'exportPDF'])->name('ptasks.export.pdf');
        Route::get('ptasks/print', [PtasksController::class, 'printView'])->name('ptasks.print');
        Route::resource('ptasks', PtasksController::class);
        Route::delete('ptasks/destroy', [PtasksController::class, 'destroy']);
        // /*Project EPO */
        Route::get('epo/export/pdf', [PepoController::class, 'exportPDF'])->name('epo.export.pdf');
        Route::get('epo/print', [PepoController::class, 'printView'])->name('epo.print');
        Route::resource('epo', PepoController::class);

        // /*Risks  */
        Route::get('risks/export/pdf', [RisksController::class, 'exportPDF'])->name('risks.export.pdf');
        Route::get('risks/print', [RisksController::class, 'printView'])->name('risks.print');
        Route::resource('risks', RisksController::class);
        Route::delete('risks/destroy', [RisksController::class, 'destroy']);

        // /*Milestones  */
        Route::get('milestones/export/pdf', [MilestonesController::class, 'exportPDF'])->name('milestones.export.pdf');
        Route::get('milestones/print', [MilestonesController::class, 'printView'])->name('milestones.print');
        Route::resource('milestones', MilestonesController::class);



        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('reports', ReportController::class);
        Route::get('reports/customer/projects', [ReportController::class, 'getCustomerProjects'])->name('reports.customer.projects');
        Route::get('reports/vendor/projects', [ReportController::class, 'getVendorProjects'])->name('reports.vendor.projects');
        Route::get('reports/supplier/projects', [ReportController::class, 'getSupplierProjects'])->name('reports.supplier.projects');
        Route::get('reports/pm/projects', [ReportController::class, 'getPMProjects'])->name('reports.pm.projects');
        Route::get('reports/am/projects', [ReportController::class, 'getAMProjects'])->name('reports.am.projects');
        Route::get('reports/export/csv', [ReportController::class, 'export'])->name('reports.export');
        Route::post('reports/cache/clear', [ReportController::class, 'clearCache'])->name('reports.cache.clear');
    }

);

// Auth::routes();
// Auth::routes(['register'=>false]);

require __DIR__ . '/auth.php';

// Route لعرض الصور من مجلد storge
Route::get('storge/{path}', function ($path) {
    $filePath = base_path('storge/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }

    $mimeType = mime_content_type($filePath);
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
    ]);
})->where('path', '.*');

// Test route for PPO categories
Route::middleware('auth')->get('/test-ppo-categories', function() {
    $projects = App\Models\Project::all();
    return view('test_ppo_categories', compact('projects'));
});

Route::get('/{page}', [AdminController::class, 'index']);
