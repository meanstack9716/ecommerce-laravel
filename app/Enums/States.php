<?php

namespace App\Enums;

enum States: string
{
    // States
    case ANDHRA_PRADESH = 'Andhra Pradesh';
    case ARUNACHAL_PRADESH = 'Arunachal Pradesh';
    case ASSAM = 'Assam';
    case BIHAR = 'Bihar';
    case CHHATTISGARH = 'Chhattisgarh';
    case GOA = 'Goa';
    case GUJARAT = 'Gujarat';
    case HARYANA = 'Haryana';
    case HIMACHAL_PRADESH = 'Himachal Pradesh';
    case JHARKHAND = 'Jharkhand';
    case KARNATAKA = 'Karnataka';
    case KERALA = 'Kerala';
    case MADHYA_PRADESH = 'Madhya Pradesh';
    case MAHARASHTRA = 'Maharashtra';
    case MANIPUR = 'Manipur';
    case MEGHALAYA = 'Meghalaya';
    case MIZORAM = 'Mizoram';
    case NAGALAND = 'Nagaland';
    case ODISHA = 'Odisha';
    case PUNJAB = 'Punjab';
    case RAJASTHAN = 'Rajasthan';
    case SIKKIM = 'Sikkim';
    case TAMIL_NADU = 'Tamil Nadu';
    case TELANGANA = 'Telangana';
    case TRIPURA = 'Tripura';
    case UTTAR_PRADESH = 'Uttar Pradesh';
    case UTTARAKHAND = 'Uttarakhand';
    case WEST_BENGAL = 'West Bengal';

    // Union Territories
    case ANDAMAN_NICOBAR_ISLANDS = 'Andaman and Nicobar Islands';
    case CHANDIGARH = 'Chandigarh';
    case DADRA_NAGAR_HAVELI_DAMAN_DIU = 'Dadra and Nagar Haveli and Daman and Diu';
    case DELHI = 'Delhi';
    case JAMMU_KASHMIR = 'Jammu and Kashmir';
    case LADAKH = 'Ladakh';
    case LAKSHADWEEP = 'Lakshadweep';
    case PUDUCHERRY = 'Puducherry';

    /**
     * Get all state/UT values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all state/UTs as a key-value array (value => value)
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($state) => [$state->value => $state->value])->toArray();
    }
}
