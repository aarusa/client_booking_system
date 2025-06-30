<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dog;
use App\Models\Client;

class DogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dogs = Dog::with('client')->get();
        
        return view('cms.modules.dogs.index', compact('dogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        
        return view('cms.modules.dogs.create', compact('clients'));
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
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:30',
            'gender' => 'nullable|in:male,female',
            'weight' => 'nullable|numeric|min:0|max:200',
            'coat_type' => 'nullable|in:short,medium,long,curly,wire',
            'spayed_neutered' => 'nullable|in:yes,no,unknown',
            'behavior' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'Selected client is invalid.',
            'name.required' => 'Dog name is required.',
            'age.integer' => 'Age must be a whole number.',
            'age.min' => 'Age cannot be negative.',
            'age.max' => 'Age cannot exceed 30 years.',
            'gender.in' => 'Gender must be male or female.',
            'weight.numeric' => 'Weight must be a number.',
            'weight.min' => 'Weight cannot be negative.',
            'weight.max' => 'Weight cannot exceed 200 lbs.',
            'coat_type.in' => 'Please select a valid coat type.',
            'spayed_neutered.in' => 'Please select a valid option.',
        ]);

        $dog = new Dog();
        $dog->client_id = $request->client_id;
        $dog->name = $request->name;
        $dog->breed = $request->breed;
        $dog->age = $request->age;
        $dog->gender = $request->gender;
        $dog->weight = $request->weight;
        $dog->coat_type = $request->coat_type;
        $dog->spayed_neutered = $request->spayed_neutered;
        $dog->behavior = $request->behavior;
        $dog->tags = $request->tags;
        $dog->notes = $request->notes;

        $dog->save();

        return redirect()->route('dogs.index')->with('success', 'Dog registered successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dog = Dog::with(['client', 'appointments'])->findOrFail($id);
        
        return view('cms.modules.dogs.show', compact('dog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dog = Dog::findOrFail($id);
        $clients = Client::all();

        return view('cms.modules.dogs.edit', compact('dog', 'clients'));
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
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:30',
            'gender' => 'nullable|in:male,female',
            'weight' => 'nullable|numeric|min:0|max:200',
            'coat_type' => 'nullable|in:short,medium,long,curly,wire',
            'spayed_neutered' => 'nullable|in:yes,no,unknown',
            'behavior' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'Selected client is invalid.',
            'name.required' => 'Dog name is required.',
            'age.integer' => 'Age must be a whole number.',
            'age.min' => 'Age cannot be negative.',
            'age.max' => 'Age cannot exceed 30 years.',
            'gender.in' => 'Gender must be male or female.',
            'weight.numeric' => 'Weight must be a number.',
            'weight.min' => 'Weight cannot be negative.',
            'weight.max' => 'Weight cannot exceed 200 lbs.',
            'coat_type.in' => 'Please select a valid coat type.',
            'spayed_neutered.in' => 'Please select a valid option.',
        ]);

        $dog = Dog::findOrFail($id);
        $dog->client_id = $request->client_id;
        $dog->name = $request->name;
        $dog->breed = $request->breed;
        $dog->age = $request->age;
        $dog->gender = $request->gender;
        $dog->weight = $request->weight;
        $dog->coat_type = $request->coat_type;
        $dog->spayed_neutered = $request->spayed_neutered;
        $dog->behavior = $request->behavior;
        $dog->tags = $request->tags;
        $dog->notes = $request->notes;

        $dog->save();

        return redirect()->route('dogs.index')->with('success', 'Dog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dog = Dog::findOrFail($id);

        // Check if dog has any appointments
        if ($dog->appointments()->count() > 0) {
            return redirect()->route('dogs.index')->with('error', 'Cannot delete dog. They have appointments scheduled.');
        }

        $dog->delete();

        return redirect()->route('dogs.index')->with('success', 'Dog deleted successfully.');
    }
}
