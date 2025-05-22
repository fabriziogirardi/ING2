<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class BranchesListing extends Controller
{
    public function index()
    {
        $branches = Branch::paginate(10);

        return view('branches-listing', compact('branches'));
    }
}
