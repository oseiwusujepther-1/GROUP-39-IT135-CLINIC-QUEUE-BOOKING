# Software Requirements Specification

## 1. Introduction

### 1.1 Purpose

This document describes the proposed requirements for the Clinic Queue Booking System. It provides a shared reference for implementation, testing, and review. The project is currently at the planning stage; requirements marked as proposed must be confirmed with the team and, where possible, clinic stakeholders.

### 1.2 Scope

The system will provide patients with a way to view appointment availability, manage bookings, and check queue information. Authorized clinic staff will be able to manage schedules, appointments, and queue status. The system is intended to support clinic administration; it is not a medical-record system, a diagnostic tool, or a substitute for emergency care.

### 1.3 Intended users

- **Patient:** Views availability, manages their own bookings, and checks their queue information.
- **Clinic staff:** Manages schedules, appointments, and queue information for clinic operations.
- **Administrator:** Manages staff access and system configuration, if this role is included in the first release.

### 1.4 Definitions

- **Appointment:** A booking for a patient to attend a clinic at a scheduled time.
- **Queue position:** A patient's current order or status in the clinic's service queue.
- **Authorized staff:** A clinic employee whose account has permission to use staff functions.
- **MVP:** Minimum viable product; the smallest agreed version that can be demonstrated and evaluated.

## 2. Overall description

### 2.1 Product perspective

The system is a proposed web application with patient-facing and staff-facing functions. The implementation technology, hosting environment, and database have not yet been selected. An SMS provider is a possible external service, but notification channels and provider selection remain to be confirmed.

### 2.2 Operating environment

The intended interface should work in a current desktop or mobile web browser. Supported browsers, hosting, and network requirements must be decided by the team before implementation.

### 2.3 Constraints and assumptions

- The first release is for one clinic unless the team explicitly expands the scope.
- Clinic staff will provide and maintain accurate appointment availability and queue updates.
- Appointment and queue policies (such as operating hours, slot length, and late arrivals) must be agreed with a stakeholder or documented as project assumptions.
- The project will store only information needed for its agreed functions and should not collect medical details unless explicitly required and approved.
- SMS, payments, telemedicine, multi-clinic support, and hospital-system integration are not required for the initial scope.
- Applicable privacy, data-protection, and accessibility requirements depend on the deployment location and must be confirmed before real patient data is used.

## 3. Functional requirements

The detailed, uniquely identified requirements are maintained in [requirements.md](./requirements.md). At a high level, the system is expected to support:

- Patient account access and profile management.
- Viewing appointment availability and managing bookings.
- Staff management of clinic schedules, bookings, and queue information.
- Patients viewing the status of their own bookings and queue information.
- Optional notifications, subject to provider and stakeholder decisions.

## 4. External interface requirements

### 4.1 User interface

- The patient interface should clearly distinguish available, booked, cancelled, and completed appointment states.
- The staff interface should provide clear controls for schedule, booking, and queue updates.
- Error messages should explain what needs correction without exposing sensitive system details.

### 4.2 External services

- If notifications are included, the system may connect to an SMS or email provider selected by the team.
- The notification provider, required credentials, costs, and delivery-failure behavior must be confirmed before integration.

### 4.3 Data storage

The system may store patient account identifiers and contact details, staff accounts and roles, clinic schedules, appointments, queue status, and notification delivery records, as required by the agreed scope. Data retention and deletion rules must be defined before production use.

## 5. Non-functional requirements

These are proposed quality requirements. The team should agree on measurable acceptance thresholds and verify them during testing.

- **Usability:** Common patient tasks should be understandable without staff assistance; the team should test the booking flow with representative users or reviewers.
- **Responsive interface:** Patient and staff pages should remain usable at common mobile and desktop viewport sizes.
- **Security:** Protected functions must require authentication, and staff-only actions must enforce role-based authorization on the server, not only in the user interface.
- **Privacy:** The system should collect the minimum personal data needed, avoid displaying one patient's information to another, and keep secrets out of source control.
- **Data integrity:** A time slot must not be assigned to more than one active appointment, and booking or queue changes must be reflected consistently.
- **Reliability:** Errors during booking or updates should be reported clearly; the system must not show a successful booking if the save operation failed.
- **Maintainability:** Features should be organized so the four team members can develop and test their assigned areas and integrate through reviewed changes.
- **Performance:** The team should define response-time targets for availability searches and booking operations before final performance testing.
- **Accessibility:** The interface should support keyboard navigation, readable labels, and sufficient visual contrast; a target accessibility standard remains to be agreed.

## 6. Core use cases

### UC-1: Book an appointment

1. The patient opens the appointment booking area.
2. The system displays available slots according to the clinic schedule.
3. The patient selects a slot and submits the booking.
4. The system verifies that the slot remains available and records the booking.
5. The system displays the booking confirmation and current status.
6. If the slot is no longer available or the save fails, the system explains the problem and does not report a successful booking.

### UC-2: Manage a booking

1. The patient views their own bookings.
2. The patient chooses to cancel or request a reschedule.
3. The system applies the clinic's agreed booking policy and updates the booking if permitted.
4. The system displays the resulting status.

### UC-3: Update the clinic queue

1. Authorized staff open the queue-management area.
2. Staff view the relevant appointments or queue entries.
3. Staff update a queue status or position using the agreed clinic workflow.
4. The system saves the update and makes the current status visible to the appropriate patient and staff users.

### UC-4: Manage clinic availability

1. Authorized staff open schedule management.
2. Staff add or change available appointment times.
3. The system validates the schedule and prevents conflicting availability or bookings according to agreed rules.
4. The system makes the updated availability available for patient booking.

## 7. Acceptance and validation

Requirements should be reviewed by all team members before implementation. Each functional requirement should have a corresponding test or demonstration. The team should validate the booking, cancellation/rescheduling, staff authorization, and queue-update workflows together using test data before presenting an integrated increment.

## 8. Items to resolve

- Which clinic or stakeholder will validate the workflows?
- Is patient self-registration required, or will accounts be created by staff?
- Which patient and staff fields are essential?
- What are the clinic's operating hours, appointment durations, and cancellation rules?
- How is queue order determined, and who may change it?
- Should queue status be updated manually, automatically, or both?
- Are notifications included in the MVP, and which delivery channel is available?
- What technology, hosting, privacy, retention, and accessibility targets are required?
