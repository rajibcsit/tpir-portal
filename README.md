# TMSS Polytechnic Institute, Rangpur (TPIR) — Student Management Portal

Laravel 12 + Blade + Tailwind CSS starter portal for TPIR. Includes separate Admin and Student experiences, profile photo uploads, class-wise bulk attendance, and batch marks entry.

## Included modules

### Admin panel
- Admin login/logout and dashboard
- Student CRUD, profile photo upload, search and filters
- Department, Technology, Semester, Session and Shift fields
- Student ID card with uploaded photo and browser Print / Save as PDF
- Daily attendance filtered by Department, Technology, Semester, Session and Shift
- Bulk attendance actions: All Present, All Absent, All Leave; individual Present / Absent / Late / Leave; edit and save by date
- Monthly attendance percentage and date-wise attendance reports
- Bulk exam marks entry for a selected Technology + Semester + Subject
- Full Marks, Pass Marks, Credit, marks, grade, grade point and credit-weighted GPA
- Fees, payments and due tracking

### Student panel
- Student login using the email and password created by an Admin
- Personal dashboard with profile photo, academic information, attendance percentage and recent attendance
- Published result records and grade points
- Own Student ID card with Print / Save as PDF

## Requirements
- PHP 8.2+
- Composer 2
- MySQL / MariaDB
- PHP extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo

## Install (Windows / XAMPP)
1. Extract ZIP and open a terminal in the project folder.
2. Install dependencies and prepare environment:
   ```bash
   composer install
   copy .env.example .env
   php artisan key:generate
   ```
3. Create a MySQL database named `polytechnic_portal` in phpMyAdmin.
4. Set `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`. Default timezone is `Asia/Dhaka`.
5. Run migrations, seed the admin account and create the public upload link:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   php artisan serve
   ```
6. Open `http://127.0.0.1:8000`.

## Demo admin
- Email: `admin@polytechnic.test`
- Password: `password`

Change the demo password before using real student data.

## Create student login
1. Admin opens **Student Management → Add New Student**.
2. Enter a unique student email address.
3. Enter a password under **Student Portal Login** (minimum 8 characters).
4. Save the student. The student can now log in with that email and password and will be directed to the Student Panel.

If no password is entered, a Student Panel account is not created. Student login accounts use the student's email address as the username.

## Attendance workflow for large classes
1. Open **Daily Attendance**.
2. Choose the date, Department, Technology, Semester and Session.
3. Click **Load Students**.
4. Use **All Present** as a starting point, then search the absent/late students and change their status; or use **All Absent / All Leave** as needed.
5. Click **Save Attendance**. Re-open the same date and group to correct records if required.

For very large class groups, ensure PHP's `max_input_vars` is set high enough (for example, 5000) in `php.ini`, then restart Apache/PHP.

## Marks entry workflow
1. Open **Exam & GPA**.
2. Select Technology and Semester. The matching active students will appear.
3. Enter Exam Name, Year, Subject Code/Name, Credit, Full Marks and Pass Marks.
4. Enter marks for each student. Leave a student's marks blank to skip them.
5. Save once to store all entered marks. Existing records for the same student + exam + year + semester + subject code are updated instead of duplicated.

## Grading policy
The current starter scale is percentage-based:

| Percentage | Letter | Grade point |
|---|---|---:|
| 80–100 | A+ | 4.00 |
| 75–79.99 | A | 3.75 |
| 70–74.99 | A- | 3.50 |
| 65–69.99 | B+ | 3.25 |
| 60–64.99 | B | 3.00 |
| 55–59.99 | B- | 2.75 |
| 50–54.99 | C+ | 2.50 |
| 45–49.99 | C | 2.25 |
| 40–44.99 | D | 2.00 |
| Below 40 or below configured Pass Marks | F | 0.00 |

GPA = `SUM(credit × grade_point) / SUM(credit)`. Confirm and adjust this scale to the currently applicable BTEB/TPIR grading rules before official use. The system currently records subject-level marks; it does not replace BTEB result submission or official transcript approval.

## Important deployment notes
This is an enhanced starter project and has not undergone a full production security audit. Before using real student records, change demo credentials, enable HTTPS, configure backups, restrict admin access, add audit logs and password reset, validate institutional grading rules, and test uploads and database migrations on a staging server. Use a unique email for every student portal account.

## Fast payment entry for large student databases
The payment entry screen uses a server-side student finder instead of loading every student into a dropdown. It supports:
- Exact/partial Roll (Student ID), student name and phone search
- Department, Technology, Semester and Session filters (combine any of them)
- A maximum of 30 matching active students per search to keep the page responsive
- Selecting a student first, then entering fee title, total fee, amount received, payment method, date and transaction note
- Payment history list filters using the same academic fields and server-side pagination

For a college with 1,000+ students, use the Roll number or select Technology + Semester + Session first, then search. Add database indexes for frequently filtered columns before scaling to much larger datasets (see the optional migration below if your hosting database is already populated).

## Optional database indexes for larger student lists
If the student table is already created and contains production data, create a new migration rather than editing an old migration:

```bash
php artisan make:migration add_student_lookup_indexes
```

In that migration's `up()` method, add indexes for `student_id`, `technology`, `semester`, `session`, and `status`; add the matching `dropIndex()` calls in `down()`. Check existing index names first to avoid duplicate indexes. For MySQL, composite indexes such as `(technology, semester, session, status)` are useful for class-wise lookups. Test migrations on a backup/staging database before production.
