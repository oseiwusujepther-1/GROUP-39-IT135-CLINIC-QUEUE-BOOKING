# Requirements List

This is the working requirements backlog for the Clinic Queue Booking System. Requirements are proposed from the project description and have not yet been validated with clinic stakeholders. **Priority** indicates a suggested MVP order: Must, Should, or Could.

## Functional requirements

| ID | Priority | Requirement |
|---|---|---|
| FR-01 | Must | The system shall allow a patient to authenticate before accessing private booking information. |
| FR-02 | Should | The system shall allow a patient to view and update the profile information required for booking. |
| FR-03 | Must | The system shall display appointment availability based on the clinic schedule maintained by authorized staff. |
| FR-04 | Must | The system shall allow a patient to book an available appointment slot. |
| FR-05 | Must | Before confirming a booking, the system shall verify that the selected slot is still available and prevent more than one active booking from occupying the same slot. |
| FR-06 | Must | The system shall allow a patient to view their own appointment details and status. |
| FR-07 | Should | The system shall allow a patient to cancel or request a reschedule according to a clinic policy defined by the team. |
| FR-08 | Must | The system shall allow authorized staff to create and update clinic availability. |
| FR-09 | Must | The system shall allow authorized staff to view and manage appointments needed for clinic operations. |
| FR-10 | Must | The system shall allow authorized staff to update queue status using the clinic's agreed workflow. |
| FR-11 | Should | The system shall display a patient's current queue information to that patient and authorized staff. |
| FR-12 | Could | The system may send appointment reminders or queue notifications through a provider selected by the team. |
| FR-13 | Could | The system may provide staff with summary counts or reports on bookings and waiting times. |
| FR-14 | Must | The system shall provide feedback when an operation succeeds or fails and shall not display a success confirmation for a failed save. |

## Non-functional requirements

| ID | Quality | Requirement |
|---|---|---|
| NFR-01 | Security | The system shall restrict staff functions to authenticated accounts with the required permissions. |
| NFR-02 | Privacy | The system shall restrict patients to viewing their own private booking and queue information. |
| NFR-03 | Data integrity | The system shall preserve consistent booking and queue status when records are created or updated. |
| NFR-04 | Usability | The interface shall provide clear labels, instructions, and error messages for patient and staff workflows. |
| NFR-05 | Responsiveness | The key patient and staff workflows shall be usable on mobile and desktop browser viewports. |
| NFR-06 | Accessibility | Interactive controls shall have meaningful labels and support keyboard use; the team will select and test against an agreed accessibility target. |
| NFR-07 | Performance | The team shall define and test response-time targets for availability lookup and booking before release. |
| NFR-08 | Maintainability | The system shall be organized and documented so team members can develop, review, and test feature areas independently. |
| NFR-09 | Error handling | The system shall report validation and service failures clearly without exposing credentials or sensitive implementation details. |

## Proposed user stories

- As a patient, I want to see available appointment times so that I can choose a suitable visit.
- As a patient, I want to book, review, cancel, or reschedule my appointment so that I can manage my visit.
- As a patient, I want to check my queue status so that I can better understand when I may be seen.
- As clinic staff, I want to manage schedules and appointments so that the displayed availability is accurate.
- As clinic staff, I want to update queue information so that patients and staff can see the current status.

## Scope exclusions for the initial version

- Online payments
- Telemedicine
- Multi-clinic support
- Integration with a hospital information system
- Automated clinical triage or medical advice

## Requirement change process

When a requirement changes, the team should update this file, note why the change was made, and review any affected implementation tasks and tests. Stakeholder decisions and unverified assumptions should be recorded in [elicitation-notes.md](./elicitation-notes.md).
