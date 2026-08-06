# SJ Systems LinkedIn pipeline

The repeatable method for producing SJ Systems' weekly LinkedIn content: 3
single-image posts + 2 carousels per week. Written so a person or an assistant
can run it, and so it survives handover.

## Content rules (apply to everything)

- No em dashes, ever.
- Always use contractions (it's, don't, we'll, you're). Never "it is",
  "do not", "cannot".
- British English throughout.
- No emojis in body text (a single `👉 Link in the comments.` line at the end
  of a caption is the only allowed emoji). No hashtags.
- Concise and punchy. Shorter beats longer. Empathetic and grounded, never
  salesy.
- Conversational CTA, e.g. "drop us a message", never "contact us today".
- LinkedIn links go in the first comment only, never in the caption.
- Post at 07:30 to 08:00 Europe/London.
- Stats need a named, verifiable source before use. If you can't source it,
  don't use the number.
- Always web-search for current facts about any software, tool or product
  rather than trusting memory.
- SJ Systems is the default context. Yarmside is an SJ Systems client, not
  the subject of SJ marketing.

## The two post formats

### 1. Single posts (3 per week)

1080 x 1350. Full-bleed photo (colour or greyscale) with a teal `#062f35`
overlay at about 58% opacity. Left-aligned two-beat headline: white with the
key phrase in cyan `#01a7b7`. Short white rule + `sjsystems` (cyan) + `.co.uk`
(white) bottom-right.

### 2. Carousels (2 per week)

7 slides, 1080 x 1350 each: cover, five numbered content slides (each with a
cut-out person or object in a circle, teal accent), CTA slide with the "Drop
us a message" button. Headlines on content slides must fit one line where
possible; uniform 82px works for most. Images are B&W cut-outs (transparent
PNG) with a teal circle behind.

## Automated stack (what does work)

- **Copy, headings, captions, layout, scheduling** — fully automated by an
  assistant with Canva access, using the templates already in the account.
- **Single-post imagery** — assistant can pull a photo from a source with a
  known CDN URL and greyscale it via Cloudinary in the URL. Works reliably.
- **Carousel text/layout** — clone one of the existing carousel designs
  (see "Templates" below) and swap text run by run so the cyan accents
  survive.

## The image-sourcing gap (be honest here)

Carousel imagery is the one piece that isn't yet fully automated, and it's
the piece that matters most because your images carry meaning. Random
smiling portraits look wrong next to specific headlines.

There are three options for handling images. Pick one before handover.

### Option A: Pexels API (Recommended for automation)

Pexels' free API explicitly permits automated and commercial use with no
attribution required. If a Pexels API key is added to the automation, the
assistant can:

1. Read the slide's meaning (e.g. "response time = a watch").
2. Search Pexels for that concept.
3. Run the result through Cloudinary background removal + greyscale.
4. Place it in the slide.

Setup: sign up at pexels.com/api, copy the key. Store it against a shared
SJ account, not a personal login.

### Option B: A paid stock library

Adobe Stock, Shutterstock or iStock — paid tiers exist that explicitly allow
API automation. Cost, but no licensing grey area and higher-quality curated
imagery. Overkill unless the volume grows.

### Option C: A person picks the images (honest fallback)

Assistant produces everything else — copy, layout, headings, captions,
schedule. The one manual step is: someone opens Canva, drops a suitable
cut-out from your existing bank (or creates a new one with Canva's
Background Remover) onto each carousel slide before publishing. About five
minutes per carousel.

**Do not use Unsplash for the automated route.** Unsplash's API terms
prohibit automated collection and off-Unsplash storage, which is what
Cloudinary caching does.

## The Cloudinary layer (image processing)

Cloud name: `rhnxcfga`. Owned on a shared SJ account. Free tier.

Add-ons enabled:
- AI Background Removal (`e_background_removal`)

Settings that matter (Settings → Security):
- Fetched URL delivery: allowed
- Restricted media types: fetch not restricted
- Allowed fetch domains: add whichever image source you use (e.g.
  `images.pexels.com`) once picked

Verified URL pattern (for any allowed remote photo URL `<PHOTO_URL>`):

```
https://res.cloudinary.com/rhnxcfga/image/fetch/e_background_removal,e_grayscale/<PHOTO_URL>
```

Test asset that proved this works end to end: Canva asset `MAHRgoyapmY` — a
businesswoman portrait fetched from a stock URL, cut out and greyscaled by
Cloudinary, ingested by Canva without any human step. See the
"buyer's questions" carousel (`DAHRgs2Sexo`) for a working example, though
note the images there are placeholder-quality and should be re-generated
once a licensed source is chosen.

Quality caveat: background removal works cleanly on tight object close-ups
(a watch, a pen on paper, a magnifying glass) and on people photographed
against plain backdrops. It struggles with busy backgrounds and shallow-focus
office shots. If the assistant uses a source photo with a messy background,
the resulting "cut-out" will still have background in it. Fix: brief tighter,
plainer photos in the search.

## Templates and assets

- Single post template: `SJ Post – <topic>` designs. Any of them works as
  a clone base.
- Carousel template (good-support): `DAHQ9JlBfj0` — clone this for new
  carousels; keep its cut-out image geometry intact.
- Carousel template (tech-debt): `DAHRUSV8Elg` — same layout, alternative
  base.
- Brand kit: `kAHOPJIDEOw` ("Yarmside" — historical name).
- Brand kit: `kAFwfCMrIeY` ("SJ Systems - V2") — use this one.

## Step by step, per weekly batch

1. **Research.** Web-search for current UK SME IT/cyber topics not
   already covered in your existing library (see the "SJ Post" designs and
   "LIN - Weekly Posts" master).

2. **Plan the week.** 3 single posts + 2 carousels, spaced Mon/Wed/Fri
   at 07:30. Cover a mix of threat, education, opportunity, and one
   practical tip.

3. **Draft the copy first.** For each post/slide, write the headline and
   body before picking any image. This is the step that keeps meaning
   attached to imagery.

4. **Design brief for each image.** For carousels especially, name the
   concept the image should carry (e.g. "response time → a watch";
   "contract → a signature"). Not "a smiling portrait".

5. **Source the image.** Use whichever route from the "image-sourcing gap"
   section is set up. Verify each result visually via `get-assets` before
   placing it. Re-roll if the cut-out has background retained.

6. **Build in Canva.** Clone the appropriate template, swap text run by
   run (so cyan accents survive), then update each image fill. Never
   `position_element` on template shapes — it breaks the composition.

7. **Write captions** to the content rules above. Structure that works: a
   vivid hook, plain-English explanation or short list, a line about how
   SJ helps, then a conversational CTA. Link in the first comment.

8. **Schedule** via Publer bulk-import CSV (see `publer-import.csv` for
   format). Publisher publishes automatically on schedule.

## Known API limits and workarounds

- Canva `update_fill` replaces an image fill entirely. Any editor-applied
  filter on the original fill is destroyed. Solution: always source images
  that are already correctly treated (transparent PNG, greyscale) via the
  Cloudinary URL, so no editor filter is needed.
- Canva `merge-designs` / `insert_pages` was unreliable during setup.
  Symptom: "success" with no page added. Workaround: build each carousel
  as its own file, don't try to append pages by API.
- Canva `position_element` uses coordinates that differ from the visual
  layout. Don't use it unless you're prepared to re-read positions
  afterwards. Prefer `update_fill` and text swaps only.

## Cleanup: designs to delete

The following designs are broken drafts from setup and should be deleted:

- `DAHRalbcMPg`, `DAHRatAuK2o`, `DAHRaiYa_iI` — off-brand "Instagram Post"
  drafts from an early misfire.
- `DAHRaip2lBg` — an empty 1-page "SJ Posts – Aug batch" file (a Canva
  merge attempt that never populated).
- `DAHRbmDJG9c`, `DAHRbm920As` — duplicate carousels of the "reactive
  support" idea, which the "good support" carousel already covers.
- `DAHRgs2Sexo` — the "buyer's questions" carousel proof-of-concept.
  Its images are placeholder-quality; keep only if you rebuild the imagery
  once a licensed source is set up.
