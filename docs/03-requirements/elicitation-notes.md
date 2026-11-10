# Requirements Elicitation Notes

## Status

**Planned / not yet validated.** The repository currently contains no interview, survey, observation, or workshop results from clinic stakeholders. The requirements in this folder are initial project proposals based on the README and proposal, not confirmed stakeholder statements. Do not present the questions below as interviews that have already happened.

## Elicitation goal

Learn how patients book appointments and how clinic staff manage schedules and queues, then confirm the MVP workflows, policies, and constraints for the Clinic Queue Booking System.

## Stakeholders to consult

- Clinic receptionist or appointment-booking staff
- Clinic staff responsible for maintaining schedules
- Patients or patient representatives
- Clinic administrator or project sponsor
- Project team members responsible for implementation and testing

## Suggested elicitation methods

1. **Semi-structured interviews:** Ask staff and patient representatives about current booking and queue processes.
2. **Workflow observation:** If permission is granted, observe a normal booking or check-in process without collecting unnecessary personal information.
3. **Short questionnaire:** Ask a broader set of potential users about booking access, preferred notifications, and usability needs.
4. **Requirements review:** Present the proposed workflows and requirement list to stakeholders and record corrections or approvals.

Before any real-world interview or observation, obtain the appropriate permission, explain the project purpose, and avoid recording identifiable health or personal information unless explicitly approved and necessary.

## Interview prompts

### For patients

- How do you currently make or change a clinic appointment?
- What information do you need before choosing a time?
- What makes booking or waiting difficult?
- What queue information would be useful to see, and when?
- Would reminders be helpful? If so, which channel would you prefer?
- What accessibility, language, or device needs should the system support?

### For clinic staff

- How are appointments and schedules currently created and updated?
- How do you handle cancellations, late arrivals, and conflicting bookings?
- How is queue order determined and changed?
- Which staff roles need access to booking or queue functions?
- What information must staff see to manage a booking?
- What should patients be able to see, and what must remain staff-only?
- What reports or operational summaries would be useful?
- What privacy, retention, or operational rules must the system follow?

## Findings log

No stakeholder sessions have been recorded yet. Complete a row for each session or review; do not include patient-identifying details.

| Date | Method and stakeholder role | Key findings (anonymized) | Requirements affected | Follow-up / owner |
|---|---|---|---|---|
| To be completed | To be completed | To be completed | To be completed | To be completed |

## Decisions and open questions

| Topic | Current status | Decision needed |
|---|---|---|
| Account creation | Unconfirmed | Patient self-registration or staff-created accounts? |
| Booking policy | Unconfirmed | Slot length, operating hours, booking horizon, cancellation deadline, and rescheduling rules? |
| Queue logic | Unconfirmed | How is order determined, and who can update it? |
| Queue visibility | Unconfirmed | Which statuses should patients see, and how frequently should they be updated? |
| Notifications | Optional / unconfirmed | Is notification delivery part of the MVP? Which provider and channel are available? |
| Data handling | Unconfirmed | What data is essential, how long is it retained, and who can access it? |
| Accessibility | Unconfirmed | Which languages and accessibility target should be supported? |
| Validation stakeholder | Unconfirmed | Who can review and approve the proposed workflows and requirements? |

## Updating requirements from findings

After elicitation, summarize findings without personal identifiers, update the relevant requirement IDs and priorities in [requirements.md](./requirements.md), and revise the SRS where scope, policy, or constraints change. Keep unresolved items visible until the team or stakeholder makes a decision.
