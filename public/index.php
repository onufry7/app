<?php

declare(strict_types=1);


require_once __DIR__ . '/../Src/autoload.php';


use App\Src\View;
use App\Src\UserController;
use App\Src\GroupController;
use App\Src\UserGroupController;


/**
 * Front controller / router.
 *
 * Handles all requests by reading the 'action' GET parameter
 * and dispatching to the corresponding controller and view.
 * 
 * It is possible to implement better routing, for example in a class, and remove code repetition.
 * All delete operations should be performed via POST requests to avoid unsafe state-changing actions triggered by GET.
 *
 */


$view = new View();
$action = $_GET['action'] ?? null;


// Return 404 view
function render404(View $view): void 
{
	header("HTTP/1.1 404 Not Found");
	$view->render('404');
}


// try {

	switch ($action) {

		// Default view and 
		// User list
		case null:
		case 'users':
			$userController = new UserController();
			$users = $userController->getUsers();
			$view->render('user/users', ['users' => $users]);
			break;

		// User details
		case 'show-user':
			$userController = new UserController();
			$user = $userController->showUser($_GET['id']);

			if($user === false) {
				$view->render('404', ['alert' => 'User dont exist']);
			} else {
				$view->render('user/user', ['user' => $user]);
			}
			break;

		// User add form
		case 'add-user':
			$view->render('user/form', ['action' => 'store-user', 'title' => 'Add user']);
			break;

		// Store new user
		case 'store-user':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$controller = new UserController();
				$result = $controller->storeUser($_POST);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;
			
		// User edit form
		case 'edit-user':
			$userController = new UserController();
			$user = $userController->editUser($_GET['id']);
			$view->render('user/form', ['user' => $user, 'action' => 'update-user', 'title' => 'Update user']);
			break;

		// Update existing user
		case 'update-user':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$controller = new UserController();
				$result = $controller->updateUser($_POST);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;

		// Delete existing user
		case 'delete-user':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$userController = new UserController();
				$result = $userController->deleteUser($_POST['id']);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;

		// Groups list
		case 'groups':
			$groupResources = new GroupController();
			$groups = $groupResources->getGroups();
			$view->render('group/groups', ['groups' => $groups]);
			break;

		// Group details
		case 'show-group':
			$groupController = new GroupController();
			$group = $groupController->showGroup($_GET['id']);

			if($group === false) {
				$view->render('404', ['alert' => 'Group dont exist']);
			} else {
				$view->render('group/group', ['group' => $group]);
			}
			break;

		// Group add form
		case 'add-group':
			$view->render('group/form', ['action' => 'store-group', 'title' => 'Add group']);
			break;

		// Store new group
		case 'store-group':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$groupController = new GroupController();
				$result = $groupController->storeGroup($_POST);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;

		// Group edit form
		case 'edit-group':
			$groupController = new GroupController();
			$group = $groupController->editGroup($_GET['id']);
			$view->render('group/form', ['group' => $group, 'action' => 'update-group', 'title' => 'Update group']);
			break;

		// Updating existing group
		case 'update-group':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$groupController = new GroupController();
				$result = $groupController->updateGroup($_POST);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;

		// Delete group
		case 'delete-group':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$groupController = new GroupController();
				$result = $groupController->deleteGroup($_POST['id']);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;

		// Add new user_group relation form
		case 'add-user-group':
			$userController = new UserController();
			$users = $userController->getUsers();
	
			$view->render('user-group/form', ['users' => $users, 'group_id' => $_GET['id'], 'title' => "Add user to group"]);
			break;
		
		// Store user_group relation
		case 'store-user-group':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$userGroupController = new UserGroupController();
				$result = $userGroupController->storeUserGroup($_POST);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;

		// Delete user_group relation
		case 'delete-user-group':
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$userGroupController = new UserGroupController();
				$result = $userGroupController->deleteUserGroup($_POST);

				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			} else {
				render404($view);
			}
			break;

		default:
			render404($view);
			break;
	}

// } catch (Throwable $th) {
// 	header('HTTP/1.1 400 Bad Request');
// 	$view->render('400');
// }
