# REST API Documentation

This document provides details for the RESTful APIs generated based on the `schema.sql` database structure.

## General Information

*   **Base URL:** The base URL for all API endpoints will depend on your server configuration (e.g., `http://localhost/your_project_directory/`).
*   **Output Format:** All APIs return JSON responses.
*   **HTTP Status Codes:** Standard HTTP status codes are used (200 OK, 201 Created, 400 Bad Request, 404 Not Found, 405 Method Not Allowed, 500 Internal Server Error).
*   **Filtering:**
    *   GET requests support filtering via query string parameters (e.g., `?column_name=value`).
    *   For tables with a `status` ENUM('active', 'inactive') column, results are filtered by `status=active` by default unless a specific `status` is provided in the query.
    *   For tables with an `is_visible` BOOLEAN column (like `latest_updates`, `latest_news`), results are filtered by `is_visible=1` (true) by default unless a specific `is_visible` value (0 or 1) is provided. This default applies if a `status` column is not present or if `status` is explicitly queried.
    *   If both `status` and `is_visible` columns exist and neither are specified in the query, the API defaults to `status=active` AND `is_visible=1`.
*   **Sorting:** Data is generally sorted by `sort_order ASC` if a `sort_order` column exists in the table.

---

## API Endpoints

### 1. Business Data

*   **Endpoint:** `/api/business_data/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   None typically used as this table stores a single record.
*   **Sample Response (200 OK):**
    ```json
    {
        "id": 1,
        "header_logo": "path/to/header_logo.png",
        "footer_logo": "path/to/footer_logo.png",
        "business_name": "Swifcon",
        "address": "123 Business Street, City, Country",
        "email": "contact@swifcon.com",
        "phone": "+1234567890",
        "instagram_link": "https://instagram.com/swifcon",
        "facebook_link": "https://facebook.com/swifcon",
        "twitter_link": "https://twitter.com/swifcon",
        "web_credits": "Developed by XYZ",
        "copyright_content": "© 2025 Swifcon. All rights reserved.",
        "updated_at": "2023-10-27 10:00:00"
    }
    ```
    *   **Sample Response (200 OK - No data):**
    ```json
    {}
    ```

### 2. Hero Sliders

*   **Endpoint:** `/api/hero_sliders/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by a specific slider ID. Example: `/api/hero_sliders/get.php?id=1`
    *   `status` (string): Filter by status. Example: `/api/hero_sliders/get.php?status=inactive` (Default: `active`)
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "title": "Innovative Solutions",
            "subtitle": "Leading the charge in tech.",
            "image": "path/to/slider1.jpg",
            "sort_order": 0,
            "status": "active",
            "created_at": "2023-10-27 09:00:00"
        },
        {
            "id": 2,
            "title": "Future Forward",
            "subtitle": "Building tomorrow, today.",
            "image": "path/to/slider2.jpg",
            "sort_order": 1,
            "status": "active",
            "created_at": "2023-10-27 09:05:00"
        }
    ]
    ```
    *   **Sample Response (200 OK - No active sliders):**
    ```json
    []
    ```
    *   **Sample Response (404 Not Found - Specific query yields no results):**
    ```json
    {
        "message": "No records found matching your criteria."
    }
    ```

### 3. Testimonials

*   **Endpoint:** `/api/testimonials/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by a specific testimonial ID.
    *   `status` (string): Filter by status. (Default: `active`)
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "photo": "path/to/client1.jpg",
            "name": "Jane Doe",
            "designation": "CEO",
            "organization": "Acme Corp",
            "content": "This service is fantastic!",
            "sort_order": 0,
            "status": "active",
            "created_at": "2023-10-26 14:00:00"
        }
    ]
    ```

### 4. About Content

*   **Endpoint:** `/api/about_content/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   None typically used as this table stores a single record.
*   **Sample Response (200 OK):**
    ```json
    {
        "id": 1,
        "about_content": "Detailed content about the company...",
        "mission": "Our mission is to...",
        "vision": "Our vision is to...",
        "years_experience": 10,
        "projects_completed": 250,
        "happy_clients": 180,
        "team_members": 45,
        "updated_at": "2023-10-27 11:00:00"
    }
    ```
    *   **Sample Response (200 OK - No data):**
    ```json
    []
    ```
    *(Note: The endpoint currently returns `[]` for no data on single record tables, might be better to return `{}`. This was reviewed: `about_content/get.php` and `business_data/get.php` now return `data[0]` or an empty object `{}` if no record, or `[]` if query leads to no results explicitly. `contact_settings/get.php` returns `data[0]` or `[]`.)*

---

### 5. Our Journey

*   **Endpoint:** `/api/our_journey/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by a specific journey entry ID.
    *   `year` (integer): Filter by year. Example: `/api/our_journey/get.php?year=2020`
    *   `status` (string): Filter by status. (Default: `active`)
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "year": 2019,
            "title": "Company Founded",
            "subtitle": "Started with a small team.",
            "sort_order": 0,
            "status": "active",
            "created_at": "2023-01-10 00:00:00"
        },
        {
            "id": 2,
            "year": 2021,
            "title": "Major Milestone Achieved",
            "subtitle": "Expanded our operations.",
            "sort_order": 1,
            "status": "active",
            "created_at": "2023-01-15 00:00:00"
        }
    ]
    ```

### 6. Our Values

*   **Endpoint:** `/api/our_values/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by a specific value entry ID.
    *   `status` (string): Filter by status. (Default: `active`)
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "title": "Integrity",
            "subtitle": "Upholding the highest standards.",
            "sort_order": 0,
            "status": "active",
            "created_at": "2023-02-01 00:00:00"
        },
        {
            "id": 2,
            "title": "Innovation",
            "subtitle": "Driving change and progress.",
            "sort_order": 1,
            "status": "active",
            "created_at": "2023-02-05 00:00:00"
        }
    ]
    ```

### 7. Industry Categories

*   **Endpoint:** `/api/industry_categories/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by a specific category ID.
    *   `category_name` (string): Filter by category name.
    *   `status` (string): Filter by status. (Default: `active`)
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "category_name": "Technology",
            "image": "path/to/tech_category.jpg",
            "content": "Innovations in the tech sector.",
            "sort_order": 0,
            "status": "active",
            "created_at": "2023-03-10 00:00:00"
        },
        {
            "id": 2,
            "category_name": "Healthcare",
            "image": "path/to/health_category.jpg",
            "content": "Advancements in healthcare.",
            "sort_order": 1,
            "status": "active",
            "created_at": "2023-03-12 00:00:00"
        }
    ]
    ```

### 8. Category Key Features

*   **Endpoint:** `/api/category_key_features/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by a specific key feature ID.
    *   `category_id` (integer): **Required or recommended** to fetch features for a specific industry category. Example: `/api/category_key_features/get.php?category_id=1`
*   **Sample Response (200 OK for `?category_id=1`):**
    ```json
    [
        {
            "id": 1,
            "category_id": 1,
            "feature_text": "AI Driven Solutions",
            "sort_order": 0
        },
        {
            "id": 2,
            "category_id": 1,
            "feature_text": "Scalable Architecture",
            "sort_order": 1
        }
    ]
    ```
    *(Note: This table does not have a `status` column, so default active filtering does not apply.)*

---

### 9. Projects

*   **Endpoint:** `/api/projects/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by project ID.
    *   `industry_category_id` (integer): Filter projects by industry category. Example: `/api/projects/get.php?industry_category_id=1`
    *   `location` (string): Filter by project location.
    *   `status` (string): Filter by status. (Default: `active`)
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "industry_category_id": 1,
            "location": "New York, USA",
            "title": "AI Platform Development",
            "image": "path/to/project_ai.jpg",
            "project_overview": "Developing a cutting-edge AI platform.",
            "video_url": "https://youtube.com/project_ai_video",
            "sort_order": 0,
            "status": "active",
            "created_at": "2023-04-15 00:00:00"
        }
    ]
    ```

### 10. Project Gallery

*   **Endpoint:** `/api/project_gallery/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by gallery image ID.
    *   `project_id` (integer): **Required or recommended** to fetch images for a specific project. Example: `/api/project_gallery/get.php?project_id=1`
*   **Sample Response (200 OK for `?project_id=1`):**
    ```json
    [
        {
            "id": 1,
            "project_id": 1,
            "image_path": "path/to/project1_gallery_img1.jpg",
            "sort_order": 0
        },
        {
            "id": 2,
            "project_id": 1,
            "image_path": "path/to/project1_gallery_img2.jpg",
            "sort_order": 1
        }
    ]
    ```
    *(Note: This table does not have a `status` column.)*

### 11. Services

*   **Endpoint:** `/api/services/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by service ID.
    *   `service_name` (string): Filter by service name.
    *   `status` (string): Filter by status. (Default: `active`)
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "service_name": "Web Development",
            "image": "path/to/web_dev_service.jpg",
            "description": "Full-stack web development services.",
            "our_expertise": "Expertise in modern web technologies...",
            "sort_order": 0,
            "status": "active",
            "created_at": "2023-05-01 00:00:00"
        }
    ]
    ```

### 12. Service Offerings

*   **Endpoint:** `/api/service_offerings/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by offering ID.
    *   `service_id` (integer): **Required or recommended** to fetch offerings for a specific service. Example: `/api/service_offerings/get.php?service_id=1`
*   **Sample Response (200 OK for `?service_id=1`):**
    ```json
    [
        {
            "id": 1,
            "service_id": 1,
            "offering_text": "Custom Website Design",
            "sort_order": 0
        },
        {
            "id": 2,
            "service_id": 1,
            "offering_text": "E-commerce Solutions",
            "sort_order": 1
        }
    ]
    ```
    *(Note: This table does not have a `status` column.)*

### 13. Service Benefits

*   **Endpoint:** `/api/service_benefits/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by benefit ID.
    *   `service_id` (integer): **Required or recommended** to fetch benefits for a specific service. Example: `/api/service_benefits/get.php?service_id=1`
*   **Sample Response (200 OK for `?service_id=1`):**
    ```json
    [
        {
            "id": 1,
            "service_id": 1,
            "benefit_text": "Improved User Engagement",
            "sort_order": 0
        },
        {
            "id": 2,
            "service_id": 1,
            "benefit_text": "Increased Conversion Rates",
            "sort_order": 1
        }
    ]
    ```
    *(Note: This table does not have a `status` column.)*

---

### 14. Contact Settings

*   **Endpoint:** `/api/contact_settings/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   None typically used as this table stores a single record.
*   **Sample Response (200 OK):**
    ```json
    {
        "id": 1,
        "office_hours": "Monday - Friday: 9:00 AM - 5:00 PM",
        "google_map_embed": "<iframe src='...'></iframe>",
        "updated_at": "2023-06-01 10:00:00"
    }
    ```
    *   **Sample Response (200 OK - No data):**
    ```json
    []
    ```
    *(Note: This endpoint returns `data[0]` or `[]` if no record)*

### 15. Form Enquiries

*   **Endpoint (Submit Enquiry):** `/api/form_enquiries/post.php`
    *   **Request Method:** `POST`
    *   **Request Payload (JSON):**
        ```json
        {
            "name": "John Doe",
            "email": "john.doe@example.com",
            "phone": "123-456-7890", // Optional
            "subject": "Service Inquiry",
            "content": "I would like to know more about your web development services."
        }
        ```
    *   **Query Parameters:** Not applicable.
    *   **Sample Response (201 Created):**
        ```json
        {
            "message": "Enquiry submitted successfully.",
            "id": 123
        }
        ```
    *   **Sample Response (400 Bad Request - Missing fields):**
        ```json
        {
            "error": "Missing required fields: subject, content"
        }
        ```
    *   **Sample Response (400 Bad Request - Invalid JSON):**
        ```json
        {
            "error": "Invalid JSON data in request body."
        }
        ```
    *   **Sample Response (400 Bad Request - Invalid Email):**
        ```json
        {
            "error": "Invalid email format."
        }
        ```

*   **Endpoint (Get Enquiries):** `/api/form_enquiries/get.php`
    *   **Request Method:** `GET`
    *   **Request Payload:** Not applicable.
    *   **Query Parameters:**
        *   `id` (integer): Filter by enquiry ID.
        *   `status` (string): Filter by status ('new', 'read', 'responded'). Example: `/api/form_enquiries/get.php?status=new`
        *   `email` (string): Filter by email address.
    *   **Sample Response (200 OK for `?status=new`):**
        ```json
        [
            {
                "id": 123,
                "name": "John Doe",
                "email": "john.doe@example.com",
                "phone": "123-456-7890",
                "subject": "Service Inquiry",
                "content": "I would like to know more about your web development services.",
                "created_at": "2023-07-15 10:30:00",
                "status": "new"
            }
        ]
        ```
        *(Note: Default active/visible filtering does NOT apply to this table. All records are fetched unless specific filters are provided.)*

### 16. Latest Updates

*   **Endpoint:** `/api/latest_updates/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by update ID.
    *   `title` (string): Filter by title.
    *   `is_visible` (boolean: 0 or 1): Filter by visibility. (Default: `1` - true) Example: `/api/latest_updates/get.php?is_visible=0`
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "image_url": "path/to/update1.jpg",
            "title": "New Product Launch",
            "subtitle": "Check out our latest product offering!",
            "is_visible": 1,
            "sort_order": 0,
            "created_at": "2023-08-01 00:00:00",
            "updated_at": "2023-08-01 00:00:00"
        }
    ]
    ```

### 17. Latest News

*   **Endpoint:** `/api/latest_news/get.php`
*   **Request Method:** `GET`
*   **Request Payload:** Not applicable.
*   **Query Parameters:**
    *   `id` (integer): Filter by news ID.
    *   `title` (string): Filter by title.
    *   `is_visible` (boolean: 0 or 1): Filter by visibility. (Default: `1` - true) Example: `/api/latest_news/get.php?is_visible=0`
*   **Sample Response (200 OK):**
    ```json
    [
        {
            "id": 1,
            "title": "Company Expansion",
            "subtitle": "We are expanding to new markets.",
            "is_visible": 1,
            "sort_order": 0,
            "created_at": "2023-08-10 00:00:00",
            "updated_at": "2023-08-10 00:00:00"
        }
    ]
    ```

---
