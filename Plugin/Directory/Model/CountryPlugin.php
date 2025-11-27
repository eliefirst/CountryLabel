<?php
declare(strict_types=1);
 
namespace Elie\CountryLabel\Plugin\Directory\Model;
 
use Magento\Directory\Model\Country;
 
/**
 * Plugin to override country names
 *
 * Intercepts Country::getName() to replace specific country names
 * Works across entire Magento: Admin, Frontend, API, Exports, PDF, etc.
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
     *
     * @param Country $subject
     * @param string|null $result
     * @return string|null
     */
    public function afterGetName(Country $subject, ?string $result): ?string
    {
        if ($result === null) {
            return $result;
        }
 
        $countryCode = $subject->getCountryId();
 
        if ($countryCode && isset($this->countryLabelMapping[$countryCode])) {
            return $this->countryLabelMapping[$countryCode];
        }
 
        return $result;
    }
 
    /**
     * After plugin for loadByCode()
     *
     * Ensures the custom name is applied when loading country by code
     *
     * @param Country $subject
     * @param Country $result
     * @return Country
     */
    public function afterLoadByCode(Country $subject, Country $result): Country
    {
        $countryCode = $result->getCountryId();
 
        if ($countryCode && isset($this->countryLabelMapping[$countryCode])) {
            $result->setData('name', $this->countryLabelMapping[$countryCode]);
        }
 
        return $result;
    }
}
