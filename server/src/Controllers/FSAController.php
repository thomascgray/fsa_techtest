<?php

namespace FsaTechTest\Controllers;

use GuzzleHttp\Client;
use FsaTechTest\Models\FsaModel as FsaModel;

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

        $model = new FsaModel();

        return $res->withJson([
            'payload' => [
                'localAuthorities' => $model->parseProfile($upstreamResponse->getBody()->getContents()),
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

        $model = new FsaModel();

        $output = $model->parseProfile($establishmentResponse->getBody()->getContents());

        return $res->withJson([
            'payload' => [
                'total' => $output['totalEstablishMents'],
                'tableData' => $output['finalTableData'],
            ]
        ]);
    }

    /**
     * [fetchEstablishments description]
     * @param  [type] $req  [description]
     * @param  [type] $res  [description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public function fetchEstablishments($req, $res, $args)
    {
        $data = $req->getParsedBody();
        $name = $data['name'];
        $address = urlencode($data['address']);

        $clientResponse = $this->fsaClient->get("Establishments?name={$name}&address={$address}&pageSize=0");

        $model = new FsaModel();
        $results = $model->parseEstablishmentSearchResults($clientResponse->getBody()->getContents());

        return $res->withJson([
            'payload' => [
                'establishments' => $results,
            ]
        ]);
    }
}
