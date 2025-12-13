<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Only manager and admin can create/edit/delete tables
        $this->middleware('role:manager,admin')->except(['index', 'show', 'available']);
    }

    /**
     * Display a listing of tables
     */
    public function index(Request $request)
    {
        $query = Table::query();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by location
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }

        // Filter by capacity
        if ($request->has('capacity') && $request->capacity != '') {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Search by table number
        if ($request->has('search') && $request->search != '') {
            $query->where('table_number', 'like', '%' . $request->search . '%');
        }

        // Order by table number
        $tables = $query->orderBy('table_number')->paginate(20);

        // Stats for dashboard
        $stats = [
            'total_tables' => Table::count(),
            'available' => Table::where('status', 'available')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'maintenance' => Table::where('status', 'maintenance')->count(),
        ];

        return view('tables.index', compact('tables', 'stats'));
    }

    /**
     * Show the form for creating a new table
     */
    public function create()
    {
        return view('tables.create');
    }

    /**
     * Store a newly created table
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:tables,table_number|max:20',
            'capacity' => 'required|integer|min:1|max:50',
            'location' => 'required|in:indoor,outdoor,vip',
            'status' => 'nullable|in:available,occupied,reserved,maintenance',
            'description' => 'nullable|string|max:500'
        ]);

        Table::create([
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'location' => $request->location,
            'status' => $request->status ?? 'available',
            'description' => $request->description
        ]);

        return redirect()->route('tables.index')
                       ->with('success', 'Meja berhasil ditambahkan!');
    }

    /**
     * Display the specified table with reservations
     */
    public function show(Table $table)
    {
        // Load recent reservations for this table
        $table->load(['reservations' => function($query) {
            $query->with('user')
                  ->latest()
                  ->limit(10);
        }]);
        
        return view('tables.show', compact('table'));
    }

    /**
     * Show the form for editing the specified table
     */
    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    /**
     * Update the specified table
     */
    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|string|max:20|unique:tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1|max:50',
            'location' => 'required|in:indoor,outdoor,vip',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'description' => 'nullable|string|max:500'
        ]);

        $table->update($request->all());

        return redirect()->route('tables.index')
                       ->with('success', 'Meja berhasil diupdate!');
    }

    /**
     * Remove the specified table
     */
    public function destroy(Table $table)
    {
        // Check if table has active reservations
        $activeReservations = $table->reservations()
                                   ->whereIn('status', ['pending', 'confirmed'])
                                   ->count();

        if ($activeReservations > 0) {
            return back()->with('error', 'Tidak dapat menghapus meja yang memiliki reservasi aktif!');
        }

        $table->delete();

        return redirect()->route('tables.index')
                       ->with('success', 'Meja berhasil dihapus!');
    }

    /**
     * Update table status (Quick update for staff)
     */
    public function updateStatus(Request $request, Table $table)
    {
        // Only staff can update status
        if (!in_array(Auth::user()->role, ['admin', 'kasir', 'manager'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:available,occupied,reserved,maintenance'
        ]);

        $table->update(['status' => $request->status]);

        return back()->with('success', 'Status meja berhasil diupdate!');
    }

    /**
     * Get available tables (API for reservation form)
     */
    public function available(Request $request)
    {
        $query = Table::where('status', 'available');

        // Filter by capacity if provided
        if ($request->has('capacity') && $request->capacity > 0) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Filter by location if provided
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }

        // Filter by date & time (check existing reservations)
        if ($request->has('date') && $request->has('time')) {
            $date = $request->date;
            $time = $request->time;

            $query->whereDoesntHave('reservations', function($q) use ($date, $time) {
                $q->where('date', $date)
                  ->where('status', 'confirmed')
                  ->whereRaw("TIME(time) BETWEEN SUBTIME(?, '01:00:00') AND ADDTIME(?, '01:00:00')", [$time, $time]);
            });
        }

        $tables = $query->orderBy('capacity')->get();

        return response()->json([
            'success' => true,
            'tables' => $tables
        ]);
    }

    /**
     * Bulk update table status
     */
    public function bulkUpdateStatus(Request $request)
    {
        // Only manager can do bulk update
        if (Auth::user()->role !== 'manager') {
            abort(403);
        }

        $request->validate([
            'table_ids' => 'required|array',
            'table_ids.*' => 'exists:tables,id',
            'status' => 'required|in:available,occupied,reserved,maintenance'
        ]);

        Table::whereIn('id', $request->table_ids)
             ->update(['status' => $request->status]);

        return back()->with('success', count($request->table_ids) . ' meja berhasil diupdate!');
    }

    /**
     * Get table statistics
     */
    public function stats()
    {
        // Only staff can access
        if (Auth::user()->role === 'customer') {
            abort(403);
        }

        $stats = [
            'total' => Table::count(),
            'available' => Table::where('status', 'available')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'maintenance' => Table::where('status', 'maintenance')->count(),
            'by_location' => [
                'indoor' => Table::where('location', 'indoor')->count(),
                'outdoor' => Table::where('location', 'outdoor')->count(),
                'vip' => Table::where('location', 'vip')->count(),
            ],
            'capacity_distribution' => Table::selectRaw('capacity, COUNT(*) as count')
                                           ->groupBy('capacity')
                                           ->orderBy('capacity')
                                           ->get(),
            'utilization_rate' => Table::where('status', 'occupied')->count() / max(Table::count(), 1) * 100
        ];

        return response()->json($stats);
    }

    /**
     * Check table availability for specific date/time
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required'
        ]);

        $table = Table::find($request->table_id);

        // Check if table has conflicting reservation
        $hasConflict = $table->reservations()
                            ->where('date', $request->date)
                            ->where('status', 'confirmed')
                            ->whereRaw("TIME(time) BETWEEN SUBTIME(?, '01:00:00') AND ADDTIME(?, '01:00:00')", 
                                     [$request->time, $request->time])
                            ->exists();

        return response()->json([
            'available' => !$hasConflict && $table->status === 'available',
            'table' => $table,
            'message' => $hasConflict 
                ? 'Meja tidak tersedia pada waktu tersebut' 
                : 'Meja tersedia'
        ]);
    }
}