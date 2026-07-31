<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginAjaxTest extends TestCase
{
    public function test_ajax_login_returns_json_validation_errors(): void
    {
        $response = $this->postJson(route('login.post'), [
            'email' => '',
            'password' => '',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
