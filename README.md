# Online Gaming Platform - PHP & MySQL

## Overview
This project is an online gaming platform developed using PHP and MySQL for the backend and HTML, CSS, and JavaScript for the frontend. The platform offers a variety of games, both free and paid, as well as an automated invoicing system, user roles, and a leaderboard for tracking players' scores.

## Features

### 1. Available Games
**Free Games**:
- Quiz: An interactive game with randomly generated questions and randomized answers.
- Snake, Memory Game, Tic-Tac-Toe.

**Paid Games**:
- Tetris, Flappy Bird, Pong, Breakout.  
  Users need to make a payment to access these games. After payment, an invoice is generated, and the game becomes available.

### 2. User Management
There are three types of user roles:
- **Guests**: Can access only free games without authentication.
- **Authenticated Users**: Can access both free games and paid games (after making a payment). Their scores are saved and displayed on the leaderboard.
- **Administrator**: Has full control over the platform: adding, editing, deleting games, and managing users (including registering other administrators).
- **Super Admin**: This unique role allows the super-administrator to modify and delete users, as well as change user roles (from user to admin and vice versa).

### 3. Automated Invoicing
For every paid game purchase, the platform automatically generates an invoice that contains details such as the game's name, price, and user information. The invoices are stored in the database and can be accessed later by the users.

### 4. Scoreboard
The scores of authenticated users are saved and displayed in a global leaderboard. The leaderboard is updated in real-time and is only visible to authenticated users.

### 5. Game Management
Administrators can add, edit, or delete games via a dedicated interface. They can also add new questions to the Quiz game, where both questions and answers are randomly generated for a more dynamic experience.

### 6. Frontend
The user interface is built using HTML, CSS, and JavaScript, providing an interactive and responsive experience. JavaScript is used for game logic and form validation, while CSS is used for modern styling of the pages.

## Technologies Used
- **Backend**: PHP, MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Database**: phpMyAdmin (MySQL)
- **Invoicing**: Automated invoicing system for paid game purchases.

## Installation and Setup

1. Clone this repository:
   ```bash
   git clone https://github.com/user/repo.git
- Set up the database using phpMyAdmin and import the SQL file to structure the tables.
- Configure the .env file to connect to the MySQL database.
- Open the application in your browser and log in to access the platform's features.
## Roles and Permissions
- Users can access and play games, view the leaderboard, and manage their purchases.
- Administrators can manage games and users by adding/modifying/suspending user accounts and games.
- The Super Admin has exclusive access to user management functions and role changes (can promote users to administrator or revoke their status).
