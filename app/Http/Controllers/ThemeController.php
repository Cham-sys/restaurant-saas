<?php

namespace App\Http\Controllers;

use App\Models\RestaurantThemeSetting;
use App\Models\Theme;
use App\Services\ThemeUploadService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $themes = Theme::latest()->paginate(10);

        return view('themes.index', compact('themes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('themes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:themes,slug',
            'description' => 'nullable|string|max:1000',
            'preview_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('preview_image')) {
            $validated['preview_image'] = $request->file('preview_image')->store('themes', 'public');
        }

        Theme::create($validated);

        return redirect()->route('themes.index')->with('success', 'Theme created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme)
    {
        return view('themes.show', compact('theme'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme)
    {
        return view('themes.edit', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Theme $theme)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:themes,slug,' . $theme->id,
            'description' => 'nullable|string|max:1000',
            'preview_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('preview_image')) {
            $validated['preview_image'] = $request->file('preview_image')->store('themes', 'public');
        }

        $theme->update($validated);

        return redirect()->route('themes.index')->with('success', 'Theme updated successfully!');
    }

    public function activate(Theme $theme)
    {
        $theme->activate();

        return redirect()->back()->with('success', 'تم تفعيل الثيم بنجاح.');
    }

    public function clone(Request $request, Theme $theme): \Illuminate\Http\RedirectResponse
    {
        $name = trim((string) $request->input('name', $theme->name.' نسخة'));
        $result = app(ThemeUploadService::class)->cloneTheme($theme, $name);

        if (! $result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', 'تم نسخ الثيم بنجاح.');
    }

    public function resetSettings(Theme $theme)
    {
        RestaurantThemeSetting::query()->where('theme_id', $theme->id)->delete();

        return redirect()->back()->with('success', 'تمت إعادة تعيين إعدادات الثيم إلى القيم الافتراضية.');
    }

    public function preview(Theme $theme): View
    {
        $settings = $theme->default_settings ?? [];

        return view('themes.preview', compact('theme', 'settings'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        if ($theme->restaurants()->count() > 0) {
            return redirect()->route('themes.index')->with('error', 'Cannot delete theme that is in use by restaurants!');
        }

        $theme->delete();

        return redirect()->route('themes.index')->with('success', 'Theme deleted successfully!');
    }
}
