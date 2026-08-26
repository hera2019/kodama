# Third-Party Notices

This repository vendors third-party libraries under `plugin/` and `style/`. Each retains its
own license. This file records what is included and where the corresponding license text lives.

本リポジトリは `plugin/` および `style/` 以下にサードパーティのライブラリを同梱しています。
各ライブラリは元のライセンスに従います。

本仓库在 `plugin/` 与 `style/` 下内置了第三方库,各自沿用原有许可证。

---

## Effective license of the combined work

The first-party code — everything outside `plugin/` and `style/` — is offered under the
[MIT License](LICENSES/MIT.txt).

However, the application loads and calls `plugin/upload/class.upload.php`, which is licensed
**GPL-2.0-only**. When this repository is distributed as a combined work, the conservative and
intended reading is that **the combined work is governed by GPL-2.0-only**. A full copy of that
license is provided at [`LICENSES/GPL-2.0.txt`](LICENSES/GPL-2.0.txt) and alongside the
component at [`plugin/upload/COPYING`](plugin/upload/COPYING), as GPL-2.0 §1 requires.

To reuse the first-party code under MIT alone, take the files outside `plugin/` and supply your
own upload handling. `plugin/upload/` is used only by the student-photo upload feature
(`page/studentedit.php`, `page/studentworks.php`, `page/IB-Admission.php`,
`style/js/kodama-photoupload.js`).

---

## Components

| Component | Version | Location | License | Full text |
|---|---|---|---|---|
| class.upload.php | 2019 release | `plugin/upload/` | **GPL-2.0-only** | [`plugin/upload/COPYING`](plugin/upload/COPYING), [`LICENSES/GPL-2.0.txt`](LICENSES/GPL-2.0.txt) |
| TCPDF | 6.2.26 | `plugin/pdf/TCPDF/` | LGPL-3.0-or-later | [`plugin/pdf/TCPDF/LICENSE.TXT`](plugin/pdf/TCPDF/LICENSE.TXT) |
| PHPMailer | 5.0.2 | `plugin/mail/` | LGPL — see note below | <https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html> |
| Bootstrap 3 | — | `style/` | MIT | <https://github.com/twbs/bootstrap/blob/v3.4.1/LICENSE> |
| AdminBSB Material Design | — | `style/` | MIT | <https://github.com/gurayyarar/AdminBSBMaterialDesign/blob/master/LICENSE> |
| jQuery | 1.8.3 and 1.12.4 (both bundled) | `style/js/` | MIT | <https://jquery.org/license/> |
| AngularJS | — | `style/js/` | MIT | <https://github.com/angular/angular.js/blob/master/LICENSE> |
| Chart.js | — | `style/js/` | MIT | <https://github.com/chartjs/Chart.js/blob/master/LICENSE.md> |
| DataTables | — | `style/js/` | MIT | <https://datatables.net/license/mit> |
| Bundled fonts under TCPDF | — | `plugin/pdf/TCPDF/fonts/` | GPL-2.0 / GPL-3.0 / Bitstream Vera — see each font's `COPYING` or `LICENSE` | in place |

### Note on PHPMailer

The bundled PHPMailer 5.0.2 header states only *"Distributed under the Lesser General Public
License (LGPL)"* and links to <http://www.gnu.org/copyleft/lesser.html>, **without naming a
version**. PHPMailer of that era was distributed under LGPL-2.1, and that is the most likely
intent, but this is an inference — the bundled file itself does not say so. See
`plugin/mail/class.phpmailer.php` lines 17 and 38 for the original wording.

---

## Not included

The following were removed from this repository and from its commit history, because they added
attack surface or triggered secret-scanning without providing any value to a code archive:

- `plugin/pdf/TCPDF/examples/` — TCPDF's bundled example suite, including a demo X.509
  certificate and matching `.p12` (subject `TCPDF DEMO`, expired 2014). Not a production
  credential, but it trips secret scanners.
- `test/` — throwaway scratch pages used while debugging front-end widgets in 2019, including
  an unauthenticated file-upload test endpoint.
