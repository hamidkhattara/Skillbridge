<?php

namespace App\Filament\Coach\Widgets;

use App\Models\Booking;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
class CoachCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Booking::class;

    // <-- 3. ADD THIS METHOD: Force the record to be null to prevent the Livewire crash
    public function mount(): void
    {
        $this->record = null;
    }
    /**
     * Fetch events specifically for the logged-in coach
     */
    public function fetchEvents(array $fetchInfo): array
    {
        // Get the currently logged-in user
        $user = auth()->user();

        // Find the Coach profile linked to this user
        $coach = $user->coach;

        // If for some reason they don't have a coach profile yet, return an empty calendar
        if (!$coach) {
            return [];
        }

        // Fetch only the bookings for THIS specific coach
        return Booking::query()
            ->where('coach_id', $coach->id)
            ->get()
            ->map(
                fn (Booking $booking) => [
                    'id' => $booking->id,
                    // Format the title as requested: "Prestation - Client Name"
                    'title' => $booking->service->name . ' - ' . $booking->client_name,
                    'start' => $booking->starts_at->toIso8601String(),
                    // Calculate the end time by adding the duration to the start time
                    'end' => $booking->starts_at->addMinutes($booking->duration)->toIso8601String(),
                    // Color code based on modality
                    'backgroundColor' => $booking->modality === 'online' ? '#3b82f6' : '#22c55e', 
                ]
            )
            ->toArray();
    }
}