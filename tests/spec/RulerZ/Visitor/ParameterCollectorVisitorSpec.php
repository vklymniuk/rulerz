<?php

declare(strict_types=1);

namespace spec\RulerZ\Visitor;

use PhpSpec\ObjectBehavior;
use RulerZ\Model\Rule;
use RulerZ\Parser\Parser;
use RulerZ\Visitor\ParameterCollectorVisitor;

class ParameterCollectorVisitorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ParameterCollectorVisitor::class);
    }

    public function it_collects_parameters()
    {
        $ruleModel = $this->parse('foo = :bar AND baz = :biz AND lolz = ?');

        $parameters = $this->visit($ruleModel);
        $parameters->shouldBeArray();
        $parameters->shouldHaveCount(3);
        $parameters->shouldHaveKey('bar');
        $parameters->shouldHaveKey('biz');
        $parameters->shouldHaveKey(0);

        $compilationData = $this->getCompilationData();
        $compilationData->shouldBeArray();
        $compilationData->shouldHaveCount(1);
        $compilationData->shouldHaveKey('parameters');

        $compilationData['parameters']->shouldBeArray();
        $compilationData['parameters']->shouldHaveCount(3);
        $compilationData['parameters']->shouldHaveKey('bar');
        $compilationData['parameters']->shouldHaveKey(0);
    }

    private function parse(string $rule): Rule
    {
        return (new Parser())->parse($rule);
    }
}
