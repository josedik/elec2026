<?php

use App\Http\Controllers\Admin\AsignarController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\DistrictspartyController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\MesaController;
use App\Http\Controllers\Admin\MuestreoController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PartyController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Schooltmp;
use Illuminate\Support\Facades\Route;

Route::get('', action: [HomeController::class, 'index']);
//Route::get('dist', action: [Schooltmp::class, 'parties']);
//Route::get('districts/show', action: [DistrictController::class, 'show'])->name('admin.districts.show');
Route::get('mesas/getMesas', action: [MesaController::class, 'getMesas'])->name('mesas.getMesas');

Route::post('mesas/storeVotes/{mesa}', action: [MesaController::class, 'storeVotes'])->name('mesas.storeVotes');

Route::get('muestreos/show/{district_id}/{samples}', action: [MuestreoController::class, 'show'])->name('muestreos.show');

Route::resource('departments', DepartmentController::class);
Route::resource('provinces', ProvinceController::class);
Route::resource('districts', DistrictController::class);
Route::resource('schools', SchoolController::class);
Route::resource('parties', PartyController::class);
Route::resource('mesas',MesaController::class);
Route::resource('voters', VoterController::class);
Route::resource('candidates', CandidateController::class);
Route::resource('districtsparty', DistrictspartyController::class);
Route::resource('muestreos', MuestreoController::class);

Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
Route::resource('users', AsignarController::class);
Route::resource('reports', ReportsController::class);

