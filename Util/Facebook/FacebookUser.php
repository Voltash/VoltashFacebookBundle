<?php
namespace Voltash\FbApplicationBundle\Util\Facebook;

use Voltash\FbApplicationBundle\Util\BaseFacebook\FacebookApiException;

/**
 * Created by JetBrains PhpStorm.
 * User: volt
 * Date: 06.09.13
 * Time: 14:03
 * To change this template use File | Settings | File Templates.
 */

class FacebookUser
{
    private $sdk;
    private $accessToken;

    function __construct(Facebook $sdk)
    {
        $this->sdk = $sdk;
    }

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->sdk->setAccessToken($accessToken);
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function uploadImage($msg, $img) {

        $this->sdk->setFileUploadSupport(true);
        try {
            $album_details = array(
                'message'=> 'Wave',
                'name'=> 'Wave'
            );
            $create_album = $this->sdk->api('/me/albums', 'post', $album_details);
            $photo_details = array(
                'message'=> $msg
            );
            $photo_details['image'] = '@' . realpath($img);
            return  $this->sdk->api('/'.$create_album['id'].'/photos', 'post', $photo_details);
        } catch (FacebookApiException $e) {
            return false;
        }

    }

    public function postToWall(array $attachment)
    {
        $attachment = array(
            'message' => 'some meesgae',
            'name' =>
            'This is my demo Facebook application!',
            'caption' => "Caption of the Post",
            'link' => 'http://mylink.com',
            'description' => 'this is a description',
            'picture' => 'http://mysite.com/pic.gif');
        try {
            return $this->sdk->api('/me/feed/','post',$attachment);
        } catch (FacebookApiException $e) {
            return false;
        }
    }




}