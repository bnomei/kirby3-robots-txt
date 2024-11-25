<?php

test('index', function () {
    $response = kirby()->render('/robots.txt');
    expect($response->code() === 200)->toBeTrue();
});
