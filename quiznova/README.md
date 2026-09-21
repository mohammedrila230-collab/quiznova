# QuizNova — Interactive Quiz & Trivia Game

QuizNova is an **ICT 2209 Web Technologies** mini project built with **HTML, CSS, Bootstrap, JavaScript, PHP and MySQL**. The public frontend uses normal `.html` pages, while PHP/MySQL provide authentication, database APIs, contact processing, sessions and the private dashboard.

## Main features

- Responsive multi-page interface using HTML, CSS and Bootstrap 5
- 5 categories: General Knowledge, Science, Technology, Sports and Movies
- Easy, Medium and Hard difficulty for every category
- 75 MySQL questions (5 questions per category/difficulty track)
- JavaScript 20-second timer, live score, progress bar and instant answer feedback
- Category search/filter controls
- PHP registration/login/logout with sessions
- Password hashing with `password_hash()` and verification with `password_verify()`
- Private dashboard with quiz history and performance statistics
- Live leaderboard based on saved MySQL quiz results
- Contact form with HTML/JavaScript validation, PHP validation and MySQL storage
- Reveal animations, event handling and responsive navigation

## Requirement mapping

| Requirement | Implementation |
|---|---|
| HTML + CSS | `index.html`, `categories.html`, `quiz.html`, `leaderboard.html`, `contact.html`, `assets/css/style.css` |
| Bootstrap | Bootstrap 5.3.3 used throughout the frontend |
| Minimum 3 pages | Home, Categories, Quiz, Leaderboard, Contact, Login/Register and Dashboard |
| Responsive design | Bootstrap grid plus responsive CSS media queries |
| Dynamic content | Quiz questions, score, timer, progress, session-aware navigation and leaderboard |
| User input + validation | Registration, login and contact forms |
| JavaScript features | Dynamic updates, form validation, event handling, filtering and animations |
| PHP + MySQL | Database connection, authentication, APIs, score storage, contact storage and dashboard |
| Registration | `auth/register.php` with `password_hash()` |
| Login/session | `auth/login.php` with `password_verify()` and PHP session |
| Logout | `auth/logout.php` destroys the session |
| Contact form | `contact.php` stores messages in the `messages` table |
| Database export | `database.sql` included |
| Documentation | This README plus comments in core code |

## Folder structure

```text
quiznova/
├── index.html
├── index.php              # compatibility redirect to index.html
├── categories.html
├── categories.php         # compatibility redirect
├── quiz.html
├── quiz.php               # compatibility redirect preserving query string
├── leaderboard.html
├── leaderboard.php        # compatibility redirect
├── contact.html
├── contact.php            # GET shows contact page; POST stores message
├── dashboard.php
├── result.php             # compatibility redirect (results are shown in quiz.html)
├── database.sql
├── README.md
├── auth/
│   ├── login.html
│   ├── login.php
│   ├── register.html
│   ├── register.php
│   └── logout.php
├── api/
│   ├── leaderboard.php
│   ├── questions.php
│   ├── save_result.php
│   └── session.php
├── includes/
│   ├── db.php
│   └── functions.php
└── assets/
    ├── css/style.css
    ├── images/README.txt
    └── js/
        ├── app.js
        ├── auth.js
        ├── categories.js
        ├── contact.js
        ├── leaderboard.js
        └── quiz.js
```

## Run with WAMP

**Important when replacing an older QuizNova version:** delete or rename the old `C:\wamp64\www\quiznova` folder first. Then copy this new `quiznova` folder into `C:\wamp64\www\`. This prevents old PHP files from remaining in the project.

1. Put the project at `C:\wamp64\www\quiznova`.
2. Start Wampserver and wait until the WAMP icon is green.
3. Open phpMyAdmin (for example `http://localhost/phpmyadmin5.2.3/`).
4. Import `database.sql`. It creates/selects the `quiz_nova` database and creates all required tables/questions.
5. Open `http://localhost/quiznova/`.

## Default database connection

`includes/db.php` uses:

```text
host: localhost
user: root
password: empty
database: quiz_nova
```

If your WAMP MySQL credentials are different, update `includes/db.php`.

## Recommended test flow

1. Home opens at `http://localhost/quiznova/`.
2. Categories search/filter works.
3. Start a quiz and complete all 5 questions.
4. Register a new account.
5. Login and complete another quiz.
6. Confirm the score appears in Dashboard.
7. Confirm the player appears in Leaderboard.
8. Submit Contact form and verify the row in phpMyAdmin `messages` table.
9. Logout and confirm Dashboard redirects to login.

## Database tables

- `users` — user accounts with hashed passwords
- `questions` — quiz questions and four answer options
- `quiz_results` — saved user scores
- `messages` — contact form submissions

## GitHub submission

Include `database.sql`, `README.md`, all HTML/CSS/JavaScript/PHP files and project folders. Push the complete `quiznova` project to a **public GitHub repository** with meaningful commit messages, then submit the repository link.
