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

    /**
     * Create a new message instance.
     */
    public function __construct($reportType, $reportData, $pdfPath = null)
    {
        $this->reportType = $reportType;
        $this->reportData = $reportData;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'stock' => 'Laporan Stok Barang',
            'sales' => 'Laporan Penjualan',
            'purchases' => 'Laporan Pembelian',
            'profit' => 'Laporan Profit & Margin',
        ];

        return new Envelope(
            subject: $subjects[$this->reportType] ?? 'Laporan Sistem Inventory',
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
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            return [
                Attachment::fromPath($this->pdfPath)
                    ->as('laporan-' . $this->reportType . '-' . date('Y-m-d') . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
