# Laravel Web Application

A comprehensive Laravel-based web application with multiple features including user management, product catalog, calculators, and dashboard functionality.

## Features

- **User Authentication & Registration**
  - Secure user registration and login
  - Social login integration (Google, Facebook, Microsoft)
  - Password reset functionality
  - Email verification

- **Product Management**
  - Product catalog with CRUD operations
  - Image upload and management
  - Product categorization
  - Search and filtering

- **Dashboard & Analytics**
  - User dashboard with overview
  - Administrative controls
  - Data visualization

- **Calculation Tools**
  - Even numbers calculator
  - Multiplication calculator
  - GPA calculator

- **User Management**
  - Role-based access control
  - User permissions system
  - Admin panel for user management

## Technology Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates, Bootstrap, Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **File Storage**: Local/Public storage
- **Build Tools**: Vite, NPM

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Apache/Nginx

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Andrew-Moris/Web-andrew-230103785.git
   cd Web-andrew-230103785
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   - Create a MySQL database
   - Update `.env` file with your database credentials
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## Configuration

### Social Login Setup

1. **Google OAuth**
   - Create credentials in Google Cloud Console
   - Add redirect URI: `http://your-domain.com/auth/google/callback`
   - Update `.env` with Google client credentials

2. **Facebook Login**
   - Create Facebook App
   - Configure OAuth redirect URI
   - Update `.env` with Facebook app credentials

3. **Microsoft Login**
   - Register app in Azure AD
   - Configure redirect URI
   - Update `.env` with Microsoft app credentials

## Usage

### User Registration
- Navigate to `/register` to create a new account
- Fill in required information
- Verify email address (if enabled)

### Product Management
- Access product catalog at `/products`
- Admin users can create, edit, and delete products
- Upload product images through the admin panel

### Dashboard
- User dashboard available at `/dashboard`
- View personal information and activity
- Access calculation tools and other features

## File Structure

```
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Middleware/          # Custom middleware
│   └── ...
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
└── public/                 # Public assets
```

## API Endpoints

The application provides RESTful API endpoints for:
- User authentication
- Product management
- User management (admin)
- Dashboard data

## Security Features

- CSRF protection
- SQL injection prevention
- XSS protection
- Secure password hashing
- Rate limiting
- Input validation and sanitization

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please contact the development team or create an issue in the GitHub repository.

## Changelog

### Version 1.0.0
- Initial release
- User authentication system
- Product catalog functionality
- Dashboard implementation
- Calculator tools
- Admin panel

---

**Developed by Andrew Morris**  
**Repository**: https://github.com/Andrew-Moris/Web-andrew-230103785
