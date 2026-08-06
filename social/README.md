# SJ Systems LinkedIn automation, handover

This folder is the durable home for SJ Systems' LinkedIn content so it keeps
running after any one person leaves. It holds a ready batch of single posts,
a Publer import file, and the repeatable method for producing more.

## What's here

- `linkedin-batch-2026-08.md` — 12 finished single posts (on-image copy,
  caption, first-comment link, schedule, Canva design ID) for readable review.
- `publer-import.csv` — the same 12 posts as a bulk-import file for Publer.
- `PIPELINE.md` — brand rules, the step-by-step method, and the honest state
  of what's automated vs what still needs a person.

## The one thing that matters most for a clean handover: ownership

Whoever leaves, the accounts must not leave with them. Before you go, make
sure these all sit on a shared SJ Systems account, not a personal login:

- The **Canva** account holding the "SJ Post" designs and the brand kits.
- The **Publer** workspace connected to the LinkedIn company page.
- The **Cloudinary** account used for image processing (cloud name
  `rhnxcfga`, free tier).
- Whichever image-source account you set up (see `PIPELINE.md` for the
  choice between Pexels API, a paid stock library, or a manual fallback).

If any of these is tied to a personal email, it stops working the day IT
deprovisions that person. Move them first.

## Honest state of automation

Automated end-to-end:
- Research and topic selection.
- Copy, headings, captions.
- Single-post design in Canva, including on-topic photo and greyscale
  treatment via Cloudinary.
- Carousel text, layout, headings, numbering and CTA slide.

Still needs a person, or one more account setup:
- **Carousel imagery.** Choosing meaning-matched images per slide is not
  yet fully automated. Two viable ways to close this gap, both documented
  in `PIPELINE.md`:
  1. Add a Pexels API key (free, explicitly permits automation) so the
     assistant can search by concept and process the result through
     Cloudinary. This is the recommended route.
  2. Accept that carousel imagery is a five-minute manual step per week
     (open Canva, drop in a cut-out from the existing bank or run Canva's
     Background Remover on a fresh photo).
- **Publishing.** The environment that builds this content can't reach
  Publer's API. So the assistant produces a Publer bulk-import CSV, and a
  person imports it once (see "One-time import into Publer" below). Publer
  then publishes on schedule without further human input.

Do **not** use Unsplash for automation. Their API guidelines prohibit
automated collection and off-Unsplash caching, which is what any
Cloudinary-fetch pipeline would do.

## How publishing works

1. Build a batch of posts up front (`linkedin-batch-2026-08.md` is the
   first batch, ready).
2. Load them into Publer once via `publer-import.csv`.
3. Publer publishes to the LinkedIn company page automatically, on schedule.

## One-time import into Publer

1. In Publer, open the LinkedIn company page workspace.
2. Export the 12 images from Canva as PNG (open each design, Share,
   Download, PNG). Design IDs are in `linkedin-batch-2026-08.md`.
3. Use Publer's bulk CSV import and upload `publer-import.csv`. Map the
   columns when prompted (Caption, Date, Time, First comment, Media).
   - If Publer won't take the image by column, attach each PNG after
     import in the same order as the CSV rows.
4. Set the schedule to the dates and times in the file (07:30, Europe/London).
5. Confirm the first-comment link posts as a comment, not in the caption.
6. Review the queue, then let it run.

## Schedule

3 single posts a week, Mon / Wed / Fri at 07:30 Europe/London, starting
Mon 11 Aug 2026. Full dates are in the CSV.

## Producing the next batch

Follow `PIPELINE.md`. It's written so a person can run it in an afternoon,
or so an assistant with the right credentials (Pexels + Cloudinary + Canva
+ Publer, all on shared accounts) can run it end to end.

## Cleanup

The following Canva designs are broken drafts from setup and should be
deleted before handover. They're listed in `PIPELINE.md` too, at the end.
