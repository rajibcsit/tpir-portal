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


## TPIR Professional v4 updates

### Role-based access
- **Admin:** Full access to dashboard, students, attendance, results/GPA, payments, and staff account management.
- **Teacher:** Daily attendance, attendance reports, exam marks, results/GPA, and read-only student directory.
- **Accountant:** Payment entry, payment history, receipts, payment reports, and read-only student directory.
- **Student:** Personal dashboard and own ID card.

Admin creates Teacher and Accountant accounts from **Teacher & Accountant** in the sidebar. Each staff account has its own email, password, and role.

### Printing
- Student directory: use **Print Student List** after applying filters.
- Student profile: use **Print Profile** or **ID Card / PDF**.
- Payment report: use **Print Report** after applying filters.
- Individual payment receipt: use **Receipt** in the payment table.

### Profile photo delivery
Student photos are stored on Laravel's `public` disk. This version serves them through an authenticated photo route, so the page does not depend on the operating system creating the `public/storage` symbolic link. Existing files must still be present under `storage/app/public/student-photos`.

### Upgrade existing installation
1. Back up your project folder and database.
2. Replace project files with this release. Keep your existing `.env` and uploaded `storage/app/public/student-photos` files.
3. Run `composer install` if dependencies are not installed.
4. Run `php artisan optimize:clear`.
5. Sign in as the Admin and create Teacher / Accountant accounts from the staff management page.

No new database migration is required for the v4 role and print changes; the `role` field was introduced in the previous portal release. Do not run `migrate:fresh` on an existing database.


## TPIR Technology Catalogue

Student admission uses Technology only; Department is not a required form field. The built-in options are Computer (CST), Civil (CT), Electrical (ET), Mechanical (MT), Textile (TT), and Architecture (AT). Semester and Session are selected separately. A compatibility value is still stored in the legacy `department` database column so existing installations and older records remain compatible.

After updating, run `php artisan migrate` and `php artisan optimize:clear`. Do not run `migrate:fresh` on an existing institute database.

## Update v6: Fee ledger and E-Question Paper

Run the following after replacing the project files and configuring the database:

```bash
php artisan migrate
php artisan optimize:clear
php artisan serve
```

### Fee ledger
- Create a fee/due record with the student's total payable amount. Initial payment can be zero.
- Use **টাকা জমা** from the fee list to record later installments.
- Each new installment is stored separately with date, method, transaction ID and note; the remaining due is recalculated from the fee record's paid total.
- Older payments remain compatible. Payments recorded before this update are retained as opening paid balances; their historical installment breakdown is not reconstructed.

### E-Question Paper
- Admin and Teacher can create and print subject question papers for Class Test, Quiz Test, Mid-term Exam, Model Test, Assignment and Final Exam.
- Paper metadata includes Technology, Semester, Subject Code/Name, duration and marks. Each question can be tagged with a chapter/unit/topic and question type.
- The paper is labelled **BTEB প্রবিধান ২০২২**. Teachers should verify subject codes, chapter coverage and marks distribution against the institute's approved BTEB 2022 syllabus before issuing a paper; the project does not include an imported official syllabus/question bank.

## BTEB-style E-Question Paper Builder (v6.1)

The question-paper builder now supports the structured printed layout shown in the supplied BTEB sample:
- Editable board, diploma/program, exam session/year/term and department heading.
- Technology, semester, subject name/code, assessment type, duration and full marks.
- Multiple question sections (for example ক-বিভাগ, খ-বিভাগ, গ-বিভাগ), each with its own instruction and multiple questions.
- Per-question topic/chapter, question type and marks; the server validates that all question marks add up to the declared full marks.
- A4 print preview with sequential question numbering, section headings, marks at the right, and signature lines.
- Older v6 question papers remain viewable using the legacy display fallback.

After replacing the project files, run `php artisan migrate` to add the optional BTEB paper-header fields, then run `php artisan optimize:clear`.

The supplied image is treated as a layout reference, not an official syllabus dataset. Teachers must verify each subject, subject code, chapter/topic and assessment structure against the applicable BTEB 2022 curriculum and institute instructions.
