<?php
namespace App\Service;

use Psr\Log\LoggerInterface;
use GuzzleHttp\Client;
use App\Model\JobOffer;


class JobService
{

  private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

  public function getJobOffers()
  {
    // get token
    $token = $this->getToken();
    $results = array();

    $client = new Client([
      // Base URI is used with relative requests
      'base_uri' => 'https://api.emploi-store.fr/partenaire/offresdemploi/v2/offres/search',
    ]);

    $response = $client->get('?range=0-9&sort=1', [
      //'debug' => TRUE,
      'headers' => [
        'Authorization' => "Bearer ".$token."",
        'Content-Type' => 'application/json',
          'Accept' => 'application/json',
      ]
    ]);

    $json_a = json_decode($response->getBody(), true)['resultats'];
    foreach ($json_a as $job_offer) {
      $offer['job_title'] = $job_offer['appellationlibelle'];
      $offer['description']= $job_offer['description'] ? $job_offer['description'] : "";
      if (array_key_exists('entreprise', $job_offer)) {
        $offer['company'] = array_key_exists('nom', $job_offer['entreprise']) ? $job_offer['entreprise']['nom'] : "Anonyme";
      }
      $offer['location'] = $job_offer['lieuTravail']['libelle'];
      $offer['contract'] = $job_offer['typeContrat'];
      $offer['url']= $job_offer['origineOffre']['urlOrigine'] ? $job_offer['origineOffre']['urlOrigine'] : "";



      // $results[] = $offer;
      // $job_title = $job_offer['appellationlibelle'];
      // $description = $job_offer['description'];
      // if (array_key_exists('entreprise', $job_offer)) {
      //   $company = array_key_exists('nom', $job_offer['entreprise']) ? $job_offer['entreprise']['nom'] : "";
      // }
      // $location = $job_offer['lieuTravail']['libelle'];
      // $contract = $job_offer['typeContrat'];

      // $offer = new JobOffer($job_title, $description, $location, $contract, $company);

      $results[] = $offer;
    }

    //return json_a;
    return $results;
  }

  private function getToken() {

    $client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'https://entreprise.pole-emploi.fr/connexion/oauth2/access_token',
    ]);

    $data = "grant_type=client_credentials&client_id=".getenv('EMPLOI_ID')."&client_secret=".getenv('EMPLOI_KEY')."&scope=application_".getenv('EMPLOI_ID')."%20api_offresdemploiv2%20o2dsoffre";

    $response = $client->post('?realm=%2Fpartenaire', [
    //'debug' => TRUE,
    'body' => $data,
    'headers' => [
    'Content-Type' => 'application/x-www-form-urlencoded',
    ]
    ]);

    return json_decode($response->getBody(), true)['access_token'];

  }
}
