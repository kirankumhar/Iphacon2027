<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class PaymentLogService
{
    /**
     * Get the dedicated payment logs directory path
     */
    public static function getLogDirectory(): string
    {
        $dir = storage_path('logs/payment');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * Write structured log to the payment channel
     */
    public static function log(string $action, string $message, array $context = [], string $level = 'info'): void
    {
        try {
            self::getLogDirectory();

            $enriched = array_merge([
                'action'     => strtoupper($action),
                'timestamp'  => now()->format('Y-m-d H:i:s'),
                'client_ip'  => request()->ip() ?? 'CLI/Unknown',
                'user_agent' => substr(request()->userAgent() ?? 'N/A', 0, 150),
            ], $context);

            Log::channel('payment')->$level("[{$action}] {$message}", $enriched);
        } catch (\Throwable $e) {
            // Fallback to default log if payment channel fails
            Log::error("Failed to write to payment log: " . $e->getMessage());
        }
    }

    public static function info(string $action, string $message, array $context = []): void
    {
        self::log($action, $message, $context, 'info');
    }

    public static function warning(string $action, string $message, array $context = []): void
    {
        self::log($action, $message, $context, 'warning');
    }

    public static function error(string $action, string $message, array $context = []): void
    {
        self::log($action, $message, $context, 'error');
    }

    /**
     * Get list of all payment log files
     */
    public static function getLogFiles(): array
    {
        $dir = self::getLogDirectory();
        if (!File::exists($dir)) {
            return [];
        }

        $files = File::files($dir);
        $result = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $result[] = [
                    'filename'      => $file->getFilename(),
                    'path'          => $file->getPathname(),
                    'size_bytes'    => $file->getSize(),
                    'size_readable' => self::formatBytes($file->getSize()),
                    'modified_at'   => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        // Sort latest first
        usort($result, function ($a, $b) {
            return strcmp($b['modified_at'], $a['modified_at']);
        });

        return $result;
    }

    /**
     * Parse log entries from a specific file with optional search and level filter
     */
    public static function getLogEntries(?string $filename = null, ?string $search = null, ?string $level = null, int $limit = 500): array
    {
        $dir = self::getLogDirectory();

        if (empty($filename)) {
            $files = self::getLogFiles();
            if (empty($files)) {
                return [];
            }
            $filename = $files[0]['filename'];
        }

        // Prevent directory traversal
        $filename = basename($filename);
        $filePath = $dir . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($filePath)) {
            return [];
        }

        $content = File::get($filePath);
        if (empty($content)) {
            return [];
        }

        // Match Laravel log pattern: [2026-10-13 14:00:00] local.INFO: [ACTION] Message {context}
        $pattern = '/^\[(\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2})\]\s+[a-zA-Z0-9_\.]+\.([A-Z]+):\s*(.+)$/m';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $entries = [];
        $totalMatches = count($matches);

        for ($i = 0; $i < $totalMatches; $i++) {
            $match = $matches[$i];
            $timestamp = $match[1][0];
            $logLevel = strtoupper($match[2][0]);
            $rawLine = $match[3][0];

            // Determine entry block until next match or EOF
            $startOffset = $match[0][1];
            $endOffset = ($i + 1 < $totalMatches) ? $matches[$i + 1][0][1] : strlen($content);
            $fullBlock = substr($content, $startOffset, $endOffset - $startOffset);

            // Extract context JSON if present
            $action = 'GENERAL';
            $message = $rawLine;
            $context = null;

            if (preg_match('/^\[([A-Z0-9_\-]+)\]\s*(.*)$/', $rawLine, $actMatch)) {
                $action = $actMatch[1];
                $message = $actMatch[2];
            }

            // Find JSON inside block
            $jsonStart = strpos($fullBlock, '{');
            $jsonEnd = strrpos($fullBlock, '}');
            if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                $jsonStr = substr($fullBlock, $jsonStart, $jsonEnd - $jsonStart + 1);
                $decoded = json_decode($jsonStr, true);
                if (is_array($decoded)) {
                    $context = $decoded;
                    // Clean message of the JSON part
                    $message = trim(str_replace($jsonStr, '', $message));
                }
            }

            // Filter by level
            if (!empty($level) && $logLevel !== strtoupper($level)) {
                continue;
            }

            // Filter by search term
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $haystack = strtolower($timestamp . ' ' . $action . ' ' . $message . ' ' . json_encode($context));
                if (strpos($haystack, $searchLower) === false) {
                    continue;
                }
            }

            $entries[] = [
                'timestamp'  => $timestamp,
                'level'      => $logLevel,
                'action'     => $action,
                'message'    => $message,
                'context'    => $context,
                'raw_block'  => trim($fullBlock),
            ];
        }

        // Return latest logs first
        return array_slice(array_reverse($entries), 0, $limit);
    }

    /**
     * Helper to format bytes to human readable string
     */
    private static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
