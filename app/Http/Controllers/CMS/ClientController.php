<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Dog;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Client::with('dogs');

        // Name search filter
        if ($request->filled('name_search')) {
            $searchTerm = $request->name_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Email search filter
        if ($request->filled('email_search')) {
            $searchTerm = $request->email_search;
            $query->where('email', 'LIKE', "%{$searchTerm}%");
        }

        // Location search filter
        if ($request->filled('location_search')) {
            $searchTerm = $request->location_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('address', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('city', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('state', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('zipcode', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Dog name search filter
        if ($request->filled('dog_name_search')) {
            $searchTerm = $request->dog_name_search;
            $query->whereHas('dogs', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Dog breed search filter
        if ($request->filled('dog_breed_search')) {
            $searchTerm = $request->dog_breed_search;
            $query->whereHas('dogs', function ($q) use ($searchTerm) {
                $q->where('breed', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Sorting
        switch ($request->get('sort', 'name_asc')) {
            case 'name_desc':
                $query->orderBy('first_name', 'desc')->orderBy('last_name', 'desc');
                break;
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
            case 'dogs_desc':
                $query->withCount('dogs')->orderBy('dogs_count', 'desc');
                break;
            case 'dogs_asc':
                $query->withCount('dogs')->orderBy('dogs_count', 'asc');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default: // name_asc
                $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc');
                break;
        }

        $clients = $query->paginate(10);
        
        return view('cms.modules.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cms.modules.clients.create');
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'reminder' => 'nullable|string|max:255',
            'dogs' => 'required|array|min:1',
            'dogs.*.name' => 'required|string|max:255',
            'dogs.*.breed' => 'nullable|string|max:255',
            'dogs.*.age' => 'nullable|numeric|min:0|max:30',
            'dogs.*.gender' => 'nullable|in:Male,Female',
            'dogs.*.weight' => 'nullable|numeric|min:0|max:500',
            'dogs.*.coat_type' => 'nullable|string|max:255',
            'dogs.*.spayed_neutered' => 'nullable|in:Yes,No,Unknown',
            'dogs.*.behavior' => 'nullable|string',
            'dogs.*.notes' => 'nullable|string',
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.max' => 'Phone number may not be greater than 20 characters.',
            'address.max' => 'Address may not be greater than 500 characters.',
            'city.max' => 'City may not be greater than 100 characters.',
            'state.max' => 'State may not be greater than 100 characters.',
            'zipcode.max' => 'Zipcode may not be greater than 20 characters.',
            'reminder.max' => 'Reminder may not be greater than 255 characters.',
            'dogs.required' => 'At least one dog is required.',
            'dogs.min' => 'At least one dog is required.',
            'dogs.*.name.required' => 'Dog name is required.',
            'dogs.*.age.numeric' => 'Age must be a number.',
            'dogs.*.age.min' => 'Age cannot be negative.',
            'dogs.*.age.max' => 'Age cannot exceed 30 years.',
            'dogs.*.weight.numeric' => 'Weight must be a number.',
            'dogs.*.weight.min' => 'Weight cannot be negative.',
            'dogs.*.weight.max' => 'Weight cannot exceed 500 lbs.',
        ]);

        // Use database transaction to ensure atomicity
        try {
            \DB::beginTransaction();

            // Create the client
            $client = new Client();
            $client->first_name = $request->first_name;
            $client->last_name = $request->last_name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->address = $request->address;
            $client->city = $request->city;
            $client->state = $request->state;
            $client->zipcode = $request->zipcode;
            $client->notes = $request->notes;
            $client->reminder = $request->reminder;

            $client->save();

            // Create the dogs
            $dogsCreated = 0;
            foreach ($request->dogs as $dogData) {
                if (!empty($dogData['name'])) {
                    $dog = new Dog();
                    $dog->client_id = $client->id;
                    $dog->name = $dogData['name'];
                    $dog->breed = $dogData['breed'] ?? null;
                    $dog->age = $dogData['age'] ?? null;
                    $dog->gender = $dogData['gender'] ?? null;
                    $dog->weight = $dogData['weight'] ?? null;
                    $dog->coat_type = $dogData['coat_type'] ?? null;
                    $dog->spayed_neutered = $dogData['spayed_neutered'] ?? null;
                    $dog->behavior = $dogData['behavior'] ?? null;
                    $dog->notes = $dogData['notes'] ?? null;
                    $dog->save();
                    $dogsCreated++;
                }
            }

            // Verify at least one dog was created
            if ($dogsCreated === 0) {
                throw new \Exception('At least one dog must be created.');
            }

            \DB::commit();

            return redirect()->route('clients.index')->with('success', 'Client and dogs created successfully.');

        } catch (\Exception $e) {
            \DB::rollback();
            
            // Return with error message and old input
            return back()
                ->withInput()
                ->withErrors(['general' => 'Failed to create client and dogs: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with(['dogs', 'appointments', 'subscriptions'])->findOrFail($id);
        
        return view('cms.modules.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::with('dogs')->findOrFail($id);

        return view('cms.modules.clients.edit', compact('client'));
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'reminder' => 'nullable|string|max:255',
            'dogs' => 'required|array|min:1',
            'dogs.*.name' => 'required|string|max:255',
            'dogs.*.breed' => 'nullable|string|max:255',
            'dogs.*.age' => 'nullable|numeric|min:0|max:30',
            'dogs.*.gender' => 'nullable|in:Male,Female',
            'dogs.*.weight' => 'nullable|numeric|min:0|max:500',
            'dogs.*.coat_type' => 'nullable|string|max:255',
            'dogs.*.spayed_neutered' => 'nullable|in:Yes,No,Unknown',
            'dogs.*.behavior' => 'nullable|string',
            'dogs.*.notes' => 'nullable|string',
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.max' => 'Phone number may not be greater than 20 characters.',
            'address.max' => 'Address may not be greater than 500 characters.',
            'city.max' => 'City may not be greater than 100 characters.',
            'state.max' => 'State may not be greater than 100 characters.',
            'zipcode.max' => 'Zipcode may not be greater than 20 characters.',
            'reminder.max' => 'Reminder may not be greater than 255 characters.',
            'dogs.required' => 'At least one dog is required.',
            'dogs.min' => 'At least one dog is required.',
            'dogs.*.name.required' => 'Dog name is required.',
            'dogs.*.age.numeric' => 'Age must be a number.',
            'dogs.*.age.min' => 'Age cannot be negative.',
            'dogs.*.age.max' => 'Age cannot exceed 30 years.',
            'dogs.*.weight.numeric' => 'Weight must be a number.',
            'dogs.*.weight.min' => 'Weight cannot be negative.',
            'dogs.*.weight.max' => 'Weight cannot exceed 500 lbs.',
        ]);

        // Use database transaction to ensure atomicity
        try {
            \DB::beginTransaction();

            $client = Client::findOrFail($id);
            $client->first_name = $request->first_name;
            $client->last_name = $request->last_name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->address = $request->address;
            $client->city = $request->city;
            $client->state = $request->state;
            $client->zipcode = $request->zipcode;
            $client->notes = $request->notes;
            $client->reminder = $request->reminder;

            $client->save();

            // Update existing dogs and create new ones
            $existingDogIds = [];
            $dogsUpdated = 0;
            
            foreach ($request->dogs as $dogData) {
                if (!empty($dogData['name'])) {
                    if (isset($dogData['id'])) {
                        // Update existing dog
                        $dog = Dog::where('id', $dogData['id'])->where('client_id', $client->id)->first();
                        if ($dog) {
                            $dog->name = $dogData['name'];
                            $dog->breed = $dogData['breed'] ?? null;
                            $dog->age = $dogData['age'] ?? null;
                            $dog->gender = $dogData['gender'] ?? null;
                            $dog->weight = $dogData['weight'] ?? null;
                            $dog->coat_type = $dogData['coat_type'] ?? null;
                            $dog->spayed_neutered = $dogData['spayed_neutered'] ?? null;
                            $dog->behavior = $dogData['behavior'] ?? null;
                            $dog->notes = $dogData['notes'] ?? null;
                            $dog->save();
                            $existingDogIds[] = $dog->id;
                            $dogsUpdated++;
                        }
                    } else {
                        // Create new dog
                        $dog = new Dog();
                        $dog->client_id = $client->id;
                        $dog->name = $dogData['name'];
                        $dog->breed = $dogData['breed'] ?? null;
                        $dog->age = $dogData['age'] ?? null;
                        $dog->gender = $dogData['gender'] ?? null;
                        $dog->weight = $dogData['weight'] ?? null;
                        $dog->coat_type = $dogData['coat_type'] ?? null;
                        $dog->spayed_neutered = $dogData['spayed_neutered'] ?? null;
                        $dog->behavior = $dogData['behavior'] ?? null;
                        $dog->notes = $dogData['notes'] ?? null;
                        $dog->save();
                        $existingDogIds[] = $dog->id;
                        $dogsUpdated++;
                    }
                }
            }

            // Verify at least one dog was updated/created
            if ($dogsUpdated === 0) {
                throw new \Exception('At least one dog must be updated or created.');
            }

            // Delete dogs that were removed from the form
            $client->dogs()->whereNotIn('id', $existingDogIds)->delete();

            \DB::commit();

            return redirect()->route('clients.index')->with('success', 'Client and dogs updated successfully.');

        } catch (\Exception $e) {
            \DB::rollback();
            
            // Return with error message and old input
            return back()
                ->withInput()
                ->withErrors(['general' => 'Failed to update client and dogs: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        // Check if client has any dogs, appointments, or subscriptions
        if ($client->dogs()->count() > 0) {
            return redirect()->route('clients.index')->with('error', 'Cannot delete client. They have dogs registered.');
        }

        if ($client->appointments()->count() > 0) {
            return redirect()->route('clients.index')->with('error', 'Cannot delete client. They have appointments.');
        }

        if ($client->subscriptions()->count() > 0) {
            return redirect()->route('clients.index')->with('error', 'Cannot delete client. They have active subscriptions.');
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
