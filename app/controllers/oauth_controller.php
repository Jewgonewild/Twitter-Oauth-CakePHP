<?php 
/**
 * Oauth Controller
 *
 * PHP 5
 *
 * @category Controller
 * @package  EP
 * @version  1.0
 * @author   Emmanuel P <hello@pozo.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://github.com/Jewgonewild/Twitter-Oauth-CakePHP
 */
class OauthController extends AppController
{
	var $name = 'oauth';
	var $uses = array('User');
	var $layout = 'ajax'; //Change this to match your layouts
	var $components = array('OauthConsumer');
	
	//Oauth callback function.
	function index($type='twitter'){
		
		if($type=='twitter')
		{
			$access_token_url = TWITTER_ACCESS_TOKEN_URL;
			$requestToken = $this->Session->read('requestToken');
			$accessToken = $this->OauthConsumer->getAccessToken($type,$access_token_url,$requestToken);
			$accessToken->key;
			$accessKey = $accessToken->key;
			$accessSecret = $accessToken->secret;
			
			if($accessToken!='' && $requestToken!='')
			{
				//Fields returned from successful Twitter Oauth return.
				$uid = '';
				$info = $this->get_twitter_info($accessKey,$accessSecret);
				$twitter_id = $info->id;
				$screen_name = $info->screen_name;
				$img = str_replace('_normal','',$info->profile_image_url);
				$location = $info->location;
				$oauth_secret = $accessToken->secret;
				$name = $info->name;
				$description = $info->description;
				
				$in_system = $this->User->find("WHERE twitter_id='".$twitter_id."'","id,twitter_id");
			
				if(!empty($in_system))
					$uid = $in_system['User']['id'];
			
				$user_data = array('User'=>array('id'=>$uid,'twitter_id'=>$twitter_id,'screen_name'=>utf8_encode($screen_name),'img'=>$img,'location'=>$location,'oauth_key'=>$accessKey,'oauth_secret'=>$accessSecret,'name'=>$name,'description'=>$description));
				
				//Persisit the data as either an insert or an update.
				$this->User->save($user_data);
				
				//Persisit Session
				if($this->User->id!=NULL) 
				{
					$this->Session->write('User.id', $this->User->id);
					$this->Session->write('User.screen_name', $screen_name);
					$this->Session->write('User.img', $img);
					$this->redirect(array('controller' => 'users', 'action' => 'index'));
					exit();
				}
				else
					echo 'DB error, please retry'; //Handle error messages gracefully in production.
					
			}
			else
				echo 'error'; //Handle error messages gracefully in production.
		}
	}
		
	//OAUTH function that redirects to authorize URL.
	function authorize($type){
		$request_token_url = TWITTER_REQUEST_TOKEN_URL;
		$authorize_token_url = TWITTER_AUTHORIZE_URL;
		$requestToken = $this->OauthConsumer->getRequestToken($type,$request_token_url,"POST");
		$this->Session->write('requestToken', $requestToken);
		$this->redirect($authorize_token_url.$requestToken->key);
		exit();
	}
	
	//Helper function that retrieves info from Twitter on behalf of an authorized user.
	private function get_twitter_info($key,$secret){
		$info = $this->OauthConsumer->get("twitter","https://twitter.com/account/verify_credentials.json",$key,$secret);
		return json_decode($info);	
	}
}
?>