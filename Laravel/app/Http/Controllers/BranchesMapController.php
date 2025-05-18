<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class BranchesMapController extends Controller
{
    public function index()
    {
        $branches = Branch::all(['name','latitude','longitude','address']);

        return view('branches-map', compact('branches'));
    }
}
