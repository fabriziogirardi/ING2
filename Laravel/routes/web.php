<?php

use App\Facades\GoogleMaps;
use App\Http\Controllers\Customer\Auth\LoginController as CustomerLoginController;
use App\Http\Controllers\Employee\Auth\LoginController as EmployeeLoginController;
use App\Http\Controllers\Employee\RegisterCustomer;
use App\Http\Controllers\Manager\Auth\LoginController as ManagerLoginController;
use App\Http\Controllers\Manager\Auth\VerifyTokenController;
use App\Http\Controllers\Manager\Branches\BranchesListing;
use App\Http\Controllers\Manager\Brand\BrandController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('components.navigation.landing');
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
    Route::get('/login', static function () {
        return view('manager.Login');
    })->name('login');

    Route::post('/login', ManagerLoginController::class)->name('login.post');

    Route::get('/verify-token', static function () {
        return view('manager.verify-token');
    })->name('verify-token');
    Route::post('/verify-token', VerifyTokenController::class)->name('verify-token');

    Route::group(['middleware' => 'auth:manager'], static function () {
        Route::get('/dashboard', static function () {
            return view('manager.dashboard');
        })->name('dashboard');
        Route::get('/logout', static function () {
            Auth::guard('manager')->logout();

            return redirect()->to(route('manager.login'));
        })->name('logout');

        Route::resource('brand', BrandController::class);

        Route::get('/viewBranches', [BranchesListing::class, '__invoke'])->name('branches.index');
    });
});
// endregion

// region Rutas del empleado
Route::group(['prefix' => 'employee', 'as' => 'employee.'], static function () {
    Route::get('/login', static function () {
        return view('employee.login');
    })->name('login');

    Route::post('/login', EmployeeLoginController::class)->name('login.post');

    Route::group(['middleware' => 'auth:employee'], static function () {
        Route::get('/customer', [RegisterCustomer::class, 'create'])->name('register_customer');
        Route::post('/customer', [RegisterCustomer::class, 'store']);

        Route::get('/logout', static function () {
            Auth::guard('employee')->logout();

            return redirect()->to(route('home'));
        })->name('logout');
    });
});
// endregion

// region Rutas del cliente
Route::group(['prefix' => 'customer', 'as' => 'customer.'], static function () {
    Route::get('/login', static function () {
        return view('customer.login');
    })->name('login');

    Route::post('/login', CustomerLoginController::class)->name('login.post');

    Route::group(['middleware' => 'auth:customer'], static function () {
        Route::get('/logout', static function () {
            Auth::guard('customer')->logout();

            return redirect()->to(route('home'));
        })->name('logout');
    });
});
// endregion
