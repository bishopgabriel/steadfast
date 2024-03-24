<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use GeoIp2\Exception\AddressNotFoundException;

class EstimateLocation
{
    protected $reader;

    public function __construct()
    {
        $databasePath = database_path('GeoLite2-City.mmdb');
        $this->reader = new Reader($databasePath);
    }

    public function getUserLocation(Request $request)
    {
        try{
            $ipAddress = $request->ip();
            $record = $this->reader->city($ipAddress);

            return [
                'status' => true,
                'city' => $record->city->name ?? null,
                'state' => $record->mostSpecificSubdivision->name ?? null,
                'country' => $record->country->name ?? null,
                'latitude' => $record->location->latitude ?? null,
                'longitude' => $record->location->longitude ?? null,
            ];
        } catch (AddressNotFoundException $e) {
            // Handle case where location information is not found for the provided IP
            return [
                'status' => false,
                'error' => 'Location information not found for the provided IP.',
            ];
        } catch (\Exception $e) {
            // Handle other exceptions
            return [
                'status' => false,
                'error' => 'An error occurred while fetching location information.',
            ];
        }
    }
}