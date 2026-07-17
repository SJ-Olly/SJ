=== Yarmside ===
Contributors: sjsystems
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Bespoke WordPress theme for Yarmside Lettings. The brand palette, typography
and layout system are fixed to the Yarmside brand guidelines; everything
editorial is editable from the dashboard.

== Setting up ==

1. Appearance > Themes > Add New > Upload Theme, then activate Yarmside.
2. Settings > Reading: set "Your homepage displays" to a static page.
   Create a page called Home (homepage) and one called Journal (posts page).
3. Create a page called Contact and assign the "Contact with valuation form"
   template. Its excerpt becomes the intro line under the heading.
4. Appearance > Menus: create the primary menu and the three footer column
   menus, and assign each to its location.
5. Appearance > Customise:
   - Site Identity: upload the supplied Yarmside logo file.
   - Homepage hero: heading, supporting line, hero photograph.
   - Homepage testimonial: quote and attribution (leave empty to hide).
   - Contact details: email, phone, address, hours. These feed the contact
     page and its structured data.
   - Footer: description and legal line.
6. Add homes under Properties in the dashboard. Each property has a
   Particulars box (type, rent, beds, baths, floor area, status), a featured
   image, an excerpt (used on the featured panel) and a description. The
   newest property becomes the featured panel on the homepage.
7. Write journal articles as ordinary Posts with a featured image and a
   category. The three most recent appear on the homepage.

== How the styling stays consistent ==

Pages use the same page header, type scale and spacing as the rest of the
site automatically. Post and page content is styled through the theme's
entry-content rules, and the editor colour palette is locked to the four
brand colours, so future content can't drift off the guidelines. Custom
colours and gradients are disabled deliberately.

== Notes ==

- The valuation form emails the address set in Customise > Contact details
  (falling back to the site admin email). For reliable delivery configure
  an SMTP plugin such as WP Mail SMTP.
- The Properties post type is registered by the theme so this proposal is
  self-contained. If the site will ever switch themes, move that
  registration into a small plugin so the property content survives.
