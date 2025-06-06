<?php

use App\Facades\GoogleMaps;
use App\Http\Controllers\Customer\Auth\LoginController as CustomerLoginController;
use App\Http\Controllers\Employee\Auth\LoginController as EmployeeLoginController;
use App\Http\Controllers\Employee\RegisterCustomer;
use App\Http\Controllers\Manager\Auth\LoginController as ManagerLoginController;
use App\Http\Controllers\Manager\Branches\BranchController;
use App\Http\Controllers\Manager\Branches\BranchesListing;
use App\Http\Controllers\Manager\Brand\BrandController;
use App\Http\Controllers\Manager\Employee\EmployeeController;
use App\Http\Controllers\Manager\Model\ModelController;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Manager;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('components.navigation.landing')->with([
        'branches' => Branch::all(),
    ]);
})->name('home');

Route::middleware('auth:customer')->group(function () {
    Route::get('/a/a', static function () {
        return 'hola a';
    })->name('a.a');
    Route::get('/a/d', static function () {
        return 'hola d';
    })->name('a.d');

    Route::get('/a/c', static function () {
        Auth::guard('customer')->logout();

        return 'Hola c ';
    })->name('a.c');
});

Route::get('/a/login', static function () {
    $res = Auth::guard('customer')->attempt(['email' => 'gerard33@example.net', 'password' => 'password']);

    return 'Hola login '.Auth::guard('customer')->user()->person->name;
})->name('a.login');

Route::get('/a/test', static function () {
    $a = Auth::guard('customer')->check();
    $b = Auth::guard('manager')->check();
    $c = Auth::guard('employee')->check();

    return 'Hola test';
});

Route::get('/map', static function () {
    $data       = GoogleMaps::searchGoogleMapsAutocomplete('16 1428');
    $details    = GoogleMaps::searchGoogleMapsPlaceDetails($data['predictions'][0]['place_id']);
    $components = GoogleMaps::getAddressCoordinates($details);

    dd($data, $details, $components);
});

Route::get('cat/{cat:slug}', static function (Category $cat) {
    dd(Product::get_all_by_category($cat)->get()->toArray());
})->name('category.index');

// region Rutas del manager
Route::group(['prefix' => 'manager', 'as' => 'manager.'], static function () {
    Route::get('/login', [ManagerLoginController::class, 'showLoginForm'])
        ->name('login')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::post('/login', [ManagerLoginController::class, 'loginAttempt'])
        ->name('login.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::get('/verify-token/{manager}', [ManagerLoginController::class, 'showTokenForm'])
        ->name('verify-token')->middleware(['guest:customer', 'guest:employee', 'guest:manager', 'signed']);
    Route::post('/verify-token/{manager}', [ManagerLoginController::class, 'tokenAttempt'])
        ->name('verify-token.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::group(['middleware' => 'auth:manager'], static function () {
        Route::get('/dashboard', static function () {
            return view('manager.dashboard');
        })->name('dashboard');
        Route::get('/logout', static function () {
            Auth::guard('manager')->logout();

            return redirect()->to(route('manager.login'));
        })->name('logout');

        // Esta ruta no va acá
        // Route::get('/viewBranches', [BranchesListing::class, '__invoke'])->name('branches.index');
        Route::resource('employee', EmployeeController::class);
        Route::resource('brand', BrandController::class);
        Route::resource('model', ModelController::class);
        Route::resource('branch', BranchController::class);
    });
});
// endregion

// region Rutas del empleado
Route::group(['prefix' => 'employee', 'as' => 'employee.'], static function () {
    Route::get('/login', [EmployeeLoginController::class, 'showLoginForm'])
        ->name('login')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::post('/login', [EmployeeLoginController::class, 'loginAttempt'])
        ->name('login.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::group(['middleware' => 'auth:employee'], static function () {
        Route::get('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');

        Route::get('/customer', [RegisterCustomer::class, 'create'])->name('register_customer');
        Route::post('/customer', [RegisterCustomer::class, 'store']);
    });
});
// endregion

// region Rutas del cliente
Route::group(['prefix' => 'customer', 'as' => 'customer.'], static function () {
    Route::get('/login', [CustomerLoginController::class, 'showLoginForm'])
        ->name('login')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::post('/login', [CustomerLoginController::class, 'loginAttempt'])
        ->name('login.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::group(['middleware' => 'auth:customer'], static function () {
        Route::get('/logout', [CustomerLoginController::class, 'logout'])->name('logout');
    });
});
// endregion
