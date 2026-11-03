<?php

namespace App\Http\Controllers;

use App\Models\FilmAsset;
use App\Models\AssetDispatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FilmAssetController extends Controller
{
    public function index()
    {
        $filmAssets = FilmAsset::latest()->get();

        return Inertia::render('FilmAssets/Index', [
            'filmAssets' => $filmAssets,
        ]);
    }

    public function create()
    {
        return Inertia::render('FilmAssets/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_tag' => 'required|string|max:255|unique:film_assets,equipment_tag',
            'equipment_category' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Dispatched',
        ]);

        FilmAsset::create($validated);

        return redirect()
            ->route('film-assets.index')
            ->with('success', 'Film asset successfully created.');
    }

    public function show(FilmAsset $filmAsset)
    {
        return Inertia::render('FilmAssets/Show', [
            'filmAsset' => $filmAsset,
        ]);
    }

    public function edit(FilmAsset $filmAsset)
    {
        return Inertia::render('FilmAssets/Edit', [
            'filmAsset' => $filmAsset,
        ]);
    }

    public function update(Request $request, FilmAsset $filmAsset)
    {
        $validated = $request->validate([
            'equipment_tag' => 'required|string|max:255|unique:film_assets,equipment_tag,' . $filmAsset->id,
            'equipment_category' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Dispatched',
        ]);

        $filmAsset->update($validated);

        return redirect()
            ->route('film-assets.index')
            ->with('success', 'Film asset successfully updated.');
    }

    public function destroy(FilmAsset $filmAsset)
    {
        $hasDispatches = AssetDispatch::where(
            'film_asset_id',
            $filmAsset->id
        )->exists();

        if ($hasDispatches) {
            return back()->withErrors([
                'filmAsset' => 'Cannot delete equipment with existing dispatch records.',
            ]);
        }

        $filmAsset->delete();

        return redirect()
            ->route('film-assets.index')
            ->with('success', 'Film asset successfully deleted.');
    }
}