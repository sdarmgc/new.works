<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CustomEmail;

class EmailController extends Controller
{
    /**
     * Path where HTML email templates are stored.
     * Place your .html files in:  resources/email-templates/
     */
    protected string $templatePath;

    public function __construct()
    {
        $this->templatePath = resource_path('email-templates');
    }

    // ---------------------------------------------------------------
    //  COMPOSE PAGE
    // ---------------------------------------------------------------

    public function compose(Request $request)
    {
        $validated = $request->validate([
            'to'          => ['string'],   // comma-separated addresses
        ]);

        $templates = $this->getTemplateList();
        $addresses = $validated['to'] ?? '';

        return view('emails.composer.compose', compact('templates', 'addresses'));
    }

    // ---------------------------------------------------------------
    //  SEND
    // ---------------------------------------------------------------

    public function send(Request $request)
    {
        $validated = $request->validate([
            'to'          => ['required', 'string'],   // comma-separated addresses
            'cc'          => ['nullable', 'string'],
            'bcc'         => ['nullable', 'string'],
            'subject'     => ['required', 'string', 'max:255'],
            'body'        => ['required', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],  // 10 MB each
        ]);

        // Parse comma-separated addresses into arrays
        $to  = $this->parseAddresses($validated['to']);
        $cc  = $this->parseAddresses($validated['cc']  ?? '');
        $bcc = $this->parseAddresses($validated['bcc'] ?? '');

        // Collect uploaded attachment paths
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('email-attachments', 'local');
            }
        }

        try {
            $mailable = new CustomEmail(
                sbj:         $validated['subject'],
                htmlBody:        $validated['body'],
                attachmentPaths: $attachmentPaths,
            );

            $mailer = Mail::to($to);

            if (!empty($cc)) {
                $mailer->cc($cc);
            }
            if (!empty($bcc)) {
                $mailer->bcc($bcc);
            }

            $mailer->send($mailable);

            // Clean up temp attachment files
            foreach ($attachmentPaths as $path) {
                Storage::disk('local')->delete($path);
            }

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ---------------------------------------------------------------
    //  LOAD A SINGLE TEMPLATE (returns JSON with html + metadata)
    // ---------------------------------------------------------------

    public function loadTemplate(string $name)
    {
        // Sanitise: only alphanumeric, dashes, underscores
        $name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $name);
        $file = $this->templatePath . '/' . $name . '.html';

        if (!file_exists($file)) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        $html = file_get_contents($file);

        return response()->json([
            'name'    => $name,
            'html'    => $html,
            'subject' => $this->extractSubject($html),
        ]);
    }

    // ---------------------------------------------------------------
    //  LIST ALL TEMPLATES (returns JSON array)
    // ---------------------------------------------------------------

    public function listTemplates()
    {
        return response()->json($this->getTemplateList());
    }

    // ---------------------------------------------------------------
    //  HELPERS
    // ---------------------------------------------------------------

    private function getTemplateList(): array
    {
        if (!is_dir($this->templatePath)) {
            return [];
        }

        $files = glob($this->templatePath . '/*.html') ?: [];

        return array_map(function (string $path) {
            $name = pathinfo($path, PATHINFO_FILENAME);
            $html = file_get_contents($path);
            return [
                'name'        => $name,
                'label'       => ucwords(str_replace(['-', '_'], ' ', $name)),
                'subject'     => $this->extractSubject($html),
                'preview'     => $this->extractPreview($html),
            ];
        }, $files);
    }

    private function parseAddresses(string $raw): array
    {
        if (empty(trim($raw))) {
            return [];
        }
        return array_filter(
            array_map('trim', preg_split('/[\s,;]+/', $raw)),
            fn($v) => filter_var($v, FILTER_VALIDATE_EMAIL)
        );
    }

    /** Pull the first <title> or data-subject from the HTML file. */
    private function extractSubject(string $html): string
    {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
            return trim(strip_tags($m[1]));
        }
        if (preg_match('/data-subject="([^"]+)"/i', $html, $m)) {
            return trim($m[1]);
        }
        return '';
    }

    /** Strip tags and return first ~120 chars of visible text. */
    private function extractPreview(string $html): string
    {
        $text = strip_tags($html);
        $text = preg_replace('/\s+/', ' ', $text);
        return mb_substr(trim($text), 0, 120);
    }
}
