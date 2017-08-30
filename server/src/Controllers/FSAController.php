<?php

namespace FsaTechTest\Controllers;

use GuzzleHttp\Client;

/**
 * an example controller to process requests
 */
class FSAController
{
    /**
     * a guzzle client to talk to the FSA API with
     * @var \GuzzleHttp\Client
     */
    private $fsaClient;

    public function __construct()
    {
        $this->fsaClient = new Client([
            'base_uri' => \Env::get('FSA_API_BASE_URL'),
            'headers' => [
                'x-api-version' => \Env::get('FSA_API_VERSION')
            ]
        ]);
    }

    /**
     * contact the FSA API to grab all the local authorities, process them
     * into a friendly format for the SPA, and return them
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $req
     * @param  \Psr\Http\Message\ResponseInterface $res
     * @param  array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listLocalAuthorities($req, $res, $args)
    {
        $upstreamResponse = $this->fsaClient->get('Authorities/basic');

        $localAuthorities = [];

        $data = json_decode($upstreamResponse->getBody()->getContents(), true);

        foreach ($data['authorities'] as $authority) {
            $localAuthorities[] = [
                'value' => $authority['LocalAuthorityId'],
                'label' => $authority['Name'],
            ];
        }

        return $res->withJson([
            'payload' => [
                'localAuthorities' => $localAuthorities
            ]
        ], 200);
    }

    /**
     * contact the FSA API to retrieve establishments for a given
     * local authority ID, process them into a usable format by the SPA,
     * and return them
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $req
     * @param  \Psr\Http\Message\ResponseInterface $res
     * @param  array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getEstablishmentsProfilePerLocalAuthority($req, $res, $args)
    {
        $id = $args['localAuthorityId'];

        $establishmentResponse = $this->fsaClient->get("Establishments?localAuthorityId={$id}&pageSize=0");

        $data = json_decode($establishmentResponse->getBody()->getContents(), true);

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
        $finalTableData = [];
        foreach ($ratings as $key => $ratingData) {
            $finalTableData[] = [
                'rating' => $key,
                'count' => number_format($ratingData['count']),
                'percentage' => $ratingData['percentage'] . '%',
            ];
        }

        return $res->withJson([
            'payload' => [
                'total' => $totalEstablishMents,
                'tableData' => $finalTableData,
            ]
        ]);
    }
}
