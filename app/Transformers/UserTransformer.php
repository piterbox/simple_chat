<?php

namespace App\Transformers;

use App\Conversation;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [ 'replies', 'user', 'users', 'parent'];

    public function transform(User $user)
    {
        $scope= $this->getCurrentScope()->getIdentifier();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar( 45),
        ];
    }


}