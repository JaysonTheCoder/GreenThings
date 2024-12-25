

<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define the routes
switch ($path) {
    case '/':
        include "./Forms/auth/users/login.php";
        break;
    case '/signup':
        include "./Forms/auth/users/signup.php";
        break;
    case '/admin-login':
        include "./Forms/auth/admin/login.php";
        break;
    case '/admin-signup':
        include "./Forms/auth/admin/signup.php";
        break;

    case '/home':
        include "./Pages/users/home.php";
        break;
    case '/items-shared':
        include "./Pages/users/sharedItems.php";
        break;

    case '/update-item':
        include "./Forms/items/udpateItems.php";
        break;
    case '/add-item':
        include "./Forms/items/addItems.php";
        break;
    case '/request-form':
        include "./Forms/items/requestItem.php";
        break;
    case '/approval-form':
        include "./Forms/items/approve.php";
        break;
    case '/update-user':
        include "./Forms/updateUser.php";
        break;
    case '/requests':
        include "./Pages/users/Request.php";
        break;
    case '/notification':
        include "./Pages/users/notifications.php";
        break;
    case '/admin-dashboard':
        include "./Pages/admin/dashboard.php";
        break;
    case '/user-management':
        include "./Pages/admin/user-management.php";
        break;
    case '/logout':
        include "./logout.php";
        break;
    case '/admin-logout':
        include "./out.php";
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>
