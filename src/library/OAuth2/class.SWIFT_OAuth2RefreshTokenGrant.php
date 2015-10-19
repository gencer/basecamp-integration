<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * Description of OAuth2CodeGrant code grant
 *
 * @author atul atri
 */
SWIFT_Loader::LoadLibrary('OAuth2:OAuth2GrantTypeBase');
SWIFT_Loader::LoadLibrary('OAuth2:OAuth2Exception');

class SWIFT_OAuth2RefreshTokenGrant extends  SWIFT_OAuth2GrantTypeBase
{

	/**
	 * Constructor
	 *
	 * @author Atul Atri
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		return true;
	}

	/**
	 * Destructor
	 *
	 * @author Atul Atri
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * Generate a Authorization Request url
	 *
	 * @param array $_params list of parameters required to build Url
	 * @param array $_params array('response_type' => 'response_type', 'client_id' => 'client_id',
	  'redirect_uri'=>'redirect_uri', 'scope' => 'scope', 'state' => 'state'), only client_id and end_point is required
	 *
	 * @return string Authorization Request url
	 * @throws SWIFT_OAuth2Exception if failed to create url
	 */
	public function GetAuthReqUrl($_endPointURL, array $_params)
	{
		throw new SWIFT_OAuth2Exception('Refresh Token Grant type does not support Authorization Request');
	}

	/**
	 * check  if required parametes are given and modify if necessary
	 *
	 * @author Atul Atri
	 * @param array $_params array of parameters
	 *
	 * @return array $_params modified parameters
	 * @throws SWIFT_OAuth2Exception if invlaid parameters are given
	 */
	protected function CheckTokenRequestParams(array $_params)
	{
		if (!isset($_params['refresh_token'])) {
			throw new SWIFT_OAuth2Exception('Refresh token is required to execute token request query');
		}

		if (!isset($_params['grant_type'])) {
			$_params['grant_type'] = 'refresh_token';
		}

		return $_params;
	}

}

?>