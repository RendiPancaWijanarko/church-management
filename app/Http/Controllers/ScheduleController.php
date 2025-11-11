<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Servant;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['servant', 'category']);

        // Filter berdasarkan bulan dan tahun
        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('service_date', $request->month)
                ->whereYear('service_date', $request->year);
        } else {
            // Default: bulan ini
            $query->whereMonth('service_date', now()->month)
                ->whereYear('service_date', now()->year);
        }

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('session')) {
            $query->where('service_session', $request->input('session'));
        }


        // Sorting
        $sortBy = $request->get('sort', 'service_date');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $schedules = $query->paginate(15);
        $categories = Category::all();

        // Data untuk filter
        $currentMonth = $request->get('month', now()->month);
        $currentYear = $request->get('year', now()->year);

        return view('schedules.index', compact(
            'schedules',
            'categories',
            'currentMonth',
            'currentYear'
        ));
    }

    public function create()
    {
        $servants = Servant::where('status', 'active')->get();
        $categories = Category::all();

        return view('schedules.create', compact('servants', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'servant_id' => 'required|exists:servants,id',
            'category_id' => 'required|exists:categories,id',
            'service_date' => 'required|date',
            'service_session' => 'required|in:KU1,KU2,KU3',
            'service_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string'
        ]);

        Schedule::create($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal pelayanan berhasil ditambahkan');
    }

    public function edit(Schedule $schedule)
    {
        $servants = Servant::where('status', 'active')->get();
        $categories = Category::all();

        return view('schedules.edit', compact('schedule', 'servants', 'categories'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'servant_id' => 'required|exists:servants,id',
            'category_id' => 'required|exists:categories,id',
            'service_date' => 'required|date',
            'service_session' => 'required|in:KU1,KU2,KU3',
            'service_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string'
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal pelayanan berhasil diperbarui');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal pelayanan berhasil dihapus');
    }

    // Tampilan berdasarkan kategori (Grouped)
    public function byCategory(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $session = $request->get('session', '');

        $query = Schedule::with(['servant', 'category'])
            ->whereMonth('service_date', $month)
            ->whereYear('service_date', $year);

        if ($session) {
            $query->where('service_session', $session);
        }

        $schedules = $query->orderBy('service_date')
            ->orderBy('service_session')
            ->get()
            ->groupBy('category_id');

        $categories = Category::all();

        return view('schedules.by-category', compact(
            'schedules',
            'categories',
            'month',
            'year',
            'session'
        ));
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $categoryId = $request->get('category');
        $session = $request->get('session');

        $query = Schedule::with(['servant', 'category'])
            ->whereMonth('service_date', $month)
            ->whereYear('service_date', $year);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($session) {
            $query->where('service_session', $session);
        }

        $schedules = $query->orderBy('service_date')
            ->orderBy('service_session')
            ->get()
            ->groupBy('category_id');

        $categories = Category::all();

        $monthName = Carbon::create()->month($month)->locale('id')->translatedFormat('F');

        $pdf = Pdf::loadView('schedules.pdf', compact(
            'schedules',
            'categories',
            'month',
            'year',
            'monthName',
            'session'
        ));

        $filename = "Jadwal_Pelayan_{$monthName}_{$year}.pdf";

        return $pdf->download($filename);
    }
}
