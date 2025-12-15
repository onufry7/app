<?php

declare(strict_types=1);


namespace App\Src;

use \PDO;

class GroupController extends Controller
{
    public function getGroups(): array
    {
        return $this->pdo
            ->query('SELECT * FROM groups ORDER BY id')
            ->fetchAll();
    }

    public function showGroup(string $id): bool|array 
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

    public function storeGroup(array $data): bool|array
    {
        $data['name'] = trim($data['name'] ?? '');

        $errors = $this->validation($data);

        $stmt = $this->pdo->prepare(
            'INSERT INTO groups (name) VALUES (:name)'
        );
        
        if(empty($errors)) {
            return $stmt->execute(['name' => $data['name']]);
        } else {
            return $errors;
        }
    }

    public function editGroup(string $id): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM groups WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateGroup(array $data): bool|array
    {
        $data['id'] = trim($data['id'] ?? '');
        $data['name'] = trim($data['name'] ?? '');

        $errors = $this->validation($data, true);

        $stmt = $this->pdo->prepare( 'UPDATE groups SET name = :name WHERE id = :id' );

        if(empty($errors)) {
            return $stmt->execute([
                'id' => $data['id'], 
                'name' => $data['name'],
            ]);
        } else {
            return $errors;
        }
    }

    public function deleteGroup(string $id): bool
    {
        $id = trim($id ?? '');
        if(is_numeric($id)) {
            $stmt = $this->pdo->prepare('DELETE FROM groups WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        }

        return false;
    }

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