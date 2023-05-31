<?php

namespace Tests\Feature\Blade;

use Tests\TestCase;

class RowTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_header_row_is_rendered()
    {
        $this->blade('<x-row header />')
            ->assertSee('sticky')
            ->assertSee('bg-gray');

        $this->blade('<x-row />')
            ->assertDontSee('sticky')
            ->assertSee('bg-white');
    }
}
