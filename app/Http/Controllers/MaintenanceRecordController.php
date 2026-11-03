<?php

namespace App\Http\Controllers;

use App\Models\AssetDispatch;
use App\Models\FilmAsset;
use App\Models\Production;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Inertia\Inertia;

class AssetDispatchController extends Controller
{
    public function index()
    {
        $assetDispatches = AssetDispatch::with([
            'filmAsset',
            'production',
            'technician',
        ])->latest()->get();

        return Inertia::render('AssetDispatches/Index', [
            'assetDispatches' => $assetDispatches,
        ]);
    }

    public function create()
    {
        return Inertia::render('AssetDispatches/Create', [
            'filmAssets' => FilmAsset::where('status', 'Available')->get(),
            'productions' => Production::all(),
            'technicians' => Technician::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_asset_id' => 'required|exists:film_assets,id',
            'production_id' => 'required|exists:productions,id',
            'technician_id' => 'required|exists:technicians,id',
            'checkout_date' => 'required|date',
            'expected_return' => 'required|date|after_or_equal:checkout_date',
        ]);

        DB::transaction(function () use ($validated) {
            $filmAsset = FilmAsset::whereKey($validated['film_asset_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($filmAsset->status !== 'Available') {
                throw ValidationException::withMessages([
                    'film_asset_id' => 'This equipment is currently unavailable.',
                ]);
            }

            $rentalDays = max(
                1,
                (int) Carbon::parse($validated['checkout_date'])
                    ->diffInDays(Carbon::parse($validated['expected_return']))
            );

            $totalRentalFee = $filmAsset->daily_rate * $rentalDays;

            AssetDispatch::create([
                'film_asset_id' => $filmAsset->id,
                'production_id' => $validated['production_id'],
                'technician_id' => $validated['technician_id'],
                'checkout_date' => $validated['checkout_date'],
                'expected_return' => $validated['expected_return'],
                'rental_days' => $rentalDays,
                'total_rental_fee' => $totalRentalFee,
            ]);

            $filmAsset->update([
                'status' => 'Dispatched',
            ]);
        });

        return redirect()
            ->route('asset-dispatches.index')
            ->with('success', 'Equipment successfully dispatched.');
    }

    public function show(AssetDispatch $assetDispatch)
    {
        $assetDispatch->load([
            'filmAsset',
            'production',
            'technician',
        ]);

        return Inertia::render('AssetDispatches/Show', [
            'assetDispatch' => $assetDispatch,
        ]);
    }

    public function edit(AssetDispatch $assetDispatch)
    {
        return Inertia::render('AssetDispatches/Edit', [
            'assetDispatch' => $assetDispatch->load([
                'filmAsset',
                'production',
                'technician',
            ]),
            'productions' => Production::all(),
            'technicians' => Technician::all(),
        ]);
    }

    public function update(Request $request, AssetDispatch $assetDispatch)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'expected_return' => [
                'required',
                'date',
                'after_or_equal:' . $assetDispatch->checkout_date->toDateString(),
            ],
        ]);

        $assetDispatch->update($validated);

        return redirect()
            ->route('asset-dispatches.index')
            ->with('success', 'Dispatch successfully updated.');
    }

    public function destroy(AssetDispatch $assetDispatch)
    {
        DB::transaction(function () use ($assetDispatch) {
            if (!$assetDispatch->actual_return) {
                $assetDispatch->filmAsset()->update([
                    'status' => 'Available',
                ]);
            }

            $assetDispatch->delete();
        });

        return redirect()
            ->route('asset-dispatches.index')
            ->with('success', 'Dispatch successfully deleted.');
    }

    public function returnEquipment(AssetDispatch $assetDispatch)
    {
        if ($assetDispatch->actual_return) {
            return back()->withErrors([
                'actual_return' => 'This equipment has already been returned.',
            ]);
        }

        DB::transaction(function () use ($assetDispatch) {
            $assetDispatch->update([
                'actual_return' => now()->toDateString(),
            ]);

            $assetDispatch->filmAsset()->update([
                'status' => 'Available',
            ]);
        });

        return redirect()
            ->route('asset-dispatches.index')
            ->with('success', 'Equipment successfully returned.');
    }
}