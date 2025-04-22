---

# 🔐 Laravel Temp Password

A Laravel package to generate **one-time, time-limited temporary passwords** for user authentication.
These passwords can be used in place of regular passwords **during login** and automatically expire after a configurable period (e.g., 5 minutes).

---

## 🚀 Features

- No need to modify your existing login logic
- Supports default and custom user models
- Auto-expires passwords (default: 5 minutes)
- Optional CLI command to generate temporary passwords
- Supports password strength levels: `simple`, `medium`, `strong`
- Uses Laravel's built-in authentication system

---

## 📦 Installation

```bash
composer require codetyme/laravel-temp-password
```
---

## 📂 Publish Config & Migrations

```bash
php artisan vendor:publish --provider="codetyme\TempPassword\TempPassServiceProvider" --tag=config
php artisan migrate
```

This will:
- Publish the `config/temp-password.php` file
- Create the `temp_passwords` database table

---

## ⚙️ Configuration

Your published config file looks like this:

```php
return [
    'enabled' => env('TEMP_PASS_ENABLED', true),

    'expiry_minutes' => env('TEMP_PASS_EXPIRY_TIME', 5), // Valid for 5 minutes

    'length' => env('TEMP_PASS_LENGTH', 8), // Default password length

    'strength' => env('TEMP_PASS_STRENGTH', 'medium'), // simple | medium | strong
];
```

You can override these settings in your `.env` file if needed:

```env
TEMP_PASS_ENABLED=true
TEMP_PASS_EXPIRY_TIME=5
TEMP_PASS_LENGTH=10
TEMP_PASS_STRENGTH=strong
```

---

## 🧠 How It Works

Once installed, the package automatically hooks into Laravel’s default authentication.  
No need to change `Auth::attempt()` or override any guards.

You can log in with either:
- Regular user password
- A one-time, time-limited temporary password

---

## 🔐 Usage

### 📌 Generate a Temp Password (Programmatically)

```php
use codetyme\TempPassword\Facades\TempPass;

// Using default settings
$password = TempPass::generate($user);

// Custom length
$password = TempPass::generate($user, 12);

// Custom length + strength
$password = TempPass::generate($user, 16, 'strong');
```

---

### 🖥️ Generate via Artisan Command (Optional)

```bash
php artisan temp-password:generate
```

#### Options:

| Option         | Description                                |
|----------------|--------------------------------------------|
| `--email`      | User's email (required or prompted)        |
| `--model`      | Fully qualified model class (optional)     |
| `--length`     | Password length (optional)                 |
| `--strength`   | Password strength (optional)               |


#### Examples:

```bash
# Prompt for email and use default model (App\Models\User)
php artisan temp-password:generate

# Provide email directly
php artisan temp-password:generate --email=someone@example.com

# Use a custom model
php artisan temp-password:generate --email=someone@example.com --model=App\\Models\\Customer

# Use a custom length
php artisan temp-password:generate --email=someone@example.com --length=10

# Use a custom length + strength
php artisan temp-password:generate --email=someone@example.com --length=15 --strength=strong
```

---

## 🔧 Example Use in Tinker

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
$password = TempPass::generate($user);

Auth::attempt(['email' => $user->email, 'password' => $password]); // returns true ✅
```

---

## 📁 Database Table

The package creates a `temp_passwords` table with the following structure:


| Column                 | Description                                |
|------------------------|--------------------------------------------|
| `authenticatable_id`   | ID of the user                             |
| `authenticatable_type` | User model class (e.g. App\Models\User)    |
| `temp_password`        | Bcrypt-hashed temp password                |
| `used`                 | Marked true after first use                |
| `created_at`           | For expiry check                           |


---

## 🛡️ Security Notes

- Passwords are hashed using `bcrypt` before storing
- Temp passwords are one-time use only
- Expire automatically after the configured minutes

---

## 📚 Developer

Rohit Suthar, Mumbai
Email: rohisuthar@gmail.com

---

## 📚 License

MIT License — open-source and free to use.

---
