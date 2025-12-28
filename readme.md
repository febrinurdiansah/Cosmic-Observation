# Cosmic Observation

A sleek, responsive space dashboard that brings NASA's cosmic data to your fingertips. This project explores daily astronomical imagery and tracks near-earth objects in real-time.

## Features
- **NASA APOD Integration:** Fetches "Astronomy Picture of the Day" with high-definition support.
- **Asteroid Watch:** Real-time tracking of near-earth objects (NEO) using NASA's NeoWs API.
- **Dynamic UX:** Interactive starfield animation and smooth tab switching.
- **Modern UI:** Responsive design with glassmorphism aesthetics and custom dark-themed calendar.
- **Smart Fallback:** Custom error states for missing visual data.

## Tech Stack
- **Frontend:** HTML5, CSS3 (Grid & Flexbox), Vanilla JavaScript.
- **Backend:** PHP (API consumption & environment management).
- **Libraries:** Flatpickr (Custom Calendar), PHP Dotenv (Security).

## Setup
1. Clone the repo: `git clone https://github.com/febrinurdiansah/Cosmic-Observation.git`
2. Run `composer install` to set up environment support.
3. Create a `.env` file and add your `NASA_API_KEY`.
4. Launch via local PHP server or XAMPP.