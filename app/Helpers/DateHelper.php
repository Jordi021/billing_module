<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;

class DateHelper {
    // Formato único para toda la aplicación
    public const APP_FORMAT = 'Y-m-d H:i:s';
    public const DB_FORMAT = 'Y-m-d H:i:s';
    public const HTML5_FORMAT = 'Y-m-d\TH:i:s';

    // 
    private const ACCEPTED_FORMATS = [
        'Y-m-d H:i:s',
        'd-m-Y H:i:s',
        'm-d-Y H:i:s',
        'Y-m-d\TH:i:s', // Formato HTML5
    ];

    // Zona horaria configurada desde el .env
    private static function getTimezone() {
        return env('APP_TIMEZONE', 'UTC'); 
    }

    public static function toDatabase($date): string {
        if (empty($date)) {
            return now()->format(self::DB_FORMAT);
        }

        if ($date instanceof Carbon) {
            return $date
                ->setTimezone(self::getTimezone())
                ->format(self::DB_FORMAT);
        }

        // Intentar todos los formatos posibles
        foreach (self::ACCEPTED_FORMATS as $format) {
            try {
                return Carbon::createFromFormat(
                    $format,
                    $date,
                    self::getTimezone()
                )->format(self::DB_FORMAT);
            } catch (\Exception $e) {
                continue;
            }
        }

        // Si ningún formato funciona, intentar parse general
        try {
            return Carbon::parse($date)
                ->setTimezone(self::getTimezone())
                ->format(self::DB_FORMAT);
        } catch (\Exception $e) {
            return now()->format(self::DB_FORMAT);
        }
    }

    public static function fromDatabase($date): string {
        if (empty($date)) {
            return '';
        }

        try {
            return Carbon::parse($date)
                ->setTimezone(self::getTimezone())
                ->format(self::APP_FORMAT);
        } catch (\Exception $e) {
            return now()->format(self::APP_FORMAT);
        }
    }

    public static function formatForDisplay($date): string {
        if (empty($date)) {
            return now()->format(self::APP_FORMAT);
        }
        return self::fromDatabase($date);
    }

    public static function formatForHtml($date): string {
        if (empty($date)) {
            return now()->setTimezone(self::getTimezone())->format(self::HTML5_FORMAT);
        }
        try {
            return Carbon::parse($date)
                ->setTimezone(self::getTimezone())
                ->format(self::HTML5_FORMAT);
        } catch (\Exception $e) {
            return now()->setTimezone(self::getTimezone())->format(self::HTML5_FORMAT);
        }
    }

    public static function isValidFormat($date): bool {
        if (empty($date)) {
            return false;
        }

        foreach (self::ACCEPTED_FORMATS as $format) {
            try {
                Carbon::createFromFormat($format, $date, self::getTimezone());
                return true;
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            Carbon::parse($date)->setTimezone(self::getTimezone());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getCurrentDateTime(): string {
        return now()
            ->setTimezone(self::getTimezone())
            ->format(self::APP_FORMAT);
    }
}
