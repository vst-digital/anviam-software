<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;

class UsersACLRepository implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return \Auth::id();
    }

    /**
     * Get ACL rules list for user
     *
     * @return array
     */
    public function getRules(): array
    {
        if (\Auth::id() === 1) {
            return [
                ['disk' => 'disk-name', 'path' => '*', 'access' => 2],
            ];
        }

        return [
            ['disk' => 'disk-name', 'path' => '/', 'access' => 1],                                  // main folder - read
            ['disk' => 'disk-name', 'path' => 'uploads', 'access' => 1],                              // only read
            ['disk' => 'disk-name', 'path' => 'uploads/'. \Auth::user()->id, 'access' => 1],        // only read
            ['disk' => 'disk-name', 'path' => 'uploads/'. \Auth::user()->id .'/*', 'access' => 2],  // read and write
        ];
    }
}
