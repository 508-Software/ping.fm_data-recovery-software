<?php
/*
	Template Name: Article Form Page
*/

ini_set('display_errors',1);
error_reporting(E_ALL);

	get_header();

    $file = __DIR__ . '/../../uploads/time_record.txt';
    $path = __DIR__ . '/../../uploads/wpallimport/files/generated-post.xml';

    $title = '';
    $h1title = '';
    $meta_title = '';
    $url = '';
    $url_descr = '';
    $anchor = '';
    $post_url = '';
    $file_url = '';
    $youtubeUrl = '';
    $faq_theme = '';

    if(file_exists($path)) {
        $xmlstring = file_get_contents($path);
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $arrayArticles = json_decode($json, TRUE);

        $arrayLastArticle = $arrayArticles;

        $title = $arrayLastArticle["page"]["title"];
        $h1title = $arrayLastArticle["page"]["h1title"];
        $meta_title = $arrayLastArticle["page"]["page_meta"];
        $url = $arrayLastArticle["page"]["url"];
        $url_descr = $arrayLastArticle["page"]["url_descr"];
        $anchor = $arrayLastArticle["page"]["anchor"];
        $post_url = $arrayLastArticle["page"]["post_url"];
        $file_url = $arrayLastArticle["page"]["page_image"];
        $youtubeUrl = !empty($arrayLastArticle["page"]["youtube_url"]) ? $arrayLastArticle["page"]["youtube_url"] : '';
        $apps_links = $arrayLastArticle["page"]["apps_links"];
        $faq_theme = $arrayLastArticle["page"]["faq_theme"];
    }

    $current = (int)file_get_contents($file);
	
?>
<style>
    main {
        padding-top: 0 !important;
    }
    .container {
        position: relative;
        max-width: 100% !important;
        padding: 0 24px !important;
        display: flex;
        justify-content: space-between;
        gap: 100px;
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
    .sBtn {
        background: #333 !important;
        color: white;
        display: block;
        max-width: 320px;
        margin: 60px 0 0;
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
        margin-top: 50px;
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
    #moreFAq {
        margin-top: 65px;
        text-align: center;
        font-weight: bold;
    }
    .checkbox {
        display:flex;
        align-items: center;
        font-weight: bold;
    }
    .checkbox input {
        width: 20px;
        height: 20px;
        margin: 0 12px 0 0;
    }
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        margin-left: -24px;
    }
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        width: 100%;
        font-size: 18px;
    }
    .tab button:hover {
        background-color: #ddd;
    }
    .tab button.active {
        background-color: #ccc;
    }
    .tabcontent {
        display: none;
        flex: 1 0;
        padding: 30px 0 0;
        border-top: none;
        animation: fadeEffect 1s;
    }
    .generate {
        color: green;
    }
    br {
        display: block !important;
    }
    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }
</style>
		<main>
			<div class="container">
                <div class="loader">
                    <h1>AI is working (2-4 mins to finish,<br> поки погодуй кота чи собаку ;-)</h1>
                    <img src="<?php echo home_url() . '/wp-content/uploads/ajax-loader.gif'; ?>" alt="loader">
                </div>
                <?php if(time() > $current) { ?>
                    <div class="tab">
                        <button class="tablinks" onclick="openTab(event, 'createdArticles')" id="defaultOpen">Generated Articles</button>
                        <button class="tablinks generate" onclick="openTab(event, 'generateArticle')" id="genNewArt">+ Generate Article</button>
                    </div>
                    <div id="createdArticles" class="tabcontent">
                        <div>
                            <h3>Last record:</h3>
                            <p>TITLE: <?php echo $title;?></p>
                            <p>H1 TITLE: <?php echo $h1title;?></p>
                            <p>URL for Post: <a target="_blank" href="<?php echo home_url() . '/' . $post_url . '/'; ?>"><?php echo home_url() . '/' . $post_url . '/';?></a></p>
                            <p>META TITLE: <?php echo $meta_title;?></p>
                            <p>URL: <?php echo $url;?></p>
                            <p>URL Description: <?php echo $url_descr;?></p>
                            <p>Anchor: <?php echo $anchor;?></p>
                            <img src="<?php echo home_url() . '/wp-content' . explode('wp-content', $file_url)[1];?>" alt="img" class="img">
                            <button type="button" class="sBtn" id="btn-reg">REGENERATE</button>

                            <form id="faqQuestions" action="/" data-action="<?php echo home_url() . '/wp-content/uploads/faq-script.php'; ?>">
                                <label for="btn-num-faq" id="moreFAq">ADD MORE FAQ QUESTIONS (default + 10)</label>
                                <input type="number" id="numberFaq" name="numberFaq" min="1" max="30" placeholder="Quantity questions (number only)">
                                <button  class="sBtn" type="button" id="btn-num-faq">ADD MORE QUESTIONS</button>
                            </form>
                        </div>
                    </div>
                    <div id="generateArticle" class="tabcontent">
                        <form id="article" action="/" data-action="<?php echo home_url() . '/wp-content/uploads/article-script.php'; ?>">
                            <h3>New record:</h3>
                            <label for="title">What would you like to write about (max 150 characters)</label>
                            <input type="text" id="title" name="title" maxlength="150" data-last="<?php echo $title;?>">
                            <label for="h1title">H1 (Article Title) (max 150 characters)</label>
                            <input type="text" id="h1title" name="h1title" maxlength="150" data-last="<?php echo $h1title;?>">
                            <label for="post_url">URL (/folder/url/)</label>
                            <input type="text" id="post_url" placeholder="slug" name="post_url" data-last="<?php echo $post_url;?>">
                            <label for="title">META TITLE</label>
                            <input type="text" id="meta_title" name="meta_title" data-last="<?php echo $meta_title;?>">
                            <label for="url">URL to pass link juice (Dofollow)</label>
                            <input type="text" id="url" name="url" data-last="<?php echo $url;?>">
                            <label for="url_descr">What does this link lead to, and where will users be directed if they click on it? (max 150 characters)</label>
                            <input type="text" id="url_descr" name="url_descr" maxlength="150" data-last="<?php echo $url_descr;?>">
                            <label for="anchor">Link Anchor (ex: “how to do something” Don’t spam!)</label>
                            <input type="text" id="anchor" name="anchor" data-last="<?php echo $anchor;?>">
                            <label for="file">Featured Image (JPG only)</label>
                            <input type="file" name="file" id="file">
                            <input type="text" name="file_url" id="file_url" class="hidden" data-last="<?php echo $file_url;?>">
                            <input type="text" name="domain_url" id="domain_url" class="hidden" value="<?php echo home_url(); ?>">
                            <label for="faq_theme">Please provide me with either a specific niche or a top keyword for which you would like an FAQ generated</label>
                            <input type="text" id="faq_theme" name="faq_theme" data-last="<?php echo $faq_theme;?>">
                            <label for="youtube_url">Add Youtube Link</label>
                            <input type="text" id="youtube_url" name="youtube_url" data-last="<?php echo $youtubeUrl;?>">
                            <label for="apps_links" class="checkbox">
                                <input type="checkbox" name="apps_links" id="apps_links" data-checked="<?php echo $apps_links; ?>">
                                Click here if you want your article to look like a “list of/best of” style (not a how to style)
                            </label>
                            <button  class="sBtn" type="submit" id="btn">Generate Article</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="loader show">
                        <h1>Article import...please wait,<br> autoreload will happen in a 1-2 minutes,<br> усі нагодовані :-)?</h1>
                        <img src="<?php echo home_url() . '/wp-content/uploads/ajax-loader.gif'; ?>" alt="loader">
                    </div>
                    <script>
                        setTimeout(function(){
                            let fullUrl = window.location.href
                            const needUrl = fullUrl.split('?')
                            window.location = needUrl[0]+'?eraseCache=' + Math.floor(Math.random() * 1000000000)
                        }, 120000);
                    </script>
                <?php } ?>
			</div>
		</main>
        <script>
        jQuery(document).ready(function() {
            jQuery('#btn-num-faq').on('click', function(e) {
                e.preventDefault()

                if(!jQuery('#numberFaq')[0].value) {
                    jQuery('#numberFaq')[0].value = 10
                }

                jQuery("#faqQuestions").submit()
                
            })
            jQuery("#faqQuestions").on("submit", function(event) {
                event.preventDefault()
                const formData = new FormData(this);

                if( jQuery('#numberFaq')[0].value.trim().length === 0 ) {
                        alert('All fields is required (Faq questions) !!') 
                } else {
                    jQuery('#btn-num-faq').attr('disabled','true');
                    jQuery('.loader').addClass('show');
                    jQuery.ajax({
                        type: 'POST',
                        url: jQuery("#faqQuestions").attr('data-action'),
                        data: formData,
                        success: function(data) {
                            if(data == 'false') {
                                alert('Some error occured in API. Please resend request')
                                jQuery('#btn').prop("disabled", false)
                                jQuery('.loader').removeClass('show')
                                return
                            }

                            alert('Faq updated and imported. Refresh page')
                        },
                        error: function(jqXHR, exception) {
                            if(exception === 'timeout') {     
                                alert('Failed from timeout');
                                return
                            }
                            alert('Some error occured in API. Please resend request')
                        },
                        cache: false,
                        contentType: false,
                        processData: false,
                        timeout: 120000
                    });
                }
            });
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
                jQuery('#youtube_url')[0].value = jQuery(jQuery('#youtube_url')[0]).attr('data-last')
                jQuery('#faq_theme')[0].value = jQuery(jQuery('#faq_theme')[0]).attr('data-last')
                if(jQuery(jQuery('#apps_links')[0]).attr('data-checked') == 'true') {
                    jQuery('#apps_links').prop('checked',true);
                } else {
                    jQuery('#apps_links').prop('checked',false);
                }
                document.getElementById("genNewArt").click();
                jQuery("#article").submit()
            })
            jQuery("#article").on("submit", function(event) {
                event.preventDefault()
                const formData = new FormData(this);
                
                if( jQuery('#title')[0].value.trim().length === 0 ||
                    jQuery('#h1title')[0].value.trim().length === 0 ||
                    jQuery('#meta_title')[0].value.trim().length === 0 ||
                    jQuery('#url')[0].value.trim().length === 0 ||
                    jQuery('#anchor')[0].value.trim().length === 0 ||
                    (jQuery('#file')[0].files.length === 0 && jQuery('#file_url')[0].value.trim().length === 0) ||
                    jQuery('#url_descr')[0].value.trim().length === 0 ||
                    jQuery('#post_url')[0].value.trim().length === 0) {
                    alert('All fields is required (Create new article) !!')
                } else {
                    jQuery('#btn').attr('disabled','true');
                    jQuery('.loader').addClass('show');
                    jQuery.ajax({
                        type: 'POST',
                        url: jQuery("#article").attr('data-action'),
                        data: formData,
                        success: function(data) {
                            if(data == 'false') {
                                alert('Some error occured in API. Please resend request')
                                jQuery('#btn').prop("disabled", false)
                                jQuery('.loader').removeClass('show')
                                return
                            }

                            alert('Article imported. Refresh page')
                        },
                        error: function(jqXHR, exception) {
                            setTimeout(function () {
                                // location.reload()
                            }, 20000);
                        },
                        cache: false,
                        contentType: false,
                        processData: false,
                        timeout: 120000
                    });
                }
            });
        });

        function openTab(evt, tabName) {
            let i, tabcontent, tablinks;

            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        document.getElementById("defaultOpen").click();
        </script>
    </body>
</html>