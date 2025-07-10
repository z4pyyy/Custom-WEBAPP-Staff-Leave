<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $factory->createDatabase();
    }


    public function createEmptyStructure(array $structure)
    {
        foreach ($structure as $path => $value) {
            $this->database->getReference($path)->set($value);
        }
    }

    public function getUsers()
    {
        return $this->database->getReference('users')->getValue();
    }

    public function createUser(string $id, array $data)
    {
        $this->database->getReference('users/' . $id)->set($data);
    }

    public function getUser($id)
    {
        return $this->database->getReference('users/' . $id)->getValue();
    }

    public function updateUser($id, array $data)
    {
        return $this->database->getReference('users/' . $id)->update($data);
    }

    public function deleteUser($id)
    {
        return $this->database->getReference('users/' . $id)->remove();
    }

    public function set(string $path, array $data)
    {
        $this->database->getReference($path)->set($data);
    }

    public function getData(string $path)
    {
        return $this->database->getReference($path)->getValue();
    }


    public function drop(string $path)
    {
        return $this->database->getReference($path)->remove();
    }
    public function addUser(array $userData): void
    {
        $newRef = $this->database->getReference('users')->push($userData);
    }
    
        public function push(string $path, array $data)
    {
        return $this->database->getReference($path)->push($data);
    }

}
