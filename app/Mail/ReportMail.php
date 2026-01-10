<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportType;
    public $reportData;
    public $pdfPath;
    public $dateRange;

    /**
     * Create a new message instance.
     */
    public function __construct($reportType, $reportData, $pdfPath, $dateRange = null)
    {
        $this->reportType = $reportType;
        $this->reportData = $reportData;
        $this->pdfPath = $pdfPath;
        $this->dateRange = $dateRange;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $reportTitles = [
            'stock' => 'Laporan Stok',
            'sales' => 'Laporan Penjualan',
            'purchases' => 'Laporan Pembelian',
            'profit' => 'Laporan Keuntungan',
        ];

        $subject = $reportTitles[$this->reportType] ?? 'Laporan';

        if ($this->dateRange) {
            $subject .= " - {$this->dateRange}";
        } else {
            $subject .= " - " . date('d F Y');
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('laporan.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
