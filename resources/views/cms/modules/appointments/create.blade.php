@extends('cms.layouts.master')

@section('title', 'Create Appointment')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Add New Appointment</h3>
                <h6 class="op-7 mb-2">Fill out the form to create a new appointment.</h6>
            </div>
        </div>
        
        <form action="{{ route('appointments.store') }}" method="POST">
        @csrf
        
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="client_id">Client</label>
                                        <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                                            <option value="">Select Client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId ?? '') == $client->id ? 'selected' : '' }}>
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
                                        </select>
                                        @error('dog_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="appointment_date">Appointment Date</label>
                                        <input type="date" name="appointment_date" id="appointment_date" 
                                               class="form-control @error('appointment_date') is-invalid @enderror" 
                                               value="{{ old('appointment_date') }}" 
                                               min="{{ date('Y-m-d') }}" required>
                                        @error('appointment_date')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="start_time">Start Time</label>
                                        <input type="time" name="start_time" id="start_time" 
                                               class="form-control @error('start_time') is-invalid @enderror" 
                                               value="{{ old('start_time') }}" required>
                                        @error('start_time')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="end_time">End Time</label>
                                        <input type="time" name="end_time" id="end_time" 
                                               class="form-control @error('end_time') is-invalid @enderror" 
                                               value="{{ old('end_time') }}" required>
                                        <small class="text-muted">
                                            <i class="fas fa-magic me-1"></i>
                                            Auto-calculated based on selected services
                                        </small>
                                        @error('end_time')
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
                                                           {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
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
                                          rows="3" placeholder="Any additional notes about this appointment...">{{ old('notes') }}</textarea>
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
                                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                            <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
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
                                            <option value="cash" {{ old('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="payid" {{ old('payment_mode') == 'payid' ? 'selected' : '' }}>PayID</option>
                                            <option value="card" {{ old('payment_mode') == 'card' ? 'selected' : '' }}>Card</option>
                                            <option value="bank_transfer" {{ old('payment_mode') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
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
                                               value="{{ old('amount_paid', 0) }}" 
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
                                               value="{{ old('paid_at') }}">
                                        @error('paid_at')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button class="btn btn-info" type="submit">Save</button>
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
        $(document).ready(function() {
            // Default appointment date to today if not set
            var $dateInput = $('#appointment_date');
            if (!$dateInput.val()) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                $dateInput.val(`${yyyy}-${mm}-${dd}`);
            }

            // Helper: disable/enable fields based on date
            function toggleFieldsByDate() {
                const hasDate = !!$dateInput.val();
                $('#start_time, #end_time, .service-checkbox').prop('disabled', !hasDate);
                if (!hasDate) {
                    $('#date-required-msg').show();
                } else {
                    $('#date-required-msg').hide();
                }
            }
            // Add helper message if not present
            if ($('#date-required-msg').length === 0) {
                $dateInput.after('<div id="date-required-msg" class="text-danger small mt-1" style="display:none;">Please select a date to enable time and services.</div>');
            }
            toggleFieldsByDate();
            $dateInput.on('change', function() {
                toggleFieldsByDate();
            });

            // Prevent interaction if date not selected
            function requireDateAlert(e) {
                if (!$dateInput.val()) {
                    alert('Please select an appointment date first.');
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            }
            $('#start_time, #end_time').on('focus', requireDateAlert);
            $(document).on('mousedown', '.service-checkbox', requireDateAlert);

            // Load dogs when client is selected
            $('#client_id').change(function() {
                loadClientDogs($(this).val());
            });

            // Load dogs for pre-selected client on page load
            const preSelectedClientId = $('#client_id').val();
            if (preSelectedClientId) {
                loadClientDogs(preSelectedClientId);
            }

            function loadClientDogs(clientId) {
                const dogSelect = $('#dog_id');
                const oldDogId = '{{ old("dog_id") }}';
                
                dogSelect.html('<option value="">Select Dog</option>');
                
                if (clientId) {
                    $.get(`/appointments/client/${clientId}/dogs`, function(data) {
                        data.forEach(function(dog) {
                            const isSelected = oldDogId && oldDogId == dog.id ? 'selected' : '';
                            dogSelect.append(`<option value="${dog.id}" data-size="${dog.size || 'medium'}" ${isSelected}>${dog.name} (${dog.breed || 'Unknown breed'}) - ${dog.size || 'Medium'} size</option>`);
                        });
                        
                        // If there's an old dog selection, trigger the change event to load prices
                        if (oldDogId) {
                            $('#dog_id').trigger('change');
                        }
                    });
                }
                
                // Reset pricing when client changes
                $('.service-price').text('$0.00');
                calculateTotals();
                updateEndTime();
            }

            // Load service prices when dog is selected
            $('#dog_id').change(function() {
                const dogId = $(this).val();
                const selectedOption = $(this).find('option:selected');
                const dogSize = selectedOption.data('size');
                
                if (dogId && dogSize) {
                    loadServicePrices(dogSize);
                } else {
                    $('.service-price').text('$0.00');
                    calculateTotals();
                }
            });

            function loadServicePrices(dogSize) {
                $.get(`/appointments/services/prices/${dogSize}`, function(data) {
                    data.forEach(function(service) {
                        $(`.service-price[data-service-id="${service.service_id}"]`).text('$' + parseFloat(service.price).toFixed(2));
                    });
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

            function updateEndTime() {
                const startTime = $('#start_time').val();
                const appointmentDate = $('#appointment_date').val();
                if (!startTime || !appointmentDate) {
                    console.log('Missing start time or appointment date');
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
                    console.log('No services selected');
                    $('#end_time').val('');
                    return;
                }
                // Calculate end time
                const [h, m] = startTime.split(':').map(Number);
                if (isNaN(h) || isNaN(m)) {
                    console.log('Invalid start time format', startTime);
                    $('#end_time').val('');
                    return;
                }
                const start = new Date(appointmentDate + 'T' + startTime);
                if (isNaN(start.getTime())) {
                    console.log('Invalid start date/time', appointmentDate, startTime);
                    $('#end_time').val('');
                    return;
                }
                const end = new Date(start.getTime() + totalDuration * 60000);
                const hh = String(end.getHours()).padStart(2, '0');
                const mm = String(end.getMinutes()).padStart(2, '0');
                $('#end_time').val(`${hh}:${mm}`);
                console.log('End time calculated:', `${hh}:${mm}`);
            }

            // Attach events for end time calculation
            $('.service-checkbox').on('change', function() {
                calculateTotals();
                updateEndTime();
            });
            $('#start_time').on('change', updateEndTime);
            $('#dog_id').on('change', function() {
                setTimeout(updateEndTime, 200); // allow prices/durations to update
            });
            $('#appointment_date').on('change', updateEndTime);

            // Also call on page load
            setTimeout(updateEndTime, 300);
        });
    </script>
@endpush 