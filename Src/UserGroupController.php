<?php

declare(strict_types=1);


namespace App\Src;

use \PDO;

class UserGroupController extends Controller
{
    public function deleteUserGroup(string $id): bool
    {
        $id = trim($id ?? '');
        if(is_numeric($id)) {
            $stmt = $this->pdo->prepare('DELETE FROM user_group WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        }

        return false;
    }

    public function storeUserGroup(array $data): bool|array
    {
        $data['user_id'] = trim($data['user_id'] ?? '');
        $data['group_id'] = trim($data['group_id'] ?? '');

        $errors = $this->validation($data);

        $stmt = $this->pdo->prepare(
            'INSERT INTO user_group (user_id, group_id) VALUES (:user_id, :group_id)'
        );

        if(empty($errors)) {
            return $stmt->execute([
                'user_id' => $data['user_id'], 
                'group_id' => $data['group_id']
            ]);
        } else {
            return $errors;
        }
    }

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