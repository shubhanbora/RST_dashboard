# RST Admin Dashboard

A PHP-based admin panel for managing employees, projects, clients, and attendance. Built with Firebase Firestore as the database and Firebase Storage for file uploads.

## Features

- Employee management with profile photos, documents (Aadhar, PAN), salary tracking
- Project tracking with homepage image and status management
- Client management with payment tracking and agreement PDFs
- Attendance system with manual marking and fingerprint device support
- Firebase Authentication for secure login
- PDF export for employees, projects, and clients

## Tech Stack

- PHP (no framework)
- Firebase Firestore (database)
- Firebase Storage (file uploads)
- Firebase Authentication (login)
- Tailwind CSS (UI)

## Setup

1. Clone the repo
2. Copy `config.sample.php` to `config.php`
3. Fill in your Firebase credentials in `config.php`
4. Enable Firestore, Storage, and Authentication in Firebase Console
5. Upload to any PHP host (tested on InfinityFree)

## Fingerprint Integration

Attendance can be auto-marked by a fingerprint device via HTTP POST to `actions/attendance_action.php` with fields `emp_id`, `date`, `status`, and `api_key`.
