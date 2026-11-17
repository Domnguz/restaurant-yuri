<?php

it('renders the public restaurant welcome page with staff login access', function () {
    $response = $this->get('/');

    $response->assertOk()->assertSee('Mr. Yuri')->assertSee(route('login'), false);
});
