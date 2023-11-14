<?php
/*
	Template Name: Article Form Page
*/

ini_set('display_errors',1);
error_reporting(E_ALL);

	get_header();

    $file = __DIR__ . '/../../uploads/time_record.txt';
    $path = __DIR__ . '/../../uploads/last-article.xml';

    $title = '';
    $h1title = '';
    $meta_title = '';
    $url = '';
    $url_descr = '';
    $anchor = '';
    $post_url = '';
    $file_url = '';

    if(file_exists($path)) {
        $xmlstring = file_get_contents($path);
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $arrayLastArticle = json_decode($json, TRUE);

        $title = $arrayLastArticle["page"]["title"];
        $h1title = $arrayLastArticle["page"]["h1title"];
        $meta_title = $arrayLastArticle["page"]["meta_title"];
        $url = $arrayLastArticle["page"]["url"];
        $url_descr = $arrayLastArticle["page"]["url_descr"];
        $anchor = $arrayLastArticle["page"]["anchor"];
        $post_url = $arrayLastArticle["page"]["post_url"];
        $file_url = $arrayLastArticle["page"]["file"];
    }

    $current = (int)file_get_contents($file);
	
?>
<style>
    .container {
        position: relative;
        max-width: 100%;
        padding: 0 24px;
        display: flex;
        justify-content: center;
        gap: 50px;
    }
    form {
        max-width: 600px;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    label, input {
        display: block;
        width: 100%;
    }
    input {
        margin: 10px 0 20px;
        border: 2px solid #333333;
        border-radius: 12px;
        padding: 6px 12px;
        font-size: 16px;
        line-height: 24px;
    }
    button {
        cursor: pointer;
        background: #333;
        color: white;
        display: block;
        max-width: 320px;
        margin: 60px auto 0;
        width: 100%;
        border-radius: 12px;
        font-size: 16px;
        line-height: 24px;
        padding: 6px 12px;
    }

    button:disabled {
        opacity: 0.5;
    }

    h1 {
        margin-top: 200px;
        text-align: center;
    }
    .hidden {
        display: none;
    }
    .img {
        display: block;
        max-width: 400px;
        height: auto;
        margin-bottom: 40px;
    }
    .loader {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100vh;
        background-color: rgb(173 173 173 / 60%); 
    }
    .loader img {
        width: 100px;
        height: 100px;
    }
    .loader.show {
        display: flex;
    }
</style>
		<main>
			<div class="container">
                <div class="loader">
                    <img src="/wp-content/uploads/ajax-loader.gif" alt="loader">
                </div>
                <?php if(time() > $current) { ?>
                    <div>
                        <h3>Last record:</h3>
                        <p>TITLE: <?php echo $title;?></p>
                        <p>H1 TITLE: <?php echo $h1title;?></p>
                        <p>URL for Post: <a href="<?php echo home_url() . '/' . $post_url . '/'; ?>"><?php echo home_url() . '/' . $post_url . '/';?></a></p>
                        <p>META TITLE: <?php echo $meta_title;?></p>
                        <p>URL: <?php echo $url;?></p>
                        <p>URL Description: <?php echo $url_descr;?></p>
                        <p>Anchor: <?php echo $anchor;?></p>
                        <img src="<?php echo '/wp-content' . explode('wp-content', $file_url)[1];?>" alt="img" class="img">
                        <button type="button" id="btn-reg">REGENERATE</button>
                    </div>
                    <form id="article" action="/">
                        <h3>New record:</h3>
                        <label for="title">TITLE (theme)</label>
                        <input type="text" id="title" name="title" data-last="<?php echo $title;?>">
                        <label for="h1title">H1 - TITLE</label>
                        <input type="text" id="h1title" name="h1title" data-last="<?php echo $h1title;?>">
                        <label for="post_url">URL for Post</label>
                        <input type="text" id="post_url" name="post_url" data-last="<?php echo $post_url;?>">
                        <label for="title">META TITLE</label>
                        <input type="text" id="meta_title" name="meta_title" data-last="<?php echo $meta_title;?>">
                        <label for="url">URL</label>
                        <input type="text" id="url" name="url" data-last="<?php echo $url;?>">
                        <label for="url_descr">URL Description</label>
                        <input type="text" id="url_descr" name="url_descr" data-last="<?php echo $url_descr;?>">
                        <label for="anchor">Anchor (link title)</label>
                        <input type="text" id="anchor" name="anchor" data-last="<?php echo $anchor;?>">
                        <label for="file">IMG</label>
                        <input type="file" name="file" id="file">
                        <input type="text" name="file_url" id="file_url" class="hidden" data-last="<?php echo $file_url;?>">
                        <button type="submit" id="btn">SEND</button>
                    </form>
                <?php } else { ?>
                    <div class="loader show">
                        <img src="/wp-content/uploads/ajax-loader.gif" alt="loader">
                    </div>
                    <h1>Article loading...please wait, autoreload will happen in a minute</h1>
                    <script>
                        setTimeout(function(){
                            location.reload();
                        }, 60000);
                    </script>
                <?php } ?>
			</div>
		</main>
        <script>
        jQuery(document).ready(function() {
            jQuery('#btn-reg').on('click', function(e) {
                e.preventDefault()
                jQuery('#title')[0].value = jQuery(jQuery('#title')[0]).attr('data-last')
                jQuery('#h1title')[0].value = jQuery(jQuery('#h1title')[0]).attr('data-last')
                jQuery('#meta_title')[0].value = jQuery(jQuery('#meta_title')[0]).attr('data-last')
                jQuery('#url')[0].value = jQuery(jQuery('#url')[0]).attr('data-last')
                jQuery('#anchor')[0].value = jQuery(jQuery('#anchor')[0]).attr('data-last')
                jQuery('#url_descr')[0].value = jQuery(jQuery('#url_descr')[0]).attr('data-last')
                jQuery('#post_url')[0].value = jQuery(jQuery('#post_url')[0]).attr('data-last')
                jQuery('#file_url')[0].value = jQuery(jQuery('#file_url')[0]).attr('data-last')
                jQuery("form").submit()
            })
            jQuery("form").on("submit", function(event) {
                event.preventDefault()
                var formData = new FormData(this);
                
                if(jQuery('#title')[0].value.trim().length === 0 ||
                    jQuery('#h1title')[0].value.trim().length === 0 ||
                    jQuery('#meta_title')[0].value.trim().length === 0 ||
                    jQuery('#url')[0].value.trim().length === 0 ||
                    jQuery('#anchor')[0].value.trim().length === 0 ||
                    (jQuery('#file')[0].files.length === 0 && jQuery('#file_url')[0].value.trim().length === 0) ||
                    jQuery('#url_descr')[0].value.trim().length === 0 ||
                    jQuery('#post_url')[0].value.trim().length === 0) {
                    alert('All fields is required !!')
                } else {
                    jQuery('#btn').attr('disabled','true');
                    jQuery('.loader').addClass('show');
                    jQuery.ajax({
                        type: 'POST',
                        url: '/wp-content/uploads/article-script.php',
                        data: formData,
                        success: function(data) {
                            if(data == 'false') {
                                alert('Some error occured in API. Please resend request')
                                jQuery('#btn').prop("disabled", false)
                                jQuery('.loader').removeClass('show')
                            } 
                        },
                        error: function(jqXHR, exception) {
                            location.reload();
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            });
        });
        </script>
    </body>
</html>