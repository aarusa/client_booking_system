<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Dog;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get current date and calculate date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Always default to today's date for the appointments section
        $selectedDate = $today;

        // Basic statistics
        $stats = [
            'total_clients' => Client::count(),
            'total_dogs' => Dog::count(),
            'total_appointments' => Appointment::count(),
            'total_services' => Service::where('is_active', true)->count(),
            'total_users' => User::count(),
        ];

        // Appointment statistics
        $appointmentStats = [
            'today' => Appointment::whereDate('appointment_date', $today)->count(),
            'this_week' => Appointment::whereBetween('appointment_date', [$thisWeek, $today])->count(),
            'this_month' => Appointment::whereBetween('appointment_date', [$thisMonth, $today])->count(),
            'scheduled' => Appointment::where('status', 'scheduled')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'in_progress' => Appointment::where('status', 'in_progress')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        // Financial statistics
        $financialStats = [
            'total_earnings' => Appointment::where('status', 'completed')->sum('total_price'),
            'this_month_earnings' => Appointment::where('status', 'completed')
                ->whereBetween('appointment_date', [$thisMonth, $today])
                ->sum('total_price'),
            'last_month_earnings' => Appointment::where('status', 'completed')
                ->whereBetween('appointment_date', [$lastMonth, $thisMonth->subDay()])
                ->sum('total_price'),
            'pending_payments' => Appointment::where('payment_status', 'pending')->sum('total_price'),
            'paid_amount' => Appointment::where('payment_status', 'paid')->sum('amount_paid'),
        ];

        // Recent appointments (upcoming)
        $recentAppointments = Appointment::with(['client', 'dog'])
            ->where('appointment_date', '>=', $today)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // Appointments for selected date
        $selectedDateAppointments = Appointment::with(['client', 'dog'])
            ->whereDate('appointment_date', $selectedDate)
            ->orderBy('start_time', 'asc')
            ->get();



        // Weekly appointment trend (last 4 weeks)
        $weeklyTrend = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $weeklyTrend[] = [
                'week' => $weekStart->format('M d'),
                'appointments' => Appointment::whereBetween('appointment_date', [$weekStart, $weekEnd])->count(),
                'earnings' => Appointment::where('status', 'completed')
                    ->whereBetween('appointment_date', [$weekStart, $weekEnd])
                    ->sum('total_price'),
            ];
        }



        // Payment method distribution
        $paymentMethodDistribution = Appointment::selectRaw('payment_mode, COUNT(*) as count')
            ->whereNotNull('payment_mode')
            ->groupBy('payment_mode')
            ->orderBy('count', 'desc')
            ->get();

        // Dog Breed Distribution
        $dogBreeds = Dog::selectRaw('breed, COUNT(*) as count')
            ->whereNotNull('breed')
            ->where('breed', '!=', '')
            ->groupBy('breed')
            ->orderBy('count', 'desc')
            ->limit(8)
            ->get();

        // Business Performance Metrics
        $businessMetrics = [
            'avg_appointment_value' => Appointment::where('status', 'completed')->avg('total_price') ?? 0,
            'avg_appointments_per_day' => Appointment::where('status', 'completed')
                ->whereBetween('appointment_date', [$thisMonth, $today])
                ->count() / max(1, $today->diffInDays($thisMonth) + 1),
            'client_retention_rate' => $this->calculateClientRetentionRate(),
            'peak_hours' => $this->getPeakHours(),
        ];

        // Service Revenue Breakdown (using JSON services_data)
        $serviceRevenue = collect();
        $completedAppointments = Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [$thisMonth, $today])
            ->get();

        $serviceTotals = [];
        foreach ($completedAppointments as $appointment) {
            $services = json_decode($appointment->services_data ?? '[]', true);
            $appointmentValue = $appointment->total_price;
            $serviceCount = count($services);
            
            if ($serviceCount > 0) {
                $valuePerService = $appointmentValue / $serviceCount;
                
                foreach ($services as $service) {
                    $serviceName = $service['name'] ?? 'Unknown Service';
                    if (!isset($serviceTotals[$serviceName])) {
                        $serviceTotals[$serviceName] = [
                            'total_revenue' => 0,
                            'appointment_count' => 0
                        ];
                    }
                    $serviceTotals[$serviceName]['total_revenue'] += $valuePerService;
                    $serviceTotals[$serviceName]['appointment_count']++;
                }
            }
        }

        // Convert to collection and sort by revenue
        $serviceRevenue = collect($serviceTotals)->map(function($data, $name) {
            return (object) [
                'name' => $name,
                'total_revenue' => $data['total_revenue'],
                'appointment_count' => $data['appointment_count']
            ];
        })->sortByDesc('total_revenue')->take(5);

        // Recent Client Activity
        $recentClientActivity = Client::with(['appointments' => function($query) use ($today) {
                $query->where('appointment_date', '>=', $today->subDays(30))
                      ->orderBy('appointment_date', 'desc');
            }])
            ->whereHas('appointments', function($query) use ($today) {
                $query->where('appointment_date', '>=', $today->subDays(30));
            })
            ->withCount(['appointments' => function($query) use ($today) {
                $query->where('appointment_date', '>=', $today->subDays(30));
            }])
            ->orderBy('appointments_count', 'desc')
            ->limit(5)
            ->get();

        return view('cms.modules.dashboard.index', compact(
            'stats',
            'appointmentStats',
            'financialStats',
            'recentAppointments',
            'selectedDateAppointments',
            'selectedDate',
            'businessMetrics',
            'serviceRevenue',
            'recentClientActivity',
            'weeklyTrend',
            'paymentMethodDistribution',
            'dogBreeds'
        ));
    }

    /**
     * Get appointments for a specific date via AJAX
     */
    public function getAppointmentsForDate(Request $request)
    {
        $date = Carbon::parse($request->date);
        
        $appointments = Appointment::with(['client', 'dog'])
            ->whereDate('appointment_date', $date)
            ->orderBy('start_time', 'asc')
            ->get();

        $formattedDate = $date->format('l, M d, Y');
        $isToday = $date->format('Y-m-d') === Carbon::today()->format('Y-m-d');
        
        $html = view('cms.modules.dashboard.partials.appointments-table', compact('appointments', 'formattedDate', 'isToday'))->render();
        
        return response()->json([
            'html' => $html,
            'date' => $date->format('Y-m-d'),
            'formattedDate' => $formattedDate,
            'isToday' => $isToday,
            'count' => $appointments->count()
        ]);
    }

    /**
     * Calculate client retention rate (clients with multiple appointments)
     */
    private function calculateClientRetentionRate()
    {
        $totalClients = Client::count();
        if ($totalClients === 0) return 0;

        // Count clients who have more than one appointment
        $repeatClients = DB::table('clients')
            ->join('appointments', 'clients.id', '=', 'appointments.client_id')
            ->select('clients.id')
            ->groupBy('clients.id')
            ->havingRaw('COUNT(appointments.id) > 1')
            ->count();

        return round(($repeatClients / $totalClients) * 100, 1);
    }

    /**
     * Get peak appointment hours
     */
    private function getPeakHours()
    {
        $peakHours = DB::table('appointments')
            ->selectRaw('HOUR(start_time) as hour, COUNT(*) as count')
            ->where('status', 'completed')
            ->whereBetween('appointment_date', [Carbon::now()->subMonths(3), Carbon::now()])
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();

        return $peakHours->map(function($hour) {
            return [
                'hour' => $hour->hour,
                'time' => Carbon::createFromFormat('H', $hour->hour)->format('h:i A'),
                'count' => $hour->count
            ];
        });
    }
}
