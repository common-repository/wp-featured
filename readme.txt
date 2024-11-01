=== WordPress Featured Listings ===
Contributors: Jared Ritchey and Damian Danielczyk
Donate link: http://www.jaredritchey.com/
Tags: MLS, IDX, real estate, listings, RETS, Automotive, Properties, Gallery
Requires at least: 2.6+
Tested up to: 2.7.1
Stable tag: 0.9.9

WordPress Featured Listings Plugin was developed to provide a website owners with an easy way to add dynamic featured listings for real estate or automotive realted products to posts and pages.

== Description ==
Jared Ritchey Design Presents, WP Featured for WordPress Real Estate Blogging at its best. Developed originally for our client use only, we have released this tool as a contribution to the WordPress community in support of the Worlds best Real Estate website platform, WordPress. This plugin provides blog website owners with a truly easy to manage easy to integrate way for featuring property listings in their posts and pages.  Ideal for the Real Estate, Automotive, Furniture, or any gallery type product related site, this plugin makes featuring products a breeze.

Tested with version 2.7.1 and WordPress MU, the plugin can support hundreds of featured listings and can be displayed or presented based entirely on user defined criteria providing the criteria is searchable. Even though this plugin was most beneficial in the hands of developers and web designers we have added the first version of a templated system to allow a relatively novice user the ability to customize the look and feel of the charted layouts. This does not mean that we have stopped supporting developers.  This plug-in does require OpenRealty 2.5.6 or above.

> For Theme Developers, please visit jaredritchey.com for details on how to distribute this plugin with your WordPress themes FREELY and Without Additional License Requirements. We can provide you easy to include PHP code to make your distribution of the plugin exceptionally easy.

== Installation ==
1. Extract the `wp-featured.zip` to your local system prior to upload.
1. EDIT featured-listings.php at this point to provide the root path to your openrealty install as follows $wpfeatures_openrealty_path = "/home/account/public_html/openrealty/";  FOR HELP CONTACT ME on my blog.
1. Upload `wp-featured` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugin Panel' in WordPress
1. While in the WordPress admin panel, simply navigate to the WP Featured by clicking on the tab for "SETTINGS".
1. Once you create a new Featured template, you want to take the Zillow Template Tag which is displayed in the list at the bottom and add it to a template tag as follows `{featured_1}, {featured_2} and so on` The tag number is the template ID number generated automatically.
1. To style the layout templates you can either create as many templates as you so desire or use the default existing example and modify it accordingly. The template is located in the /wp-content/plugins/wp-featured/templates folder.

== Frequently Asked Questions ==
1. DO I NEED OPEN REALTY?
*YES and you can download it by visiting http://www.open-realty.org*
1. Will this work with non real estate related projects?
*I have this working now on an Automotive dealership, a Furniture store and a pet adoption site so yes.!*
1. Can I display more than one template tag per post?
*You can but you may experience a seriously slow page load.*
1. Will this work in WordPress Multi User?
*Yes, we have this very plugin installed on Real Estate sites where there are literally dozens of Realtor agent blogs.*
1. *Are there any special settings I should be aware of?*
*Yes there are a few very important ones, the first is that the plugin can be modified to prevent people from deleting template instances which is ideal for using it in a WordPress MU type setting and there is also a pair of video tutorials you can examine to help with configuration by visiting [JaredRitchey](http://www.jaredritchey.com/wp-featured/ "Featured Instructional Videos")*
1. Are there any linkback requirements?
*Linkback Requirements and posts about the product are certainly appreciated but by no means required. With all of our plugins we do not add link back features by default either. :-)*
1. Can I distribute this plugin with my themes (commercial or gpl)?
*Yes, I have some code that will assist you in doing this so distribution will be much easier for your projects.*


== Screenshots ==
1. `/tags/0.9.8/screenshot-1.jpg`
2. `/tags/0.9.8/screenshot-2.jpg`
3. `/tags/0.9.8/screenshot-3.jpg`
4. `/tags/0.9.8/screenshot-4.jpg`

== Important Recent Updates This Release ==
1. BUG FIX FOR FILE PATH, it is now required to open the core file and manually add the OpenRealty path until the next release.
1. Fully CSS XHTML validated output of the chart results.
1. Introduction of the new template system for easy styling and structuring.
1. Integrated feature to allow for an animated slideshow.

== Features In Next Release ==
1. Support for a slideshow feature using the JW Image Rotator.
1. Support for a slideshow feature using jQuery.
1. It will offer a widgeted version so you can display them in the sidebar.
1. THESIS THEME support will certainly be added.

== Additional Configuration Notes ==
Support forum is brand spaking new and is scheduled to be open for configuration helps and notes on Saturday May 16th

== Quick Start Guide ==
1. First field should be your ID number like MLS Number for instance, then add things like city or maybe state, one field is required.
1. Determine the layout you want, most of the time people can style these as a side by side or simply a stacked listings.
1. Where are you planning to use the featured listing? A page or a post.
1. Its BEST TO put the WordPress READMORE tag above the listings tag because it can make your site real long if following a standard blog format.
1. As always additional and more detailed information is available on the JaredRitchey.com website.

== What Kind Styling Can I Really Do ==
* The fact is this, we built this for our clients originally and design was first on our mind. Every element is rendered based on the template you create, in our demo we use div tags for wrapping them so styling has a great many potential avenues.
* Styling can be customized to be animated but I have not yet included that code.

> Example layouts and css can be found on the project(s) site located at JaredRitchey.com or by contacting us for technical support.

