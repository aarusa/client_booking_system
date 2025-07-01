<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Dog;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the active tab from request, default to 'all'
        $activeTab = $request->get('tab', 'all');
        
        // Base query
        $query = Appointment::with(['client', 'dog', 'services']);

        // Apply status filter based on active tab
        if ($activeTab !== 'all') {
            $query->where('status', $activeTab);
        }

        // Single date filter
        if ($request->filled('appointment_date')) {
            $query->where('appointment_date', $request->appointment_date);
        } else {
            // Date range filter
            if ($request->filled('date_from')) {
                $query->where('appointment_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('appointment_date', '<=', $request->date_to);
            }
        }

        // Additional status filter (for when not using tabs)
        if ($request->filled('status') && $activeTab === 'all') {
            $query->where('status', $request->status);
        }

        // Client search filter
        if ($request->filled('client_search')) {
            $searchTerm = $request->client_search;
            $query->whereHas('client', function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Location search filter
        if ($request->filled('location_search')) {
            $searchTerm = $request->location_search;
            $query->whereHas('client', function ($q) use ($searchTerm) {
                $q->where('address', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('city', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('state', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('zipcode', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Sorting
        switch ($request->get('sort', 'date_desc')) {
            case 'date_asc':
                $query->orderBy('appointment_date', 'asc')->orderBy('start_time', 'asc');
                break;
            case 'client_asc':
                $query->join('clients', 'appointments.client_id', '=', 'clients.id')
                      ->orderBy('clients.first_name', 'asc')
                      ->orderBy('clients.last_name', 'asc')
                      ->select('appointments.*');
                break;
            case 'client_desc':
                $query->join('clients', 'appointments.client_id', '=', 'clients.id')
                      ->orderBy('clients.first_name', 'desc')
                      ->orderBy('clients.last_name', 'desc')
                      ->select('appointments.*');
                break;
            case 'price_desc':
                $query->orderBy('total_price', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('total_price', 'asc');
                break;
            default: // date_desc
                $query->orderBy('appointment_date', 'desc')->orderBy('start_time', 'desc');
                break;
        }

        $appointments = $query->paginate(10);
        
        // Get counts for each status for the tabs
        $statusCounts = [
            'all' => Appointment::count(),
            'scheduled' => Appointment::where('status', 'scheduled')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'in_progress' => Appointment::where('status', 'in_progress')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];
        
        return view('cms.modules.appointments.index', compact('appointments', 'activeTab', 'statusCounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $clients = Client::with('dogs')->get();
        $selectedClientId = $request->get('client_id');
        
        return view('cms.modules.appointments.create', compact('clients', 'selectedClientId'));
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
            'dog_id' => 'required|exists:dogs,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'services' => 'required|array|min:1',
            'services.*' => 'integer|between:1,6',
            'notes' => 'nullable|string',
            'payment_status' => 'nullable|in:pending,paid,partial,refunded',
            'payment_mode' => 'nullable|in:cash,payid,card,bank_transfer',
            'amount_paid' => 'nullable|numeric|min:0',
            'paid_at' => 'nullable|date',
        ], [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'Selected client is invalid.',
            'dog_id.required' => 'Please select a dog.',
            'dog_id.exists' => 'Selected dog is invalid.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.after_or_equal' => 'Appointment date must be today or in the future.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Start time format is invalid.',
            'end_time.required' => 'End time is required.',
            'end_time.date_format' => 'End time format is invalid.',
            'end_time.after' => 'End time must be after start time.',
            'services.required' => 'Please select at least one service.',
            'services.array' => 'Services must be selected.',
            'services.min' => 'Please select at least one service.',
            'services.*.integer' => 'Invalid service selected.',
            'services.*.between' => 'Invalid service selected.',
            'payment_status.in' => 'Invalid payment status selected.',
            'payment_mode.in' => 'Invalid payment mode selected.',
            'amount_paid.numeric' => 'Amount paid must be a valid number.',
            'amount_paid.min' => 'Amount paid cannot be negative.',
            'paid_at.date' => 'Payment date must be a valid date.',
        ]);

        // Create datetime objects for comparison
        $startDateTime = Carbon::parse($request->appointment_date . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($request->appointment_date . ' ' . $request->end_time);

        // Check for scheduling conflicts
        $conflict = Appointment::where('appointment_date', $request->appointment_date)
            ->where(function($query) use ($startDateTime, $endDateTime) {
                $query->where(function($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_time', '<=', $startDateTime)
                      ->where('end_time', '>', $startDateTime);
                })->orWhere(function($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_time', '<', $endDateTime)
                      ->where('end_time', '>=', $endDateTime);
                })->orWhere(function($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_time', '>=', $startDateTime)
                      ->where('end_time', '<=', $endDateTime);
                });
            })
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($conflict) {
            return back()->withErrors(['scheduling' => 'This time slot conflicts with an existing appointment.'])->withInput();
        }

        // Define services with variable pricing based on dog size
        $services = [
            1 => ['name' => 'Basic Grooming'],
            2 => ['name' => 'Full Grooming'],
            3 => ['name' => 'Nail Trim'],
            4 => ['name' => 'Ear Cleaning'],
            5 => ['name' => 'De-shedding Treatment'],
            6 => ['name' => 'Puppy Grooming'],
        ];

        // Get the dog to determine size for pricing
        $dog = Dog::find($request->dog_id);
        $dogSize = $dog->size ?? 'medium'; // Default to medium if size not set

        // Define pricing based on dog size
        $pricing = [
            'small' => [
                1 => 35.00, // Basic Grooming
                2 => 60.00, // Full Grooming
                3 => 12.00, // Nail Trim
                4 => 10.00, // Ear Cleaning
                5 => 30.00, // De-shedding Treatment
                6 => 25.00, // Puppy Grooming
            ],
            'medium' => [
                1 => 45.00, // Basic Grooming
                2 => 75.00, // Full Grooming
                3 => 15.00, // Nail Trim
                4 => 12.00, // Ear Cleaning
                5 => 40.00, // De-shedding Treatment
                6 => 35.00, // Puppy Grooming
            ],
            'large' => [
                1 => 55.00, // Basic Grooming
                2 => 90.00, // Full Grooming
                3 => 18.00, // Nail Trim
                4 => 15.00, // Ear Cleaning
                5 => 50.00, // De-shedding Treatment
                6 => 45.00, // Puppy Grooming
            ],
            'extra_large' => [
                1 => 65.00, // Basic Grooming
                2 => 110.00, // Full Grooming
                3 => 20.00, // Nail Trim
                4 => 18.00, // Ear Cleaning
                5 => 60.00, // De-shedding Treatment
                6 => 55.00, // Puppy Grooming
            ],
        ];

        $appointment = new Appointment();
        $appointment->client_id = $request->client_id;
        $appointment->dog_id = $request->dog_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->start_time = $startDateTime;
        $appointment->end_time = $endDateTime;
        $appointment->status = 'scheduled';
        $appointment->notes = $request->notes;

        // Payment fields
        $appointment->payment_status = $request->payment_status ?: 'pending';
        $appointment->payment_mode = $request->payment_mode;
        $appointment->amount_paid = $request->amount_paid ?: 0.00;
        $appointment->paid_at = $request->paid_at ? Carbon::parse($request->paid_at) : null;

        // Calculate total price based on dog size
        $totalPrice = 0;
        $selectedServices = [];
        foreach ($request->services as $serviceId) {
            if (isset($pricing[$dogSize][$serviceId])) {
                $price = $pricing[$dogSize][$serviceId];
                $totalPrice += $price;
                $selectedServices[] = [
                    'id' => $serviceId,
                    'name' => $services[$serviceId]['name'],
                    'price' => $price
                ];
            }
        }
        $appointment->total_price = $totalPrice;

        // Store services as JSON in the appointment with pricing info
        $appointment->services_data = json_encode($selectedServices);
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::with(['client', 'dog'])->findOrFail($id);
        
        return view('cms.modules.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::with(['client', 'dog'])->findOrFail($id);
        $clients = Client::with('dogs')->get();
        
        return view('cms.modules.appointments.edit', compact('appointment', 'clients'));
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
            'dog_id' => 'required|exists:dogs,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled',
            'services' => 'required|array|min:1',
            'services.*' => 'integer|between:1,6',
            'notes' => 'nullable|string',
            'payment_status' => 'nullable|in:pending,paid,partial,refunded',
            'payment_mode' => 'nullable|in:cash,payid,card,bank_transfer',
            'amount_paid' => 'nullable|numeric|min:0',
            'paid_at' => 'nullable|date',
        ], [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'Selected client is invalid.',
            'dog_id.required' => 'Please select a dog.',
            'dog_id.exists' => 'Selected dog is invalid.',
            'appointment_date.required' => 'Appointment date is required.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Start time format is invalid.',
            'end_time.required' => 'End time is required.',
            'end_time.date_format' => 'End time format is invalid.',
            'end_time.after' => 'End time must be after start time.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'services.required' => 'Please select at least one service.',
            'services.array' => 'Services must be selected.',
            'services.min' => 'Please select at least one service.',
            'services.*.integer' => 'Invalid service selected.',
            'services.*.between' => 'Invalid service selected.',
            'payment_status.in' => 'Invalid payment status selected.',
            'payment_mode.in' => 'Invalid payment mode selected.',
            'amount_paid.numeric' => 'Amount paid must be a valid number.',
            'amount_paid.min' => 'Amount paid cannot be negative.',
            'paid_at.date' => 'Payment date must be a valid date.',
        ]);

        $appointment = Appointment::findOrFail($id);

        // Create datetime objects for comparison
        $startDateTime = Carbon::parse($request->appointment_date . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($request->appointment_date . ' ' . $request->end_time);

        // Check for scheduling conflicts (excluding current appointment)
        $conflict = Appointment::where('appointment_date', $request->appointment_date)
            ->where('id', '!=', $id)
            ->where(function($query) use ($startDateTime, $endDateTime) {
                $query->where(function($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_time', '<=', $startDateTime)
                      ->where('end_time', '>', $startDateTime);
                })->orWhere(function($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_time', '<', $endDateTime)
                      ->where('end_time', '>=', $endDateTime);
                })->orWhere(function($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_time', '>=', $startDateTime)
                      ->where('end_time', '<=', $endDateTime);
                });
            })
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($conflict) {
            return back()->withErrors(['scheduling' => 'This time slot conflicts with an existing appointment.'])->withInput();
        }

        // Define services with variable pricing based on dog size
        $services = [
            1 => ['name' => 'Basic Grooming'],
            2 => ['name' => 'Full Grooming'],
            3 => ['name' => 'Nail Trim'],
            4 => ['name' => 'Ear Cleaning'],
            5 => ['name' => 'De-shedding Treatment'],
            6 => ['name' => 'Puppy Grooming'],
        ];

        // Get the dog to determine size for pricing
        $dog = Dog::find($request->dog_id);
        $dogSize = $dog->size ?? 'medium'; // Default to medium if size not set

        // Define pricing based on dog size
        $pricing = [
            'small' => [
                1 => 35.00, // Basic Grooming
                2 => 60.00, // Full Grooming
                3 => 12.00, // Nail Trim
                4 => 10.00, // Ear Cleaning
                5 => 30.00, // De-shedding Treatment
                6 => 25.00, // Puppy Grooming
            ],
            'medium' => [
                1 => 45.00, // Basic Grooming
                2 => 75.00, // Full Grooming
                3 => 15.00, // Nail Trim
                4 => 12.00, // Ear Cleaning
                5 => 40.00, // De-shedding Treatment
                6 => 35.00, // Puppy Grooming
            ],
            'large' => [
                1 => 55.00, // Basic Grooming
                2 => 90.00, // Full Grooming
                3 => 18.00, // Nail Trim
                4 => 15.00, // Ear Cleaning
                5 => 50.00, // De-shedding Treatment
                6 => 45.00, // Puppy Grooming
            ],
            'extra_large' => [
                1 => 65.00, // Basic Grooming
                2 => 110.00, // Full Grooming
                3 => 20.00, // Nail Trim
                4 => 18.00, // Ear Cleaning
                5 => 60.00, // De-shedding Treatment
                6 => 55.00, // Puppy Grooming
            ],
        ];

        $appointment->client_id = $request->client_id;
        $appointment->dog_id = $request->dog_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->start_time = $startDateTime;
        $appointment->end_time = $endDateTime;
        $appointment->status = $request->status;
        $appointment->notes = $request->notes;

        // Payment fields
        $appointment->payment_status = $request->payment_status ?: 'pending';
        $appointment->payment_mode = $request->payment_mode;
        $appointment->amount_paid = $request->amount_paid ?: 0.00;
        $appointment->paid_at = $request->paid_at ? Carbon::parse($request->paid_at) : null;

        // Calculate total price based on dog size
        $totalPrice = 0;
        $selectedServices = [];
        foreach ($request->services as $serviceId) {
            if (isset($pricing[$dogSize][$serviceId])) {
                $price = $pricing[$dogSize][$serviceId];
                $totalPrice += $price;
                $selectedServices[] = [
                    'id' => $serviceId,
                    'name' => $services[$serviceId]['name'],
                    'price' => $price
                ];
            }
        }
        $appointment->total_price = $totalPrice;

        // Store services as JSON in the appointment with pricing info
        $appointment->services_data = json_encode($selectedServices);
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Check if appointment can be cancelled
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.index')->with('error', 'Cannot delete completed appointments.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Get dogs for a specific client (AJAX)
     */
    public function getClientDogs($clientId)
    {
        $dogs = Dog::where('client_id', $clientId)
                   ->select('id', 'name', 'breed', 'size')
                   ->get();
        return response()->json($dogs);
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, $id)
    {
        // Check permission
        if (!auth()->user()->can('appointment-edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update appointment status.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated successfully.',
            'status' => $request->status
        ]);
    }

    /**
     * Get service prices for a specific dog size (AJAX)
     */
    public function getServicePrices($dogSize)
    {
        // Validate dog size
        $validSizes = ['small', 'medium', 'large', 'extra_large'];
        if (!in_array($dogSize, $validSizes)) {
            return response()->json(['error' => 'Invalid dog size'], 400);
        }

        // Define pricing based on dog size
        $pricing = [
            'small' => [
                1 => 35.00, // Basic Grooming
                2 => 60.00, // Full Grooming
                3 => 12.00, // Nail Trim
                4 => 10.00, // Ear Cleaning
                5 => 30.00, // De-shedding Treatment
                6 => 25.00, // Puppy Grooming
            ],
            'medium' => [
                1 => 45.00, // Basic Grooming
                2 => 75.00, // Full Grooming
                3 => 15.00, // Nail Trim
                4 => 12.00, // Ear Cleaning
                5 => 40.00, // De-shedding Treatment
                6 => 35.00, // Puppy Grooming
            ],
            'large' => [
                1 => 55.00, // Basic Grooming
                2 => 90.00, // Full Grooming
                3 => 18.00, // Nail Trim
                4 => 15.00, // Ear Cleaning
                5 => 50.00, // De-shedding Treatment
                6 => 45.00, // Puppy Grooming
            ],
            'extra_large' => [
                1 => 65.00, // Basic Grooming
                2 => 110.00, // Full Grooming
                3 => 20.00, // Nail Trim
                4 => 18.00, // Ear Cleaning
                5 => 60.00, // De-shedding Treatment
                6 => 55.00, // Puppy Grooming
            ],
        ];

        $prices = [];
        foreach ($pricing[$dogSize] as $serviceId => $price) {
            $prices[] = [
                'service_id' => $serviceId,
                'price' => $price
            ];
        }

        return response()->json($prices);
    }

}
