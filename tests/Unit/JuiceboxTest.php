<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Models\User;

class JuiceboxTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    use RefreshDatabase;

    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_show_posts()
    {
        Post::with('user')->get();

        $this->assertTrue(true);
    }

    public function test_create_user()
    {
        // Arrange
        $data = [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('testing123'),
        ];

        // insert a new user
        $user = User::create($data);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Admin', $user->name);
        $this->assertEquals('admin@gmail.com', $user->email);
        $this->assertEquals(Hash::make('testing123'), $user->password);
    }
}
