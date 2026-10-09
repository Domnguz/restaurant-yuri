<?php

it('redirects the restaurant entry point to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
