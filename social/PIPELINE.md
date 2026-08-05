# SJ Systems LinkedIn post pipeline

The repeatable method for making a batch of single-image LinkedIn posts in the
house style. Follow it in order. It's written so a person or an assistant can
run it.

## Content rules (apply to everything)

- No em dashes, ever.
- Always use contractions (it's, don't, we'll, you're).
- British English.
- No emojis, no hashtags, no salesy language. Empathetic and grounded.
- Concise and punchy. Shorter beats longer.
- Conversational CTA, for example "drop us a message", never "contact us today".
- LinkedIn links go in the first comment only, never in the caption.
- Post at 07:30 to 08:00.
- Stats need a named, verifiable source before use. If you can't source it,
  don't use the number.
- Always web-search for current facts about any software, tool or product
  rather than trusting memory.
- SJ Systems is the default context. Yarmside is an SJ Systems client, not the
  subject of SJ marketing.

## Visual style (the "SJ Post" template)

- Size 1080 x 1350.
- Background: a black-and-white photo at about 70% opacity, with a teal
  `#062f35` overlay at about 58% on top. This gives the duotone look.
- Headline: bold rounded brand font, left aligned, white with the key phrase in
  cyan `#01a7b7`. Two beats with a blank line between them, for example
  "One altered invoice." then "One payment you'll never get back."
- Footer: a short white rule and `sjsystems` in cyan with `.co.uk` in white,
  bottom right.
- Keep headlines short enough not to collide with the footer. If a headline
  runs long, drop the font size (72 to 78) until it fits.

## Step by step

1. Research. Web-search current UK SME IT and cyber topics. Pick topics that
   aren't already in the recent history (see the "SJ Post" designs and the
   "LIN - Weekly Posts" master in Canva). Aim for a mix: a threat, a
   misconception, an opportunity, a practical tip.

2. Write the on-image copy as a two-beat line per topic, with one cyan phrase in
   each beat.

3. Build each design by duplicating an existing "SJ Post" design in Canva (this
   keeps the font, colours, duotone and footer identical), then swap the copy.
   Swap it run by run so the white and cyan colouring survives, rather than
   replacing the whole text block.

4. Source one distinct, relevant photo per post. Use royalty-free sources such
   as Unsplash direct CDN links (`images.unsplash.com/photo-...`). Upload into
   Canva and replace the photo on the page. Make each photo black and white
   (Duotone or Saturation 0). Never reuse the same photo across posts.

5. Write the caption to the content rules above. Structure that works: a vivid
   hook, a short plain-English explanation or a short list, a line about how SJ
   helps, then a conversational CTA. Put the link in the first comment, not the
   caption.

6. Schedule 3 a week, Monday / Wednesday / Friday at 07:30.

7. Load into Publer (bulk CSV import), review the queue, publish.

## Notes and known limits

- The Canva API can duplicate designs, swap text run by run, and replace photos,
  but it can't apply an image filter, so the black-and-white step is manual in
  the Canva editor.
- The Canva "combine pages into one file" API did not work reliably in testing,
  so posts are kept as one design each. That's fine for Publer, which takes one
  image per post anyway.
- The build environment can't reach Publer or LinkedIn, so the final import and
  publish is done by a person, or by an assistant in an environment that has
  Publer access. See README.md.
