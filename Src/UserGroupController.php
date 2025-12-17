<?php

declare(strict_types=1);


namespace App\Src;


/**
 * Controller for managing user-group relationships.
 * 
 */
class UserGroupController extends Controller
{
    /**
     * [Description for storeUserGroup]
     *
     * @param array $data
     * 
     * @return array
     * 
     */
    public function storeUserGroup(array $data): array
    {
        $data['user_id'] = trim($data['user_id'] ?? '');
        $data['group_id'] = trim($data['group_id'] ?? '');

        $errors = $this->validation($data);

        $stmt = $this->pdo->prepare(
            'INSERT INTO user_group (user_id, group_id) VALUES (:user_id, :group_id)'
        );

        if(empty($errors)) {
            $saveResult = $stmt->execute([
                'user_id' => $data['user_id'], 
                'group_id' => $data['group_id']
            ]);
        }

        $response = 'show-group&id=' . $data['group_id'];
        $result = $this->createAjaxResponse($saveResult ?? false, $errors, $response);
        return $result;
    }


    /**
     * Delete a user–group relation by its ID.
     *
     * @param array $data
     * 
     * @return bool Returns true if deletion was successful, false otherwise.
     * 
     */
    public function deleteUserGroup(array $data): array
    {
        $userId = trim($data['user_id'] ?? '');
        $groupId = trim($data['group_id'] ?? '');

        $errors = [];
        !is_numeric($userId) && $errors[] = 'User Id is incorrect!';
        !is_numeric($groupId) && $errors[] = 'Group Id is incorrect!';
        

        if(empty($errors)) {
            $stmt = $this->pdo->prepare('DELETE FROM user_group WHERE user_id = :user_id AND group_id = :group_id');
            $stmtResult = $stmt->execute(['user_id' => $userId, 'group_id' => $groupId]);
        }

        $isSuccess = (empty($errors) && $stmtResult) ? true : false;
        $response = 'show-group&id=' . $groupId;
        
        $result = $this->createAjaxResponse($isSuccess, $errors, $response);
        return $result;
    }


    /**
     * Validate user–group relation data.
     * 
     * Checks required fields, numeric values, and ensures that the relation does not already exist.
     *
     * @param array $data Data to validate.
     * 
     * @return array Array of error messages, empty if validation passed.
     * 
     */
    private function validation(array $data): array
    {
        $errors = [];

        if ($data['user_id'] === '') {
            $errors[] = "User ID is required";
        }

        if(!is_numeric($data['user_id'])) {
            $errors[] = "Incorrect user ID";
        }

        if ($data['group_id'] === '') {
            $errors[] = "Group ID is required";
        }

        if(!is_numeric($data['group_id'])) {
            $errors[] = "Incorrect group ID";
        }

        $stmtCheck = $this->pdo->prepare(
            'SELECT 1 FROM user_group WHERE user_id = :user_id AND group_id = :group_id'
        );
        $stmtCheck->execute([
            'user_id'  => $data['user_id'],
            'group_id' => $data['group_id'],
        ]);

        $exists = (bool) $stmtCheck->fetchColumn();

        if($exists) {
            $errors[] = "Relation exist";
        }

        return $errors;
    }
}