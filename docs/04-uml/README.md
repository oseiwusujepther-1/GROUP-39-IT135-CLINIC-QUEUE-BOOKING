# UML Diagrams

These diagrams are maintained as **PlantUML source files** (`.puml`). The source is plain text, so it can be reviewed, changed, versioned, and pushed to GitHub like other project files. The diagrams are design proposals based on the current SRS and must be updated if stakeholder decisions change the requirements.

## Diagrams

- [Use-case diagram](./use-case-diagram.puml) — patient and clinic staff capabilities.
- [Class diagram](./class-diagram.puml) — proposed core domain entities and relationships.
- [Appointment-booking sequence diagram](./appointment-booking-sequence.puml) — successful booking and unavailable-slot/error paths.

## Viewing and rendering

The `.puml` files can be opened in a text editor and rendered with a PlantUML-compatible viewer or the PlantUML extension in VS Code. Rendering is optional; commit the `.puml` source files so the diagrams remain editable and reviewable. Do not put real patient information in diagram examples.

## Modeling assumptions to confirm

- Patients and staff have separate permissions.
- Appointment availability is represented by clinic-managed time slots.
- A slot can have at most one active appointment.
- Queue information is associated with a booked appointment; the clinic still needs to confirm how queue order and updates work.
- Notifications are optional and depend on selecting an external provider.
