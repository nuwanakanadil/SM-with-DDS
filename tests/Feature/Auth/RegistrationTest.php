<?php

it('registration redirects to login', function () {
    $this->get('/register')
        ->assertRedirect('/login');
});

it('public users cannot self register', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertStatus(405);

    $this->assertGuest();
});
