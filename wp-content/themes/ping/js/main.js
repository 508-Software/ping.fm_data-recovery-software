!function(t){t(document).on("click","section:last-child p:nth-child(even)",(function(){if("display: block;"===t(this).next().attr("style"))return t(this).next().hide("normal"),void t(this).removeClass("show");t(this).addClass("show"),t(this).next().show("normal")})),t(document).on("click","aside article",(function(){var n=t(this).find("a");n&&n[0].click()})),t(document).on("click",".homePosts article",(function(){var n=t(this).find("a");n&&n[0].click()})),t("main:not(.homepage) article > section:first-child").append('<span class="website">Official Website</span>'),t(document).on("click",".website",(function(n){var i=t("main:not(.homepage) article > section:first-child").find("a");i&&i[0].click()})),t(document).on("click","section div button",(function(){t(this).find(".quantity").hasClass("approved")||(t(this).find(".quantity").html(+t(this).find(".quantity").text()+1),t(this).find(".quantity").addClass("approved"))})),t("img").each(((n,i)=>{i.onerror=function(n){t(n.target).attr("src","./img/default.jpg"),t(n.target).attr("srcset","./img/default.jpg")}})),t(document).on("click",".menu-button",(function(){t(".menu-button").hasClass("open")?(t(".menu-button").removeClass("open"),t(".header-menu").removeClass("open")):(t(".menu-button").addClass("open"),t(".header-menu").addClass("open"))}))}(jQuery);
!function(t){t(document).on("click",".article",(function(){var i=t(this).find("a");i&&i[0].click()})),t(document).on("click",".toggle-link",(function(i){if("A"!==i.target.tagName){t(this).parent().toggleClass("active");const i=t(this).parent().find(".panel-collapse");parseInt(i.css("max-height"))?(i.css({"max-height":0}),i.removeClass("in")):(i.css({"max-height":i.prop("scrollHeight")+24+"px"}),i.addClass("in"))}}))}(jQuery);