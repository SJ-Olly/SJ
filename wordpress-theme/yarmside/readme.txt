# Yarmside — WordPress theme

A custom WordPress theme for **Yarmside Lettings and Management Ltd**, built from the
Yarmside site design and brand guidelines. The theme lives in [`yarmside/`](yarmside/).

## Brand compliance

- **Colours** — exactly the four palette colours from the brand guidelines
  (Yarmside orange `#d8651e`, dark grey `#141414`, orange cream `#f5f1ea`,
  grey orange `#8e867a`). Every other shade on the site is a percentage tint
  of those four, generated in CSS. Colours can be adjusted under
  **Appearance → Customise → Yarmside settings → Brand colours** and the whole
  site (including the block editor palette) follows.
- **Typography** — Montserrat (500/600/700) for headlines, Raleway (400/500)
  for body copy. One bold headline, clean Raleway underneath, body text
  left-aligned, per the typography rules.
- **Logo** — the supplied brand file is bundled and used automatically; upload
  a replacement under **Customise → Site Identity → Logo**. It is never
  rendered below the 120px minimum width.

## Installation

1. Zip the theme folder: `cd` to this repo and run `zip -r yarmside.zip yarmside`.
2. In WordPress admin go to **Appearance → Themes → Add New Theme → Upload Theme**
   and upload `yarmside.zip` (or copy `yarmside/` into `wp-content/themes/`).
3. Activate it. First activation automatically creates:
   - all pages from the design — **Home**, **Landlords**, **About**, **Contact**
     and **Journal** (set as the posts page), with the design's content built
     from editable block patterns;
   - the **Properties** section (custom post type) with the archive at `/properties/`;
   - the primary menu and the three footer menu columns;
   - example properties and journal articles matching the design, ready to be
     replaced with real content.
4. Go to **Settings → Permalinks** and click **Save** once to flush permalinks
   if property links 404.

## Day-to-day use

| Task | Where |
| --- | --- |
| Add a property | **Properties → Add property** — title is the address, featured image is the main photo, "The particulars" box holds rent/beds/baths/EPC etc., side box adds gallery photos. The excerpt is the card description. Tick "Feature this property" to place it in the homepage bay. |
| Add a journal article | **Posts → Add New** — category becomes the card tag, read time is calculated automatically. |
| Add a page | **Pages → Add New** — the title becomes the branded page heading, the excerpt becomes the lede underneath it. Insert ready-made branded sections from the block inserter → **Patterns → Yarmside** (service tiers, process band, testimonial, CTA band, team grid, spec list, contact layout). |
| Edit homepage copy | **Appearance → Customise → Yarmside settings → Homepage** (hero photo, headings, testimonial, CTA). |
| Contact details | **Customise → Yarmside settings → Contact details** — feeds the contact page, footer, structured data and the enquiry form recipient. |
| Enquiry form | Already on the Contact page (`[yarmside_valuation_form]` shortcode — drop it on any page). Submissions are emailed to the contact email, with honeypot + nonce spam protection, no plugin needed. |

## Theme notes

- Property archive filters (All / Houses / Flats / Available now) work from the
  property type taxonomy and lettings status — new property types automatically
  get a filter button.
- Performance: single stylesheet, one deferred script, preconnected fonts,
  cropped image sizes for every card context, no emoji/embed cruft.
- Accessibility: skip link, focus states, aria labels/pressed states,
  reduced-motion support.
- SEO: RealEstateAgent structured data driven by the Customizer, clean titles
  via `title-tag`.
