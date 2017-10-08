<?php

namespace FsaTechTest\Models;

class FsaModel
{
    public function parseLocalAuthorities($rawClientData)
    {
        $data = json_decode($rawClientData, true);

        foreach ($data['authorities'] as $authority) {
            $localAuthorities[] = [
                'value' => $authority['LocalAuthorityId'],
                'label' => $authority['Name'],
            ];
        }

        return $localAuthorities;
    }

    public function parseProfile($rawClientData)
    {
        $data = json_decode($rawClientData, true);

        $totalEstablishMents = 0;
        $ratings = [];

        // first pass parses the raw FSA API data into something
        // workable
        foreach ($data['establishments'] as $establishment) {
            $establishmentRating = $establishment['RatingValue'];
            if (empty($ratings[$establishmentRating]['count'])) {
                $ratings[$establishmentRating]['count'] = 0;
            }
            $ratings[$establishmentRating]['count'] = $ratings[$establishmentRating]['count'] + 1;
            $totalEstablishMents++;
        }

        // second pass adds up some percentages
        foreach ($ratings as $key => $ratingData) {
            $ratings[$key]['percentage'] = number_format(($ratingData['count'] / $totalEstablishMents) * 100);
        }

        // final pass parses our final calculations into something easy
        // for the SPA to display
        $tableData = [];
        foreach ($ratings as $key => $ratingData) {
            $tableData[] = [
                'rating' => $key,
                'count' => number_format($ratingData['count']),
                'percentage' => $ratingData['percentage'] . '%',
            ];
        }

        return [
            'totalEstablishMents' => $totalEstablishMents,
            'tableData' => $tableData,
        ];
    }

    public function parseEstablishmentSearchResults($rawClientData)
    {
        $clientData = json_decode($rawClientData, true);

        $results = [];

        foreach ($clientData['establishments'] as $establishment) {
            $results[] = [
                'name' => $establishment['BusinessName'],
                'type' => $establishment['BusinessType'],
                'address' => join([$establishment['AddressLine1'], $establishment['AddressLine2'], $establishment['AddressLine3'], $establishment['AddressLine4'], $establishment['PostCode']], ', '),
                'rating' => $establishment['RatingValue'],
                'rating_date' => $establishment['RatingDate']
            ];
        }

        return $results;
    }
}
