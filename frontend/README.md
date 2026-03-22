# MacFit — Frontend

The client-side interface for the MacFit Gym Management System. Built with **Vue.js** and powered by **Vite**, it provides gym staff with a clean, responsive UI to manage branches, workout categories, and membership bundles.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Vue.js 3 |
| Build Tool | Vite |
| Language | JavaScript |
| Styling | CSS |
| Package Manager | npm |

---

## Application Flow

```
User (Browser)
      │
      ▼
  Vue Router  (client-side navigation)
      │
      ▼
  Views / Pages  (e.g. Bundles, Branches)
      │
      ▼
  Components  (reusable UI elements)
      │
      ▼
  API Calls  (fetch/axios → Laravel backend)
      │
      ▼
  MacFit Backend API  (http://127.0.0.1:8000/api)
```

The frontend is a Single Page Application (SPA). Vue Router handles navigation without page reloads, and all data is fetched from the Laravel backend API.

---

## Current Capabilities

### 🏋️ Branch Overview
- View and manage gym locations and branches

### 🗂️ Workout Categories
- Browse and organise workout types (Strength, Cardio, HIIT, Yoga, etc.)

### 📦 Membership Bundle Management
- View all available membership bundles
- Create new bundles with session durations, linked locations, and schedules
- Edit and delete existing bundles

---

## Getting Started

### Prerequisites

Make sure the following are installed:
- [Node.js](https://nodejs.org/) (v18+)
- [npm](https://www.npmjs.com/)
- The [MacFit backend](../backend/README.md) running locally

### 1. Clone the repository

```bash
git clone https://github.com/alexamita/MacFitness.git
cd MacFitness/frontend
```

### 2. Install dependencies

```bash
npm install
```

### 3. Configure the API base URL

Make sure your backend is running at `http://127.0.0.1:8000`. If your API lives at a different URL, update the base URL in your API config file (e.g. `src/api/` or inside your Axios/fetch setup).

### 4. Start the development server

```bash
npm run dev
```

The app will be available at `http://localhost:5173`

### 5. Build for production

```bash
npm run build
```

---

## Project Structure

```
frontend/
├── public/             # Static assets
├── src/
│   ├── assets/         # Images, fonts, global styles
│   ├── components/     # Reusable UI components
│   ├── views/          # Page-level components
│   ├── router/         # Vue Router configuration
│   └── main.js         # App entry point
├── index.html          # HTML shell
├── vite.config.js      # Vite configuration
└── package.json        # Dependencies and scripts
```

---

## Related

- [MacFitness Backend →](../backend/README.md)
- [Full Repository →](https://github.com/alexamita/MacFitness)
