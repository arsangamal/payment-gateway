# Payment Gateway API

A robust Laravel 12 RESTful API for managing orders and payments with support for multiple payment gateways (Stripe and Fawry). This application demonstrates a scalable architecture using repositories, services, and the factory pattern for payment gateway abstraction.

## Project Overview

This is a backend API that allows users to:
- **Authentication**: Register and log in with JWT-based authentication
- **Order Management**: Create, read, update, delete, and confirm orders with multiple items
- **Payment Processing**: Process payments through multiple payment gateways with a unified interface
- **Payment History**: Track all payments made and their status

The application follows Laravel best practices with:
- Repository pattern for data access abstraction
- Service layer for business logic
- Factory pattern for payment gateway implementations
- Eloquent ORM with proper relationships and scoping
- Comprehensive test coverage with PHPUnit

## Tech Stack

- **Framework**: Laravel 12
- **Database**: SQLite (configurable)
- **Authentication**: JWT (php-open-source-saver/jwt-auth)
- **Testing**: PHPUnit
- **Code Style**: Laravel Pint

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (or another database configured in `.env`)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd payment-gateway-task
   ```

2. **Run the setup script**
   ```bash
   composer run setup
   ```
   
   This script will:
   - Install PHP dependencies via Composer
   - Create `.env` file from `.env.example`
   - Generate application key
   - Run database migrations
   - Install Node dependencies
   - Build frontend assets

3. **Start the development server**
   ```bash
   composer run dev
   ```
   
   This will concurrently start:
   - Laravel development server (port 8000)
   - Queue listener for background jobs
   - Laravel Pail for real-time logs
   - Vite dev server for frontend assets

The API will be available at `http://localhost:8000/api`

### Configuration

Create a `.env` file in the root directory with your configuration:

```env
APP_NAME=PaymentGateway
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
JWT_SECRET=your-jwt-secret-here

# Stripe Gateway Configuration
STRIPE_API_KEY=your-stripe-api-key
STRIPE_API_SECRET=your-stripe-api-secret

# Fawry Gateway Configuration
FAWRY_CLIENT_ID=your-fawry-client-id
FAWRY_CLIENT_SECRET=your-fawry-client-secret
```

## API Endpoints

All endpoints (except `/login` and `/register`) require authentication via Bearer token.

### Authentication

- `POST /api/login` - User login
- `POST /api/register` - User registration

### Orders

- `GET /api/orders` - List all orders for the authenticated user
- `POST /api/orders` - Create a new order
- `GET /api/orders/{order}` - Get a specific order
- `PUT /api/orders/{order}` - Update an order
- `DELETE /api/orders/{order}` - Delete an order
- `GET /api/orders/{order}/confirm` - Confirm order status

### Payments

- `POST /api/payments/{order}/pay` - Process payment for an order
- `GET /api/payments` - List all payments for the authenticated user
- `GET /api/payments/{payment}` - Get a specific payment

## Adding a New Payment Gateway

The application uses the **Factory Pattern** to manage payment gateways. This makes it easy to add new gateways without modifying existing code.

### Step 1: Create a New Gateway Class

Create a new file in `app/Gateways/` that implements the `IPaymentGateway` interface:

```php
<?php

namespace App\Gateways;

use App\Interfaces\IPaymentGateway;
use App\Models\Order;

class PayPalGateway implements IPaymentGateway
{
    public function pay(Order $order): bool
    {
        // Implement your PayPal payment logic here
        // Call PayPal API, process payment, etc.
        
        // Return true if payment successful, false otherwise
        return true;
    }
}
```

### Step 2: Register the Gateway in Configuration

Add your new gateway to the `config/gateways.php` configuration file:

```php
'gateways' => [
    'paypal' => [
        'implementation' => PayPalGateway::class,
        'client_id' => env('PAYPAL_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
    ],
    // ... other gateways
],
```

### Step 3: Add Environment Variables

Add your gateway credentials to the `.env` file:

```env
PAYPAL_CLIENT_ID=your-paypal-client-id
PAYPAL_CLIENT_SECRET=your-paypal-client-secret
```

### Step 4: Use the New Gateway

The `PaymentGatewayFactory` will automatically handle instantiation:

```php
use App\Gateways\PaymentGatewayFactory;

$gateway = PaymentGatewayFactory::make('paypal'); // Creates PayPalGateway instance
$gateway->pay($order);
```

Or set it as the default gateway by updating `config/gateways.php`:

```php
'default' => 'paypal',
```

### Step 5: Write Tests (Recommended)

Create a test for your new gateway:

```php
use PHPUnit\Framework\TestCase;
use App\Gateways\PayPalGateway;
use App\Models\Order;

class PayPalGatewayTest extends TestCase
{
    public function testPaymentProcessing(): void
    {
        $gateway = new PayPalGateway();
        $order = Order::factory()->create();
        
        $result = $gateway->pay($order);
        
        $this->assertTrue($result);
    }
}
```

## Running Tests

### Run All Tests

```bash
php artisan test --compact
```

### Run Tests for a Specific File

```bash
php artisan test --compact tests/Feature/PayControllerTest.php
```

### Run Tests with a Specific Filter

```bash
php artisan test --compact --filter=testCreateOrderWithItems
```

### Run Tests with Code Coverage

```bash
php artisan test --coverage
```

### Available Test Files

The application includes comprehensive feature tests:

- **Authentication**: Login and registration flows
- **Order Management**: CRUD operations, order confirmation
- **Payment Processing**: Payment creation, payment retrieval, multi-gateway support
- **Authorization**: Ensures users can only access their own orders and payments

Tests use Laravel's testing utilities with:
- Factory pattern for test data generation
- Database transactions for isolation
- JSON assertions for API responses
- Proper authentication mocking

## Project Structure

```
app/
├── Gateways/              # Payment gateway implementations
│   ├── FawryGateway.php
│   ├── StripGateway.php
│   └── PaymentGatewayFactory.php
├── Http/
│   ├── Controllers/       # API endpoint handlers
│   │   ├── Auth/
│   │   ├── Order/
│   │   └── Payment/
│   └── Requests/          # Form request validation
├── Interfaces/            # Contracts for abstraction
│   ├── IPaymentGateway.php
│   ├── IOrderRepository.php
│   └── IPaymentRepository.php
├── Models/                # Eloquent models
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Payment.php
│   └── User.php
├── Repositories/          # Data access layer
│   ├── OrderRepository.php
│   └── PaymentRepository.php
├── Services/              # Business logic layer
│   ├── OrderService.php
│   └── PaymentService.php
├── OrderStatus.php        # Order status enum
└── PaymentStatus.php      # Payment status enum
```

## Database Schema

### Users Table
- id, name, email, password, email_verified_at, remember_token, timestamps

### Orders Table
- id, user_id, total, status, timestamps

### Order Items Table
- id, order_id, product_name, quantity, price, timestamps

### Payments Table
- id, order_id, payment_gateway, status, external_payment_id, timestamps

## Authentication

The API uses JWT (JSON Web Token) authentication. To authenticate:

1. Register a new user: `POST /api/register`
2. Login: `POST /api/login`
3. Use the returned token in the `Authorization` header: `Bearer <token>`

## Error Handling

The API returns structured JSON responses with appropriate HTTP status codes:

- `200`: Successful GET/PUT request
- `201`: Successful POST request
- `204`: Successful DELETE request
- `400`: Bad request (validation errors)
- `401`: Unauthorized (invalid/missing token)
- `404`: Resource not found
- `422`: Unprocessable entity
- `500`: Internal server error

## Contributing

1. Follow Laravel conventions and PSR-12 coding standards
2. Write tests for new features
3. Run `vendor/bin/pint --dirty` before committing to ensure code style compliance
4. Ensure all tests pass before submitting changes

## License

This project is open-sourced software licensed under the MIT license.
