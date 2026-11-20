<?php

describe('the contact form', function () {
    it('accepts a valid submission', function () {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('message', 'Thanks for reaching out!');
    });

    it('rejects a submission with no name', function () {
        $response = $this->post('/contact', [
            'email' => 'john@example.com',
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('rejects an invalid email', function () {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
    });
});
