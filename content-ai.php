<?php
/*
Plugin Name: ContentAI Plugin
Description: A simple plugin that generates Blog Post Using Google Gemini APIs
*/
require_once("rest-endpoints.php");

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
ini_set("display_errors","On");

function my_prefix_add_admin_menus() 
{
	add_menu_page(
		'ContentAI', /* Page title */
		'ContentAI', /* Menu title */
		'manage_options', /* Capability */
		'content-ai-admin-menu', /* Unique Menu slug */
		'content_ai_callback', /* Callback */
		'dashicons-admin-generic', /* Icon */
		5 /* Position 1 (very top because why not) */
	);

    add_submenu_page(
		'content-ai-admin-menu',
		'Settings',
		'Settings',
		'manage_options',
		'content-ai-settings',
		'content_ai_settings_callback'
	);
}

add_action( 'admin_menu', 'my_prefix_add_admin_menus' );

/**
 * Callback function to render the menu page.
 */
function content_ai_callback()
{
	?>
    <style>
        /** Spinner */
        .loader {
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
            margin-left: auto;
            margin-right: auto;
            display:none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    /** Outline Cards */

    /* Float four columns side by side */
    .column {
    float: left;
    width: 25%;
    padding: 0 10px;
    }

    /* Remove extra left and right margins, due to padding */
    .row {margin: 0 -5px;}

    /* Clear floats after the columns */
    .row:after {
    content: "";
    display: table;
    clear: both;
    }

    /* Responsive columns */
    @media screen and (max-width: 600px) {
    .column {
        width: 100%;
        display: block;
        margin-bottom: 20px;
    }
    }

    /* Style the counter cards */
    .card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    padding: 16px;
    text-align: left;
    background-color: #f1f1f1;
    height:300px;
    width:300px;
    }

    /* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

    /* Modal Content */
    .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 600px;
    }

    /* The Close Button */
    .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    }

    .close:hover,
    .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
    }
    </style>
    <script type="text/javascript">
    jQuery(document).ready(function() { 

        var topicsList={};

        jQuery("#runAPIbtn").click(function(){
                    jQuery("#spinner").show();
                    jQuery("#runAPIbtn").prop("disabled",true);
                    jQuery("#blog-ideas").html("");

                    var data={};
                    data.topic=jQuery("#prompt").val();
                    data.words=jQuery("#words").val();

                    makeAPIRequest(data);

    });

    function makeAPIRequest(data)
    {
        // pass your data in method
     jQuery.ajax({
             type: "POST",
             url: "http://localhost/wordpress/wp-json/wp-content-ai/v2/outlines/",
             data: JSON.stringify(data),// now data come in this function
             contentType: "application/json; charset=utf-8",
             crossDomain: true,
             dataType: "json",
             success: function (data, status, jqXHR) {
                topicsList=data;
                 console.log(data);
                 //alert("success");
                 showCards(data);

             },

             error: function (jqXHR, status) {
                 // error handler
                 alert("fail");
                 console.log(jqXHR);
                 alert('fail' + status.code);
             }
          });
    }

    function showCards(mydata)
    {
        
        //console.log(mydata);

        jQuery("#spinner").hide();
        jQuery("#runAPIbtn").prop("disabled",false);
        jQuery("#blog-ideas").html("");
        var output="";
        // create outline in card format here and print out into div card
        for (let i = 0; i < mydata.length; i++) 
        {
            output+= '<div class="column"><div class="card"><h1>'+mydata[i].topic + "</h1><h5>"+mydata[i].summary+"</h5>";
            output+='<button class="view-outline-btn" data-item-index='+i+'>View Outline</button>';
            output+='<button class="create-article-btn" data-item-index='+i+' style="margin-left:10px;">Create Article</button>';
            output+="</div></div>";
        }

        jQuery('#blog-ideas').html(output);

        jQuery(".view-outline-btn").each(function () {
            jQuery(this).on( "click", function() {
                // prepare div box for showing the outline

                var itemIndex= jQuery(this).data("item-index");
                var outlineItem = topicsList[itemIndex];

                createModelContent(outlineItem,itemIndex);

                console.log(outlineItem);

                jQuery("#myModal").show();
                jQuery("#close-modal").on( "click", function() {
                    jQuery("#myModal").hide();
                });
                return false;
            });
        });
        
        jQuery(".create-article-btn").each(function () {
                jQuery(this).on( "click", function() {
                        var itemIndex= jQuery(this).data("item-index");
                        var outlineItem = topicsList[itemIndex];
                        
                        console.log(outlineItem);

                        jQuery.ajax({
                                type: "POST",
                                url: "http://localhost/wordpress/wp-json/wp-content-ai/v2/create-post/",
                                data: JSON.stringify(outlineItem),// now data come in this function
                                contentType: "application/json; charset=utf-8",
                                crossDomain: true,
                                dataType: 'json',
                                success: function (data, status, jqXHR) {
                                    console.log(data);
                                },

                                error: function (jqXHR, status) {
                                    // error handler
                                    //alert("fail");
                                    console.log(jqXHR);
                                    //alert('fail' + status.code);
                                }
                            });

                });
        });

    }

    function createModelContent(outlineItem,itemIndex)
    {
        var html="";
        html+="<h2>"+outlineItem.topic;
        html+='<button class="create-article" data-item-index='+itemIndex+' style="margin-left:10px;">Create Article</button>';
        html+="</h2>";
        html+="<p>"+outlineItem.summary+"</p>";
        
        outlineItem.outline.forEach((element) => {
            html+="<h4>"+element.subtopic+"</h4>";
            html+="<p>"+element.subtopic_summary+"</p>";
        });

        jQuery("#outline-content-model").html(html);
    }

});// jQuery Ends

    </script>

	<div class="wrap">
		<h1>Generate Blog Post</h1>
        <br>
        <input type="text"   id="prompt" name="prompt" value="Digital marketing trends 2023" style="width:600px" placeholder="Blog Post Topic" />
        <input type="number" id="words"  name="words"  value="1500" style="width:110px" placeholder="Word count" /> Between 100-1500 words
        <br> <br>
        <input type="button" id="runAPIbtn" name="runAPI" value="Generate Blog Post" />
	</div>
    <div class="wrap" id="response-area" style="position: relative;">
         <div class="loader" id="spinner"></div>
         <div class="row" id="blog-ideas">
         </div>
    </div>

    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close" id="close-modal">&times;</span>
            <div id="outline-content-model"></div>
        </div>
    </div>

	<?php
}

/**
 * Callback function to render the movie settings page.
 */
function content_ai_settings_callback() {
	?>
    <script type="text/javascript">
        function submit_content_ai_settings()
        {
            alert(1);
            // Call AJAX to show blog outlines below.
        }
    </script>
	<div class="wrap">
		<h1><?php esc_html_e( 'Settings', 'my-prefix' ); ?></h1>
        <form id="settings-form" method="post" action="options.php" onsubmit="return submit_content_ai_settings()">
            <?php settings_fields( 'content-ai-settings-group' ); ?>
            <?php do_settings_sections( 'content-ai-settings-group' ); ?>
            <p>Credentials</p>
            <table class="form-table">
                <tr valign="top">
                <th scope="row">Enter Google Gemini API Key</th>
                <td><input type="text" name="google_gemini_api_key" value="<?php echo esc_attr( get_option('google_gemini_api_key') ); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
	</div>
	<?php
}

add_action( 'admin_init', 'content_ai_settings' );

function content_ai_settings() {
	register_setting( 'content-ai-settings-group', 'google_gemini_api_key' );
}

?>