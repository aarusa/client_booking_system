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
    public function index()
    {
        $appointments = Appointment::with(['client', 'dog', 'services'])->orderBy('appointment_date', 'desc')->get();
        
        return view('cms.modules.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::with('dogs')->get();
        
        return view('cms.modules.appointments.create', compact('clients'));
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

        // Define services array
        $services = [
            1 => ['name' => 'Basic Grooming', 'price' => 45.00],
            2 => ['name' => 'Full Grooming', 'price' => 75.00],
            3 => ['name' => 'Nail Trim', 'price' => 15.00],
            4 => ['name' => 'Ear Cleaning', 'price' => 12.00],
            5 => ['name' => 'De-shedding Treatment', 'price' => 40.00],
            6 => ['name' => 'Puppy Grooming', 'price' => 35.00],
        ];

        $appointment = new Appointment();
        $appointment->client_id = $request->client_id;
        $appointment->dog_id = $request->dog_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->start_time = $startDateTime;
        $appointment->end_time = $endDateTime;
        $appointment->status = 'scheduled';
        $appointment->notes = $request->notes;

        // Calculate total price
        $totalPrice = 0;
        foreach ($request->services as $serviceId) {
            if (isset($services[$serviceId])) {
                $totalPrice += $services[$serviceId]['price'];
            }
        }
        $appointment->total_price = $totalPrice;

        $appointment->save();

        // Store services as JSON in the appointment
        $appointment->services_data = json_encode($request->services);
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

        // Define services array
        $services = [
            1 => ['name' => 'Basic Grooming', 'price' => 45.00],
            2 => ['name' => 'Full Grooming', 'price' => 75.00],
            3 => ['name' => 'Nail Trim', 'price' => 15.00],
            4 => ['name' => 'Ear Cleaning', 'price' => 12.00],
            5 => ['name' => 'De-shedding Treatment', 'price' => 40.00],
            6 => ['name' => 'Puppy Grooming', 'price' => 35.00],
        ];

        $appointment->client_id = $request->client_id;
        $appointment->dog_id = $request->dog_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->start_time = $startDateTime;
        $appointment->end_time = $endDateTime;
        $appointment->status = $request->status;
        $appointment->notes = $request->notes;

        // Calculate total price
        $totalPrice = 0;
        foreach ($request->services as $serviceId) {
            if (isset($services[$serviceId])) {
                $totalPrice += $services[$serviceId]['price'];
            }
        }
        $appointment->total_price = $totalPrice;

        // Store services as JSON in the appointment
        $appointment->services_data = json_encode($request->services);
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
        $dogs = Dog::where('client_id', $clientId)->get();
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
}
