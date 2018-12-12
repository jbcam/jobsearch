<?php
namespace App\Model;

class JobOffer
{
  public $job_title;
  public $description;
  public $location;
  public $contract;
  public $company;

  public function __construct($job_title, $description, $location, $contract, $company) {
    $this->$job_title = $job_title;
    $this->$description = $description;
    $this->$location = $location;
    $this->$contract = $contract;
    $this->$company = $company;
  }

  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
  }

}
