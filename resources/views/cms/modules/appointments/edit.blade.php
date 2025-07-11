@extends('cms.layouts.master')

@section('title', 'Edit Appointment')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Edit Appointment</h3>
                <h6 class="op-7 mb-2">Fill out the form to edit appointment.</h6>
            </div>
        </div>
        
        <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')
        
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="client_id">Client</label>
                                        <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required disabled>
                                            <option value="">Select Client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" 
                                                        {{ old('client_id', $appointment->client_id) == $client->id ? 'selected' : '' }}>
                                                    {{ $client->first_name }} {{ $client->last_name }} ({{ $client->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="dog_id">Dog</label>
                                        <select name="dog_id" id="dog_id" class="form-control @error('dog_id') is-invalid @enderror" required>
                                            <option value="">Select Dog</option>
                                            @foreach($clients as $client)
                                                @if($client->id == $appointment->client_id)
                                                    @foreach($client->dogs as $dog)
                                                        <option value="{{ $dog->id }}" 
                                                                data-size="{{ $dog->size ?? 'medium' }}"
                                                                {{ old('dog_id', $appointment->dog_id) == $dog->id ? 'selected' : '' }}>
                                                            {{ $dog->name }} ({{ $dog->breed ?? 'Unknown breed' }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('dog_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="appointment_date">Appointment Date</label>
                                        <input type="date" name="appointment_date" id="appointment_date" 
                                               class="form-control @error('appointment_date') is-invalid @enderror" 
                                               value="{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) }}" required>
                                        @error('appointment_date')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="start_time">Start Time</label>
                                        <input type="time" name="start_time" id="start_time" 
                                               class="form-control @error('start_time') is-invalid @enderror" 
                                               value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}" required>
                                        @error('start_time')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="end_time">End Time</label>
                                        <input type="time" name="end_time" id="end_time" 
                                               class="form-control @error('end_time') is-invalid @enderror" 
                                               value="{{ old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('H:i')) }}" required>
                                        @error('end_time')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="in_progress" {{ old('status', $appointment->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Services</label>
                                @error('services')
                                    <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                @enderror
                                
                                <div class="border rounded">
                                    @php
                                        $selectedServices = json_decode($appointment->services_data ?? '[]', true) ?: [];
                                        $selectedServiceIds = collect($selectedServices)->pluck('id')->toArray();
                                    @endphp
                                    @foreach($services as $service)
                                        <div class="border-bottom p-3" style="{{ $loop->last ? 'border-bottom: none !important;' : '' }}">
                                            <div class="row align-items-start">
                                                <div class="col-auto">
                                                    <input class="form-check-input service-checkbox" 
                                                           type="checkbox" 
                                                           name="services[]" 
                                                           value="{{ $service->id }}" 
                                                           id="service_{{ $service->id }}"
                                                           data-service-id="{{ $service->id }}"
                                                           data-duration="{{ $service->duration }}"
                                                           {{ in_array($service->id, old('services', $selectedServiceIds)) ? 'checked' : '' }}>
                                                </div>
                                                <div class="col">
                                                    <label class="form-check-label fw-bold" for="service_{{ $service->id }}">
                                                        {{ $service->name }}
                                                    </label>
                                                    <div class="text-muted small">{{ $service->description }}</div>
                                                </div>
                                                <div class="col-3 text-end">
                                                    <div class="text-primary fw-bold service-price" data-service-id="{{ $service->id }}">$0.00</div>
                                                    <div class="text-info small">{{ $service->duration }} min</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Prices vary based on dog size. Select a dog to see accurate pricing.
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" placeholder="Any additional notes about this appointment...">{{ old('notes', $appointment->notes) }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-control @error('payment_status') is-invalid @enderror">
                                            <option value="">Select Payment Status</option>
                                            <option value="pending" {{ old('payment_status', $appointment->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ old('payment_status', $appointment->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="partial" {{ old('payment_status', $appointment->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                            <option value="refunded" {{ old('payment_status', $appointment->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                        @error('payment_status')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_mode">Payment Mode</label>
                                        <select name="payment_mode" id="payment_mode" class="form-control @error('payment_mode') is-invalid @enderror">
                                            <option value="">Select Payment Mode</option>
                                            <option value="cash" {{ old('payment_mode', $appointment->payment_mode) == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="payid" {{ old('payment_mode', $appointment->payment_mode) == 'payid' ? 'selected' : '' }}>PayID</option>
                                            <option value="card" {{ old('payment_mode', $appointment->payment_mode) == 'card' ? 'selected' : '' }}>Card</option>
                                            <option value="bank_transfer" {{ old('payment_mode', $appointment->payment_mode) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        </select>
                                        @error('payment_mode')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount_paid">Amount Paid</label>
                                        <input type="number" name="amount_paid" id="amount_paid" 
                                               class="form-control @error('amount_paid') is-invalid @enderror" 
                                               value="{{ old('amount_paid', $appointment->amount_paid) }}" 
                                               step="0.01" min="0" placeholder="0.00">
                                        @error('amount_paid')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="paid_at">Payment Date</label>
                                        <input type="datetime-local" name="paid_at" id="paid_at" 
                                               class="form-control @error('paid_at') is-invalid @enderror" 
                                               value="{{ old('paid_at', $appointment->paid_at ? \Carbon\Carbon::parse($appointment->paid_at)->format('Y-m-d\TH:i') : '') }}">
                                        @error('paid_at')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button class="btn btn-info" type="submit">Update</button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <div class="card-title mb-0">
                                <i class="fas fa-clipboard-list me-2 text-muted"></i>Appointment Summary
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="text-dark mb-3">Selected Services</h6>
                                <div id="selected-services" class="border rounded p-3 bg-light" style="min-height: 80px;">
                                    <p class="text-muted mb-0">No services selected</p>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted mb-1">
                                            <i class="fas fa-clock me-1"></i>Duration
                                        </div>
                                        <div id="total_duration" class="h5 mb-0 text-dark">0 min</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted mb-1">
                                            <i class="fas fa-dollar-sign me-1"></i>Total Price
                                        </div>
                                        <div id="total_price" class="h5 mb-0 text-dark">$0.00</div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Select services above to see pricing and duration details
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
@endsection

@push('scripts')
    <script>
        // Pass selected dog info from PHP to JS
        var selectedDogId = @json($selectedDogId);
        var selectedDogSize = @json($selectedDogSize);
        $(document).ready(function() {
            // Load dogs when client is selected
            $('#client_id').change(function() {
                const clientId = $(this).val();
                const dogSelect = $('#dog_id');
                
                dogSelect.html('<option value="">Select Dog</option>');
                
                if (clientId) {
                    $.get(`/appointments/client/${clientId}/dogs`, function(data) {
                        data.forEach(function(dog) {
                            dogSelect.append(`<option value="${dog.id}" data-size="${dog.size || 'medium'}" ${old('dog_id', $appointment->dog_id) == dog.id ? 'selected' : ''}>${dog.name} (${dog.breed || 'Unknown breed'}) - ${dog.size || 'Medium'} size</option>`);
                        });
                        // If there's an old dog selection, trigger the change event to load prices
                        if (old('dog_id', $appointment->dog_id)) {
                            $('#dog_id').trigger('change');
                        }
                        updateEndTime();
                    });
                }
                
                // Reset prices when client changes
                $('.service-price').text('$0.00');
                calculateTotals();
                updateEndTime();
            });

            // Load service prices when dog is selected
            $('#dog_id').change(function() {
                const dogId = $(this).val();
                const selectedOption = $(this).find('option:selected');
                const dogSize = selectedOption.data('size');
                
                console.log('Dog changed - ID:', dogId, 'Size:', dogSize);
                
                if (dogId && dogSize) {
                    loadServicePrices(dogSize);
                } else {
                    $('.service-price').text('$0.00');
                    calculateTotals();
                    updateEndTime();
                }
            });

            function loadServicePrices(dogSize) {
                console.log('loadServicePrices called with dogSize:', dogSize);
                $.get(`/appointments/services/prices/${dogSize}`, function(data) {
                    console.log('Received pricing data:', data);
                    data.forEach(function(service) {
                        console.log('Setting price for service', service.service_id, 'to', service.price);
                        $(`.service-price[data-service-id="${service.service_id}"]`).text('$' + parseFloat(service.price).toFixed(2));
                    });
                    // After all prices are set, force a full summary update
                    setTimeout(function() {
                        // Debug: log checked checkboxes and their prices
                        $('.service-checkbox:checked').each(function() {
                            const serviceId = $(this).data('service-id');
                            const price = $(`.service-price[data-service-id="${serviceId}"]`).text();
                            console.log('Checked service', serviceId, 'price:', price);
                        });
                        calculateTotals();
                        updateEndTime();
                    }, 0);
                }).fail(function(xhr, status, error) {
                    console.error('Failed to load service prices:', error);
                    console.log('Response:', xhr.responseText);
                    calculateTotals();
                    updateEndTime();
                });
            }

            // Calculate total price when services are selected
            $('.service-checkbox').change(function() {
                calculateTotals();
            });

            function calculateTotals() {
                let total = 0;
                let totalDuration = 0;
                let selectedServices = [];
                
                $('.service-checkbox:checked').each(function() {
                    const serviceId = $(this).data('service-id');
                    const duration = parseInt($(this).data('duration'));
                    const name = $(this).closest('.row').find('.form-check-label').text().trim();
                    const priceElement = $(`.service-price[data-service-id="${serviceId}"]`);
                    const price = parseFloat(priceElement.text().replace('$', '')) || 0;
                    
                    total += price;
                    totalDuration += duration;
                    selectedServices.push(name);
                });
                
                $('#total_price').text('$' + total.toFixed(2));
                $('#total_duration').text(totalDuration + ' min');
                
                const servicesDiv = $('#selected-services');
                if (selectedServices.length > 0) {
                    servicesDiv.html(selectedServices.map(service => 
                        `<span class="badge bg-primary me-1 mb-1">${service}</span>`
                    ).join(''));
                } else {
                    servicesDiv.html('<p class="text-muted mb-0">No services selected</p>');
                }
            }

            // Initialize totals
            calculateTotals();
            updateEndTime();
            
            // On page load, set the dog dropdown and trigger change to load prices and update summary
            if (selectedDogId) {
                $('#dog_id').val(selectedDogId).trigger('change');
            } else {
                calculateTotals();
                updateEndTime();
            }

            function updateEndTime() {
                const startTime = $('#start_time').val();
                const appointmentDate = $('#appointment_date').val();
                if (!startTime || !appointmentDate) {
                    $('#end_time').val('');
                    return;
                }
                // Sum durations of checked services
                let totalDuration = 0;
                $('.service-checkbox:checked').each(function() {
                    const dur = parseInt($(this).data('duration'));
                    if (!isNaN(dur)) totalDuration += dur;
                });
                if (totalDuration === 0) {
                    $('#end_time').val('');
                    return;
                }
                // Calculate end time
                const [h, m] = startTime.split(':').map(Number);
                const start = new Date(appointmentDate + 'T' + startTime);
                const end = new Date(start.getTime() + totalDuration * 60000);
                const hh = String(end.getHours()).padStart(2, '0');
                const mm = String(end.getMinutes()).padStart(2, '0');
                $('#end_time').val(`${hh}:${mm}`);
            }

            // Attach events
            $('.service-checkbox').change(updateEndTime);
            $('#start_time').change(updateEndTime);
            // Also call on page load
            updateEndTime();
        });
    </script>
@endpush 