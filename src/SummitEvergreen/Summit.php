<?php namespace SummitEvergreen;

/**
 * Class Summit
 * @package SummitEvergreen
 */
class Summit {

	protected $apiKey;
	protected $acctKey;
	protected $requiredFields;
	protected $optionalFields;
	protected $requestURL = "https://my.summitevergreen.com/purchases/webhook/";
	protected $requestData = array();
	protected $errors = array();
	protected $return;

	/**
	 * @param $acctKey - Customer Account Key
	 * @param $apiKey - Customer API Key
	 */
	public function __construct ($acctKey, $apiKey)
	{
		$this->acctKey = $acctKey;
		$this->apiKey = $apiKey;

		$this->requiredFields = array(
			'email',
			'first_name',
			'last_name',
			'price',
			'sku'
		);

		$this->optionalFields = array(
			'order_id',
			'payment_id'
		);
	}

	/**
	 * @param array $purchaseData
	 *
	 * @return $this
	 */
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

		foreach ($this->optionalFields as $field)
		{
			if(isset($purchaseData[$field]))
			{
				$this->requestData[$field] = $purchaseData[$field];
			}
		}

		return $this;
	}

	/**
	 * Returns json_encoded string.
	 * @return mixed|string
	 */
	public function addPurchase()
	{
		// if we have an error in the earlier processes, we need to stop before we contact the remote server.
		if($this->haveErrors())
		{
			return $this->formatErrors();
		}

		$this->requestURL .= $this->acctKey . "/payment/custom/";

		return $this->sendRequest();
	}


	/**
	 * Returns json_encoded string.
	 * @return mixed|string
	 */
	public function doRefund()
	{
		// if we have an error in the earlier processes, we need to stop before we contact the remote server.
		if($this->haveErrors())
		{
			return $this->formatErrors();
		}

		$this->requestURL .= $this->acctKey . "/refund/custom/";

		return $this->sendRequest();
	}

	/**
	 * @return mixed
	 */
	private function sendRequest ()
	{
		$ch = curl_init($this->requestURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		return $result;
	}

	/**
	 * @return bool
	 */
	private function haveErrors()
	{
		if(count($this->errors) > 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	private function formatErrors()
	{
		$return = array(
			'message' => 'SDK Error',
			'errors' => $this->errors
		);
		return json_encode($return);
	}
}
