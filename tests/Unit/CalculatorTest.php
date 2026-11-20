<?php

use App\Support\Calculator;

covers(Calculator::class);

describe('Calculator', function () {
    beforeEach(function () {
        $this->calculator = new Calculator;
    });

    it('adds two numbers', function () {
        expect($this->calculator->add(2, 3))->toBe(5);
    });

    it('subtracts two numbers', function () {
        expect($this->calculator->subtract(10, 4))->toBe(6);
    });

    it('multiplies two numbers', function () {
        expect($this->calculator->multiply(4, 5))->toBe(20);
    });

    it('divides two numbers', function () {
        expect($this->calculator->divide(10, 2))->toBe(5.0);
    });

    it('refuses to divide by zero', function () {
        $this->calculator->divide(10, 0);
    })->throws(InvalidArgumentException::class, 'Cannot divide by zero.');

    it('adds many pairs correctly', function (int $a, int $b, int $expected) {
        expect($this->calculator->add($a, $b))->toBe($expected);
    })->with([
        'two positives' => [2, 3, 5],
        'a negative' => [-2, 3, 1],
        'two negatives' => [-2, -3, -5],
        'zeroes' => [0, 0, 0],
    ]);
});
