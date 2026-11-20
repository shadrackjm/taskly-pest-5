<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

// $this->get('/url');          // HTTP GET
// $this->post('/url', $data);  // HTTP POST with body data
// $this->put('/url', $data);   // HTTP PUT
// $this->patch('/url', $data); // HTTP PATCH
// $this->delete('/url');       // HTTP DELETE

// // COMMON RESPONSE ASSERTIONS
// $response->assertStatus(200);          // status code is 200
// $response->assertOk();                 // shorthand for assertStatus(200)
// $response->assertRedirect('/login');   // response is a redirect to /login
// $response->assertSee('Welcome');       // response body contains the text "Welcome"
// $response->assertDontSee('Error');     // response body does NOT contain "Error"
// $response->assertJson(['key' => 'value']); // JSON response matches this array
// $response->assertSessionHas('message');    // session has this key
// $response->assertUnauthorized();       // status is 401
// $response->assertForbidden();          // status is 403
// $response->assertNotFound();           // status is 404
