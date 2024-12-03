# **LMS**

A comprehensive Learning Management System (LMS) built to simplify online learning and course management.

![Alt Text](https://raw.githubusercontent.com/aungmoe32/cv/refs/heads/main/images/lms.jpg)

## **Demo**

Explore the demo: [YouTube](https://youtu.be/a6o9gL58PYc?si=dXAeuJTCtZ6jkFf_).

## **Features**

-   **User Authentication**: Login and register functionality with role-based permissions.
-   **Course Management**:
    -   Courses have sections and lectures with support for video, audio, text, and documents.
    -   Includes filtering and searching by categories, price, and levels.
-   **User Roles**:
    -   **Student**: Enroll in courses, rate/comment, and learn from curriculums.
    -   **Instructor**: CRUD operations on courses and sections, and monetize courses.
    -   **Admin**: Manage users, categories, and roles.
-   **Course Lecture File Support**:
    -   Video: MP4, QuickTime
    -   Audio: MP3, WAV, M4A
    -   Document: PDF
-   **Ratings & Comments**: Students can rate and review courses.
-   **Payment Integration**: Uses PayPal sandbox for course payments.

## **Tech Stack**

-   **Backend**: Laravel 8
-   **Frontend**: Vue 2 with Vuetify for UI
-   **State Management**: Vuex
-   **Routing**: Vue Router
-   **API Security**: Laravel Sanctum

## **Implementations**

-   **SPA with Laravel Sanctum API**: Built Vue.js SPA connected to a Laravel Sanctum API backend for secure authentication and data handling.
-   **Vuetify for UI and Responsive Design**: Built with Vuetify to create UI components like curriculums and tables, ensuring a clean and responsive design for all devices.
-   **State Management in Vue**: Implemented state management using Vuex, allowing centralized management of application state.
-   **Role Permissions**:
    -   Students, Instructors, and Admins have distinct access levels.
-   **Laravel Eloquent Polymorphic Relationship for Course Lecture Types**: Implemented in Laravel Eloquent that links various lecture content types (video, audio, document, text) to a course lecture.
-   **API Protection with Middlewares**: Secured the API using Laravel Auth middlewares and implemented custom middleware such as `IsSubscribed` to ensure users have an active subscription and `IsCourseOwner` to verify course ownership before allowing access to specific routes.
-   **Laravel Sanctum Session-Based Authentication**: Using Laravel Sanctum's session-based cookie authentication, which provides CSRF protection and secures credentials against XSS attacks.

-   **Eloquent Events for Relationship Models**: I used Laravel Eloquent events to handle updates and deletions of related models, ensuring the integrity and proper management of relationships.

-   **PayPal Integration**: Integrated PayPal using its sandbox environment for testing payments, allowing users to make secure transactions for courses within the application.

## Challenges

-   **State Management in Vue**: Managing the application's state efficiently with Vuex.
-   **Responsive UI**: Ensuring that the user interface works well across different screen sizes and devices.
-   **Optimized Eloquent Relationships**: Improving database queries and relationships in Laravel's Eloquent ORM for better performance.
-   **PayPal Integration**: Integrating PayPal for payment processing, including handling transactions and testing with the sandbox environment.

## **Installation**

### **Frontend**

1.  Install dependencies:

    `npm install`

2.  Compile assets:

    `npm run dev`

### **Backend**

1.  Install dependencies:

    `composer install`

2.  Copy `.env` configuration:

    `cp .env.example .env`

3.  Migrate and seed database:

    `php artisan migrate && php artisan db:seed`

4.  Create a symbolic link:

    `php artisan storage:link`

5.  Serve the application:

    `php artisan serve`

### Payment

Test with Paypal sandbox :

-   Create a Paypal developer account.
-   Copy CLIENT_ID and CLIENT_SECRET
    Add CLIENT_ID and CLIENT_SECRET to .env file

```env
...
PAYPAL_SANDBOX_CLIENT_ID=...
PAYPAL_SANDBOX_CLIENT_SECRET=...
```

### License

This project is licensed under the MIT License – see the [LICENSE](./LICENSE) file for details.
