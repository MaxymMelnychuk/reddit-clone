# 🍿 Reddit Clone – by Maxym Melnychuk

## 📖 Description

This project is a **simplified Reddit clone** built **quickly with PHP**.  
It was created as a **learning project** to practice:

- User management  
- Creating and viewing discussions  
- Adding and managing comments  
- Admin functionalities  


## 🧩 Current Features

### 👤 User Side
- View all discussions  
- Read and post comments on different discussions  
- Create your own discussions  

### 🛡️ Admin Side
- Delete comments and discussions  
- Hide or show sensitive comments  
- Create discussions and post comments like a normal user  


## 🛠️ Technologies Used

- **PHP** (backend)  
- **MySQL** (database)  
- **HTML / CSS** (simple frontend)  
- **Laragon** for local server  
- **phpMyAdmin** for database management (basic setup)  


## ⚙️ Installation and Setup

### Prerequisites
- **PHP**  
- **Local server (Laragon, XAMPP, or MAMP)**  
- **MySQL / MariaDB**  

### Steps

1. **Clone the repository**
```bash
git clone https://github.com/MaxymMelnychuk/reddit-clone.git
```
2. **Place the project in your server's www/htdocs folder**
3. **Create the database in phpMyAdmin (default: security)**
4. **Update config.php with your database credentials:**
- define('DB_HOST', 'localhost');
- define('DB_NAME', 'security');
- define('DB_USER', 'root');
- define('DB_PASS', '');

5. **Open the project in your browser**
Example Laragon: http://localhost/reddit-clone/

⚡ **Notes**

This project is very basic and meant for learning purposes

Admin functionality is simple but allows testing delete and hide comment features

Styling is minimal, focus is on PHP / MySQL logic

Emphasis was put on web security, including:

- Input validation
- CSRF protection
- Password hashing
- Proper session management
