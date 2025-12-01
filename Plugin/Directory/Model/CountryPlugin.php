<?php
declare(strict_types=1);

namespace ElielWeb\CountryLabel\Plugin\Directory\Model;

use Magento\Directory\Model\Country;

/**
 * Plugin to override country names
 *
 * Intercepts Country::getName() to replace specific country names
 * Works across entire Magento: Admin, Frontend, API, Exports, PDF, etc.
 *
 * Hyva Theme Compatible: Uses only afterGetName() plugin to avoid
 * GraphQL/cache conflicts with afterLoadByCode()
 */
class CountryPlugin
{
    /**
     * Country code to custom label mapping
     *
     * @var array<string, string>
     */
    private array $countryLabelMapping = [
        'TW' => 'Taiwan China Region',
        // Add more country overrides here if needed:
        // 'US' => 'United States of America',
        // 'GB' => 'United Kingdom',
    ];

    /**
     * After plugin for getName()
     *
     * Replaces country name if mapping exists for the country code
     * This single plugin is sufficient as getName() is always called when displaying country names
     *
     * @param Country $subject
     * @param string|null $result
     * @return string|null
     */
    public function afterGetName(Country $subject, ?string $result): ?string
    {
        // Return early if no result or empty result
        if ($result === null || $result === '') {
            return $result;
        }

        // Get country code with proper type checking
        $countryCode = $subject->getCountryId();

        // Ensure country code is a non-empty string
        if (!is_string($countryCode) || $countryCode === '') {
            return $result;
        }

        // Return custom label if mapping exists
        if (isset($this->countryLabelMapping[$countryCode])) {
            return $this->countryLabelMapping[$countryCode];
        }

        return $result;
    }
}
