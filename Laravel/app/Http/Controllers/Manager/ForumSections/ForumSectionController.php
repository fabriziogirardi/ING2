<?php

namespace App\Http\Controllers\Manager\ForumSections;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ForumSection\StoreForumSectionRequest;
use App\Http\Requests\Manager\ForumSection\UpdateForumSectionRequest;
use App\Models\ForumSection;

class ForumSectionController extends Controller
{
    public function index()
    {
        return view('manager.sections.index');
    }

    public function create()
    {
        return view('manager.sections.create');
    }

    public function store(StoreForumSectionRequest $request)
    {
        // Validate and store the new forum section
        ForumSection::create([
            'name' => $request->validated('name'),
        ]);

        return redirect()->to(route('manager.sections.index'))->with('success', __('manager/section.created'));
    }

    public function edit()
    {
        // Show the form to edit an existing forum section
        return view('manager.sections.edit');
    }

    public function update(UpdateForumSectionRequest $request, ForumSection $section)
    {
        // Validate and update the existing forum section
        $section->update([
            'name' => $request->validated('name'),
        ]);

        return redirect()->to(route('manager.sections.index'))->with('success', __('manager/section.updated'));
    }

    public function destroy(ForumSection $section)
    {
        // Delete the specified forum section
        $section->delete();

        return redirect()->route('manager.sections.index')->with('success', __('manager/section.deleted'));
    }

    public function restore(string $id)
    {
        // Restore a deleted forum section
        ForumSection::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('manager.sections.index')->with('success', __('manager/section.restored'));
    }
}
