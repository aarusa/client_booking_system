<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServicePrice;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::with('prices')->get();
        
        return view('cms.modules.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cms.modules.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'prices' => 'required|array',
            'prices.small' => 'required|numeric|min:0',
            'prices.medium' => 'required|numeric|min:0',
            'prices.large' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Service name is required.',
            'name.unique' => 'This service name already exists.',
            'duration.required' => 'Duration is required.',
            'duration.min' => 'Duration must be at least 1 minute.',
            'prices.required' => 'Pricing information is required.',
            'prices.small.required' => 'Small dog price is required.',
            'prices.medium.required' => 'Medium dog price is required.',
            'prices.large.required' => 'Large dog price is required.',
            'prices.small.numeric' => 'Small dog price must be a number.',
            'prices.medium.numeric' => 'Medium dog price must be a number.',
            'prices.large.numeric' => 'Large dog price must be a number.',
        ]);

        $service = new Service();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->duration = $request->duration;
        $service->is_active = $request->has('is_active');
        $service->save();

        // Create service prices
        $prices = $request->prices;
        foreach ($prices as $dogSize => $price) {
            ServicePrice::create([
                'service_id' => $service->id,
                'dog_size' => $dogSize,
                'price' => $price,
            ]);
        }

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::with('prices')->findOrFail($id);
        
        return view('cms.modules.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::with('prices')->findOrFail($id);
        
        // Organize prices by dog size
        $prices = [];
        foreach ($service->prices as $price) {
            $prices[$price->dog_size] = $price->price;
        }

        return view('cms.modules.services.edit', compact('service', 'prices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $id,
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'prices' => 'required|array',
            'prices.small' => 'required|numeric|min:0',
            'prices.medium' => 'required|numeric|min:0',
            'prices.large' => 'required|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->name = $request->name;
        $service->description = $request->description;
        $service->duration = $request->duration;
        $service->is_active = $request->has('is_active');
        $service->save();

        // Update service prices
        $prices = $request->prices;
        foreach ($prices as $dogSize => $price) {
            ServicePrice::updateOrCreate(
                [
                    'service_id' => $service->id,
                    'dog_size' => $dogSize,
                ],
                [
                    'price' => $price,
                ]
            );
        }

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // Check if service is used in appointments
        if ($service->appointments()->count() > 0) {
            return redirect()->route('services.index')->with('error', 'Cannot delete service. It is being used in appointments.');
        }

        // Delete associated prices
        $service->prices()->delete();
        
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
} 