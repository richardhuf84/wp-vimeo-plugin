<?php
  /**
   * Use the Vimeo PHP library to do stuff!
   *
   * See https://github.com/vimeo/vimeo.php
   *
   */

// Require the autoload.php that will load the Vimeo PHP library. We load it
// manually, rather than using composer.
require( CUSTOM_PLUGIN_PATH . "/vimeo.php/autoload.php");


// Vimeo Client id for my app
$client_id = '987142d8d121526342ffb6a08f331d160bb93c59';

// Vimeo Client secret for my app
$client_secret = 'h/2sgJF4z0rbxsKEMI+1n38czMFyNpFS5aoRFZ9kZmc4i4DPajSLefoMWS0al7UF5ufkuKM+PvkLtcuy+x5Kdwvy+i5N/UW9zgZPm6ULrW/dUUV62fHGGwEVkXSnyiAa';

$lib = new \Vimeo\Vimeo($client_id, $client_secret);

// scope is an array of permissions your token needs to access. You can read more at https://developer.vimeo.com/api/authentication#scopes
$token = $lib->clientCredentials('public');

// usable access token
$public_token = $token['body']['access_token'];


// accepted scopes
// var_dump($token['body']['scope']);

// use the token
$lib->setToken($token['body']['access_token']);

// Make a request
$response = $lib->request('/videos', array('query' => 'grindstone'), 'GET');

var_dump($response);

/**
 * get_video_uri_by_id function summary
 *
 * Gets video from vimeo by video ID and returns that video's URI
 *
 * @param type var Description
 * @return return type string $video_uri
 */
function get_video_uri_by_id($vimeo_id='')
{
  $response = $lib->request('/videos/' . $vimeo_id, 'GET');

  return $video_uri;
}


// echo '<pre>';
// var_dump($video_query);
// echo '</pre>';
// die;

// print_r($response);
// die;



/**
 * Query the Vimeo API
 *
 * Do a search using a Video title and return a list of results for the user to choose from
 *
 * @param type string $post_ID, object $post, calback $update
 * @return return type string
 *
 */

function query_custom_plugin_API_by_video_title( $post_ID, $post, $update )
{

  if( $post->post_type !== 'vimeo-video' )
  {
    return;
  }

  // First, get the game title entered on the posts meta box using the post ID
  $queried_game_title = get_post_meta( $post_ID, '_custom_plugin_meta_value_key', true );

  // CPT field: Description - ACF field
  $description_field_key = 'field_58607fcb79ee8';

  // Search the API for the game title
  $query_url = CUSTOM_PLUGIN_BASE_URL . CUSTOM_PLUGIN_API_KEY . "&parameter=1";

  $description_field_new_value  = 'test';

  update_field( $description_field_key, $description_field_new_value, $post_ID );

}


add_action( 'save_post', 'query_custom_plugin_API_by_video_title', 10, 3 );
