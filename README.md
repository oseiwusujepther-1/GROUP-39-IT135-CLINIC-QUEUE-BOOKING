# GROUP-39-IT135-CLINIC-QUEUE-BOOKING
SOFTWARE ENGINEERING
# Clinic Queue Booking System

> Patients and clinic staff can book appointments, manage queue status, and reduce waiting time so that healthcare services become more organized, efficient, and patient-friendly.

## The problem
Many clinics still rely on manual appointment booking and long waiting lines, which often causes confusion, overcrowding, and delayed consultations. Patients may arrive without knowing whether a doctor is available, while staff spend time handling bookings and updating queue positions by hand. This leads to frustration, missed appointments, and inefficient use of hospital resources. A digital queue booking system helps clinics organize appointments more effectively and improves the patient experience.

## Core features
1. Patient registration and login - Patients can create accounts, update their profile information, and securely access the booking system.
2. Appointment scheduling - Patients can view available time slots and book medical appointments with doctors or clinic departments.
3. Queue status tracking - Patients can join a digital queue and monitor their position in real time through the system.
4. Rescheduling and cancellation - Users can change or cancel appointments when needed without visiting the clinic in person.
5. Admin and staff dashboard - Clinic staff can manage appointments, doctor schedules, queue updates, and patient records.
6. Notifications and reminders - The system sends reminders for upcoming consultations and queue updates to patients.
7. Reporting and analytics - Staff can review appointment trends, waiting times, and service usage for better resource planning.

Later (out of scope for now):
- Online payment integration
- Telemedicine consultation
- Multi-clinic support
- Integration with a hospital information system

## External service and data
- External service: SMS notification provider such as Twilio or a similar messaging gateway
- Data stored: users, patients, doctors, appointment records, queue status, clinic schedules, notifications, and administrative logs

## Team (roles rotate every 3 weeks)
| Name | GitHub | Role (weeks 1-3) |
|------|--------|------------------|
|Osei-wusu Jepther      | @      | Project manager  |
|      | @      | Lead developer   |
|      | @      | Tester           |
|      | @      | Documentation lead |

## Process model: Agile Scrum

The Clinic Queue Booking System will be developed using Agile Scrum. Scrum organizes work into short, time-boxed iterations called sprints. At the end of each sprint, the team aims to have a tested, usable increment of the system and uses feedback to decide what to do next.

The team will use three-week sprints, coordinating them with the project’s existing three-week role rotation. The team may adjust the sprint length if course deadlines or project needs require it.

### Why Scrum is suitable

The system includes several related areas, including patient account access, appointment booking, queue tracking, and clinic staff tools. The exact workflows and priorities may become clearer as the team discusses requirements and reviews early versions. Scrum allows the team to build these features incrementally and revise priorities based on feedback rather than committing to every detail before implementation begins.

Scrum also supports parallel contributions by the four team members while making integration a shared responsibility. Each member can take the lead on an agreed feature area, but the team coordinates shared interfaces, reviews one another’s changes, and tests the combined system regularly. This helps identify integration problems earlier than waiting until all features are complete.

### How the team will use Scrum

1. **Maintain a product backlog:** Record and prioritize user stories, defects, documentation, and technical tasks. Each item should have a clear outcome and acceptance criteria where practical.
2. **Plan each sprint:** Select a realistic set of high-priority backlog items, agree on the sprint goal, and identify dependencies and owners.
3. **Develop and integrate:** Work in feature branches, integrate changes through pull requests, and test features together throughout the sprint.
4. **Review the increment:** Demonstrate completed work to teammates and, when available, stakeholders. Gather feedback and add or reprioritize backlog items as needed.
5. **Reflect and improve:** Discuss what went well, what caused difficulty, and one or more improvements to try in the next sprint.

The team should hold brief regular check-ins to share progress, next steps, and blockers. These can be asynchronous when members cannot meet at the same time.

### Team responsibilities

The four members will share responsibility for the product backlog, sprint goal, quality, and delivery. A rotating coordinator can organize meetings and track blockers; a member may also coordinate priorities and clarify expected outcomes. Feature ownership can follow the proposed division in the project proposal, while code review and integration remain team responsibilities. The team will rotate its assigned roles every three weeks as stated in the README.

### Definition of done

A backlog item is considered complete when:

- It meets its agreed acceptance criteria.
- Its changes are integrated into the shared branch through the team’s review process.
- Relevant tests have been run and pass, or any known limitation is documented.
- User-facing behavior and setup or usage documentation are updated when needed.
- The team can demonstrate the completed behavior as part of the increment.

### Process justification and limitations

Scrum is appropriate for this student project because it gives a small team a straightforward structure for planning, dividing, integrating, and reviewing work. Short iterations make progress visible and provide opportunities to respond to feedback. However, Scrum does not remove the need for clear communication or upfront coordination on shared data and interfaces. The team should keep its backlog manageable, avoid taking on more work than can be completed in a sprint, and treat privacy, access control, and correctness of booking and queue data as ongoing quality concerns.

See the [process model document](docs/02-process-model/process-model.md).

## Documentation
| Folder | Contents |
|--------|----------|
| docs/01-proposal | Problem statement, member profiles |
| docs/02-process-model | Process model justification |
| docs/03-requirements | SRS, requirements, elicitation notes |
| docs/04-uml | UML diagrams |

## How to run
To be completed when the first working increment is available.

1. Clone the repository.
2. Install the required dependencies.
3. Configure the environment variables and database connection.
4. Run the backend and frontend services.
5. Open the application in the browser to use the clinic booking system.
