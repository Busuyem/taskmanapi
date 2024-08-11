# Task Management APIs

1. Clone the repository:
    ```bash
    git clone https://github.com/Busuyem/taskmanapi.git
    cd taskmanapi
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Set up the environment file:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Configure your database in `.env`.

5. Run migrations:
    ```bash
    php artisan migrate
    ```

6. Serve the application:
    ```bash
    php artisan serve
    ```

## API Documentation

### Authentication Endpoints

- Register a new user:
    ```
    POST /api/register
    ```

- Login:
    ```
    POST /api/login
    ```

- Include the token in the Authorization header:
    ```
    Authorization: Bearer {token}
    ```

### Tasks Endpoints

- List all tasks:
    ```
    GET /api/tasks
    ```

- Create a new task:
    ```
    POST /api/tasks
    ```

- Show a specific task:
    ```
    GET /api/tasks/{id}
    ```

- Update a task:
    ```
    PUT /api/tasks/{id}
    ```

- Delete a task:
    ```
    DELETE /api/tasks/{id}
    ```

## Testing

Run tests:
```bash
php artisan test
