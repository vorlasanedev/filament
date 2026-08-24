<?php

namespace App\Jobs;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportUsersPdfJob implements ShouldQueue
{
    use Queueable;

    public $userIds;
    public $recipientId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $userIds, int $recipientId)
    {
        $this->userIds = $userIds;
        $this->recipientId = $recipientId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::whereIn('id', $this->userIds)->get();
        $recipient = User::find($this->recipientId);

        if (!$recipient) {
            return;
        }

        $pdf = Pdf::loadView('pdf.user-export', ['users' => $users])->setPaper('a4', 'portrait');
        
        $filename = 'exports/user-forms-export-' . Str::uuid() . '.pdf';
        
        Storage::disk('public')->put($filename, $pdf->output());

        Notification::make()
            ->title('PDF Export Ready')
            ->body('Your users export has been generated successfully.')
            ->success()
            ->actions([
                Action::make('download')
                    ->button()
                    ->url(Storage::url($filename), shouldOpenInNewTab: true)
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipient);
    }
}
