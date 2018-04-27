<?php

	/**
	 * Find this API at https://github.com/AlwokeInteractive/MinimalGraphAPI/
	 * @author Kevin Gregull for Alwoke Interactive
	 */
	class AlwokeFB
	{
		private $appID="";
		private $appSecret="";
		private $token=null;
		
		/**
		 * Construct Facebook with AppID and AppSecret
		 * @param string $appID
		 * @param string $appSecret
		 */
		public function __construct($appID,$appSecret)
		{
			$this->appID=$appID;
			$this->appSecret=$appSecret;
		}
		
		/**
		 * Returns the AppToken
		 * @return string AppToken
		 */
		public function GetAppToken()
		{
			return $this->appID."|".$this->appSecret;	
		}
		
		/**
		 * Returns a Login URL that you can redirect the User to
		 * @param string $redirect The URL to which Facebook will redirect the User after Login
		 * @param string $scope The permissions that you want to access. (Seperated with ",")
		 * @return string The Facebook OAuth Login URL
		 */
		public function GetLoginURL($redirect,$scope=null)
		{
			if ($scope===null)
			{
				return "https://www.facebook.com/v2.12/dialog/oauth?client_id=".$this->appID."&redirect_uri=".urlencode($redirect);
			}
			else
			{
				return "https://www.facebook.com/v2.12/dialog/oauth?client_id=".$this->appID."&redirect_uri=".urlencode($redirect)."&scope=".urlencode($scope);
			}
		}
		
		/**
		 * Set a Token to overwrite to AppToken in case you need more Permissions
		 * @param string $token
		 */
		public function SetToken($token)
		{
			$this->token=$token;
		}
		
		/**
		 * Returns either the set Token or AppToken as Fallback
		 * @return string - The Token
		 */
		public function GetToken()
		{
			if (isset($this->token))
			{
				return $this->token;
			}
			else
			{
				return $this->GetAppToken();
			}
		}
		
		private $proxyType=CURLPROXY_HTTP;
		private $proxyHost="";
		private $proxyAuth="";
		public function SetProxy($host,$type=CURLPROXY_HTTP,$auth="")
		{
			$this->proxyType=$type;
			$this->proxyHost=$host;
			$this->proxyAuth=$auth;
		}
		
		/**
		 * Call Facebook API mit newest Version
		 * @param string $url - The Relative URL (Starts with /)
		 * @param string $method - POST,GET,PUT
		 * @throws Exception - If a CURL Error occured!
		 */
		public function API($url,$method="GET",$params=array(),$raw=false)
		{
			// Parse Parameters
			$params["method"]=$method;
			
			if (preg_match("/oauth/",$url))
			{
				$params["client_id"]=$this->appID;
				$params["client_secret"]=$this->appSecret;
			}
			else
			{
				$params["access_token"]=$this->GetToken();
			}
			
			$curl=curl_init();
			$options=array(CURLOPT_URL=>(($raw)?"":"https://graph.facebook.com").$url,CURLOPT_POSTFIELDS=>http_build_query($params,null,'&'),CURLOPT_CUSTOMREQUEST=>"POST",CURLOPT_RETURNTRANSFER=>true,CURLOPT_ENCODING=>"",CURLOPT_MAXREDIRS=>10,CURLOPT_TIMEOUT=>30,CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,CURLOPT_PROXYTYPE=>$this->proxyType,CURLOPT_PROXY=>$this->proxyHost,CURLOPT_PROXYAUTH=>$this->proxyAuth);
			curl_setopt_array($curl,$options);
			$text=curl_exec($curl);
			$error=curl_error($curl);
			if ($error=="")
			{
				return json_decode($text);
			}
			else
			{
				throw new Exception($error);
			}
		}
	}
  
  
