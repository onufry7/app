<?php

declare(strict_types=1);


namespace App\Src;


use DateTime;
use PDO;


/**
 * Controller for managing users.
 * 
 */
class UserController extends Controller
{

    /**
     * Returns all users from the database.
     *
     * @return array<int, array<string, mixed>> List of users
     * 
     */
    public function getUsers(): array
    {
        return $this->pdo
            ->query('SELECT * FROM users ORDER BY id')
            ->fetchAll();
    }


    /**
     * Returns a single user by ID along with the groups they belong to.
     *
     * @param string $id User ID
     * 
     * @return array<string, mixed>|false User data with 'groups' key, or false if not found
     * 
     */
    public function showUser(string $id): array|false 
    {
        $stmtUser = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmtUser->execute(['id' => $id]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $stmtGroups = $this->pdo->prepare(
            'SELECT g.*, ug.id AS user_group_id
            FROM groups g
            INNER JOIN user_group ug ON ug.group_id = g.id
            WHERE ug.user_id = :user_id'
        );
        $stmtGroups->execute(['user_id' => $id]);
        $user['groups'] = $stmtGroups->fetchAll(PDO::FETCH_ASSOC);

        return $user;
    }

    
    /**
     * Stores a new user in the database.
     *
     * Validates data and returns a standardized response suitable for Ajax calls.
     *
     * @param array $data Array containing keys: 'name', 'password', 'first_name', 'last_name', 'birth_date'.
     *
     * @return array Returns an array with keys:
     *               - 'success' => bool
     *               - 'errors'  => array of validation errors (empty if none)
     */
    public function storeUser(array $data): array
    {
        $data['name'] = trim($data['name'] ?? '');
        $data['password'] = trim($data['password'] ?? '');
        $data['first_name'] = trim($data['first_name'] ?? '');
        $data['last_name'] = trim($data['last_name'] ?? '');
        $data['birth_date'] = trim($data['birth_date'] ?? '');

        $errors = $this->validation($data);

        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, password, first_name, last_name, birth_date)
            VALUES (:name, :password, :first_name, :last_name, :birth_date)'
        );
        
        if(empty($errors)) {
            $saveResult = $stmt->execute([
                'name' => $data['name'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'first_name' => $data['first_name'] ?: null,
                'last_name' => $data['last_name'] ?: null,
                'birth_date' => $data['birth_date'] ?: null,
            ]);
        }

        $result = $this->createAjaxResponse($saveResult ?? false, $errors);
        return $result;
    }


    /**
     * Retrieves a single user data for editing.
     *
     * @param string $id User ID
     *
     * @return array Returns an associative array of user data.
     */
    public function editUser(string $id): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Updates an existing user data in the database.
     *
     * Validate data and returns a standardized response suitable for Ajax calls.
     *
     * @param array $data Array containing keys: 'id', 'name', 'password', 'first_name', 'last_name', 'birth_date'.
     *
     * @return array Returns an array with keys:
     *               - 'success' => bool
     *               - 'errors'  => array of validation errors (empty if none)
     */    
    public function updateUser(array $data): array
    {
        $data['id'] = trim($data['id'] ?? '');
        $data['name'] = trim($data['name'] ?? '');
        $data['password'] = trim($data['password'] ?? '');
        $data['first_name'] = trim($data['first_name'] ?? '');
        $data['last_name'] = trim($data['last_name'] ?? '');
        $data['birth_date'] = trim($data['birth_date'] ?? '');

        $errors = $this->validation($data, true);

        $sql = '
            UPDATE users SET 
                name = :name,
                first_name = :first_name,
                last_name = :last_name,
                birth_date = :birth_date
        ';

        $params = [
            'id' => $data['id'],
            'name' => $data['name'],
            'first_name' => $data['first_name'] ?: null,
            'last_name' => $data['last_name'] ?: null,
            'birth_date' => $data['birth_date'] ?: null,
        ];

        if (!empty($data['password'])) {
            $sql .= ', password = :password';
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);

        if(empty($errors)) {
            $updateResult = $stmt->execute($params);
        }

        $result = $this->createAjaxResponse($updateResult ?? false, $errors);
        return $result;
    }


    /**
     * Deletes a user by ID.
     *
     * @param string $id User ID
     *
     * @return array Returns an array with keys:
     *               - 'success' => bool
     *               - 'errors'  => array of validation errors (empty if none)
     * 
     */
    public function deleteUser(string $id): array
    {
        $userId = trim($id ?? '');

        $errors = [];
        !is_numeric($userId) && $errors[] = 'User Id is incorrect!';
        

        if(empty($errors)) {
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmtResult = $stmt->execute(['id' => $userId]);
        }

        $isSuccess = (empty($errors) && $stmtResult) ? true : false;
        $response = 'users';
        
        $result = $this->createAjaxResponse($isSuccess, $errors, $response);
        return $result;
    }


    /**
     * Validates user data.
     *
     * Checks required fields, string lengths, and date format.
     *
     * @param array $data Array of user data to validate.
     * @param bool $isUpdate If true, validates as an update.
     *
     * @return array Array of error messages, empty if validation passed.
     */
    private function validation(array $data, bool $isUpdate = false): array
    {
        $errors = [];

        if ($isUpdate) {
            if ($data['id'] === '') {
                $errors[] = "User ID is required";
            }

            if(!is_numeric($data['id'])) {
                $errors[] = "Incorrect user ID";
            }
        } else {
            if ($data['password'] === '') {
                $errors[] = "Password is required";
            }
        }
        
        if ($data['name'] === '') {
            $errors[] = "Name is required";
        }

        if (strlen($data['name']) > 255) {
            $errors[] = "Name cannot be longer than 255 characters";
        }

        if (strlen($data['password']) > 255) {
            $errors[] = "Password cannot be longer than 255 characters";
        }

        if ($data['first_name'] !== '' && strlen($data['first_name']) > 255) {
            $errors[] = "First name cannot be longer than 255 characters";
        }

        if ($data['last_name'] !== '' && strlen($data['last_name']) > 255) {
            $errors[] = "Last name cannot be longer than 255 characters";
        }

        if ($data['birth_date'] !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $data['birth_date']);
            if (!$d || $d->format('Y-m-d') !== $data['birth_date']) {
                $errors[] = "Date of birth is incorrect";
            }
        }

        return $errors;
    }

}
