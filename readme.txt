=== Woocommerce Newsletter Image Generator ===
Tags: newsletter, image, generator, woocommerce_newsletter_image, newsletter_image_generator
Requires at least: 5.2
Tested up to: 5.2
Requires PHP: 5.3
Stable tag: trunk

This plugin allow you as a shop owner to create images for the products with custom text or images so you can create an email campaign or newsletter with the price and prices.

== Description ==

Don't like reading ? see this [video](https://vimeo.com/367379058)

**Requirement for the plugin**
* imagick extension
* Please make sure you enabled imagick extension on your server.

**Description**
This plugin allow you as a shop owner to create images for the products with custom text or images so you can create an email campaign or newsletter with the prices.

So let's say you have a shop with some products, and you want to create an email campaign, you will have when you enable this plugin and new endpoint or route that you can specify a list of parameters and options to manipulate the product image.

**Example to get the image endpoint:**
* *Production:*
/entry/?productId=32&width=100&height=100&options=eyJ0b3BMZWZ0Ijp7InNob3dQcm9kdWN0VGl0bGUifX0=
* *Testing:*
/entry/?productId=32&test&width=100&height=100

So what is the difference, if you just want to test the image rendered you can provide the `test` parameter without no value and this will get the options from the database, but this will not be the case in the production env as the customer want something fast we don't to have overload on out servers

Then on production (live) we just provide the encode64 string for the options, `options={string}`, where you find it ? you will find it the options page.

You will have when you enable the plugin 6 positions in the product image like below
![product image](https://i.ibb.co/LPLNCWg/product-positions.jpg)

**For each position you have the following options**

**Free Options**

* Top left position
* Middle left position
* Product title

**Premium Options**

* Top left postion
* middle left position 
* bottom left position
* Right top position
* Middle right position
* Bottom right position
* product title, position x, position y, text color, text size, text background
* product category, position x, position y, text color, text size, text background
* active price, position x, position y, text color, text size, text background
* product sale price, position x, position y, text color, text size, text background
* product regular price, position x, position y, text color, text size, text background
* image url which you can upload first in the media part, position x, position y

**What if you want something more custom** [for premium users]
You can have some custom text with placeholder for the above options also, example let's say you want to display on the product image text like \"the product x has an awesome price which is 10$"
Then you add the input field text like this "the product {{product_title}} has an awesome price which is {{product_active_price}}" and when you add this in the input field, the plugin will just replace the placeholder with the right values, which in this case product title and the product active price.

So what is the available options also ?
* `{{product_title}}`
* `{{product_active_price}}`
* `{{product_sale_price}}`
* `{{product_regular_price}}`
* `{{product_category}}`

Do you have other custom options to add, just send to me a message and I will add it also.


== Installation ==
Upload the plugin zip file to the /wp-content/plugins/ directory
Activate the plugin through the ‘Plugins’ menu in WordPress

== Frequently Asked Questions ==

= to be added =
to be answered.

== Changelog ==

= 1.0 =
* init version

== Screenshots ==
1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png