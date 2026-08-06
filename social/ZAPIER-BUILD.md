# SJ Systems LinkedIn automation, Zapier build guide

Concrete, step-by-step guide to build the weekly LinkedIn pipeline in Zapier
so it runs without a person after handover. Written for a non-technical
successor or a freelancer to work from directly.

Estimated build time: **half a day**, mostly Canva template setup.

---

## The pipeline in one diagram

```
Google Sheet (topic ideas + copy)
        │
        │  Zapier: weekly loop
        ▼
  For each single post (3/week)         For each carousel (2/week)
        │                                       │
        ▼                                       ▼
  Pexels search → CDN URL             For each of 5 concept slides:
        │                                Pexels search → CDN URL
        ▼                                       │
  Cloudinary greyscale URL                     ▼
        │                                PhotoRoom cut-out
        ▼                                       │
  Canva autofill (SJ Post template)            ▼
        │                                Canva autofill (SJ Carousel template)
        ▼                                       │
  Canva export PNG                             ▼
        │                                Canva export each page as PNG
        ▼                                       │
  Publer: schedule post ─────────────────────►  Publer: schedule carousel
```

At the end of it, LinkedIn publishes on schedule with nobody involved.

---

## Prerequisites (accounts, all on a shared SJ login)

| Account | Purpose | Cost | Status |
|---|---|---|---|
| Canva Pro | Templates, brand kit, autofill via Zapier | Existing | ✅ |
| Cloudinary | Greyscale (URL transform) | Free | ✅ Cloud name: `rhnxcfga` |
| Pexels API | Licensed image search | Free | ✅ Key stored |
| PhotoRoom API | Background removal on carousel imagery | Free sandbox (1000/mo) or Basic (~£20/mo) | ⏳ Needs sign-up |
| Publer | Scheduled publishing to LinkedIn | Existing | ✅ |
| Zapier | The orchestrator | Starter plan (~£20/mo) | ⏳ Needs sign-up |
| Google Sheets | Input source (topics + copy) | Free | ⏳ Create sheet |
| Anthropic (Claude) or OpenAI | Copy generation (optional) | ~£5-10/mo usage | ⏳ Optional |

**Every credential goes on a shared SJ account, not a personal login.** If any
of these is tied to someone's personal email, it dies the day they leave.

---

## Step 0. One-time Canva setup (30 mins)

Zapier's Canva integration works via **Brand Templates with data fields**.
Your existing designs are regular designs, so they need converting once:

1. Open the single-post base design (`SJ Post – Ransomware` or any of the
   "SJ Post" designs). Duplicate it and rename `SJ Post — Zapier Template`.
2. In Canva editor: click each text you want Zapier to fill, choose
   **"Connect data"** from the right-click menu, name the field:
   - `headline_beat_1` (white)
   - `headline_accent_1` (cyan)
   - `headline_beat_2` (white)
   - `headline_accent_2` (cyan)
3. Click the photo, choose **"Connect data"**, name it `background_image`.
4. Publish as a **Brand Template**: `... menu → Publish as brand template`.
5. Repeat for the carousel base (`SJ Carousel — Zapier Template`), with
   fields:
   - Cover: `cover_headline`, `cover_accent`, `cover_subhead_1`, `cover_subhead_accent`, `cover_image`
   - Slide 1-5 (each): `heading_N`, `body_N`, `image_N`
   - CTA: `cta_headline`, `cta_body`
6. Note both Brand Template IDs from Canva's URL bar. Save them in the
   Google Sheet's "Templates" tab.

Guide: search Canva help for **"Autofill for Enterprise"** or
**"Bulk Create with Brand Templates"** — same underlying feature.

---

## Step 1. Create the Google Sheet (10 mins)

One sheet, three tabs. Copy this structure:

**Tab: Queue** (each row = one scheduled post)

| type | topic | image_query | slide_briefs | scheduled_date | scheduled_time | status |
|---|---|---|---|---|---|---|
| single | Password reuse | office desk with sticky notes | | 2026-08-25 | 07:30 | pending |
| carousel | Buying IT support | | watch, contract signing, handshake, laptop setup, magnifying glass | 2026-08-27 | 07:30 | pending |

**Tab: Copy** (headlines + body text keyed by topic)

| topic | headline_beat_1 | headline_accent_1 | headline_beat_2 | headline_accent_2 | caption | first_comment_link |
|---|---|---|---|---|---|---|
| Password reuse | One password, | everywhere. | One breach, | everything. | *(full caption text)* | https://sjsystems.co.uk/services/security/ |

For carousels use a second sub-sheet with one row per slide (cover, 01–05,
CTA), same key.

**Tab: Templates**

| name | brand_template_id |
|---|---|
| single_post | *(from Canva URL)* |
| carousel | *(from Canva URL)* |

---

## Step 2. Zap A — Single post pipeline

**Trigger**
- App: **Schedule by Zapier**
- Frequency: Every day at 06:00
- (Filters below make sure it only runs when there's a pending post due
  today or tomorrow.)

**Action 1: Google Sheets — Lookup Row**
- Worksheet: `Queue`
- Lookup column: `status`, value: `pending`
- Also lookup: `type = single`
- Order by `scheduled_date` ascending
- Return: first match only

**Action 2: Filter**
- Continue only if: `scheduled_date` is today or tomorrow.

**Action 3: Google Sheets — Lookup Row** (the Copy tab)
- Match on `topic` from Action 1

**Action 4: Webhooks by Zapier — GET**
- URL: `https://api.pexels.com/v1/search`
- Query params:
  - `query`: `{{image_query}}` from Action 1
  - `per_page`: `1`
  - `orientation`: `portrait`
- Headers:
  - `Authorization`: your Pexels API key
- Zapier will parse the JSON response. Grab `photos.0.src.large` — that's
  the CDN URL of the top hit.

**Action 5: Formatter by Zapier — Text**
- Transform: `Replace`
- Build the Cloudinary URL:
  `https://res.cloudinary.com/rhnxcfga/image/fetch/e_grayscale,c_fill,w_1080,h_1350/{{step4.photos.0.src.large}}`

This is the greyscale, correctly-sized image URL. Verified working end to
end during setup.

**Action 6: Canva — Autofill Design from Brand Template**
- Brand Template: `SJ Post — Zapier Template` (from Templates tab)
- Field values:
  - `headline_beat_1`: from Copy tab
  - `headline_accent_1`: from Copy tab
  - `headline_beat_2`: from Copy tab
  - `headline_accent_2`: from Copy tab
  - `background_image`: URL from Action 5
- Returns: new design ID + design URL

**Action 7: Canva — Export Design**
- Design ID: from Action 6
- Format: PNG, size 1080×1350

Returns a download URL.

**Action 8: Publer — Create Scheduled Post**
- Workspace: SJ LinkedIn workspace
- Account: SJ Systems company page
- Content: `{{caption}}` from Copy tab
- Media: `{{download_url}}` from Action 7
- Schedule: `{{scheduled_date}} {{scheduled_time}}` Europe/London
- First comment: `{{first_comment_link}}` from Copy tab

**Action 9: Google Sheets — Update Row**
- Set `status = scheduled` on the queue row.

Done. Repeat this Zap for each pending single row (Zapier's "Looping by
Zapier" step, or run the whole Zap once per row via a `Schedule per row`
config).

---

## Step 3. Zap B — Carousel pipeline

Same shape as Zap A, but with a **loop over 5 slides**:

**Trigger:** same daily schedule.

**Filter:** `type = carousel` AND `scheduled_date` is today or tomorrow.

**Loop (Looping by Zapier):** iterate over the 5 slide briefs from
`slide_briefs` column (comma-separated in the sheet).

Inside each loop iteration:

1. **Webhooks (Pexels)**: search for `slide_brief`, get top CDN URL.
2. **Webhooks (PhotoRoom)**: POST the Pexels URL to
   `https://image-api.photoroom.com/v2/edit`
   - Query: `imageUrl={{pexels_url}}&removeBackground=true&outputSize=1080x1080&background.color=transparent`
   - Header: `x-api-key: sandbox_YOUR_KEY` (sandbox = watermarked, free)
     or your paid key.
   - Returns: PNG URL of the cut-out (transparent background).
3. **Webhooks (Cloudinary greyscale)**: build the URL
   `https://res.cloudinary.com/rhnxcfga/image/fetch/e_grayscale/{{photoroom_url}}`
   - Returns: greyscale cut-out URL.
4. **Store** in a temp variable per slide.

After the loop, one **Canva Autofill** action with all six images (cover
+ 5 slides) plus the copy fields → returns the completed carousel design.

**Canva Export:** export all 7 pages as PNG.

**Publer:** carousel post to LinkedIn with the 7 PNGs in order + caption
+ first-comment link.

**Mark row scheduled.**

---

## Step 4. Weekly rhythm

Two options for how content gets into the sheet:

**Option A: Manual — 15 minutes on a Monday.** Your successor spends 15
mins filling in that week's topic + copy rows in the Google Sheet. Zaps
handle everything else.

**Option B: Fully autonomous with AI.** A third Zap runs Sunday evenings,
calls Claude/OpenAI with the SJ voice rules + last few topics posted, and
writes the next week's rows into the sheet. Then Zaps A and B pick them
up. This is what makes it truly zero-touch, but it needs a review checkpoint
(see below) or you'll ship whatever the AI drafts.

**Recommended review checkpoint**: add a manual "approved" flag column
Zap A checks. Someone eyeballs the queue for two minutes on Monday, ticks
approved, and the week's posts go out. Prevents a bad AI draft embarrassing
the brand.

---

## Step 5. Cost estimate

Ongoing monthly:

| Item | Cost |
|---|---|
| Zapier Starter (2,000 tasks/mo, multi-step Zaps) | ~£20 |
| PhotoRoom Basic (or sandbox if watermarks OK) | £0-20 |
| Pexels API | £0 |
| Cloudinary | £0 |
| Claude/OpenAI for copy (optional) | £5-10 |
| **Total** | **~£25-50/month** |

Zapier's Starter plan supports the multi-step Zaps this build needs.
Estimated ~200 tasks/month at 5 posts/week × 4 weeks × ~10 steps per
post = well inside 2,000.

---

## Step 6. Test order (do these in sequence)

1. **Manually run one Pexels + Cloudinary URL** in the browser. Confirm
   the greyscale PNG loads.
2. **Manually run one PhotoRoom URL** via `curl` with the sandbox key,
   confirm you get back a cut-out.
3. **Build the Canva Brand Templates**, autofill one manually from the
   Canva Autofill UI to confirm fields map correctly.
4. **Build Zap A first**, test with one hardcoded row in the sheet.
5. **Once A works end-to-end**, build Zap B.
6. **Turn on the schedule** only when both are proven with three consecutive
   dry runs.

---

## What's in this repo that plugs straight in

- `linkedin-batch-2026-08.md` — 12 finished posts to seed the Google Sheet
  before Zapier starts generating new ones. Buys you a month of runway.
- `publer-import.csv` — quick way to schedule those 12 immediately without
  waiting for Zapier build.
- `PIPELINE.md` — the brand rules, content principles, and voice guide.
  Feed these into the Claude/OpenAI copy prompt.
- Verified Cloudinary cloud name: `rhnxcfga`
- Pexels API key: stored securely, hand off separately.
- Canva Brand Kit ID: `kAFwfCMrIeY` (SJ Systems - V2)
- Canva base designs to convert into Brand Templates:
  `DAHRaWQZoXY` (single post template), `DAHQ9JlBfj0` (carousel template).

---

## The one thing that must not slip

Every credential above sits on a **shared SJ account, not a personal login**.
If any of them are tied to a leaving employee's email, this whole pipeline
dies the day their account is deprovisioned. Migrate the ownership before
handover, not after.
