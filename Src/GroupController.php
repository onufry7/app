<?php

declare(strict_types=1);


namespace App\Src;


use PDO;


/**
 * Controller for managing groups.
 *
 */
class GroupController extends Controller
{
    /**
     * Get all groups from the database.
     *
     * @return array List of groups as associative arrays.
     */
    public function getGroups(): array
    {
        return $this->pdo
            ->query('SELECT * FROM groups ORDER BY id')
            ->fetchAll();
    }


    /**
     * Get a single group with its users.
     *
     * @param string $id Group ID.
     * @return array|bool Group data with 'users' key
     */
    public function showGroup(string $id): array 
    {
        $stmtGroup = $this->pdo->prepare('SELECT * FROM groups WHERE id = :id');
        $stmtGroup->execute(['id' => $id]);
        $group = $stmtGroup->fetch(PDO::FETCH_ASSOC);
        

        $stmtUsers = $this->pdo->prepare(
            'SELECT u.*, ug.id AS user_group_id
            FROM users u
            JOIN user_group ug ON ug.user_id = u.id
            WHERE ug.group_id = :group_id'
        );
        $stmtUsers->execute(['group_id' => $id]);
        $group['users'] = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

        return $group;
    }


    /**
     * Store a new group in database.
     *
     * Validates data and returns a standardized response suitable for Ajax calls.
     *
     * @param array $data ['name' => string] Group data.
     * @return array Returns an array with keys:
     *               - 'success' => bool
     *               - 'errors'  => array of validation errors (empty if none)
     */
    public function storeGroup(array $data): array
    {
        $data['name'] = trim($data['name'] ?? '');

        $errors = $this->validation($data);

        $stmt = $this->pdo->prepare(
            'INSERT INTO groups (name) VALUES (:name)'
        );
        
        if(empty($errors)) {
            $saveResult = $stmt->execute(['name' => $data['name']]);
        }

        $result = $this->createAjaxResponse($saveResult ?? false, $errors);
        return $result;
    }


    /**
     *  Retrieves a single group data for editing.
     *
     * @param string $id Group ID
     * 
     * @return array Returns an associative array of group data.
     * 
     */
    public function editGroup(string $id): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM groups WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Updates an existing group data in the database.
     * 
     * Validate data and returns a standardized response suitable for Ajax calls.
     *
     * @param array $data Array containing keys: 'id', 'name'.
     * 
     * @return array Returns an array with keys:
     *               - 'success' => bool
     *               - 'errors'  => array of validation errors (empty if none)
     * 
     */
    public function updateGroup(array $data): array
    {
        $data['id'] = trim($data['id'] ?? '');
        $data['name'] = trim($data['name'] ?? '');

        $errors = $this->validation($data, true);

        $stmt = $this->pdo->prepare( 'UPDATE groups SET name = :name WHERE id = :id' );

        if(empty($errors)) {
            $updateResult = $stmt->execute([
                'id' => $data['id'], 
                'name' => $data['name'],
            ]);
        }
        
        $result = $this->createAjaxResponse($updateResult ?? false, $errors);
        return $result;
    }


    /**
     * Deletes a group by ID.
     *
     * @param string $id Group ID
     * 
     * @return array Returns an array with keys:
     *               - 'success' => bool
     *               - 'errors'  => array of validation errors (empty if none)
     * 
     */
    public function deleteGroup(string $id): array
    {
        $groupId = trim($id);

        $errors = [];
        !is_numeric($groupId) && $errors[] = 'Group Id is incorrect!';
        
        if(empty($errors)) {
            $stmt = $this->pdo->prepare('DELETE FROM groups WHERE id = :id');
            $stmtResult = $stmt->execute(['id' => $groupId]);
        }

        $isSuccess = (empty($errors) && $stmtResult) ? true : false;
        $response = 'groups';
        
        $result = $this->createAjaxResponse($isSuccess, $errors, $response);
        return $result;
    }

    
    /**
     * Validates group data.
     *
     * @param array $data Array of group data to validate.
     * @param bool $isUpdate If true, validates as an update.
     * 
     * @return array Array of error messages, empty if validation passed.
     * 
     */
    private function validation(array $data, bool $isUpdate = false): array
    {
        $errors = [];

        if ($isUpdate) {
            if ($data['id'] === '') {
                $errors[] = "Group ID is required";
            }

            if(!is_numeric($data['id'])) {
                $errors[] = "Incorrect group ID";
            }
        }

        if ($data['name'] === '') {
            $errors[] = "Name is required";
        }

        if (strlen($data['name']) > 255) {
            $errors[] = "Name cannot be longer than 255 characters";
        }

        return $errors;
    }
}