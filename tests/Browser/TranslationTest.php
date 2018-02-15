<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class TranslationTest extends DuskTestCase
{
    public function testTruthiness()
    {
        $this->assertTrue(true);
    }

    public function testSessionDetection()
    {
        $this->browse(function (Browser $browser) {
            $browser = $browser->visit('/')->click('@en-link');
            $this->assertEquals(
                $browser->attribute('@en-link', 'active'),
                'yes'
            );
            $this->assertEquals(
                $browser->attribute('@id-link', 'active'),
                'no'
            );

            $browser = $browser->visit('/')->click('@id-link');
            $this->assertEquals(
                $browser->attribute('@id-link', 'active'),
                'yes'
            );
            $this->assertEquals(
                $browser->attribute('@en-link', 'active'),
                'no'
            );
        });
    }

    public function testTranslation()
    {
        foreach (['en', 'id'] as $locale) {
            $this->browse(function (Browser $browser) use ($locale) {
                $browser = $browser->visit('/')->click("@{$locale}-link");

                foreach (['function', 'directive'] as $method) {
                    foreach ($browser->elements(".{$method}-{$locale} li") as $element) {
                        $elementKey = $element->getAttribute('key');
                        $elementDefault = $element->getAttribute('default');

                        $this->assertEquals(
                            translate($elementKey, $elementDefault, $locale),
                            $element->getText()
                        );
                    }
                }
            });
        }
    }
}
