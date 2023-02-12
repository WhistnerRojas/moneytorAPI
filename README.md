How to deploy SwiftMoneyTorAPI in your local machine

<ol>
    <li>clone this repository</li>
    <li>composer install</li>
    <li>cp .env.example .env</li>
    <li>php artisan migrate</li>
    <li>php artisan jwt:secret</li>
    <li>php artisan serve</li>
</ol>