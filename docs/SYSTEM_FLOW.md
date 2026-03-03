# System Flow

This document describes how data and control move through the system: user interaction, HTTP request/response, database operations, service usage, and error handling.

---

## 1. User Interaction Flow

### 1.1 Anonymous User: Browse Events → Register as Participant

```mermaid
sequenceDiagram
    participant U as User (Browser)
    participant R as Routes
    participant LW as Livewire
    participant M as Model/DB

    U->>R: GET /events
    R->>LW: EventPage
    LW->>M: Event::query() (public events)
    M-->>LW: events
    LW-->>U: Event list

    U->>R: GET /daftar/{id}
    R->>LW: PesertaForm (event id)
    LW->>M: Event::find(id)
    U->>LW: Submit form (peserta + category)
    LW->>M: Peserta create, Penyertaan create
    LW-->>U: Redirect /payment/{event_id}

    U->>R: GET /payment/{event_id}
    R->>LW: PaymentForm
    U->>LW: Confirm payment
    LW->>M: Penyertaan update (status_bayaran)
    LW-->>U: Success / history
```

### 1.2 Authenticated User: Login and Role-Based Redirect

```mermaid
flowchart LR
    A[GET /] --> B{Authenticated?}
    B -->|No| C[View dashboard / login]
    B -->|Yes| D{Role?}
    D -->|admin| E[Redirect /admin/dashboard]
    D -->|organiser| F[Redirect /organiser/dashboard]
    D -->|user| C
```

### 1.3 Organiser / Team: Event Dashboard and RBAC

```mermaid
sequenceDiagram
    participant U as User
    participant R as Routes
    participant MW as Middleware
    participant C as Controller/Livewire
    participant E as Event Model

    U->>R: GET /admin/events/5/dashboard
    R->>MW: auth
    MW-->>R: OK
    R->>MW: team.permission (event in route)
    MW->>E: Event::find(5), team member? permission?
    alt No access
        MW-->>U: 403
    else OK
        MW->>C: EventDashboardPage
        C->>E: Event + relations
        E-->>C: data
        C-->>U: View
    end
```

### 1.4 Score Submission (Public Token-Based)

```mermaid
sequenceDiagram
    participant J as Judge/Scorer
    participant R as Routes
    participant MC as MarkahController
    participant G as Group
    participant S as Score

    J->>R: GET /markah/{token}
    R->>MC: form(token)
    MC->>G: Group::where('token', token)->with event, pesertas
    G-->>MC: group
    MC->>MC: Load category per peserta, existing scores
    MC-->>J: Score form (Blade)

    J->>R: POST /markah/{token}
    R->>MC: submit(request, token)
    MC->>MC: validate scores array
    loop For each score
        MC->>S: Score::updateOrCreate
    end
    MC-->>J: redirect back with success
```

---

## 2. HTTP Request/Response Cycle

### 2.1 Generic Request Lifecycle

```mermaid
flowchart TB
    subgraph Request["Request"]
        R1[HTTP Request]
    end

    subgraph Middleware["Middleware Stack"]
        M1[Global]
        M2[auth]
        M3[admin / role / team.permission]
    end

    subgraph Handler["Handler"]
        H1[Controller action or Livewire component]
    end

    subgraph Response["Response"]
        Res1[View / Redirect / JSON]
    end

    R1 --> M1
    M1 --> M2
    M2 --> M3
    M3 --> H1
    H1 --> Res1
```

### 2.2 Typical Controller Flow (e.g. Team Member Store)

```mermaid
flowchart LR
    A[POST /organiser/events/1/team] --> B[Auth]
    B --> C[role: admin,organiser]
    C --> D[TeamMemberController@store]
    D --> E[Event::findOrFail]
    E --> F[Owner check: event.user_id === Auth::id]
    F --> G[Validate request]
    G --> H[Create Role if custom]
    H --> I[EventTeamMember::create]
    I --> J[Mail::send TeamMemberInvitation]
    J --> K[redirect + flash success]
```

- **Success**: Redirect to team index with `session('success')`.
- **Validation failure**: `back()->withErrors()->withInput()`.
- **Authorization failure**: `abort(403, '...')`.

### 2.3 Livewire Flow (e.g. Event Creation)

```mermaid
flowchart TB
    A[GET /admin/create-event] --> B[CreateEvent Livewire]
    B --> C[Render form]
    C --> D[User submits]
    D --> E[Livewire action: create event]
    E --> F[Event::create + categories/customCategories]
    F --> G[Redirect to event dashboard]
```

---

## 3. Database Operations

### 3.1 Main Entity Relationships

```mermaid
erDiagram
    users ||--o{ events : "owns"
    users ||--o{ event_team_members : "member"
    events ||--o{ event_team_members : "has"
    events ||--o{ groups : "has"
    events }o--o{ categories : "category_event"
    events ||--o{ custom_categories : "has"
    events }o--o{ pesertas : "penyertaan"
    pesertas }o--o{ groups : "group_peserta"
    groups ||--o{ scores : "has"
    events ||--o{ scores : "has"
    pesertas ||--o{ scores : "has"
    roles ||--o{ event_team_members : "assigned"

    users { int id string role }
    events { int id int user_id }
    event_team_members { int event_id int user_id int role_id string status }
    groups { int event_id string token }
    penyertaan { int event_id int peserta_id string status_bayaran polymorphic category }
    scores { int event_id int group_id int peserta_id decimal round1,2,3,average }
```

### 3.2 Participant Registration (Peserta + Penyertaan)

```mermaid
flowchart LR
    A[PesertaForm submit] --> B[Create/update Peserta]
    B --> C[Penyertaan create]
    C --> D[event_id, peserta_id, categorizable_type, categorizable_id]
    D --> E[Optional: group_token]
```

- **Peserta**: One record per physical participant (reusable across events).
- **Penyertaan**: Pivot per event; holds category (polymorphic), payment status, group_token.

### 3.3 Grouping and Scores

```mermaid
flowchart TB
    A[GroupController: storeGroup / autoGroup] --> B[Group::create]
    B --> C[Group::generateQrCode (token → URL)]
    C --> D[group_peserta attach]
    D --> E[Judge visits /markah/{group.token}]
    E --> F[MarkahController::submit]
    F --> G[Score::updateOrCreate per peserta]
```

- **Group**: `event_id`, `name`, `capacity`, `token`, `qr_code` (file path).
- **Score**: `event_id`, `group_id`, `peserta_id`, `round1`, `round2`, `round3`, `average` (auto-calculated on save), `remarks`.

---

## 4. Service Layer Interactions

The application does not use dedicated service classes for most logic; controllers and Livewire components call Eloquent models and facades directly.

| Concern | Where it lives | Notes |
|--------|----------------|--------|
| **Auth** | Fortify, middleware | Login, register, 2FA, session |
| **Mail** | `Mail::send()` in controllers | Team invitation (TeamMemberInvitation mailable) |
| **Storage** | `Storage::disk('public')` | Posters, QR codes, certificate templates |
| **PDF** | `Barryvdh\DomPDF` in CertificateController | Certificates, participant PDF export |
| **QR** | `Group::generateQrCode()` | External API (qrserver.com), file stored locally |
| **Permissions** | `config/team_permissions.php` | Permission keys and role defaults |
| **Event access** | `Event::getEventsForUser()`, `Event::canBeAccessedBy()` | Used in AdminController, GroupController |

### 4.1 Mail Flow (Team Invitation)

```mermaid
sequenceDiagram
    participant C as TeamMemberController
    participant E as EventTeamMember
    participant M as Mail
    participant U as Invitee

    C->>E: EventTeamMember::create (pending, token)
    C->>M: Mail::to(email)->send(TeamMemberInvitation)
    M->>U: Email with link
    U->>U: GET /team/invitation/{token}
    U->>C: acceptInvitation(token)
    C->>E: update status=accepted, accepted_at
    C-->>U: redirect to event dashboard or register
```

### 4.2 Certificate Generation Flow

```mermaid
flowchart TB
    A[CertificateController: exportSingle/Group/All] --> B[Load event, participants, categories]
    B --> C[getCertificateTemplatePath (Event model)]
    C --> D[Category / CustomCategory template path]
    D --> E[Pdf::loadView + data]
    E --> F[Stream download or ZIP]
```

---

## 5. Error Handling Flow

### 5.1 Authorization and Abort

```mermaid
flowchart TB
    A[Request] --> B{Authenticated?}
    B -->|No| C[redirect login]
    B -->|Yes| D{Route has team.permission?}
    D -->|No| E[Continue]
    D -->|Yes| F{Admin or Organiser?}
    F -->|Yes| E
    F -->|No| G{Event in route?}
    G -->|No| H[abort 403]
    G -->|Yes| I{Team member with permission?}
    I -->|No| H
    I -->|Yes| E
```

- **403**: "Event not found.", "You are not a team member of this event.", "You do not have the required permission: X", or "Only the event organizer can manage team members".
- **404**: `findOrFail()` when resource missing (event, organiser, team member, group, etc.).

### 5.2 Validation Errors

- **Form request validation**: `$request->validate([...])` in controllers.
- **Failure**: `Illuminate\Validation\ValidationException` → redirect back with `errors` and `old()` input.
- **Livewire**: Validation in component; errors shown in form.

### 5.3 Exception Handling

- Laravel default exception handling (`bootstrap/app.php` → `withExceptions`); no custom handler in the codebase.
- Logging: `Log::error()` / `Log::warning()` in places (e.g. certificate category, invitation email failure, QR generation failure).
- User-facing: Redirect with `session('error', '...')` when mail fails or invitation resend fails.

### 5.4 End-to-End Error Flow (Example: Team Invite)

```mermaid
flowchart LR
    A[Store invitation] --> B{Duplicate email?}
    B -->|Yes| C[back withErrors]
    B -->|No| D[Create + send mail]
    D --> E{Mail sent?}
    E -->|No| F[Log + redirect with success but warning message]
    E -->|Yes| G[redirect with success]
```

---

## 6. Summary Diagrams

### 6.1 Permission Check (team.permission)

```mermaid
flowchart TD
    R[Route: event in param] --> A[CheckTeamPermission]
    A --> B[user?]
    B -->|No| C[redirect login]
    B -->|Yes| D[admin or organiser?]
    D -->|Yes| E[Allow]
    D -->|No| F[Resolve event from route]
    F --> G[EventTeamMember for user+event, status=accepted]
    G --> H[teamMember->hasPermission(permission)]
    H -->|Yes| E
    H -->|No| I[abort 403]
```

### 6.2 Data Flow Overview

```mermaid
flowchart TB
    subgraph Input["Input"]
        I1[User / Browser]
        I2[Token (markah, invitation)]
    end

    subgraph App["Application"]
        A1[Routes + Middleware]
        A2[Controllers + Livewire]
        A3[Models]
    end

    subgraph Output["Output"]
        O1[Views / Redirects]
        O2[PDF / Excel export]
        O3[Email]
    end

    I1 --> A1
    I2 --> A1
    A1 --> A2
    A2 --> A3
    A3 --> A2
    A2 --> O1
    A2 --> O2
    A2 --> O3
```

This document should be read together with **SYSTEM_OVERVIEW.md**, **COMPONENTS.md**, and **API_DOCUMENTATION.md** for full coverage of architecture, components, and endpoints.
