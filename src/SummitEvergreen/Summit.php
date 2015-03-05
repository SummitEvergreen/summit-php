<?php namespace SummitEvergreen;

use SummitEvergreen\SummitException;

class Summit {

	protected $apiKey;
	protected $acctHash;
	protected $requiredFields;
	protected $requestURL = "http://my.summitevergreen.com/purchases/webhook/";
	protected $requestData = array();
	protected $errors = array();
	protected $return;

	public function __construct ($acctHash, $apiKey)
	{
		$this->acctHash = $acctHash;
		$this->apiKey = $apiKey;

		$this->requiredFields = array(
			'email',
			'first_name',
			'last_name',
			'price',
			'sku'
		);
	}

	public function setPurchaseData($purchaseData)
	{
		$this->requestData['api'] = $this->apiKey;

		foreach ($this->requiredFields as $field)
		{
			if(isset($purchaseData[$field]))
			{
				$this->requestData[$field] = $purchaseData[$field];
			}
			else
			{
				$this->errors[] = "Missing purchase field: ".$field;
			}

		}

		return $this;
	}

	public function addPurchase()
	{
		// if we have an error in the earlier processes, we need to stop before we contact the remote server.
		if($this->haveErrors())
		{
			return $this->formatErrors();
		}

		$this->requestURL .= $this->acctHash . "/payment/";

		return $this->sendRequest();
	}


	public function doRefund()
	{
		// if we have an error in the earlier processes, we need to stop before we contact the remote server.
		if($this->haveErrors())
		{
			return $this->formatErrors();
		}

		$this->requestURL .= $this->acctHash . "/refund/";

		return $this->sendRequest();
	}

	private function sendRequest ()
	{
		$ch = curl_init($this->requestURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		return $result;
	}

	private function haveErrors()
	{
		if(count($this->errors) > 0)
		{
			return true;
		}
		return false;
	}

	private function formatErrors()
	{
		$return = array(
			'message' => 'SDK Error',
			'errors' => $this->errors
		);
		return json_encode($return);
	}
}