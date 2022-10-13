<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ChangePasswordController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(User $user): User
    {
        dd($user);
    }
}
