# KODAMA

**Student records and attendance management for Japanese language schools**
日本語学校 / 塾向けの学生カルテ・出欠管理システム

[日本語 →](README.ja.md) ・ [中文 →](README.zh-CN.md)

A school management system written from scratch in PHP between 2018 and 2020, and used in
production at a Japanese language school. Its most distinctive idea is that it **derives the
school calendar from check-in data** rather than asking anyone to maintain one.

> [!WARNING]
> **This is a read-only code archive. Do not deploy it on a public network.**
> The application has unauthenticated data endpoints, SQL injection, an unauthenticated
> file-upload handler and reflected XSS. It is published so that the design and the code can be
> *read*, not so that it can be run as a service. If you want to try the demo, bind it to
> `127.0.0.1` only and use throwaway data. See [Known limitations](#known-limitations).

---

## What this is

Japanese language schools do two things constantly, and both are tedious:

**Tracking attendance.** Not just presence, but attendance *rate* measured in コマ (class
periods). The Immigration Services Agency checks this number when reviewing a student's
residence status. Getting it wrong has real consequences for the student.

**Issuing certificates.** Enrolment certificates, graduation certificates, the Certificate of
Eligibility application — each is an official form with a fixed layout, filled in by hand,
over and over.

KODAMA makes these one system: the student record is the source of truth, attendance is
computed automatically from check-ins, and certificates are generated as PDFs straight from
the record.

Built solo, from database schema to front-end — no framework, no Composer, just PHP, jQuery
and hand-written SQL. Product decisions and testing were handled by colleagues.

---

## The interesting part: inferring the school calendar

Most attendance systems require you to maintain a school calendar telling them which days
have classes. For a language school that is impractical — rescheduled lessons, national
holidays, school-specific closures. Maintaining the calendar becomes its own chore, and the
moment someone forgets to update it, every attendance rate is wrong.

KODAMA inverts this: **don't ask for a calendar, look at who actually checked in.**

For each class and each time slot, it compares the number of check-ins against enrolment:

| Check-in rate | Verdict | Reasoning |
|---|---|---|
| 0% | `休` School holiday | Nobody came → there was no class that day |
| > 50% | `出` Class held | Counts toward コマ totals and attendance rate |
| 0–50% | `不` Unknown | Not decided automatically; flagged for a human to confirm |

Implemented in [`attend/situation_class.php:109`](attend/situation_class.php#L109).

Refusing to guess in the middle band is the design decision I am happiest with. This
attendance rate ends up in front of immigration officials. When the system isn't sure, it
should say so, rather than hand back a confident-looking wrong number.

Check-in windows tolerate arriving early or leaving late (`aheadperiod` / `delayperiod` in
the `classtime` table), and the late/early-leave thresholds (`allowlate` / `allowearly`) are
configurable per school.

---

## Features

### Attendance

- **Check-in screens** — individual and group modes, plus an HTTP endpoint so remote devices
  can post check-ins ([`attend/AddAttendRecordGet.php`](attend/AddAttendRecordGet.php))
- **Monthly ledger** — a colour-coded matrix of 31 days against up to 4 class periods per day,
  so one screen shows a student's or a class's entire month at a glance

  States: `出` present · `欠` absent · `遅` late/left early · `公` excused · `休` on leave ·
  `帰` temporarily home · `-` school holiday · `不` unknown
- **Two parallel metrics** — attendance rate by **コマ** (class periods) and by **days**.
  Immigration looks at periods; schools manage by days. Arriving late counts as present for
  the day but costs one コマ, which is exactly why the two numbers differ
  ([`attend/getstudentmonthattand.php:205`](attend/getstudentmonthattand.php#L205))
- **Manual override** — automatic verdicts can be corrected by hand; the `manualmodified`
  flag preserves the fact that a human intervened
- **Full recompute** — [`attend/situation_rebuildall.php`](attend/situation_rebuildall.php)
  rebuilds all statistics from raw check-in records, so changing the rules is not scary

### Student records

Enrolment details, residence status (residence card number, passport, expiry), exam scores,
interview notes, commendations and penalties, career outcomes, tuition payments and a
portfolio of student work — normalised across tables, with `student` holding basics and
`student2` holding immigration-related fields.

### Certificate generation

Built on TCPDF, filling real official layouts directly from student data:

| | |
|---|---|
| Certificate of Enrolment (在学証明書) | Certificate of Graduation (卒業証明書) |
| Certificate of Registration (在籍証明書) | Expected Graduation (卒業見込証明書) |
| Certificate of Completion (修了証明書) | Expected Completion (修了見込証明書) |
| Completion Diploma (修了証書) | Graduation Diploma (卒業証書) |
| Certificate of Withdrawal (退学証明書) | Letter of Recommendation (推薦書) |
| Academic Record & Attendance (学業成績及び出席状況証明書) | Re-entry Approval (承认书) |

Plus the hardest one: the **Certificate of Eligibility application**
(在留資格認定証明書交付申請書) — multi-page, with well over a hundred fields.

### Access control

Three roles (administrator / teacher / agency), driven by the `userrights` table. Password
reset by email via PHPMailer, with tokens in a `token` table expiring after two days.

### Interface

Japanese and Chinese UI, with several selectable colour themes remembered via cookie.

---

## Running it locally

**Requirements:** PHP 7.4 (with PDO and GD) · MySQL 5.7+ · Apache or Nginx

PHP 8 is **not** supported: the bundled TCPDF 6.2.26 uses `$str{i}` string offsets, which
were removed in PHP 8.0. Upgrading the vendored libraries would be required first.

```bash
git clone https://github.com/hera2019/kodama.git
```

**1. Create the database and load the demo data**

```bash
mysql -u root -p -e "CREATE DATABASE kodama_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;"
```

```bash
mysql -u root -p kodama_demo < db/kodama_demo.sql
```

**2. Configure the database connection**

```bash
cp config/config.example.php config/config.php
```

Edit `config/config.php` with your credentials. It is listed in `.gitignore` and will not be
committed.

**3. Point a web server at the project root and open it**

Demo account:

| Username | Password |
|---|---|
| `admin` | `kodama-demo` |

The demo database contains 2 classes, about 30 students and close to a thousand check-in
records spanning 2019-08 to 2020-02 — enough to see the attendance matrix and the automatic
school-holiday detection doing real work. All names, photos and contact details in it are
fictional or placeholder content.

---

## Layout

```
kodama/
├── index.php              Entry point
├── attend/                Check-in and attendance statistics
│   ├── attend_class.php       Check-in record operations
│   ├── CheckInUI*.php         Check-in screens (individual / group)
│   ├── situation_class.php    ★ Class statistics + school-holiday inference
│   ├── situation_month.php    Monthly aggregation
│   └── situation_rebuildall.php  Full statistics rebuild
├── page/                  Feature pages (students, classes, scores, certificates…)
│   ├── PDFWrite*.php          Certificate PDF generation
│   └── IB-Admission*.php      Certificate of Eligibility application
├── dataproc/              Data layer (*_class.php data classes / *_proc.php Ajax endpoints)
├── frame/                 Page chrome (header, sidebars, footer)
├── include/               Database connection and global helpers
├── user/                  Sign-in, registration, password reset
├── mail/                  Outbound email
├── template/pdf/          Certificate layout templates
├── config/                Database config (create config.php yourself)
├── db/kodama_demo.sql     Demo database
├── data/photo/            Student photos (placeholders only in this repo)
├── style/                 Front-end assets
└── plugin/                Third-party libraries (TCPDF / PHPMailer / class.upload)
```

---

## Data model

22 tables. The ones that matter:

| Table | Purpose |
|---|---|
| `student` / `student2` | Student basics / residence-status fields |
| `attendance` | Raw check-ins, one row per day, up to 4 periods (`time11`–`time42`) |
| `situationclass` | Per class and period aggregates, including the holiday verdict |
| `situationmonth` | Per student and month aggregates, daily states stored as JSON |
| `classtime` | Period timetable and late/early-leave thresholds |
| `attendproperty` | Attendance state dictionary (present / absent / late / …) |
| `idconfig` | Generic lookup table (nationality, enrolment status, course, visa, outcome…) |
| `studentdata` | Application form submissions, stored as JSON |
| `usermanage` / `userrights` | Users and roles |
| `operatelog` | Audit log |

---

## Stack

| | |
|---|---|
| Backend | PHP 7.4, PDO — parameterised in most places, but several queries are assembled by string concatenation (see [Known limitations](#known-limitations)); no framework |
| Database | MySQL (MyISAM), utf8mb4 |
| Frontend | Bootstrap 3 with the AdminBSB theme, jQuery, DataTables, bootstrap-treeview, bootstrap-datetimepicker |
| PDF | TCPDF |
| Email | PHPMailer |
| Uploads | class.upload.php |

Roughly 14,000 lines of first-party PHP and JavaScript, excluding third-party libraries.

---

## Known limitations

This is 2018–2020 code, written by one person learning as they went. Several things are plainly
wrong by today's standards — and some were already wrong by 2019 standards. They are listed
here rather than hidden: an archive that oversells itself is worse than one that is honest.

### Security defects

These are the reason for the warning at the top of this file.

- **The Ajax data endpoints have no authentication.** `dataproc/*_proc.php` exposes query, add,
  update and delete operations for students, users, check-ins and school settings, and none of
  the six files checks for a session or a role. Anyone who can reach the URL can read student
  records and password hashes, or modify and delete data.
- **SQL injection.** Several queries are assembled by string concatenation *before* being handed
  to `prepare()`, so the prepared statement protects nothing: the WHERE clause in `QueryStudent`
  (`$sql .= $Param`), the SET clause in `UpdateStudent`, and the ID list in `DeleteStudent` —
  all in [`dataproc/student_class.php`](dataproc/student_class.php).
- **Unauthenticated file upload with a caller-supplied path.**
  [`plugin/upload/upload.php`](plugin/upload/upload.php) takes its destination directory from the
  `dir` request parameter, concatenates it into a path with no `realpath()` containment, and
  performs no authentication. Combined with permissive accepted types, this allows arbitrary
  file writes and path traversal.
- **Reflected XSS.** [`page/info.php`](page/info.php) echoes `$_GET['title']` and `$_GET['info']`
  unescaped; other pages emit database content unescaped as well.
- **Session fixation.** [`user/signin.php`](user/signin.php) passes a user-controlled cookie
  straight into `session_id()` and never regenerates the ID after a successful login. Session
  cookies carry no `HttpOnly`, `Secure` or `SameSite` attributes.
- **Weak credential handling.** Passwords are stored with MySQL `SHA()` — unsalted SHA-1.
  "Remember me" keeps the password base64-encoded in the session. Generated passwords come from
  `substr(md5(time()), 0, 8)` ([`user/resetpassword.php`](user/resetpassword.php)), guessable
  from the request timestamp.
- **No CSRF protection.** No form submission carries a token.
- **The demo administrator password is published** in this README and the demo database ships
  with the matching hash. It is a demo credential, not a default to keep.

### Design and maintenance

- **MyISAM tables**, so no foreign keys and no transactions. Cross-table consistency is enforced
  in application code.
- **Data and presentation are entangled.** `dataproc/*_class.php` mixes SQL, business logic and
  HTML assembly in the same file.
- **No automated tests.** The attendance logic — the part most deserving of them — was verified
  entirely by hand.
- **Dependencies are vendored by hand** into `plugin/` and `style/`, pinned to whatever was
  current in 2019. No Composer, no npm. See [THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md).
- **PHP 8 is not supported**, as noted under [Running it locally](#running-it-locally).

Rebuilding it today, I would reach for Laravel or Slim with Composer, move to InnoDB with real
foreign keys, put every endpoint behind framework authentication and authorisation, escape
output by default through a template engine, and extract the attendance inference into a
standalone, testable domain service.

---

## About this code

- Development started in **2018**, before I knew how to use Git — versioning meant copying
  folders by hand
- The repository history begins in **November 2019**, when the project moved into Git
- **2019–2020** was the main development period; 2021 and 2023 saw occasional maintenance
- Written by one person; product and testing were handled by colleagues
- Ran in production at a Japanese language school

This repository is a public archive. All production credentials, real school information and
personal data have been removed, including from the commit history. Names, photos and contact
details in the demo database are fictional or placeholder content. The project is no longer
maintained.

---

## License

The first-party code — everything outside `plugin/` and `style/` — is offered under the
[MIT License](LICENSES/MIT.txt).

The application loads and calls `plugin/upload/class.upload.php`, which is **GPL-2.0-only**.
When this repository is distributed as a combined work, the conservative reading is that
**the combined work is governed by GPL-2.0-only**; a full copy of that license is included at
[`LICENSES/GPL-2.0.txt`](LICENSES/GPL-2.0.txt) and beside the component at
[`plugin/upload/COPYING`](plugin/upload/COPYING).

Every bundled component, its version and its license are listed in
**[THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md)**.
