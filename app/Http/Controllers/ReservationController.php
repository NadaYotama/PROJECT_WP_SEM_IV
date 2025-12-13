<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display reservation form (Public)
     */
    public function index()
    {
        $availableTables = Table::where('status', 'available')->get();
        return view('reservation.index', compact('availableTables'));
    }

    /**
     * Display reservations management for staff (Kasir/Admin/Manager)
     */
    public function staffIndex(Request $request)
    {
        $query = Reservation::with(['user', 'table']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $reservations = $query->latest()->paginate(20);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show create reservation form for staff
     */
    public function create()
    {
        $availableTables = Table::where('status', 'available')->get();
        return view('reservations.create', compact('availableTables'));
    }

    /**
     * Show the form for creating a new reservation for customers.
     */
    public function customerCreate()
    {
        $availableTables = Table::where('status', 'available')->get();
        return view('customer.reservations.create', compact('availableTables'));
    }

    /**
     * Display the authenticated customer's reservations.
     */
    public function myReservations()
    {
        $user = auth()->user();
        $reservations = Reservation::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('customer.reservations.index', compact('reservations'));
    }

    /**
     * Store a newly created reservation (Public/Customer)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'guests' => 'required|integer|min:1',
            'table_id' => 'required|exists:tables,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'notes' => 'nullable|string'
        ]);

        // Check if user is authenticated
        $userId = Auth::check() ? Auth::id() : null;

        // Get the selected table
        $table = Table::find($request->table_id);

        // Check if table is available
        if (!$table || $table->status !== 'available') {
            return back()->withErrors(['table_id' => 'Meja yang dipilih tidak tersedia.'])->withInput();
        }

        // Check if table capacity matches guests
        if ($table->capacity < $request->guests) {
            return back()->withErrors(['table_id' => 'Kapasitas meja tidak mencukupi untuk jumlah tamu.'])->withInput();
        }

        $reservation = Reservation::create([
            'user_id' => $userId,
            'table_id' => $table->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'guests' => $request->guests,
            'date' => $request->date,
            'time' => $request->time,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        // Update table status to reserved
        $table->update(['status' => 'reserved']);

        if (Auth::check()) {
            return redirect()->route('customer.reservations.index')
                           ->with('success', 'Reservasi berhasil dibuat! Kami akan mengkonfirmasi segera.');
        } else {
            return redirect()->route('reservation.index')
                           ->with('success', 'Reservasi berhasil dibuat! Kami akan menghubungi Anda untuk konfirmasi.');
        }
    }

    /**
     * Manage reservations (Admin/Kasir/Manager)
     */
    public function manage(Request $request)
    {
        $this->middleware('role:admin,kasir,manager');

        $query = Reservation::with(['user', 'table']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $reservations = $query->latest()->paginate(20);

        return view('reservations.manage', compact('reservations'));
    }

    /**
     * Update reservation status
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $oldStatus = $reservation->status;
        $reservation->update(['status' => $request->status]);

        // Update table status
        if ($reservation->table) {
            if ($request->status === 'confirmed') {
                $reservation->table->update(['status' => 'reserved']);
            } elseif ($request->status === 'completed' || $request->status === 'cancelled') {
                $reservation->table->update(['status' => 'available']);
            }
        }

        return back()->with('success', 'Status reservasi berhasil diupdate!');
    }

    /**
     * Show reservation details
     */
    public function show(Reservation $reservation)
    {
        // Check authorization
        if (Auth::user()->role === 'customer' && $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->load(['user', 'table']);
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Cancel reservation (Customer)
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status !== 'pending' && $reservation->status !== 'confirmed') {
            return back()->with('error', 'Reservasi tidak dapat dibatalkan!');
        }

        $reservation->update(['status' => 'cancelled']);

        // Free up the table
        if ($reservation->table) {
            $reservation->table->update(['status' => 'available']);
        }

        return back()->with('success', 'Reservasi berhasil dibatalkan!');
    }

    /**
     * Get reservation statistics
     */
    public function stats()
    {
        $stats = [
            'today_reservations' => Reservation::whereDate('date', today())->count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed_today' => Reservation::where('status', 'confirmed')
                                          ->whereDate('date', today())
                                          ->count(),
            'upcoming' => Reservation::where('status', 'confirmed')
                                   ->where('date', '>=', today())
                                   ->count(),
        ];

        return response()->json($stats);
    }
}
