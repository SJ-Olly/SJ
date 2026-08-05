# SJ Systems LinkedIn automation, handover

This folder is the durable home for SJ Systems' LinkedIn content so it keeps
running after any one person leaves. It holds a ready batch of posts, a Publer
import file, and the repeatable method for making the next batch.

## What's here

- `linkedin-batch-2026-08.md` — 12 finished posts (on-image copy, caption, first
  comment link, schedule, Canva design ID) for readable review.
- `publer-import.csv` — the same 12 posts as a bulk-import file for Publer.
- `PIPELINE.md` — the brand rules and the step-by-step method for producing the
  next batch, so this isn't tied to any one person.

## The one thing that matters most for a clean handover: ownership

Whoever leaves, the accounts must not leave with them. Before you go, make sure
these all sit on a shared SJ Systems account, not a personal login:

- The **Canva** account holding the "SJ Post" designs and the brand kit.
- The **Publer** workspace connected to the LinkedIn company page.
- Whatever runs any future automation (see "Automating the next batch" below).

If any of these is tied to a personal email, it stops working the day IT
deprovisions that person. Move them first.

## How publishing actually works here (and an honest limit)

The goal is hands-off publishing. The reliable way to get that, and the way set
up here, is:

1. Build a batch of posts up front (done: 12 posts, about 4 weeks at 3 a week).
2. Load them into Publer once, scheduled across the coming weeks.
3. Publer publishes them to LinkedIn automatically, on schedule, with nobody
   touching anything.

Publer is the piece that does the unattended publishing. It does that well.

Honest limit worth knowing: the assistant that built these runs in a locked-down
environment that cannot reach Publer or LinkedIn over the network, and there's no
Publer or LinkedIn connector available to it. So it can prepare everything, but
it can't press "import" or "publish" for you. That single import is the one
manual step. Everything after it is automatic.

## One-time import into Publer

1. In Publer, open the LinkedIn company page workspace.
2. Export the 12 images from Canva as PNG (open each design, Share, Download,
   PNG). Design IDs and links are in `linkedin-batch-2026-08.md`.
3. Use Publer's bulk CSV import and upload `publer-import.csv`. Map the columns
   when prompted (Caption, Date, Time, First comment, Media).
   - If Publer won't take the image by column, attach each PNG to its post after
     import. The rows are in the same order as the images.
4. Set the schedule to the dates and times in the file (07:30, Europe/London).
   Confirm the first comment link posts as a comment, not in the caption.
5. Review the queue, then let it run.

## Before you publish: make the photos B&W

The brand rule is a black-and-white photo with the teal overlay. The 12 designs
currently use colour photos under the teal overlay, because the Canva API this
was built through can't apply an image filter (it refused the recolour
operation on a photo fill). Fixing it is quick in the Canva editor:

- Open each design, click the photo, then Edit photo, Filters or Adjust, and
  either pick Duotone or drag Saturation to 0.
- One click per image, 12 images.

Do this before exporting the PNGs for Publer.

## Schedule

3 posts a week, Monday / Wednesday / Friday at 07:30 Europe/London, starting
Mon 11 Aug 2026. Full dates are in the CSV.

## Automating the next batch

Producing the next batch is a repeatable method, written up in `PIPELINE.md`. It
can be re-run by a person in an afternoon, or automated if you give an
assistant an environment that can reach Publer. Two ways to make it truly
unattended long term:

- Ask an admin to allow `app.publer.com` on the automation environment's network
  policy and store a Publer API key on a shared account. Then a scheduled job can
  generate and push posts on its own.
- Or connect a LinkedIn or Publer connector at the claude.ai org level, so an
  assistant can publish through the connector instead of blocked network calls.

Until one of those is in place, treat this as: assistant prepares the batch, a
person does the one Publer import.
