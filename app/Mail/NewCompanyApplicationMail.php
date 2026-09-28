<?php

namespace App\Mail;

use App\Models\Directory;
use App\Models\ListingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCompanyApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ListingRequest $request,
    ) {}

    public function envelope(): Envelope
    {
        $directory = $this->resolveDirectory();
        $siteName = $directory?->name ?? config('app.name');

        return new Envelope(
            subject: "[Yeni Başvuru] {$this->request->company_name} · {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-company-application',
            with: [
                'listing' => $this->request,
                'directory' => $this->resolveDirectory(),
            ],
        );
    }

    private function resolveDirectory(): ?Directory
    {
        if (! $this->request->directory_id) {
            return null;
        }

        return Directory::withoutGlobalScope('directory')->find($this->request->directory_id);
    }
}
