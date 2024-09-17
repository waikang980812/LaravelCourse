<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingController extends Controller
{

    // use AuthorizesRequests;

    // public function __construct(){
    //     $this->authorizeResource(Listing::class,'listing');
    // }


    // public function __construct() // defining middleware in controller
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filters = $request->only([
            'priceFrom', 'priceTo', 'beds', 'baths', 'areaFrom', 'areaTo',
        ]);


        Gate::authorize('viewAny', Listing::class);
        return inertia('Listing/Index', [
            'filters' => $filters,
            'listings' => Listing::mostRecent()
                ->filter($filters)
                ->withoutSold()
                ->paginate(10)
                ->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Listing::class);
        return inertia('Listing/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // dd($request->beds);

        Gate::authorize('create', Listing::class);
        $request->user()->listings()->create([
            ...$request->all(),
            ...$request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|min:1|max:20000000',
            ])
        ]);

        return redirect()->route('listing.index')->with('success', 'Listing was created!');
    }


    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     return inertia('Listing/Show',['listing' => Listing::find($id)]);
    // }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        // if (Auth::user()->cannot('view', $listing)) {
        //     abort(403);
        // }

        Gate::authorize('view', $listing);
        $listing->load(['images']);
        $offer = !Auth::user() ? null : $listing->offers()->byMe()->first();
        return inertia('Listing/Show', ['listing' => $listing, 'offerMade' => $offer]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        Gate::authorize('update', $listing);
        return inertia('Listing/Edit', ['listing' => $listing]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        Gate::authorize('update', $listing);
        $listing->update([
            ...$request->all(),
            ...$request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|min:1|max:20000000',
            ])
        ]);

        return redirect()->route('listing.index')->with('success', 'Listing updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Listing $listing)
    // {
    //     Gate::authorize('delete', $listing);
    //     $listing->delete();

    //     return redirect()->back()->with('success', 'Listing was deleted!');
    // }
}
