<?php

namespace App\Http\Controllers\Manager\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Employee\RegisterEmployeeRequest;
use App\Models\Employee;
use App\Models\GovernmentIdType;
use App\Models\Person;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with('person')->get();

        return view('manager.employee.index', [
            'employees' => $employees,
        ]);
    }

    public function create(Request $request)
    {
        $governmentIdTypes = GovernmentIdType::all();

        return view('manager.employee.create', [
            'governmentIdTypes' => $governmentIdTypes,
        ]);
    }

    public function store(RegisterEmployeeRequest $request)
    {
        $person = Person::create([
            'first_name'            => $request->validated('first_name'),
            'last_name'             => $request->validated('last_name'),
            'email'                 => $request->validated('email'),
            'government_id_type_id' => $request->validated('government_id_type_id'),
            'government_id_number'  => $request->validated('government_id_number'),
        ]);

        Employee::create([
            'person_id' => $person->id,
            'password'  => bcrypt($request->validated('password')),
        ]);

        return redirect()->route('manager.employee.index')->with([
            'success' => __('manager.employee.created'),
        ]);
    }
}
