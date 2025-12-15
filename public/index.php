<?php

declare(strict_types=1);


require_once __DIR__ . '/../Src/autoload.php';

use App\Src\View;
use App\Src\UserController;
use App\Src\GroupController;
use App\Src\UserGroupController;
use \Throwable;


$view = new View();
$action = $_GET['action'] ?? null;

// It is possible to implement better routing, for example in a class, and remove code repetition.
try {

	switch ($action) {
		case null:
		case 'users':
			$userController = new UserController();
			$users = $userController->getUsers();
			$view->render('user/users', ['users' => $users]);
			break;

		case 'show-user':
			$userController = new UserController();
			$user = $userController->showUser($_GET['id']);

			if($user === false) {
				$view->render('404', ['alert' => 'User dont exist']);
			} else {
				$view->render('user/user', ['user' => $user]);
			}
			break;

		case 'add-user':
			$view->render('user/form', ['action' => 'store-user', 'title' => 'Add user']);
			break;

		case 'store-user':
			$userController = new UserController();
			$result = $userController->storeUser($_POST);

			if(is_array($result)) {
				$result = implode('<br>', $result);
			} else {
				$result = ($result) ? 'Add Success!' : 'Add Failed!';
			}

			$view->render('user/form', ['alert' => $result, 'action' => 'store-user', 'title' => 'Add user']);
			break;

		case 'edit-user':
			$userController = new UserController();
			$user = $userController->editUser($_GET['id']);
			$view->render('user/form', ['user' => $user, 'action' => 'update-user', 'title' => 'Update user']);
			break;

		case 'update-user':
			$userController = new UserController();
			$result = $userController->updateUser($_POST);

			if(is_array($result)) {
				$result = implode('<br>', $result);
			} else {
				$result = ($result) ? 'Update Success!' : 'Update Failed!';
			}

			$view->render('user/form', ['alert' => $result, 'action' => 'update-user', 'title' => 'Update user']);
			break;

		case 'delete-user':
			$userController = new UserController();
			$result = $userController->deleteUser($_GET['id']);
			$result = ($result) ? 'Delete Success!' : 'Delete Failed!';
			$users = $userController->getUsers();

			$view->render('user/users', ['alert' => $result, 'users' => $users]);
			break;

		case 'groups':
			$groupResources = new GroupController();
			$groups = $groupResources->getGroups();
			$view->render('group/groups', ['groups' => $groups]);
			break;

		case 'show-group':
			$groupController = new GroupController();
			$group = $groupController->showGroup($_GET['id']);

			if($group === false) {
				$view->render('404', ['alert' => 'Group dont exist']);
			} else {
				$view->render('group/group', ['group' => $group]);
			}
			break;

		case 'add-group':
			$view->render('group/form', ['action' => 'store-group', 'title' => 'Add group']);
			break;

		case 'store-group':
			$groupController = new GroupController();
			$result = $groupController->storeGroup($_POST);

			if(is_array($result)) {
				$result = implode('<br>', $result);
			} else {
				$result = ($result) ? 'Add Success!' : 'Add Failed!';
			}

			$view->render('group/form', ['alert' => $result, 'action' => 'store-group', 'title' => 'Add group']);
			break;

		case 'edit-group':
			$groupController = new GroupController();
			$group = $groupController->editGroup($_GET['id']);
			$view->render('group/form', ['group' => $group, 'action' => 'update-group', 'title' => 'Update group']);
			break;

		case 'update-group':
			$groupController = new GroupController();
			$result = $groupController->updateGroup($_POST);

			if(is_array($result)) {
				$result = implode('<br>', $result);
			} else {
				$result = ($result) ? 'Update Success!' : 'Update Failed!';
			}

			$view->render('group/form', ['alert' => $result, 'action' => 'update-group', 'title' => 'Update group']);
			break;

		case 'delete-group':
			$groupController = new GroupController();
			$result = $groupController->deleteGroup($_GET['id']);
			$result = ($result) ? 'Delete Success!' : 'Delete Failed!';
			$groups = $groupController->getGroups();

			$view->render('group/groups', ['alert' => $result, 'groups' => $groups]);
			break;

		case 'delete-user-group':
			$userGroupController = new UserGroupController();
			$result = $userGroupController->deleteUserGroup($_GET['id']);

			$groupController = new GroupController();
			$group = $groupController->showGroup($_GET['group_id']);

			$result = ($result) ? 'Delete user from group Success!' : 'Delete user from group Failed!';

			$view->render('group/group', ['alert' => $result, 'group' => $group]);
			break;

		case 'add-user-group':
			$userController = new UserController();
			$users = $userController->getUsers();
	
			$view->render('user-group/form', ['users' => $users, 'group_id' => $_GET['id'], 'title' => "Add user to group"]);
			break;
		
		case 'store-user-group':
			$userGroupController = new UserGroupController();
			$result = $userGroupController->storeUserGroup($_POST);

			$groupController = new GroupController();
			$group = $groupController->showGroup($_POST['group_id']);

			if(is_array($result)) {
				$result = implode('<br>', $result);
			} else {
				$result = ($result) ? 'Add user to group Success!' : 'Add user to group Failed!';
			}

			$view->render('group/group', ['alert' => $result, 'group' => $group]);
			break;

		default:
			header("HTTP/1.1 404 Not Found");
			$view->render('404');
			break;
	}

} catch (Throwable $th) {
	header('HTTP/1.1 400 Bad Request');
	$view->render('400');
}


