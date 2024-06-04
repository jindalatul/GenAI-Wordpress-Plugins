<?php
require_once("content-AI-library.php");

/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,
 * or null if none.
 */
/* Create Custom Endpoint */

/*
End points
http://localhost/wordpress/wp-json/wp-content-ai/v1/custom-ep/

create a new POST request in your API testing tool, and submit the name and email data as a JSON object in the request body:
{
    "topic": "John Doe",
    "seed-keyword": "jon@gmail.com"
}
*/

add_action( 'rest_api_init', 'create_custon_endpoint' );

function create_custon_endpoint(){
    register_rest_route(
        'wp-content-ai/v2',
        '/credits',
        array(
            'methods' => 'GET',
            'callback' => 'wp_get_api_credits',
        )
    );

    register_rest_route(
        'wp-content-ai/v2',
        '/outlines/',
        array(
            'methods'  => 'POST',
            'callback' => 'wp_generate_ai_blog_outlines'
        )
    );

    register_rest_route(
        'wp-content-ai/v2',
        '/create-post/',
        array(
            'methods'  => 'POST',
            'callback' => 'wp_generate_ai_blog_post'
        )
    );

}

function wp_get_api_credits() {
    // your code
    return 'This is your data!';
}

function wp_generate_ai_blog_outlines( $request )
{
    /*
    array(
            'topic' => $request['topic'],
            'words' => $request['words'],
     )
    */
    
    //$result = GenerateIdeas(); 
    $result = GetBlogPostIdeas($request['topic']);
    echo $result;
    die();
}

function wp_generate_ai_blog_post($request)
{
    $data = $request->get_json_params();    //print_r($queryParams);
    $result = createArticle($data);
    echo $result;
    die();
}
?>