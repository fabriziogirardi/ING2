<?php

use App\Facades\GoogleMaps;
use App\Http\Controllers\Manager\Auth\LoginController;
use App\Http\Controllers\Manager\Auth\VerifyTokenController;
use App\Http\Controllers\Manager\Employee\EmployeeController;
use App\Http\Controllers\Manager\Brand\BrandController;
use App\Http\Controllers\Manager\Model\ModelController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('landing.landing');
});

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
        return view('manager.login');
    })->name('login');

    Route::post('/login', LoginController::class)->name('login.post');

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

        Route::resource('employee', EmployeeController::class);

        Route::group(['prefix' => 'brand', 'as' => 'product.brand.'], static function () {
            Route::get('/', static function () {
                return view('manager.product.brand');
            })->name('index');

            Route::post('/', [BrandController::class, 'store'])->name('store');

            Route::put('/{brand}', [BrandController::class, 'update'])->name('update');

            Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'model', 'as' => 'product.model.'], static function () {
            Route::get('/', static function () {
                return view('manager.product.model');
            })->name('index');

            Route::post('/', [\App\Http\Controllers\Manager\Model\ModelController::class, 'store'])->name('store');

            Route::put('/{model}', [\App\Http\Controllers\Manager\Model\ModelController::class, 'update'])->name('update');

            Route::delete('/{model}', [\App\Http\Controllers\Manager\Model\ModelController::class, 'destroy'])->name('destroy');
        });

        Route::get('/viewBranches', [\App\Http\Controllers\Manager\Branches\BranchesListing::class, '__invoke'])->name('branches.index');
    });
});

Route::resources([
    'model' => ModelController::class,
]);
// endregion
