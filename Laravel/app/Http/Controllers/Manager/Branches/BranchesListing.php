<?php

namespace App\Http\Controllers\Manager\Branches;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class BranchesListing extends Controller
{
    public function __invoke()
    {
        $branches = Branch::paginate(10);

        return view('branches-listing', compact('branches'));
    }
}
