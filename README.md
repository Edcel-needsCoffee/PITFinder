
# PIT Navigation System

A real-time campus map navigation system for Palompon Institute of Technology (PIT). Built with Leaflet.js, WebSocket, Node.js, and MySQL.

---

## Features

- Interactive map with building polygons
- Real-time live updates via WebSocket
- Floor-by-floor room navigation with accordion view
- Admin dashboard with live audit logs and insights
- Admin authentication with session tokens
- Activity tracking — logs every add, update, and delete with who did it and when
- Docker support for easy deployment

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML, CSS, JavaScript, Leaflet.js |
| Backend | Node.js, WebSocket (ws) |
| Database | MySQL (mysql2/promise) |
| Containerization | Docker, docker-compose |

---

## Project Structure

```
NavMap/
├── index.html              # Main map page
├── admin.html              # Admin dashboard
├── webSocketServer.js      # Node.js WebSocket server
├── Dockerfile              # Docker build instructions
├── docker-compose.yml      # Multi-container setup
├── init.sql                # Database schema and default admin
├── package.json
└── README.md
```

---

## Database Tables

| Table | Description |
|---|---|
| `buildings` | Building name, description, colors |
| `building_points` | Polygon coordinates per building |
| `rooms` | Rooms per building with floor number |
| `announcements` | Campus announcements |
| `admin_users` | Admin accounts |
| `activity_logs` | Full audit trail of all CRUD actions |

---

## Getting Started

### Option 1 — Run with Docker (Recommended)

**Requirements:** Docker Desktop installed

```bash
# 1. Clone the repository
git clone https://github.com/yourusername/NavMap.git
cd NavMap

# 2. Start all containers
docker-compose up --build
```

Once running, open your browser:

| URL | Description |
|---|---|
| `http://localhost:8080/index.html` | Map navigation page |
| `http://localhost:8080/admin.html` | Admin dashboard |
| `http://localhost:8081` | phpMyAdmin (DB manager) |

To stop:
```bash
docker-compose down
```

---

### Option 2 — Run Manually (with XAMPP)

**Requirements:** Node.js, XAMPP (MySQL + Apache)

```bash
# 1. Install dependencies
npm install

# 2. Import the database
# Open phpMyAdmin → create database pit_finder → import pit_finder.sql

# 3. Start the WebSocket server
node webSocketServer.js

# 4. Open in browser
# http://localhost/NavMap/index.html
```

---

## Default Admin Credentials

| Field | Value |
|---|---|
| Username | `admin` |
| Password | `admin123` |

> Change the password after first login.

---

## WebSocket Actions

| Action | Description |
|---|---|
| `getBuildings` | Fetch all buildings with polygons and rooms |
| `adminLogin` | Authenticate admin user |
| `getDashboard` | Fetch stats, audit logs, buildings overview |
| `saveBuilding` | Add a new building |
| `deleteBuilding` | Delete a building |
| `updateBuilding` | Update building name/description |
| `addRoom` | Add a room to a building |
| `deleteRoom` | Delete a room |
| `updateRoom` | Update room details |

---

## Live Update Behavior

The server polls the database every **3 seconds**. Any change made — whether through the admin panel or directly in phpMyAdmin — is automatically detected and pushed to all connected clients via WebSocket in real time.

---

## CI Pipeline

This project uses GitHub Actions for continuous integration.

On every push or pull request, the pipeline automatically:
- installs Node.js dependencies
- checks `webSocketServer.js` for syntax errors
- builds Docker containers
- starts the services to verify that the system runs correctly

---

## Contributors

- Developed by: The Finders
- Institution: Palompon Institute of Technology
- Course: BSIT 2-A
