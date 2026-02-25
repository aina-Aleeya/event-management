# API Documentation

This document lists all HTTP endpoints (web routes) with method, URL, authentication, and request/response behaviour. The application is web-based; responses are typically HTML views or redirects unless noted (e.g. file download).

**Base URL:** Application URL (e.g. `https://yourapp.test`).  
**Auth:** Session-based (Laravel Fortify). No API tokens.

---

## 1. Public Routes (No Authentication)

### 1.1 Home / Dashboard

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/` | dashboard | Home. If authenticated: admin → `/admin/dashboard`, organiser → `/organiser/dashboard`; else dashboard view. |

**Response:** Redirect or `view('dashboard')`.

---

### 1.2 User-Facing (from user.php)

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/events` | events.page | Public event list (Livewire EventPage). |
| GET | `/daftar/{id}` | peserta.form | Registration form for event `id` (Livewire PesertaForm). |
| GET | `/events/{id}` | event.details | Event details (Livewire EventDetails). |
| GET | `/payment/{event_id}` | payment.form | Payment form (Livewire PaymentForm). |

**Example request (registration):**  
- User submits PesertaForm (name, email, category, etc.).  
- **Response:** Redirect to payment form or next step.

---

### 1.3 Score Submission (Token-Based)

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/markah/{token}` | markah.form | Score entry form for group identified by `token`. |
| POST | `/markah/{token}` | markah.submit | Submit scores for that group. |

**POST /markah/{token}**

- **Request body (form):**  
  - `scores`: array of `{ peserta_id, round1?, round2?, round3?, remarks? }`.
- **Validation:**  
  - `scores` required, array; `scores.*.peserta_id` required, exists in pesertas; rounds nullable numeric min 0; remarks nullable string max 255.
- **Response:** Redirect to `markah.form` with `session('success', 'Scores submitted successfully!')`.
- **Errors:** 404 if group token invalid; 422 on validation failure (back with errors).

---

### 1.4 Ad Click Tracking

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/ads/{id}/click` | ads.click | Track click for ad/event `id`. |

**Response:** Redirect or tracking logic (implementation in EventController).

---

### 1.5 Team Invitation Acceptance

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/team/invitation/{token}` | team.invitation.accept | Accept team invitation by token. |

**Response:**  
- If expired: redirect to login with error message.  
- If no user: redirect to register with email, name, invitation_token.  
- If user exists: update invitation to accepted, redirect to event dashboard with success.

---

## 2. Authenticated User Routes (user.php)

**Middleware:** `auth`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/history` | history | User’s event history (Livewire HistoryPage). |
| GET | `/history-participant/{eventId}` | history.participant | Participant list for event (Livewire SenaraiPeserta). |
| GET | `/settings` | - | Redirect to `settings/profile`. |
| GET | `/settings/profile` | profile.edit | Profile edit (Volt). |
| GET | `/settings/password` | password.edit | Password edit (Volt). |
| GET | `/settings/appearance` | appearance.edit | Appearance (Volt). |
| GET | `/settings/two-factor` | two-factor.show | 2FA (Volt); may use `password.confirm`. |
| GET | `/events/{eventId}/certificates` | - | Gate: if `!$event->areCertificatesAvailable()` show certificates-not-available view; else (current code) no return—likely intended to show certificate list or redirect. |

---

## 3. Admin-Only Routes (Super Admin)

**Prefix:** `/admin`. **Name prefix:** `admin.`. **Middleware:** `auth`, `admin`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/admin/dashboard` | admin.dashboard | Admin dashboard. |
| GET | `/admin/organisers/{organiser}/dashboard` | admin.organisers.dashboard | View specific organiser’s dashboard. |
| GET | `/admin/organisers` | admin.organisers.index | List organisers. |
| GET | `/admin/organisers/create` | admin.organisers.create | Create organiser form. |
| POST | `/admin/organisers` | admin.organisers.store | Store organiser. |
| GET | `/admin/organisers/{organiser}` | admin.organisers.show | Show organiser. |
| GET | `/admin/organisers/{organiser}/edit` | admin.organisers.edit | Edit organiser form. |
| PUT/PATCH | `/admin/organisers/{organiser}` | admin.organisers.update | Update organiser. |
| DELETE | `/admin/organisers/{organiser}` | admin.organisers.destroy | Delete organiser. |

**POST /admin/organisers (store)**

- **Request body:** `name`, `email`, `password`, `password_confirmation`.
- **Validation:** name required string max 255; email required email unique; password required confirmed (Rules\Password).
- **Response:** Redirect to `admin.organisers.index` with success.

**PUT/PATCH /admin/organisers/{organiser} (update)**

- **Request body:** `name`, `email`, `password?`, `password_confirmation?`.
- **Response:** Redirect to `admin.organisers.index` with success.

---

## 4. Shared Admin Prefix (Auth + RBAC)

**Prefix:** `/admin`. **Name prefix:** `admin.`. **Middleware:** `auth`.  
Access: Admin, Organiser, or event team member with the required permission for the event in the route.

### 4.1 Event Management

**Middleware:** `team.permission:create_event` (where applied).

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/admin/create-event` | admin.create-event | Create event (Livewire CreateEvent). |
| GET | `/admin/events/{event}/dashboard` | admin.event.dashboard | Event dashboard (Livewire). |
| GET | `/admin/events/{eventId}/edit` | admin.event.edit | Edit event (Livewire EditEvent). |

---

### 4.2 Participants

**Middleware:** `team.permission:view_participants`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/admin/participants/{event}` | admin.participants | Participants list for event. |
| GET | `/admin/participant/{peserta}` | admin.participant.view | View single participant. |
| GET | `/admin/event/{event}/participants/export` | admin.event.participants.export | Export participants (e.g. Excel). |
| GET | `/admin/event/{event}/participants/pdf` | admin.event.participants.pdf | Export participants PDF. |

**Response (export):** File download (Excel/CSV or PDF).

---

### 4.3 Grouping

**Middleware:** `team.permission:group_participants`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/admin/grouping` | admin.grouping.index | Grouping index (list events). |
| GET | `/admin/events/{event}/groups` | admin.groups | Groups for event. |
| POST | `/admin/events/{event}/groups/store` | admin.group.store | Create group. |
| POST | `/admin/events/{event}/groups/auto` | admin.group.auto | Auto-create groups. |
| POST | `/admin/events/{event}/assign` | admin.group.assign | Assign participants to group. |
| POST | `/admin/groups/{event}/move` | admin.group.move | Move participant between groups. |
| POST | `/admin/groups/{event}/remove` | admin.group.remove | Remove participant from group. |
| GET | `/admin/events/{event}/grouping/{category}` | admin.grouping.category | Grouping by category. |
| GET | `/admin/events/{event}/grouping` | admin.event.grouping | Event grouping view. |

**POST admin.group.store / admin.group.auto / admin.group.assign**

- Request bodies are form data (e.g. group name, capacity; participant IDs).  
- **Response:** Redirect back or to grouping view with success/error flash.

---

### 4.4 Rankings & Reports

**Middleware:** `team.permission:view_participants`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/admin/ranking-report/{event}` | admin.ranking.report | Ranking report (Livewire). |
| GET | `/admin/event/{event}/leaderboard` | admin.event.leaderboard | Leaderboard (Livewire). |
| GET | `/admin/event/{event}/ranking/export` | admin.event.ranking.export | Export ranking (e.g. Excel). |
| GET | `/admin/ranking/{event}` | admin.ranking.show | Ranking view. |
| GET | `/admin/ranking/{event}/export-sheet` | admin.ranking.export.sheet | Export scoresheet. |
| GET | `/admin/ranking/{event}/export-pdf` | admin.ranking.export.pdf | Export ranking PDF. |

**Response (export):** File download.

---

### 4.5 Scoresheets

**Middleware:** `team.permission:manage_scoresheet`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/admin/scoresheet/export-group/{event}/{group}` | admin.scoresheet.export-group | Export scoresheet for one group. |
| GET | `/admin/scoresheet/export-all-groups/{event}` | admin.scoresheet.export-all-groups | Export scoresheet for all groups. |

**Response:** File download (e.g. Excel).

---

### 4.6 Certificates

**Middleware:** `team.permission:manage_certificates`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/admin/events/{event}/certificates` | admin.certificate.manage | Certificate management page. |
| PUT | `/admin/events/{event}/certificate-settings` | admin.certificate.update-settings | Update certificate settings. |
| GET | `/admin/certificate/export-single/{event}/{peserta}` | admin.certificate.single | Download single participant certificate (PDF). |
| GET | `/admin/certificate/export-group/{event}/{group}` | admin.certificate.group | Download certificates for a group (ZIP). |
| GET | `/admin/certificate/export-all/{event}` | admin.certificate.all | Download all certificates (ZIP). |

**PUT admin.certificate.update-settings**

- **Request body:** Form/JSON with certificate settings (fields depend on implementation).  
- **Response:** Redirect or JSON with success/error.

---

## 5. Organiser-Only Routes

**Prefix:** `/organiser`. **Name prefix:** `organiser.`. **Middleware:** `auth`, `role:admin,organiser`.

| Method | Endpoint | Name | Description |
|--------|----------|------|-------------|
| GET | `/organiser/dashboard` | organiser.dashboard | Organiser dashboard. |
| GET | `/organiser/events/{event}/team` | organiser.events.team.index | Team members list. |
| GET | `/organiser/events/{event}/team/create` | organiser.events.team.create | Invite team member form. |
| POST | `/organiser/events/{event}/team` | organiser.events.team.store | Send invitation. |
| GET | `/organiser/events/{event}/team/{teamMember}/edit` | organiser.events.team.edit | Edit team member (e.g. role). |
| PUT | `/organiser/events/{event}/team/{teamMember}` | organiser.events.team.update | Update team member. |
| PATCH | `/organiser/events/{event}/team/{teamMember}/role` | organiser.events.team.updateRole | Update team member role. |
| DELETE | `/organiser/events/{event}/team/{teamMember}` | organiser.events.team.destroy | Remove team member. |
| POST | `/organiser/events/{event}/team/{teamMember}/resend` | organiser.events.team.resend | Resend invitation email. |

**POST organiser.events.team.store**

- **Request body:**  
  - `name` (required), `email` (required), `role_id` (required if no custom role),  
  - `custom_role_name`, `custom_role_description`, `custom_role_permissions[]` (if custom role),  
  - `message` (optional).
- **Validation:** name required string max 255; email required email; role_id required_without:custom_role_name, exists:roles; custom_role_name required_without:role_id; custom_role_permissions required_with:custom_role_name array; message nullable string max 1000.
- **Response:** Redirect to `organiser.events.team.index` with success, or back with errors (e.g. email already invited).
- **Side effect:** Sends TeamMemberInvitation email; on mail failure, still redirects with warning in message.

**PATCH organiser.events.team.updateRole**

- **Request body:** `role_id` (required, exists:roles).  
- **Response:** Redirect to team index with success.

**Authorization:** All team routes require `event.user_id === Auth::id()` (only event owner can manage team); otherwise 403.

---

## 6. Error Responses Summary

| Code | When |
|------|------|
| **302** | Redirect after success or validation error (back). |
| **403** | Not authenticated (redirect to login), or not authorised (admin/organiser/team permission), or not event owner (team management). |
| **404** | Resource not found (findOrFail: event, organiser, team member, group, etc.). |
| **422** | Validation failed (redirect back with `errors` and `old()`). |

---

## 7. Permission → Routes Quick Reference

| Permission | Routes (examples) |
|------------|-------------------|
| create_event | admin.create-event |
| view_participants | admin.participants, admin.participant.view, exports, ranking.report, leaderboard, ranking.show, exports |
| group_participants | admin.grouping.*, admin.groups, admin.group.* |
| manage_scoresheet | admin.scoresheet.export-group, admin.scoresheet.export-all-groups |
| submit_scores | (internal score management; public markah is token-based) |
| manage_certificates | admin.certificate.manage, admin.certificate.update-settings, admin.certificate.single/group/all |

Admin and Organiser roles bypass `team.permission` and can access any of these when the route is under the shared admin prefix.

---

For system architecture and flows, see **SYSTEM_OVERVIEW.md** and **SYSTEM_FLOW.md**. For component details, see **COMPONENTS.md**.
