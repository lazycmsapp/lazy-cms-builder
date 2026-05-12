<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\FieldGroup;
use Acme\CmsDashboard\Models\Field;
use Acme\CmsDashboard\Models\PostType;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index()
    {
        $fieldGroups = FieldGroup::withCount('fields')->orderBy('order')->get();
        return view('cms-dashboard::admin.acpt.fields.index', compact('fieldGroups'));
    }

    public function create()
    {
        $postTypes = PostType::where('is_active', true)->get();
        return view('cms-dashboard::admin.acpt.fields.create', compact('postTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'rules' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $group = FieldGroup::create([
            'title' => $validated['title'],
            'rules' => $request->rules,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        if ($request->has('fields')) {
            $order = 0;
            foreach ($request->fields as $fieldData) {
                if (empty($fieldData['label']) || empty($fieldData['name'])) continue;
                Field::create([
                    'field_group_id' => $group->id,
                    'label' => $fieldData['label'],
                    'name' => $fieldData['name'],
                    'type' => $fieldData['type'] ?? 'text',
                    'instructions' => $fieldData['instructions'] ?? null,
                    'required' => isset($fieldData['required']),
                    'order' => $order++,
                ]);
            }
        }

        return redirect()->route('admin.acpt.fields.index')->with('success', 'Field group and fields created successfully.');
    }

    public function edit(FieldGroup $field)
    {
        $field->load('fields');
        $postTypes = PostType::where('is_active', true)->get();
        return view('cms-dashboard::admin.acpt.fields.edit', ['fieldGroup' => $field, 'postTypes' => $postTypes]);
    }

    public function update(Request $request, FieldGroup $field)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $field->update([
            'title' => $request->title,
            'rules' => $request->rules,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        // Update existing fields
        if ($request->has('fields')) {
            foreach ($request->fields as $id => $data) {
                Field::where('id', $id)->update([
                    'label' => $data['label'],
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'required' => isset($data['required']),
                ]);
            }
        }

        // Create new fields added during this edit session
        if ($request->has('new_fields')) {
            foreach ($request->new_fields as $data) {
                if (empty($data['label']) || empty($data['name'])) continue;
                Field::create([
                    'field_group_id' => $field->id,
                    'label' => $data['label'],
                    'name' => $data['name'],
                    'type' => $data['type'] ?? 'text',
                    'required' => isset($data['required']),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Field group updated successfully.');
    }

    public function destroy(FieldGroup $field)
    {
        $field->delete();
        return redirect()->route('admin.acpt.fields.index')->with('success', 'Field group deleted.');
    }

    // AJAX store/delete for individual fields
    public function storeField(Request $request)
    {
        $request->validate([
            'field_group_id' => 'required|exists:custom_field_groups,id',
            'label' => 'required|string',
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $field = Field::create($request->all());
        return response()->json(['success' => true, 'field' => $field]);
    }

    public function deleteField(Field $field)
    {
        $field->delete();
        return response()->json(['success' => true]);
    }
}
