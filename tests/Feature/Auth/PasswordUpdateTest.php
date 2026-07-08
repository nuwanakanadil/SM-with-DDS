<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect('/profile');
});

test('temporary-password users are redirected to change password and can update without current password', function () {
    $user = User::factory()->create([
        'email' => 'temp-user@example.com',
        'must_change_password' => true,
    ]);

    $loginResponse = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $loginResponse->assertRedirect('/profile#password');

    $response = $this
        ->actingAs($user->refresh())
        ->from('/profile#password')
        ->put('/password', [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    $user->refresh();

    expect($user->must_change_password)->toBeFalse();
    expect(Hash::check('new-password', $user->password))->toBeTrue();
});
