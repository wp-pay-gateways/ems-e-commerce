<?php

/**
 * Title: EMS e-Commerce client
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_EMS_ECommerce_Client {
	/**
	 * Action URL to start a payment request in the test environment,
	 * the POST data is sent to.
	 *
	 * @see page 14 - http://pronamic.nl/wp-content/uploads/2013/10/integratiehandleiding_rabo_omnikassa_en_versie_5_0_juni_2013_10_29451215.pdf
	 * @var string
	 */
	const ACTION_URL_TEST = 'https://test.ipg-online.com/connect/gateway/processing';

	/**
	 * Action URL For a payment request in the production environment,
	 * the POST data is sent to
	 *
	 * @see page 14 - http://pronamic.nl/wp-content/uploads/2013/10/integratiehandleiding_rabo_omnikassa_en_versie_5_0_juni_2013_10_29451215.pdf
	 * @var string
	 */
	const ACTION_URL_PRODUCTION = 'https://www.ipg-online.com/connect/gateway/processing';

	//////////////////////////////////////////////////

	const ISO_639_1_ENGLISH = 'en';

	const ISO_639_1_FRENCH = 'fr';

	const ISO_639_1_GERMAN = 'de';

	const ISO_639_1_ITALIAN = 'it';

	const ISO_639_1_SPANISH = 'es';

	const ISO_639_1_DUTCH = 'nl';

	//////////////////////////////////////////////////

	public static function get_supported_language_codes() {
		return array(
			self::ISO_639_1_ENGLISH,
			self::ISO_639_1_FRENCH,
			self::ISO_639_1_GERMAN,
			self::ISO_639_1_ITALIAN,
			self::ISO_639_1_SPANISH,
			self::ISO_639_1_DUTCH,
		);
	}

	public static function is_supported_language( $language ) {
		$languages = self::get_supported_language_codes();

		return in_array( $language, $languages, true );
	}

	//////////////////////////////////////////////////

	/**
	 * Hash algorithm SHA256 indicator
	 *
	 * @var string
	 */
	const HASH_ALGORITHM_SHA256 = 'sha256';

	//////////////////////////////////////////////////

	/**
	 * The action URL
	 *
	 * @var string
	 */
	private $action_url;

	//////////////////////////////////////////////////

	/**
	 * Currency code in ISO 4217-Numeric codification
	 *
	 * @doc http://en.wikipedia.org/wiki/ISO_4217
	 * @doc http://www.iso.org/iso/support/faqs/faqs_widely_used_standards/widely_used_standards_other/currency_codes/currency_codes_list-1.htm
	 *
	 * @var string N3
	 */
	private $currency_numeric_code;

	/**
	 * Storename
	 *
	 * @var string N15 @todo DOC - Storename format requirement
	 */
	private $storename;

	/**
	 * Normal return URL
	 *
	 * @var string ANS512 url
	 */
	private $return_url;

	/**
	 * Amount
	 *
	 * @var string N12
	 */
	private $amount;

	/**
	 * Transaction reference
	 *
	 * @var string AN35
	 */
	private $transaction_reference;

	/**
	 * Key version
	 *
	 * @var string N10
	 */
	private $key_version;

	//////////////////////////////////////////////////

	/**
	 * Automatic response URL
	 *
	 * @var string ANS512 url
	 */
	private $automatic_response_url;

	/**
	 * Customer language in ISO 639‐1 Alpha2
	 *
	 * @doc http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
	 * @var string A2
	 */
	private $customer_language;

	/**
	 * Payment method
	 *
	 * @var array
	 */
	private $payment_method;

	/**
	 * Order ID
	 *
	 * @var string AN32
	 */
	private $order_id;

	/**
	 * Expiration date
	 *
	 * @var DateTime
	 */
	private $expiration_date;

	//////////////////////////////////////////////////

	/**
	 * Shared secret
	 *
	 * @var string
	 */
	private $secret;

	//////////////////////////////////////////////////

	/**
	 * Issuer ID.
	 *
	 * @var string
	 */
	private $issuer_id;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initalize an EMS e-Commerce object
	 */
	public function __construct() {
	}

	//////////////////////////////////////////////////

	/**
	 * Get the action URL
	 *
	 * @return the action URL
	 */
	public function get_action_url() {
		return $this->action_url;
	}

	/**
	 * Set the action URL
	 *
	 * @param string $url an URL
	 */
	public function set_action_url( $url ) {
		$this->action_url = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the currency numeric code
	 *
	 * @return string currency numeric code
	 */
	public function get_currency_numeric_code() {
		return $this->currency_numeric_code;
	}

	/**
	 * Set the currency code
	 *
	 * @param string $currencyCode
	 */
	public function set_currency_numeric_code( $currency_numeric_code ) {
		$this->currency_numeric_code = $currency_numeric_code;
	}

	//////////////////////////////////////////////////

	/**
	 * Get storename
	 *
	 * @return string
	 */
	public function get_storename() {
		return $this->storename;
	}

	/**
	 * Set the storename
	 *
	 * @param string $merchant_id
	 */
	public function set_storename( $storename ) {
		$this->storename = $storename;
	}

	//////////////////////////////////////////////////

	/**
	 * Get normal return URL
	 *
	 * @return string
	 */
	public function get_return_url() {
		return $this->return_url;
	}

	/**
	 * Set the normal return URL
	 *
	 * LET OP! De URL mag geen parameters bevatten.
	 *
	 * @param string $return_url
	 */
	public function set_return_url( $return_url ) {
		$this->return_url = $return_url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get amount
	 *
	 * @return float
	 */
	public function get_amount() {
		return $this->amount;
	}

	/**
	 * Get formmated amount
	 *
	 * @return int
	 */
	public function get_formatted_amount() {
		return Pronamic_WP_Pay_Util::amount_to_cents( $this->amount );
	}

	/**
	 * Set amount
	 *
	 * @param float $amount
	 */
	public function set_amount( $amount ) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	/**
	 * Get transaction reference
	 *
	 * @return string
	 */
	public function get_transaction_reference() {
		return $this->transaction_reference;
	}

	/**
	 * Set transaction reference
	 * AN..max35 (AN = Alphanumeric, free text)
	 *
	 * @param string $transactionReference
	 */
	public function set_transaction_reference( $transaction_reference ) {
		$this->transaction_reference = Pronamic_WP_Pay_Gateways_EMS_ECommerce_DataHelper::filter_an( $transaction_reference, 35 );
	}

	//////////////////////////////////////////////////

	/**
	 * Get key version
	 *
	 * @return string
	 */
	public function get_key_version() {
		return $this->key_version;
	}

	/**
	 * Set key version
	 *
	 * @param string $key_version
	 */
	public function set_key_version( $key_version ) {
		$this->key_version = $key_version;
	}

	//////////////////////////////////////////////////

	/**
	 * Get automatic response URL
	 *
	 * @return string
	 */
	public function get_automatic_response_url() {
		return $this->automatic_response_url;
	}

	/**
	 * Set automatic response URL
	 *
	 * LET OP! De URL mag geen parameters bevatten.
	 *
	 * @param string $automatic_response_url
	 */
	public function set_automatic_response_url( $automatic_response_url ) {
		$this->automatic_response_url = $automatic_response_url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get customer language
	 *
	 * @return string
	 */
	public function get_customer_language() {
		return $this->customer_language;
	}

	/**
	 * Set customer language
	 *
	 * @param string $customerLanguage
	 */
	public function set_customer_language( $customer_language ) {
		$this->customer_language = $customer_language;
	}

	//////////////////////////////////////////////////

	/**
	 * Set the payment method.
	 *
	 * @param string $payment_method
	 */
	public function set_payment_method( $payment_method ) {
		$this->payment_method = $payment_method;
	}

	/**
	 * Get the payment method.
	 *
	 * @return string ANS128 listString comma separated list
	 */
	public function get_payment_method() {
		return $this->payment_method;
	}

	//////////////////////////////////////////////////

	/**
	 * Get order ID
	 *
	 * @return string
	 */
	public function get_order_id() {
		return $this->order_id;
	}

	/**
	 * Set order ID
	 *
	 * @param string $orderId
	 */
	public function set_order_id( $order_id ) {
		$this->order_id = $order_id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get expiration date
	 *
	 * @return DateTime
	 */
	public function get_expiration_date() {
		return $this->expiration_date;
	}

	/**
	 * Get expiration date
	 *
	 * @return string
	 */
	public function get_formatted_expiration_date() {
		$result = null;

		if ( null !== $this->expiration_date ) {
			$result = $this->expiration_date->format( DATE_ISO8601 );
		}

		return $result;
	}

	/**
	 * Set expiration date
	 *
	 * @param DateTime $expirationDate
	 */
	public function set_expiration_date( DateTime $date = null ) {
		$this->expiration_date = $date;
	}

	//////////////////////////////////////////////////

	/**
	 * Get data
	 *
	 * @return string
	 */
	public function get_data() {
		// Payment Request - required fields
		$required_fields = array(
			'txntype'        => 'sale',
			'timezone'       => 'Europe/Amsterdam',
			'txndatetime'    => current_time( 'Y:m:d-H:i:s' ),
			'hash_algorithm' => 'SHA256',
			'storename'      => $this->get_storename(),
			'mode'           => 'payonly',
			'chargetotal'    => number_format( ( $this->get_formatted_amount() / 100 ), 2 ),
			'currency'       => $this->get_currency_numeric_code(),
		);

		// Payment request - optional fields
		$optional_fields = array(
//			'transactionReference' => $this->get_transaction_reference(),
//			'expirationDate'       => $this->get_formatted_expiration_date(),
			'oid'                => $this->get_order_id(),
			'language'           => $this->get_customer_language(),
			'paymentMethod'      => $this->get_payment_method(),
			'responseFailURL'    => $this->get_return_url(),
			'responseSuccessURL' => $this->get_return_url(),
			'idealIssuerID'      => $this->get_issuer_id(),
		);

		// @see http://briancray.com/2009/04/25/remove-null-values-php-arrays/
		$optional_fields = array_filter( $optional_fields );

		// Data
		$data = $required_fields + $optional_fields;

		return $data;
	}

	//////////////////////////////////////////////////

	/**
	 * Get shared secret
	 *
	 * @return string
	 */
	public function get_secret() {
		return $this->secret;
	}

	/**
	 * Set shared secret
	 *
	 * @return string
	 */
	public function set_secret( $secret ) {
		$this->secret = $secret;
	}

	//////////////////////////////////////////////////

	/**
	 * Get hash
	 *
	 * @return string
	 */
	public function get_hash() {
		$data   = $this->get_data();
		$secret = $this->get_secret();

		return self::compute_hash( $data, $secret );
	}

	/**
	 * Compute hash
	 *
	 * @param string $data
	 * @param string $secret
	 *
	 * @return string
	 */
	public static function compute_hash( $data, $secret ) {
		$value = $data['storename'] . $data['txndatetime'] . $data['chargetotal'] . $data['currency'] . $secret;

		$value = bin2hex( $value );

		return hash( self::HASH_ALGORITHM_SHA256, $value );
	}

	//////////////////////////////////////////////////

	/**
	 * Get fields
	 *
	 * @since 1.1.2
	 * @return array
	 */
	public function get_fields() {
		$fields = $this->get_data();

		$fields['hash'] = $this->get_hash();

		return $fields;
	}

	//////////////////////////////////////////////////

	public function get_response_code_description() {
		return array(
			'00' => 'Transaction success, authorization accepted',
			'02' => 'Please call the bank because the authorization limit on the card has been exceeded',
			'03' => 'Invalid merchant contract',
			'05' => 'Do not honor, authorization refused',
			'12' => 'Invalid transaction, check the parameters sent in the request',
			'14' => 'Invalid card number or invalid Card Security Code or Card (for MasterCard) or invalid Card Verification Value (for Visa/MAESTRO)',
			'17' => 'Cancellation of payment by the end user',
			'24' => 'Invalid status',
			'25' => 'Transaction not found in database',
			'30' => 'Invalid format',
			'34' => 'Fraud suspicion',
			'40' => 'Operation not allowed to this Merchant',
			'60' => 'Pending transaction',
			'63' => 'Security breach detected, transaction stopped',
			'75' => 'The number of attempts to enter the card number has been exceeded (three tries exhausted)',
			'90' => 'Acquirer server temporarily unavailable',
			'94' => 'Duplicate transaction',
			'97' => 'Request time-out; transaction refused',
			'99' => 'Payment page temporarily unavailable',
		);
	}

	//////////////////////////////////////////////////

	public function set_issuer_id( $issuer_id ) {
		$this->issuer_id = $issuer_id;
	}

	public function get_issuer_id() {
		return $this->issuer_id;
	}
}